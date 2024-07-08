<?php

/*
 * Plugin Name:       AFC Demo
 * Plugin URI:        https://woocopilot.com/plugins/woo-custom-price/
 * Description:       Total  word count in every Wordpress single post.
 * Version:           1.0.1
 * Requires at least: 6.5
 * Requires PHP:      7.2
 * Author:            WooCopilot
 * Author URI:        https://woocopilot.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       afc-demo
 * Domain Path:       /languages
 */
//
/*
AFC Demo is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

AFC Demo  is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with AFC Demo. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
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

add_action('plugins_loaded', 'wordcount_load_text_domain');
