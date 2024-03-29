<?php if ( (!defined('ABS_PATH')) ) exit('ABS_PATH is not loaded. Direct access is not allowed.'); ?>
<?php if ( !OC_ADMIN ) exit('User access is not allowed.'); ?>
<?php if( !osc_get_preference('footer_link', 'digitalq_theme') && !osc_get_preference('donation', 'digitalq_theme') ) { ?>
<form name="_xclick" action="https://www.paypal.com/in/cgi-bin/webscr" method="post" class="nocsrf">
    <input type="hidden" name="cmd" value="_donations">
    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="business" value="info@osclass.org">
    <input type="hidden" name="item_name" value="Osclass project">
    <input type="hidden" name="return" value="http://osclass.org/paypal/">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="lc" value="US" />
    <input type="hidden" name="custom" value="<?php echo osc_admin_render_theme_url('dq-content/themes/digitalq/admin/settings.php'); ?>&donation=successful&source=digitalq">
    <div id="flashmessage" class="flashmessage flashmessage-inline flashmessage-warning" style="color: #505050; display: block; ">
        <p><?php _e('I would like to contribute to the development of Osclass with a donation of', 'digitalq'); ?> <select name="amount" class="select-box-medium">
            <option value="50">50$</option>
            <option value="25">25$</option>
            <option value="10" selected>10$</option>
            <option value="5">5$</option>
            <option value=""><?php _e('Custom', 'digitalq'); ?></option>
        </select><input type="submit" class="btn btn-mini" name="submit" value="<?php echo osc_esc_html(__('Donate', 'digitalq')); ?>"></p>
    </div>
</form>
<?php } ?>
<h2 class="render-title <?php echo (osc_get_preference('footer_link', 'digitalq_theme') ? '' : 'separate-top'); ?>"><?php _e('Theme settings', 'digitalq'); ?></h2>
<form action="<?php echo osc_admin_render_theme_url('dq-content/themes/digitalq/admin/settings.php'); ?>" method="post" class="nocsrf">
    <input type="hidden" name="action_specific" value="settings" />
    <fieldset>
        <div class="form-horizontal">
            <div class="form-row">
                <div class="form-label"><?php _e('Search placeholder', 'digitalq'); ?></div>
                <div class="form-controls"><input type="text" class="xlarge" name="keyword_placeholder" value="<?php echo osc_esc_html( osc_get_preference('keyword_placeholder', 'digitalq_theme') ); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Show lists as:', 'digitalq'); ?></div>
                <div class="form-controls">
                    <select name="defaultShowAs@all">
                        <option value="gallery" <?php if(digitalq_default_show_as() == 'gallery'){ echo 'selected="selected"' ; } ?>><?php _e('Gallery','digitalq'); ?></option>
                        <option value="list" <?php if(digitalq_default_show_as() == 'list'){ echo 'selected="selected"' ; } ?>><?php _e('List','digitalq'); ?></option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-label"><?php _e('Footer link', 'digitalq'); ?></div>
                <div class="form-controls">
                    <div class="form-label-checkbox"><input type="checkbox" name="footer_link" value="1" <?php echo (osc_get_preference('footer_link', 'digitalq_theme') ? 'checked' : ''); ?> > <?php _e('I want to help Osclass by linking to <a href="http://osclass.org/" target="_blank">osclass.org</a> from my site with the following text:', 'digitalq'); ?></div>
                    <span class="help-box"><?php _e('This website is proudly using the <a title="Osclass web" href="http://osclass.org/">classifieds scripts</a> software <strong>Osclass</strong>', 'digitalq'); ?></span>
                </div>
            </div>
            <div class="form-actions">
                <input type="submit" value="<?php _e('Save changes', 'digitalq'); ?>" class="btn btn-submit">
            </div>
        </div>
    </fieldset>
</form>