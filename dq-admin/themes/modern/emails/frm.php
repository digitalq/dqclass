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

    osc_enqueue_script('tiny_mce');

    function customPageHeader(){ ?>
        <h1><?php _e('Settings'); ?></h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');
    //customize Head
    function customHead() { ?>
        <script type="text/javascript">
            tinyMCE.init({
                mode : "textareas",
                theme : "advanced",
                skin: "cirkuit",
                width: "100%",
                height: "440px",
                language: 'en',
                theme_advanced_toolbar_align : "left",
                theme_advanced_toolbar_location : "top",
                plugins : "adimage,advlink,media,contextmenu",
                entity_encoding : "raw",
                theme_advanced_buttons1_add : "forecolorpicker,fontsizeselect",
                theme_advanced_buttons2_add: "media",
                theme_advanced_buttons3: "",
                theme_advanced_disable : "styleselect,anchor",
                file_browser_callback : "ajaxfilemanager",
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

            $(document).ready(function(){
                // dialog filters
                $('#dialog-test-it').dialog({
                    autoOpen: false,
                    modal: true,
                    width: 360,
                    minHeight: 42,
                    title: '<?php echo osc_esc_js( __('Send email') ); ?>'
                });
                $('#btn-display-test-it').click(function(){
                    $('#dialog-test-it').dialog('open');
                    return false;
                });
                
                $('#btn-test-it').click(function() {
                    var name   = $('input[name*="#s_title"]:visible').attr('name');
                    var locale = name.replace("#s_title","");
                    
                    var idTinymce = locale+"#s_text";
                    
                    $.post('<?php echo osc_admin_base_url(true); ?>',
                    {  
                        page: 'ajax',
                        action: 'test_mail_template',
                        email:  $('input[name="test_email"]:visible').val(),
                        title:  $('input[name*="s_title"]:visible').val(),
                        body: escape(tinyMCE.get(idTinymce).getContent({format : 'html'}) )
                    },
                    function(data) {
                        alert(data.html); 
                    }, 'json');
                    return false;
                });
            });
            
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

    function customPageTitle($string) {
        return sprintf(__('Edit email template &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    $email      = __get("email");
    $aEmailVars = EmailVariables::newInstance()->getVariables( $email );
    
    $locales = OSCLocale::newInstance()->listAllEnabled();

    osc_current_admin_theme_path('parts/header.php'); ?>

<div class="grid-row no-bottom-margin">
    <div class="row-wrapper">
        <h2 class="render-title"><?php _e('Edit email template'); ?></h2>
    </div>
</div>
<div id="pretty-form">
    <div class="grid-row grid-100">
        <div class="row-wrapper">
            <div id="item-form">
                <div id="left-side">

                    <?php printLocaleTabs(); ?>
                     <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                        <input type="hidden" name="page" value="emails" />
                        <input type="hidden" name="action" value="edit_post" />
                        <?php PageForm::primary_input_hidden($email); ?>
                        <div id="left-side">
                            <?php printLocaleTitlePage($locales, $email); ?>
                            <div>
                                <label><?php _e('Internal name'); ?></label>
                                <?php PageForm::internal_name_input_text($email); ?>
                                <div class="flashmessage flashmessage-warning flashmessage-inline">
                                    <p><?php _e('Used to identify the email template'); ?></p>
                                </div>
                            </div>
                            <div class="input-description-wide">
                                <?php printLocaleDescriptionPage($locales, $email); ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-actions form-inline">
                            <input type="submit" value="<?php echo osc_esc_html(__('Save changes')); ?>" class="btn btn-submit" />
                            <a id="btn-display-test-it" class="btn btn-submit"><?php _e('Test it'); ?></a>
                        </div>
                    </form>
                </div>
                <div id="right-side">
                    <div class="well ui-rounded-corners">
                        <h3 style="margin: 0;margin-bottom: 10px;text-align: center; color: #616161;"><?php _e('Legend'); ?></h3>
                        <?php foreach($aEmailVars as $key => $value) { ?>
                        <label><b><?php echo $key; ?></b><br/><?php echo $value;?></label><hr/>
                        <?php } ?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<div id="dialog-test-it" class="hide">
    <input type="text" name="test_email" class="input-actions"/>
    <input type="submit" id="btn-test-it" href="#" class="btn btn-blue submit-right" value="<?php _e('Send email'); ?>"/>
</div>
<?php osc_current_admin_theme_path('parts/footer.php'); ?>