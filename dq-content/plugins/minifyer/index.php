<?php /*
Plugin Name: Minifyer
Plugin URI: http://www.cmsplugins.com.br/
Description: This plugin is a wrapper for the php minify lib creatd by Steve Clay, it compresses sources of content (usually files), combines the result and serves it with appropriate HTTP headers.
Version: 1.0
Author: Jair Milanes Junior
Author URI: http://www.cmsplugins.com.br/
Short Name: minifyer
Plugin update URI: minifyer
*/
/**
 * Minifify all the scripts and styles currently i the queue
 */
function minify(){
	minify_styles();
	minify_scripts();
}

/**
 * Gets a minify url with all the styles in the queue
 * @return string Url
 */
function minify_styles(){
	$styles = minify_filter_external_css();
	osc_enqueue_style('minify_styles', osc_route_url('minify_styles', array( 'minify_files' => implode( ',', minify_clean_url( $styles ) ) ) ) );
	return;
}

function minify_filter_external_css(){
	$styles = Styles::newInstance()->getStyles();
	foreach( $styles as $key => $url ){
		if( false === strpos( $url, osc_base_url() ) ){
			unset($styles[$key]);
		} else {
			osc_remove_style($key);
		}
	}
	return $styles;
}

/**
 * Gets a minify url with all the scripts in the queue
 * @return string Url
 */
function minify_scripts(){
	$scripts = minify_filter_external_js();
	osc_register_script('minify_scripts', osc_route_url('minify_scripts', array( 'minify_files' => implode( ',', minify_clean_url( $scripts ) ) ) ) );
	osc_enqueue_script('minify_scripts');
	return;
}

function minify_filter_external_js() {
	$scripts = Scripts::newInstance()->getScripts();
	$registered = Scripts::newInstance()->registered;
	foreach( $registered as $file ){
		$exists = array_search( $file['url'], $scripts );
		if( false !== $exists ){
			if( false === strpos( $file['url'], osc_base_url() ) ){
				unset($scripts[$exists]);
			} else {
				osc_remove_script($file['key']);
			}
		}
	}
	return $scripts;
}

/**
 * Sanitize the file url removing the base url
 * @param array $urls
 * @return array Urls
 */
function minify_clean_url( array $urls ){
	foreach( $urls as &$url ){
		$url = str_replace(osc_base_url(),'/', $url );
	}
	return $urls;
}

/**
 * Process minify requests
 */
function minify_init(){
	if(strpos(Params::getParam('minify_files'), '../')!==false) { die; };
	$_GET['f'] = preg_replace('|([\/]+)|', '/', REL_WEB_URL.str_replace(",", ",".REL_WEB_URL, Params::getParam('minify_files')));
	//$_GET['f'] = Params::getParam('minify_files');
	require_once dirname(__FILE__).'/minify.php';
	die();
}

function minify_admin(){
	osc_admin_render_plugin( osc_plugin_path( dirname(__FILE__) ) . '/admin/conf.php' );
}

osc_add_hook( 'header', 'minify', 9 );
osc_add_hook( 'custom_controller', 'minify_init' );
osc_add_route('minify_styles', 'styles/f=(.*)', 'styles/f={minify_files}', 'minifyer/minify.php' );
osc_add_route('minify_scripts', 'scripts/f=(.*)', 'scripts/f={minify_files}', 'minifyer/minify.php' );
osc_register_plugin( osc_plugin_path( __FILE__ ), '' );
osc_add_hook( osc_plugin_path( __FILE__ ) . '_configure', 'minify_admin' );
