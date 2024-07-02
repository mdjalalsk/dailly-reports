<?php

/*
 * Plugin Name:       Post to QR Code
 * Plugin URI:        https://woocopilot.com/plugins/woo-custom-price/
 * Description:       Total  word count in every Wordpress single post.
 * Version:           1.0.1
 * Requires at least: 6.5
 * Requires PHP:      7.2
 * Author:            WooCopilot
 * Author URI:        https://woocopilot.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       post-to-qrcode
 * Domain Path:       /languages
 */
//
/*
Post to QR code is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Post to QR code is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Post to QR code. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

//function wordcount_activations()
//{
//
//}
//register_activation_hook(__FILE__, 'wordcount_activations');
//function wordcount_deactivation(){
//
//}
//register_deactivation_hook(__FILE__, 'wordcount_deactivation');


$postqrcode_countries = array(
    __("none", "post-to-qrcode"),
    __("Afghanistan", "post-to-qrcode"),
    __("Bangladesh", "post-to-qrcode"),
    __("Bhutan", "post-to-qrcode"),
    __("India", "post-to-qrcode"),
    __("Maldives", "post-to-qrcode"),
    __("Nepal", "post-to-qrcode"),
    __("Pakistan", "post-to-qrcode")
);


function postqrcode_load_text_domain()
{
    load_pligun_textdomain('post-to-qrcode', false, dirname(__FILE__) . "/languages");
}

add_action('loaded_plugins', 'postqrcode_load_text_domain');

function postqrcode_qrcode_genarate($content)
{
    $current_post_id = get_the_ID();
    $current_post_title = get_the_title($current_post_id);
    $current_post_url = urlencode(get_the_permalink($current_post_id));
    $current_post_type=get_post_type($current_post_id);

    /*
     * Check post type
     * */
    $exclude_post_types=apply_filters('qrcode_exclude_post_type', array());

    if(in_array($current_post_type, $exclude_post_types)){
        return $content;
    }
    $setting_filed_height = get_option('ptq_height');
    $setting_filed_width = get_option('ptq_width');
    $height=$setting_filed_height?$setting_filed_height:180;
    $width=$setting_filed_width?$setting_filed_width:180;
    $daimension=apply_filters('qrcode_daimension', "{$height}x{$width}");

    $image_src = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s',$daimension,$current_post_url);
    $content .= sprintf("<div class='qrcode'><img src='%s' alt='%s' /></div>", $image_src,$current_post_title);
    return $content;
}

add_filter('the_content', 'postqrcode_qrcode_genarate');
function postqrcode_exclude_post_type($post_types)
{
$post_types[]='page';
//array_push($post_types,'page')
return $post_types;
}

add_filter('qrcode_exclude_post_type', 'postqrcode_exclude_post_type');
function postqrcode_settings_init(){

//    function add_settings_section(     $id,     $title,     $callback,     $page,     $args = array() ): void
//Adds a new section to a settings page.
//    Part of the Settings API. Use this to define new settings sections for an admin page. Show settings sections in your admin page callback function with do_settings_sections(). Add settings fields to your section with add_settings_field().
//    The $callback argument should be the name of a function that echoes out any content you want to show at the top of the settings section before the actual fields. It can output nothing if you want.
//    Parameters:
//string $id – Slug-name to identify the section. Used in the 'id' attribute of tags. string $title – Formatted title of the section. Shown as the heading for the section. callable $callback – Function that echos out any content at the top of the section (between heading and fields). string $page – The slug-name of the settings page on which to show the section. Built-in pages include 'general', 'reading', 'writing', 'discussion', 'media', etc. Create your own using add_options_page(); array $args – { Arguments used to create the settings section.
//    Since:

    add_settings_section('ptq_sections',__('Post To QR Code','post-to-qrcode',),'posttqrcode_settings_section','general');

//    function add_settings_section(     $id,     $title,     $callback,     $page,     $args = array() ): void
//Adds a new section to a settings page.
//    Part of the Settings API. Use this to define new settings sections for an admin page. Show settings sections in your admin page callback function with do_settings_sections(). Add settings fields to your section with add_settings_field().
//    The $callback argument should be the name of a function that echoes out any content you want to show at the top of the settings section before the actual fields. It can output nothing if you want.
//    Parameters:
//string $id – Slug-name to identify the section. Used in the 'id' attribute of tags. string $title – Formatted title of the section. Shown as the heading for the section. callable $callback – Function that echos out any content at the top of the section (between heading and fields). string $page – The slug-name of the settings page on which to show the section. Built-in pages include 'general', 'reading', 'writing', 'discussion', 'media', etc. Create your own using add_options_page(); array $args – { Arguments used to create the settings section.
//    Since:

    add_settings_field('ptq_height', __('QR Code Height', 'post-to-qrcode'), 'postqrcode_settings_height_field', 'general','ptq_sections');
    add_settings_field('ptq_width', __('QR Code Width', 'post-to-qrcode'), 'postqrcode_settings_width_field', 'general','ptq_sections');
   // add seclect options in setting options
    add_settings_field('ptq_select', __('Select Country', 'post-to-qrcode'), 'postqrcode_settings_select_field', 'general','ptq_sections');
   //add checkbox options in admin setting
    add_settings_field('ptq_checkbox', __('Favorite Country', 'post-to-qrcode'), 'postqrcode_settings_checkbox_field', 'general','ptq_sections');
    add_settings_field('ptq_toggle_field', __('Toggle field', 'post-to-qrcode'), 'postqrcode_settings_toggle_field', 'general','ptq_sections');

    //simplify callback functions for multiple filed
    add_settings_field('extrafiled1', __('Test Extra field1', 'post-to-qrcode'), 'postqrcode_settings_disply_field', 'general','ptq_sections',array('extrafiled1'));
    add_settings_field('filedextra', __('Test Extra field2', 'post-to-qrcode'), 'postqrcode_settings_disply_field', 'general','ptq_sections',array('filedextra'));

    //register filed
    register_setting('general', 'ptq_height', array('sanitize_callback' => 'esc_attr'));
    register_setting('general', 'ptq_width', array('sanitize_callback' => 'esc_attr'));

    register_setting('general', 'ptq_select', array('sanitize_callback' => 'esc_attr'));
    register_setting('general', 'ptq_checkbox');
    register_setting('general', 'ptq_toggle_field');

    register_setting('general', 'extrafiled1', array('sanitize_callback' => 'esc_attr'));
    register_setting('general', 'filedextra', array('sanitize_callback' => 'esc_attr'));
}

// checkbox options setting callback
function postqrcode_settings_checkbox_field(){
    global $postqrcode_countries;
    $options = get_option('ptq_checkbox');
    $countries=apply_filters('ptq_countries',$postqrcode_countries);
//    $countries = array(
//        "Afghanistan",
//        "Bangladesh",
//        "Bhutan",
//        "India",
//        "Maldives",
//        "Nepal",
//        "Pakistan"
//    );

    foreach($countries as $country) {
        $checked='';
        if(is_array($options)&& in_array($country, $options)){
     $checked='checked';
        }
        printf("<input type='checkbox' name='ptq_checkbox[]' value='%s' %s>%s<br>", esc_attr($country), $checked, esc_html($country));
    }
}
// seclect options setting callback
function postqrcode_settings_select_field()
{
    global $postqrcode_countries;
    $options = get_option('ptq_select');
    $countries=apply_filters('ptq_countries',$postqrcode_countries);
//    $countries = array(
//        "Afghanistan",
//        "Bangladesh",
//        "Bhutan",
//        "India",
//        "Maldives",
//        "Nepal",
//        "Pakistan"
//    );

    printf("<select id='%s' name='%s'>", 'ptq_select', 'ptq_select');
    foreach($countries as $country) {
        $selected = ($options == $country) ? 'selected' : '';
        printf("<option value='%s' %s>%s</option>", esc_attr($country), $selected, esc_html($country));
    }
    printf("</select>");

}
function posttqrcode_settings_section(){
echo "<p>".__("Setting for Post To QR code Plugin","post-to-qrcode")."</p>";

}
// One callback show multiple add setting field
function postqrcode_settings_disply_field($args){
    $options = get_option($args[0]);
    printf("<input type='text' id='%s' name='%s' value='%s' />", $args[0],$args[0] , esc_attr($options));


}
function postqrcode_settings_height_field(){
    $height = get_option('ptq_height');
    printf("<input type='text' id='%s' name='%s' value='%s' />", 'ptq_height', 'ptq_height', esc_attr($height));
}

function postqrcode_settings_width_field(){
    $width = get_option('ptq_width');
    printf("<input type='text' id='%s' name='%s' value='%s' />", 'ptq_width', 'ptq_width', esc_attr($width));
}

function ptq_countries_list($countries){
    array_push($countries,'Argentina');
    return $countries;
}

//Country arrar change add_filter
add_filter('ptq_countries','ptq_countries_list');
add_action('admin_init', 'postqrcode_settings_init');


function  postqrcode_settings_toggle_field(){
    $options = get_option('ptq_toggle_field');
  echo '<div id="toggle1"></div>';
    echo "<input type='hidden' id='ptq_toggle_field' name='ptq_toggle_field' value='" . esc_attr($options) . "'/>";
}
// load js and css for toggle feild
function postqrcode_assets($hook_suffix) {
    if ('options-general.php' == $hook_suffix) {
        wp_enqueue_style('ptq-minitoogle-css', plugin_dir_url(__FILE__) . 'assets/css/minitoggle.css');
        wp_enqueue_script('ptq-js-minitoogle', plugin_dir_url(__FILE__) . 'assets/js/minitoggle.js', array('jquery'), '1.0', true);
        wp_enqueue_script('postqrcode-main-js', plugin_dir_url(__FILE__) . 'assets/js/ptq-main.js', array('jquery'), time(), true);
    }
}

// Enqueue assets
add_action('admin_enqueue_scripts', 'postqrcode_assets');









