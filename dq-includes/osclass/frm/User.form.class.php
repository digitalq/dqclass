<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class UserForm extends Form {

        static public function primary_input_hidden($user) {
            parent::generic_input_hidden("id", (isset($user["pk_i_id"]) ? $user['pk_i_id'] : '') );
        }

        static public function name_text($user = null) {
            parent::generic_input_text("s_name", isset($user['s_name'])? $user['s_name'] : '', null, false);
        }

        static public function username_text($user = null) {
            parent::generic_input_text("s_username", isset($user['s_username'])? $user['s_username'] : '', null, false);
        }

        static public function email_login_text($user = null) {
            parent::generic_input_text("email", isset($user['s_email'])? $user['s_email'] : '', null, false);
        }

        static public function password_login_text($user = null) {
            parent::generic_password("password", '', null, false);
        }

        static public function rememberme_login_checkbox($user = null) {
            parent::generic_input_checkbox("remember", '1', false);
        }

        static public function old_password_text($user = null) {
            parent::generic_password("old_password", '', null, false);
        }

        static public function password_text($user = null) {
            parent::generic_password("s_password", '', null, false);
        }

        static public function check_password_text($user = null) {
            parent::generic_password("s_password2", '', null, false);
        }

        static public function email_text($user = null) {
            parent::generic_input_text("s_email", isset($user['s_email'])? $user['s_email'] : '', null, false);
        }

        static public function website_text($user = null) {
            parent::generic_input_text("s_website", isset($user['s_website'])? $user['s_website'] : '', null, false);
        }

        static public function mobile_text($user = null) {
            parent::generic_input_text("s_phone_mobile", isset($user['s_phone_mobile'])? $user['s_phone_mobile'] : '', null, false);
        }

        static public function phone_land_text($user = null) {
            parent::generic_input_text("s_phone_land", isset($user['s_phone_land'])? $user['s_phone_land'] : '', null, false);
        }

        static public function info_textarea($name, $locale = 'en_US', $value = '') {
            parent::generic_textarea($name . '[' . $locale . ']', $value);
        }

        static public function multilanguage_info($locales, $user = null) {
            $num_locales = count($locales);
            if($num_locales > 1) { echo '<div class="tabber">'; }
            foreach($locales as $locale) {
                if($num_locales>1) { echo '<div class="tabbertab">'; };
                    if($num_locales > 1) { echo '<h2>' . $locale['s_name'] . '</h2>'; }
                    $info = '';
                    if( is_array($user) ) {
                        if( isset($user['locale'][$locale['pk_c_code']])) {
                            if(isset($user['locale'][$locale['pk_c_code']]['s_info'])) {
                                $info = $user['locale'][$locale['pk_c_code']]['s_info'];
                            }
                        }
                    }
                    self::info_textarea('s_info', $locale['pk_c_code'], $info);
                if($num_locales>1) { echo '</div>'; };
            }
            if($num_locales>1) { echo '</div>'; };
        }

        static public function country_select($countries, $user = null) {
            if( count($countries) >= 1 ) {
                parent::generic_select('countryId', $countries, 'pk_c_code', 's_name', __('Select a country...'), (isset($user['fk_c_country_code'])) ? $user['fk_c_country_code'] : null);
            } else {
                parent::generic_input_text('country', (isset($user['s_country'])) ? $user['s_country'] : null);
            }
        }

        static public function country_text($user = null) {
            parent::generic_input_text('country', (isset($user['s_country'])) ? $user['s_country'] : null);
        }

        static public function region_select($regions, $user = null) {
            if( count($regions) >= 1 ) {
                parent::generic_select('regionId', $regions, 'pk_i_id', 's_name', __('Select a region...'), (isset($user['fk_i_region_id'])) ? $user['fk_i_region_id'] : null);
            } else {
                parent::generic_input_text('region', (isset($user['s_region'])) ? $user['s_region'] : null);
            }
        }

        static public function region_text($user = null) {
            parent::generic_input_text('region', (isset($user['s_region'])) ? $user['s_region'] : null);
        }

        static public function city_select($cities, $user = null) {
            if( count($cities) >= 1 ) {
                parent::generic_select('cityId', $cities, 'pk_i_id', 's_name', __('Select a city...'), (isset($user['fk_i_city_id'])) ? $user['fk_i_city_id'] : null);
            } else {
                parent::generic_input_text('city', (isset($user['s_city'])) ? $user['s_city'] : null);
            }
        }

        static public function city_text($user = null) {
            parent::generic_input_text('city', (isset($user['s_city'])) ? $user['s_city'] : null);
        }

        static public function city_area_text($user = null) {
            parent::generic_input_text('cityArea', (isset($user['s_city_area'])) ? $user['s_city_area'] : null);
        }

        static public function address_text($user = null) {
            parent::generic_input_text('address', (isset($user['s_address'])) ? $user['s_address'] : null);
        }

        static public function zip_text($user = null) {
            parent::generic_input_text('zip', (isset($user['s_zip'])) ? $user['s_zip'] : null);
        }

        static public function is_company_select($user = null) {
            $options = array(
                array( 'i_value' => '0', 's_text' => __('User') )
                ,array( 'i_value' => '1', 's_text' => __('Company') )
            );

            parent::generic_select( 'b_company', $options, 'i_value', 's_text', null, (isset($user['b_company'])) ? $user['b_company'] : null );
        }

        static public function user_select($users){
            Form::generic_select('userId', $users, 'pk_i_id', 's_name',  __('All') , NULL );
        }

        static public function js_validation() {
?>
<script type="text/javascript">
    $(document).ready(function(){
        // Code for form validation
        $("form[name=register]").validate({
            rules: {
                s_name: {
                    required: true
                },
                s_email: {
                    required: true,
                    email: true
                },
                s_password: {
                    required: true,
                    minlength: 5
                },
                s_password2: {
                    required: true,
                    minlength: 5,
                    equalTo: "#s_password"
                }
            },
            messages: {
                s_name: {
                    required: "<?php _e("Name: this field is required"); ?>."
                },
                s_email: {
                    required: "<?php _e("Email: this field is required"); ?>.",
                    email: "<?php _e("Invalid email address"); ?>."
                },
                s_password: {
                    required: "<?php _e("Password: this field is required"); ?>.",
                    minlength: "<?php _e("Password: enter at least 5 characters"); ?>."
                },
                s_password2: {
                    required: "<?php _e("Second password: this field is required"); ?>.",
                    minlength: "<?php _e("Second password: enter at least 5 characters"); ?>.",
                    equalTo: "<?php _e("Passwords don't match"); ?>."
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
<?php
        }

        static public function js_validation_old() {
?>
<script type="text/javascript">
$(document).ready(function(){
    $('#s_name').focus(function(){
        $('#s_name').css('border', '');
    });

    $('#s_email').focus(function(){
        $('#s_email').css('border', '');
    });

    $('#s_password').focus(function(){
        $('#s_password').css('border', '');
        $('#password-error').css('display', 'none');
    });

    $('#s_password2').focus(function(){
        $('#s_password2').css('border', '');
        $('#password-error').css('display', 'none');
    });
});

function checkForm() {
    var num_errors = 0;
    if( $('#s_name').val() == '' ) {
        $('#s_name').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if( $('#s_email').val() == '' ) {
        $('#s_email').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if( $('#s_password').val() != $('#s_password2').val() ) {
        $('#password-error').css('display', 'block');
        num_errors = num_errors + 1;
    }
    if( $('#s_password').val() == '' ) {
        $('#s_password').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if( $('#s_password2').val() == '' ) {
        $('#s_password2').css('border', '1px solid red');
        num_errors = num_errors + 1;
    }
    if(num_errors > 0) {
        return false;
    }

    return true;
}
</script>
<?php
        }

        static public function js_validation_edit() {
?>
<script type="text/javascript">
    $(document).ready(function(){
        // Code for form validation
        $("form[name=register]").validate({
            rules: {
                s_name: {
                    required: true
                },
                s_email: {
                    required: true,
                    email: true
                },
                s_password: {
                    minlength: 5
                },
                s_password2: {
                    minlength: 5,
                    equalTo: "#s_password"
                }
            },
            messages: {
                s_name: {
                    required: "<?php _e("Name: this field is required"); ?>."
                },
                s_email: {
                    required: "<?php _e("Email: this field is required"); ?>.",
                    email: "<?php _e("Invalid email address"); ?>."
                },
                s_password: {
                    minlength: "<?php _e("Password: enter at least 5 characters"); ?>."
                },
                s_password2: {
                    minlength: "<?php _e("Second password: enter at least 5 characters"); ?>.",
                    equalTo: "<?php _e("Passwords don't match"); ?>."
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
<?php
        }

        static public function location_javascript($path = 'front') {
?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#countryId").on("change",function(){
            var pk_c_code = $(this).val();
            <?php if($path=="admin") { ?>
                var url = '<?php echo osc_admin_base_url(true)."?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
            <?php } else { ?>
                var url = '<?php echo osc_base_url(true)."?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
            <?php }; ?>
            var result = '';

            if(pk_c_code != '') {

                $("#regionId").attr('disabled',false);
                $("#cityId").attr('disabled',true);
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    success: function(data){
                        var length = data.length;
                        if(length > 0) {
                            result += '<option value=""><?php _e("Select a region..."); ?></option>';
                            for(key in data) {
                                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                            }
                            $("#region").before('<select name="regionId" id="regionId" ></select>');
                            $("#region").remove();

                            $("#city").before('<select name="cityId" id="cityId" ></select>');
                            $("#city").remove();

                        } else {
                            result += '<option value=""><?php _e('No results') ?></option>';
                            $("#regionId").before('<input type="text" name="region" id="region" />');
                            $("#regionId").remove();

                            $("#cityId").before('<input type="text" name="city" id="city" />');
                            $("#cityId").remove();
                        }
                        $("#regionId").html(result);
                        $("#cityId").html('<option selected value=""><?php _e("Select a city..."); ?></option>');
                    }
                 });
             } else {
                 // add empty select
                 $("#region").before('<select name="regionId" id="regionId" ><option value=""><?php _e("Select a region..."); ?></option></select>');
                 $("#region").remove();

                 $("#city").before('<select name="cityId" id="cityId" ><option value=""><?php _e("Select a city..."); ?></option></select>');
                 $("#city").remove();

                 if( $("#regionId").length > 0 ){
                     $("#regionId").html('<option value=""><?php _e("Select a region..."); ?></option>');
                 } else {
                     $("#region").before('<select name="regionId" id="regionId" ><option value=""><?php _e("Select a region..."); ?></option></select>');
                     $("#region").remove();
                 }
                 if( $("#cityId").length > 0 ){
                     $("#cityId").html('<option value=""><?php _e("Select a city..."); ?></option>');
                 } else {
                     $("#city").before('<select name="cityId" id="cityId" ><option value=""><?php _e("Select a city..."); ?></option></select>');
                     $("#city").remove();
                 }

                $("#regionId").attr('disabled',true);
                $("#cityId").attr('disabled',true);
             }
        });

        $("#regionId").on("change",function(){
            var pk_c_code = $(this).val();
            <?php if($path=="admin") { ?>
                var url = '<?php echo osc_admin_base_url(true)."?page=ajax&action=cities&regionId="; ?>' + pk_c_code;
            <?php } else { ?>
                var url = '<?php echo osc_base_url(true)."?page=ajax&action=cities&regionId="; ?>' + pk_c_code;
            <?php }; ?>

            var result = '';

            if(pk_c_code != '') {

                $("#cityId").attr('disabled',false);
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    success: function(data){
                        var length = data.length;
                        if(length > 0) {
                            result += '<option selected value=""><?php _e("Select a city..."); ?></option>';
                            for(key in data) {
                                result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                            }
                            $("#city").before('<select name="cityId" id="cityId" ></select>');
                            $("#city").remove();
                        } else {
                            result += '<option value=""><?php _e('No results') ?></option>';
                            $("#cityId").before('<input type="text" name="city" id="city" />');
                            $("#cityId").remove();
                        }
                        $("#cityId").html(result);
                    }
                 });
             } else {
                $("#cityId").attr('disabled',true);
             }
        });


        if( $("#regionId").attr('value') == "") {
            $("#cityId").attr('disabled',true);
        }

        if( $("#countryId").prop('type').match(/select-one/) ) {
            if( $("#countryId").attr('value') == "") {
                $("#regionId").attr('disabled',true);
            }
        }
    });
</script>
    <?php
        }
    }

    /* file end: ./dq-includes/osclass/frm/User.form.class.php */
?>
