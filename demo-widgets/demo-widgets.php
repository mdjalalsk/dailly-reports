<?php
/*
 * Plugin Name:       Demo Widgets
 * Plugin URI:        https://woocopilot.com/plugins/woo-custom-price/
 * Description:       The Individual Order Notice plugin is designed to enhance the customer experience on your WooCommerce store by enforcing a strict order limit and providing clear notifications when the limit is exceeded. This plugin ensures that customers can only place one order at a time, preventing multiple orders and potential stock issues.
 * Version:           1.0.1
 * Requires at least: 6.5
 * Requires PHP:      7.2
 * Author:            WooCopilot
 * Author URI:        https://woocopilot.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       demo-widgets
 * Domain Path:       /languages
 */
/*
Demo Widgets is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Demo Widgets is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Demo Widgets. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/
defined('ABSPATH') || exit;

require_once dirname( __FILE__ ) . '/inc/class-demo-widgets.php';


function demo_load_widget() {
    register_widget('Demo_Widgets');
}
add_action('widgets_init', 'demo_load_widget');
?>