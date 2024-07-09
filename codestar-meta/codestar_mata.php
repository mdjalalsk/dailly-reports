<?php
/*
 * Plugin Name:       Code Star
 * Plugin URI:        https://woocopilot.com/plugins/woo-custom-price/
 * Description:       How to use Codestar framework for meta box filed.
 * Version:           1.0.1
 * Requires at least: 6.5
 * Requires PHP:      7.2
 * Author:            WooCopilot
 * Author URI:        https://woocopilot.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       code-star
 * Domain Path:       /languages
 */
//
/*
Code star is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Code star  is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with AFC Demo. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

require_once plugin_dir_path( __FILE__ ) . 'libs/cfm/codestar-framework.php';
function cs_load_textdomain()
{
    load_plugin_textdomain('code-star', false, plugin_basename(dirname(__FILE__)) . '/languages');
}

add_action('plugins_loaded', 'cs_load_textdomain');



