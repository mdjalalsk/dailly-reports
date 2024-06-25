<?php

/*
 * Plugin Name:       Word Count
 * Plugin URI:        https://woocopilot.com/plugins/woo-custom-price/
 * Description:       Total  word count in every Wordpress single post.
 * Version:           1.0.1
 * Requires at least: 6.5
 * Requires PHP:      7.2
 * Author:            WooCopilot
 * Author URI:        https://woocopilot.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       word-count
 * Domain Path:       /languages
 */
//
/*
Word count is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Word count is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with word count. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

function wordcount_activations(){

}
register_activation_hook(__FILE__, 'wordcount_activations');
//function wordcount_deactivation(){
//
//}
//register_deactivation_hook(__FILE__, 'wordcount_deactivation');


function wordcount_load_text_domain(){
    load_pligun_textdomain('word-count',false,dirname(__FILE__)."/languages");
}

add_action('loaded_plugins', 'wordcount_load_text_domain');

function wordcount_wordcount($content){
    do_action('wordcount_start');
    $stripped_content=strip_tags($content);
    $count_word=str_word_count($stripped_content);
    $label=__('Total number of Word','word-count');
    $label=apply_filters('wordcount_wordcount_label',$label,);
    $tags=apply_filters('wordcount_tag','h2');
    $content.=sprintf('<%s>%s:%s</%s>',$tags,$label,$count_word,$tags);
    return $content;

}

add_filter('the_content', 'wordcount_wordcount');
function wordcount_wordcount_label($label){
    $label="Total Words";
    return $label;
}
add_filter('wordcount_wordcount_label','wordcount_wordcount_label');
function wordcount_tag($tag){
$tag="h5";
return $tag;
}

add_filter('wordcount_tag','wordcount_tag');
