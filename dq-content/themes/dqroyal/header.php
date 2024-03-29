<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */
?>
<!-- container -->
<div class="container">
<!-- header -->
<div id="header">
    <a id="logo" href="<?php echo osc_base_url(); ?>">
    <?php echo logo_header(); ?>
    </a>
    <div id="user_menu">
        <ul>
            <?php if(osc_users_enabled()) { ?>
                <?php if( osc_is_web_user_logged_in() ) { ?>
                    <li class="first logged">
                        <?php echo sprintf(__('Hi %s', 'dqroyal'), osc_logged_user_name() . '!'); ?>  &middot;
                        <strong><a href="<?php echo osc_user_dashboard_url(); ?>"><?php _e('My account', 'dqroyal'); ?></a></strong> &middot;
                        <a href="<?php echo osc_user_logout_url(); ?>"><?php _e('Logout', 'dqroyal'); ?></a>
                    </li>
                <?php } else { ?>
                    <li class="first">
                        <a id="login_open" href="<?php echo osc_user_login_url(); ?>"><?php _e('Login', 'dqroyal'); ?></a>
                        <?php if(osc_user_registration_enabled()) { ?>
                            &middot;
                            <a href="<?php echo osc_register_account_url(); ?>"><?php _e('Register for a free account', 'dqroyal'); ?></a>
                        
                        <?php }; ?>
                        <form id="login" action="<?php echo osc_base_url(true); ?>" method="post">
                            <fieldset>
                                <input type="hidden" name="page" value="login" />
                                <input type="hidden" name="action" value="login_post" />
                                <label for="email"><?php _e('E-mail', 'dqroyal'); ?></label><br/>
                                <?php UserForm::email_login_text(); ?><br/>
                                <label for="password"><?php _e('Password', 'dqroyal'); ?></label><br/>
                                <?php UserForm::password_login_text(); ?>
                                <p class="checkbox"><?php UserForm::rememberme_login_checkbox();?> <label for="remember"><?php _e('Remember me', 'dqroyal'); ?></label></p>
                                <button type="submit"><?php _e('Log in', 'dqroyal'); ?></button>
                                <div class="forgot">
                                    <a href="<?php echo osc_recover_user_password_url(); ?>"><?php _e("Forgot password?", 'dqroyal');?></a>
                                </div>
                            </fieldset>
                        </form>
                    </li>
                <?php } ?>
            <?php } ?>

        <div class="fb-login-button" data-max-rows="1" data-size="medium" data-show-faces="false" data-auto-logout-link="false"></div>



            <?php if ( osc_count_web_enabled_locales() > 1) { ?>
                <?php osc_goto_first_locale(); ?>
                <li class="last with_sub">
                    <strong><?php _e("Language", 'dqroyal'); ?></strong>
                    <ul>
                        <?php $i = 0;  ?>
                        <?php while ( osc_has_web_enabled_locales() ) { ?>
                            <li <?php if( $i == 0 ) { echo "class='first'"; } ?>><a id="<?php echo osc_locale_code(); ?>" href="<?php echo osc_change_language_url ( osc_locale_code() ); ?>"><?php echo osc_locale_name(); ?></a></li>
                            <?php $i++; ?>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>

        <?php if( osc_users_enabled() || ( !osc_users_enabled() && !osc_reg_user_post() )) { ?>
            <div class="form_publish">
                <strong class="publish_button"><a href="<?php echo osc_item_post_url_in_category(); ?>"><?php _e("Publish your ad for free", 'dqroyal');?></a></strong>
            </div>
        <?php } ?>
        <div class="empty"></div>
    </div>
</div>

<!-- Facebook Login header -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=114647455311278";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<!-- /Facebook Login header -->

<div class="clear"></div>
<!-- /header -->
<?php
    osc_show_widgets('header');

    $breadcrumb = osc_breadcrumb('&raquo;', false);
    if( $breadcrumb != '') { ?>
    <div class="breadcrumb">
        <?php echo $breadcrumb; ?>
        <div class="clear"></div>
    </div>
<?php
    }
?>
<div class="forcemessages-inline">
    <?php osc_show_flash_message(); ?>
</div>
