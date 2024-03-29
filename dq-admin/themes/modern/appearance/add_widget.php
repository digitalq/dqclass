<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
    /**
     * Osclass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2012 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    osc_enqueue_script('jquery-validate');
    osc_enqueue_script('tiny_mce');

    $info   = __get("info");
    $widget = __get("widget");

    if( Params::getParam('action') == 'edit_widget' ) {
        $title  = __('Edit widget');
        $edit   = true;
        $button = osc_esc_html( __('Save changes') );
    } else {
        $title  = __('Add widget');
        $edit   = false;
        $button = osc_esc_html( __('Add widget') );
    }

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){
        if( Params::getParam('action') == 'edit_widget' ) {
            $title  = __('Edit widget');
        } else {
            $title  = __('Add widget');
        }
        ?>
        <h1><?php echo $title; ?></h1>
    <?php
    }
    function customPageTitle($string) {
        return sprintf(__('Appearance &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');
    function customHead() {
        $info   = __get("info");
        $widget = __get("widget");
        if( Params::getParam('action') == 'edit_widget' ) {
            $title  = __('Edit widget');
            $edit   = true;
            $button = osc_esc_html( __('Save changes') );
        } else {
            $title  = __('Add widget');
            $edit   = false;
            $button = osc_esc_html( __('Add widget') );
        }
        ?>
        <script type="text/javascript">
            tinyMCE.init({
                mode : "textareas",
                theme : "advanced",
                skin: "cirkuit",
                width: "500px",
                height: "340px",
                theme_advanced_buttons3 : "",
                theme_advanced_toolbar_align : "left",
                theme_advanced_toolbar_location : "top",
                plugins : "adimage,advlink,media,contextmenu",
                entity_encoding : "raw",
                theme_advanced_buttons1_add : "forecolorpicker,fontsizeselect",
                theme_advanced_buttons2_add: "media",
                theme_advanced_disable : "styleselect",
                extended_valid_elements : "script[type|src|charset|defer]",
                relative_urls : false,
                remove_script_host : false,
                convert_urls : false
            });

            function ajaxfilemanager(field_name, url, type, win) {
                var ajaxfilemanagerurl = "<?php echo osc_base_url(); ?>/dq-includes/osclass/assets/js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";
                var view = 'detail';
                switch (type) {
                    case "image":
                        view = 'thumbnail';
                        break;
                    case "media":
                        break;
                    case "flash":
                        break;
                    case "file":
                        break;
                    default:
                        return false;
                }
                tinyMCE.activeEditor.windowManager.open({
                    url: "<?php echo osc_base_url(); ?>/dq-includes/osclass/assets/js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php?view=" + view,
                    width: 782,
                    height: 440,
                    inline : "yes",
                    close_previous : "no"
                },{
                    window : win,
                    input : field_name
                });
            }

        </script>

        <script type="text/javascript">
            $(document).ready(function(){
                // Code for form validation
                $("form[name=widget_form]").validate({
                    rules: {
                        description: {
                            required: true
                        }
                    },
                    messages: {
                        description: {
                            required:  '<?php echo osc_esc_js(__("Description: this field is required")); ?>.'
                        }
                    },
                    errorLabelContainer: "#error_list",
                    wrapper: "li",
                    invalidHandler: function(form, validator) {
                        $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
                    },
                    submitHandler: function(form){
                        $('button[type=submit], input[type=submit]').attr('disabled', 'disabled');
                        form.submit();
                    }
                });
            });
        </script>
    <?php }
    osc_add_hook('admin_header', 'customHead', 10);
    osc_current_admin_theme_path( 'parts/header.php' ); ?>
<div id="widgets-page">
    <div class="widgets">
        <div id="item-form">
            <ul id="error_list"></ul>
            <form name="widget_form" action="<?php echo osc_admin_base_url(true); ?>" method="post">
                <input type="hidden" name="action" value="<?php echo ( $edit ? 'edit_widget_post' : 'add_widget_post' ); ?>" />
                <input type="hidden" name="page" value="appearance" />
                <?php if( $edit) { ?>
                <input type="hidden" name="id" value="<?php echo Params::getParam('id', true); ?>" />
                <?php } ?>
                <input type="hidden" name="location" value="<?php echo Params::getParam('location', true); ?>" />
                <fieldset>
                    <div class="input-line">
                        <label><?php _e('Description (for internal purposes only)'); ?></label>
                        <div class="input">
                            <input type="text" class="large" name="description" value="<?php if( $edit ) { echo osc_esc_html($widget['s_description']); } ?>" />
                        </div>
                    </div>
                    <div class="input-description-wide">
                        <label><?php _e('HTML Code for the Widget'); ?></label>
                        <textarea name="content" id="body"><?php if( $edit ) { echo osc_esc_html($widget['s_content']); } ?></textarea>
                    </div>
                    <div class="form-actions">
                        <input type="submit" value="<?php echo $button; ?>" class="btn btn-submit" />
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>