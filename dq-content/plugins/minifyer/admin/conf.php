
<style>
#minify {
	width: 720px;
	float: left;
}
#minify .float-left {
	float: left;
	width: 750px;
	padding: 0;
}
#minify form label {
	display: block;
	float: left;
	line-height: 35px;
	font-weight: bold;
	width: 130px;
	margin-right: 15px;
}
pre {
	width: 450px;                          /* specify width  */
	white-space: pre-wrap;                 /* CSS3 browsers  */
	white-space: -moz-pre-wrap !important; /* 1999+ Mozilla  */
	white-space: -pre-wrap;                /* Opera 4 thru 6 */
	white-space: -o-pre-wrap;              /* Opera 7 and up */
	word-wrap: break-word;                 /* IE 5.5+ and up */
}
pre:hover {
	position: relative;
	width: 700px;
	z-index: 99;
}
</style>
<div id="minify" class="plugin-configuration form-horizontal">
	<div class="float-left">
		<h1><?php _e('About','minify')?></h1>
		<div class="description">
			<h3><?php _e('Load you ad\'s site faster than ever before!','minify')?></h3>
			<p><?php _e('This plugin is a wrapper for the php Minify library created by <a target="_blak" href="https://github.com/mrclay">Steve Clay</a>.','minify')?></p>
			<h3><?php _e('What it does?','minify')?></h3>
			<p><?php _e('It compresses sources of content
(usually files), combines the result and serves it with appropriate
HTTP headers. These headers can allow clients to perform conditional
GETs (serving content only when clients do not have a valid cache)
and tell clients to cache the file for a period of time.','minify')?></p>
			<span class="creator"><?php echo sprintf( _m('Copied from %s','minify'), '<a target="_blak" href="https://github.com/mrclay/minify">https://github.com/mrclay/minify</a>' )?></span>
			<h3><?php _e('Instructions','minify')?></h3>
			<p> <?php _e('In order for this plugin to do it\'s job, the scripts need to be in the
			queue to be loaded, that means you you have to register and queue your
			scripts and styles ( obs: styles don\'t need to be registered ).','minify');?></p>
			<h4> <?php _e('For scripts:', 'minify')?></h4>
			<pre>osc_register_script('my_theme', osc_plugin_url(__FILE__).'js/my_theme.js')<br/>osc_enqueue_script('my_theme');</pre>
			<h4> <?php _e('For styles:', 'minify')?></h4>
			<pre>osc_enqueue_style('my_theme', osc_plugin_url(__FILE__).'js/my_theme.js' );</pre>
			<p><?php _e('Once you have this, all you have to do is enable the plugin and voalÃ¡, all
			your css and js are minified and cached.','minify');?></p>
			<p> <?php sprintf( _m('If you want to know more about the Minify library go to it\'s page at Github - %s','minify'), '<a target="_blak" href="https://github.com/mrclay/minify">https://github.com/mrclay/minify</a>')?></p>
			<br/>
			<br/>
			<p> <?php _e('Created by','minify')?> Jair Milanes Junior</p>
			<?php /*
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="BDUJ8SAAMGKAS">
				<input type="image" src="https://www.paypalobjects.com/pt_BR/BR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - A maneira mais fácil e segura de efetuar pagamentos online!">
				<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
			</form>
			*/ ?>
			<p> <?php _e('More OsClass plugins at <a href="http://blog.layoutz.com.br" target="_blak">blog.layoutz.com.br</a>','minify')?></p>
		</div>
	</div>
</div>