<?php
/*
Plugin Name: Kenny Login
Plugin URI: http://www.wastedpotential.com
Description: Kenny Loggins + Wordpress Login = Kenny Login! Customize your Wordpress login with awesometude! <a href="options-general.php?page=kenny-login">Settings can be found here</a>
Version: 1.0.0
Author: Andy Watt
Author URI: http://www.wastedpotential.com
License: GPLv2 or later
*/


//set up custom login css:
function kenny_register_styles() {
    wp_register_style('kenny_style', plugins_url('styles/kenny-login-topgun.css', __FILE__));
    wp_enqueue_style('kenny_style');
}
add_action( 'login_enqueue_scripts', 'kenny_register_styles' );



//change the login button text:
function kenny_custom_submit_text ( $text ) {
    if ($text == 'Log In'){$text = 'Log in to the Danger Zone';}
    return $text;
}
add_filter( 'gettext', 'kenny_custom_submit_text' );



//add HTML markup to login page:
function kenny_login_image(  ) {
    $html = '<p class="kenny-image"><a';

    $options = get_option('kenny_options');
    if ($options['kenny_login_add_link']) {
        $html .= ' href="http://www.kennyloggins.com" target="_blank"';
    }
    $html .= '></a></p>';
    return $html;
}
add_filter('login_message', 'kenny_login_image');



// add the admin options page
function kenny_admin_add_page() {
    add_options_page('Kenny Login Settings', 'Kenny Login', 'manage_options', 'kenny-login', 'kenny_admin_options_page');
}
add_action('admin_menu', 'kenny_admin_add_page');



//set up options page:
function kenny_admin_options_page() {
    ?>
    <div>
        <h1>Kenny Login</h1>
        <form action="options.php" method="post">
            <?php settings_fields('kenny_default_options'); ?>
            <?php do_settings_sections('kenny_main_section'); ?>

            <input name="Submit" class="button-primary" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form></div>

<?php
}


function kenny_admin_init(){
    register_setting( 'kenny_default_options', 'kenny_options', 'kenny_options_validate' );
    add_settings_section('kenny_main', '', 'kenny_admin_section_text', 'kenny_main_section');
    add_settings_field('add_link', 'Show Some Love', 'kenny_admin_add_link', 'kenny_main_section', 'kenny_main');
}
add_action('admin_init', 'kenny_admin_init');


function kenny_admin_section_text() {
    //do nothing
    //echo '<p>Main description of this section here.</p>';
}


function kenny_admin_add_link() {
    $options = get_option('kenny_options');
    $html = "<input type='checkbox' id='kenny_login_add_link' name='kenny_options[kenny_login_add_link]' value='1' " . checked(1, $options['kenny_login_add_link'], false) . '/>';
    $html .= '<label for="kenny_login_add_link">Link Kenny\'s image to <a href="http://www.kennyloggins.com" target="_blank">kennyloggins.com</a></label>';
    echo $html;
}


function kenny_options_validate($input) {
    $options = get_option('kenny_options');
    $options['kenny_login_add_link'] = $input['kenny_login_add_link'];
    return $options;
}