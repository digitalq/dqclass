<?php
/*
Plugin Name: PHP Info
Plugin URI: http://forums.osclass.org/plugins/(new-plugin)-php-info/
Description: This plugin displays your php info.
Version: 1.1.0
Author: JChapman 
Author URI: http://forums.osclass.org/index.php?action=profile;u=1728
Short Name: phpInfo
Plugin update URI: phpinfo
Support URI: http://forums.osclass.org/plugins/(new-plugin)-php-info/
*/

    function phpInfo_admin_menu() {
        echo '<h3><a href="#">PHP Info</a></h3>
            <ul>
                <li><a href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/phpinfo.php') . '">&raquo; ' . __('PHP Info', 'phpInfo') . '</a></li>
            </ul>';
    }

    function phpInfo_admin_menu_init() {
        osc_admin_menu_plugins(__('PHP Info', 'phpInfo'), osc_route_admin_url('phpinfo'), 'php_info', null, null );
    }

    if(osc_version()<320) {
        osc_add_hook('admin_menu', 'phpInfo_admin_menu');
    } else {
        osc_add_route('phpinfo', 'phpinfo', 'phpinfo', osc_plugin_folder(__FILE__).'admin/phpinfo.php');
        osc_add_hook('admin_menu_init', 'phpInfo_admin_menu_init');
    }
   
?>
