<?php
/*
* Plugin Name:       Shortcode Test
* Plugin URI:        https://woocopilot.com/plugins/woo-custom-price/
* Description:       This plugin is practice for shortcode in wp.
* Version:           1.0.1
* Requires at least: 6.5
* Requires PHP:      7.2
* Author:            WooCopilot
* Author URI:        https://woocopilot.com/
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:       shortcode-test
* Domain Path:       /languages
*/
//
/*
Shortcode Test code is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Shortcode Test is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Post to Shortcode Test. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

function st_load_plugin_textdomain()
{
    load_plugin_textdomain('shortcode-test', false, dirname(__FILE__) . "/languages");
}

add_action('plugins_loaded','st_load_plugin_textdomain');

// button shortcode using only parameter
//shortcode= [button url="https://wordpress.org/plugins/shortcodes-ultimate/" label="Baba Button"/]
function shortcode_test_button($attributes)
{
    $deafults=array(
        'url'=>'',
        'label'=>__("Deafults Button",'shortcode-test'),
    );
    $btn_attributes=shortcode_atts($deafults,$attributes);
return sprintf('<a target="_blank" class="wp-block-button__link wp-element-button" href="%s">%s</a>',
    $btn_attributes['url'],
    $btn_attributes['label']
);
}
add_shortcode( 'button', 'shortcode_test_button' );


//button shortcut using content and nested shortcode.
//Shortcode=[button2 url="https://wordpress.org/plugins/shortcodes-ultimate/"]Babar Dorbar[/button2]
function shortcode_test_button2($attributes,$label)
{
return sprintf('<a target="_blank" class="wp-block-button__link wp-element-button" href="%s">%s</a>',
    $attributes['url'],
    do_shortcode($label)
);
}
add_shortcode( 'button2', 'shortcode_test_button2' );

function shortcode_text_uppercase($attributes,$content='')
{
return strtoupper(do_shortcode($content));
}
add_shortcode('uc','shortcode_text_uppercase');
//Shortcode for Google Map
function google_map_shortcode($attributes)
{
    $deafults=array(
        'place'=>"Mirpur",
        'width'=> 600,
        'height'=>500,
    );
    $btn_attributes=shortcode_atts($deafults,$attributes);
    $place = urlencode($btn_attributes['place']);
    $width = esc_attr($btn_attributes['width']);
    $height = esc_attr($btn_attributes['height']);
    $iframe = '<iframe 
        width="' . $width . '" 
        height="' . $height . '" 
        src="https://maps.google.com/maps?q=' . $place . '&output=embed"
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy">
    </iframe>';
    return $iframe;
}

add_shortcode('google_map', 'google_map_shortcode');

//tiny slider assets management
function tainy_image_size_init(){
    add_image_size( 'tainy_thumb', 600, 400, true );
}
add_action('init','tainy_image_size_init');
function tainy_slider_scripts()
{
    wp_enqueue_style('tainy-slider-style', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css', null, '1.0');
    wp_enqueue_script('tainy-slider-js', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js', null, '1.0', true);
    wp_enqueue_script('main-slider-js', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'tainy_slider_scripts');
// shortcode for slider
function tnyslider_shortcode($arguments,$content){
    $deafults=array(
        'width'=>600,
        'height'=>400,
        'id'=>''
    );
    $attributes=shortcode_atts($deafults,$arguments);
    $content=do_shortcode($content);
    $shortcode_output=<<<EOD
     <div id="{$attributes['id']}" style="width: {$attributes['width']};height: {$attributes['height']};">
    <div class="slider">
        {$content}
     </div>
     </div>
    EOD;
    return $shortcode_output;
}
function tnyslide_shortcode($arguments){
    $deafults=array(
        'caption'=>600,
        'id'=>'',
        'size'=>'tainy_thumb'
    );
    $attributes=shortcode_atts($deafults,$arguments);
    $image_src=wp_get_attachment_image_src( $attributes['id'], $attributes['size'] );
    $shortcode_output=<<<EOD
     <div class="slide">
     <p> <img src="{$image_src[0]}" alt="{$attributes['caption']}"> </p>
     <p>{$attributes['caption']}</p>
</div>
EOD;
return$shortcode_output;

}

add_shortcode('tnyslider', 'tnyslider_shortcode');
add_shortcode('tnyslide', 'tnyslide_shortcode');

