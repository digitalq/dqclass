<?php
/*
Plugin Name: Ghostbuster
Plugin URI: http://www.oscanyon.com/products/ghostbuster/
Description: Git rid of ghost ads.
Version: 4.0
Author: osCanyon
Author URI: http://oscanyon.com
Short Name: ghostAds
Plugin update URI: ghostbuster
Support URI: http://forums.osclass.org/plugins/%28plugin%29-ghostbuster-4-0/
*/

    require('ModelGhost.php');


    function ghostTitle($title) {
		if( osc_version() < 320) {
	        $file = explode('/', Params::getParam('file'));
	        if($file[0] == 'ghost_fix'){
	            $title = 'Ghostbuster';
	        }
		} else {
			$file = Params::getParam('route');
	        if($file == 'ghost-conf'){
	            $title = 'Ghostbuster';
	        }
		}
        return $title;
    }

    if( osc_version() >= 300){
        osc_add_filter('custom_plugin_title','ghostTitle');
    }

    /**
    * Add a link to the ghostbuster plugin
    */
    function ghost_admin_toolbar_menu() {
        if(osc_version()<320) {
            AdminToolbar::newInstance()->add_menu(array(
                'id'        => 'ghostFix',
                'title'     => '<i class="circle circle-red">!</i>' . __('Ghostbuster needs your attention','ghost_fix'),
                'href'      => osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'admin/config.php'),
                'meta'      => array('class' => 'action-btn action-btn-black', 'title' => __('Click here to view the problem.', 'ghost_fix'))
            ));
        } else {
            AdminToolbar::newInstance()->add_menu(array(
                'id'        => 'ghostFix',
                'title'     => '<i class="circle circle-red">!</i>' . __('Ghostbuster needs your attention','ghost_fix'),
                'href'      => osc_route_admin_url('ghost-conf'),
                'meta'      => array('class' => 'action-btn action-btn-black', 'title' => __('Click here to view the problem.', 'ghost_fix'))
            ));
        }
    }

    if(ghost_toolbar_count() == 1) {
		osc_add_hook('add_admin_toolbar_menus', 'ghost_admin_toolbar_menu', 1);
	}

	function ghost_toolbar_count() {
        return(osc_get_preference('ghost_admin_toolbar_count', 'plugin-ghost')) ;
    }

    function fix_admin_menu () {
        if( OSCLASS_VERSION < '2.4.0') {
            echo '<h3><a href="#">Ghostbuster</a></h3><ul>';
            echo '<li class="" ><a href="' . osc_admin_render_plugin_url('ghost_fix/admin/config.php') . '" > &raquo; '. __('Ghostbuster', 'ghost_fix') . '</a></li>';
            echo '</ul>';
        } else {
            echo '<li id="ghostbuster"><h3><a href="#">Ghostbuster</a></h3><ul>';
            echo '<li class="" ><a href="' . osc_admin_render_plugin_url('ghost_fix/admin/config.php') . '" > &raquo; '. __('Ghostbuster', 'ghost_fix') . '</a></li>';
            echo '</ul></li>';
        }
    }

    // init check if there are ghost listings.
    function ghostAdCheck () {

        /* core database tables checks added 2011*/
		$items = ModelGhost::newInstance()->ghostCheck('t_item', 'pk_i_id');
		if($items > 0) {
			osc_add_hook('add_admin_toolbar_menus', 'ghost_admin_toolbar_menu', 1);
		}
    }

    function ghost_auto($id) {
		$del = ModelGhost::newInstance()->ghostDelete('t_item', $id, 'pk_i_id');
		$failedDel = array();
		while ($del != true) {

			$subject = ModelGhost::newInstance()->dao->getErrorDesc();
			//find the table that is causing the problem.
			$pattern = '/' . DB_TABLE_PREFIX . 't_(.*?)`/';
			//find the primary key name in the foreign table
			$patt = '/FOREIGN KEY \(`(.*?)`\)/';
			if(!preg_match_all($pattern, $subject, $matches)) {
				$failedDel[ $matches[1][0] ] = ModelGhost::newInstance()->dao->getErrorDesc();
				Cookie::newInstance()->push('ghostAtt', '1');
				Cookie::newInstance()->set();
			}
			if(!preg_match($patt, $subject, $match)) {
				$failedDel[ $matches[1][0] ] = ModelGhost::newInstance()->dao->getErrorDesc();
				Cookie::newInstance()->push('ghostAtt', '1');
				Cookie::newInstance()->set();
			}

			/*if(array_key_exists($matches[1][0], $failedDel) || array_key_exists($match[1], $failedDel) ) {
				echo '<pre>';
				echo 'Please PM me the following error. Thanks <a href="http://forums.osclass.org/index.php?action=profile;u=1728" target="_blank">Jay</a>.<br /> <br />';
				echo json_encode($failedDel);
				echo '</pre>';
				$i = 1;
				Session::newInstance()->_set('ghostAtt', '1');
				break;
			}*/

			Session::newInstance()->_set('ghostAtt', '0');

			if($matches[1][0] == 'item_resource') {
				//the following section removes the images if any associated with the listing.
				$itemRes = ModelGhost::newInstance()->getResource($id);

				@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '.' 		   . $itemRes['s_extension']);
				@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '_original.'  . $itemRes['s_extension']);
				@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '_preview.'   . $itemRes['s_extension']);
				@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '_thumbnail.' . $itemRes['s_extension']);
			}

			$result = ModelGhost::newInstance()->ghostDelete('t_' . $matches[1][0], $id, $match[1]);
			if($result === false) {
				$i = 1;
				$failedDel[ $matches[1][0] ] = ModelGhost::newInstance()->dao->getErrorDesc();
			}
			$del = ModelGhost::newInstance()->ghostDelete('t_item', $id, 'pk_i_id');
		}
	}

	function ghost_cron() {
		Log::newInstance()->insertLog('ghostbuster', 'cron start', 0, date('y-m-d'), 'admin', '1');
		/* core database tables checks added 2011*/
		$items = ModelGhost::newInstance()->ghostCheck('t_item', 'pk_i_id');
		/* core table counts */
		$ghostCount = count($items);
		/* end core tables counts */

		if( $ghostCount > 0 ) {

			if(@$items != '') {
				$i = 0;
				foreach ($items as $item) {
					$del = ModelGhost::newInstance()->ghostDelete('t_item', $item['pk_i_id'], 'pk_i_id');
					$failedDel = array();
					while ($del != true) {

						$subject = ModelGhost::newInstance()->dao->getErrorDesc();
						echo $subject;
						//find the table that is causing the problem.
						$pattern = '/' . DB_TABLE_PREFIX . 't_(.*?)`/';
						//find the primary key name in the foreign table
						$patt = '/FOREIGN KEY \(`(.*?)`\)/';
						if(!preg_match_all($pattern, $subject, $matches)) {
							$failedDel[ $matches[1][0] ] = ModelGhost::newInstance()->dao->getErrorDesc();
							osc_set_preference('ghost_admin_toolbar_count', 1, 'plugin-ghost', 'INTEGER');
						}
						if(!preg_match($patt, $subject, $match)) {
							$failedDel[ $matches[1][0] ] = ModelGhost::newInstance()->dao->getErrorDesc();
							osc_set_preference('ghost_admin_toolbar_count', 1, 'plugin-ghost', 'INTEGER');
						}
						/*echo '<pre>';
						print_r($matches);
						print_r($match);
						echo '</pre>';*/

						if(array_key_exists($matches[1][0], $failedDel) || array_key_exists($match[1], $failedDel) ) {
							Log::newInstance()->insertLog('ghostbuster', 'cron error', 0, json_encode($failedDel), 'admin', '1');
							$i = 1;
							osc_set_preference('ghost_admin_toolbar_count', 1, 'plugin-ghost', 'INTEGER');
							break;
						}

						osc_set_preference('ghost_admin_toolbar_count', 0, 'plugin-ghost', 'INTEGER');

						if(@$matches[1][0] == 'item_resource') {
							//the following section removes the images if any associated with the listing.
							$itemRes = ModelGhost::newInstance()->getResource($item['pk_i_id']);

							@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '.' 		   . $itemRes['s_extension']);
							@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '_original.'  . $itemRes['s_extension']);
							@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '_preview.'   . $itemRes['s_extension']);
							@unlink(osc_base_path() . $itemRes['s_path'] . $itemRes['pk_i_id'] . '_thumbnail.' . $itemRes['s_extension']);
						}

						$result = ModelGhost::newInstance()->ghostDelete('t_' . $matches[1][0], $item['pk_i_id'], $match[1]);
						if($result === false) {
							$i = 1;
							$failedDel[ $matches[1][0] ] = ModelGhost::newInstance()->dao->getErrorDesc();
						}
						$del = ModelGhost::newInstance()->ghostDelete('t_item', $item['pk_i_id'], 'pk_i_id');
						if($del === true) {
							Log::newInstance()->insertLog('ghostbuster', 'cron delete', 0, 'Listing id ' . $item['pk_i_id'] . ' was deleted', 'admin', '1');
						}

					}

				}
			}
		}
		Log::newInstance()->insertLog('ghostbuster', 'cron end', 0, date('y-m-d'), 'admin', '1');
	}

    function ghost_fix_install(){
        ghostAdCheck();
        osc_set_preference('ghost_admin_toolbar_count', 0, 'plugin-ghost', 'INTEGER');
    }

    function ghost_fix_uninstall(){
        osc_delete_preference('ghost_admin_toolbar_count', 'plugin-ghost');
        osc_delete_preference('ghost_cron_toolbar_count', 'plugin-ghost');
        osc_delete_preference('ghost_ad_cron_checker', 'plugin-ghost');
    }

    function ghost_admin_menu() {
        osc_admin_menu_tools(__('Ghostbuster', 'ghost_fix'), osc_route_admin_url('ghost-conf'), 'ghost_fix', null, null );
    }

    osc_register_plugin(osc_plugin_path(__FILE__), 'ghost_fix_install') ;
    osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall','ghost_fix_uninstall') ;

    // Add link in admin menu page
    if( osc_version() < 300) {
        osc_add_hook('admin_menu', 'fix_admin_menu') ;
    } else if(osc_version()<320) {
        osc_admin_menu_tools(__('Ghostbuster', 'ghost_fix'), osc_admin_render_plugin_url(osc_plugin_folder(__FILE__) . 'admin/config.php'), 'ghost_fix', null, null );
    } else {
        osc_add_route('ghost-conf', 'ghost/conf', 'ghost/conf', osc_plugin_folder(__FILE__).'admin/config.php');
        osc_add_hook('admin_menu_init', 'ghost_admin_menu');
    }
	// Core needs either a new hook or some rearranging of code for this to work right.
    //osc_add_hook('delete_item', 'ghost_auto',10);

    osc_add_hook('cron_hourly', 'ghost_cron');
?>
