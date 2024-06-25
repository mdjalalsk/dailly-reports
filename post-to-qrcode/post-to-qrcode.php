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

    $image_src = sprintf('https://api.qrserver.com/v1/create-qr-code/?size=185*185&ecc=L&qzone=1&data=%s',$current_post_url);
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






