<?php
/*
 * Plugin Name:       Meta Box
 * Plugin URI:        https://woocopilot.com/plugins/woo-custom-price/
 * Description:       Meta Box practices for wp
 * Version:           1.0.1
 * Requires at least: 6.5
 * Requires PHP:      7.2
 * Author:            WooCopilot
 * Author URI:        https://woocopilot.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       meta-box
 * Domain Path:       /languages
 */

/*
Meta Box is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Meta Box is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Meta Box. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

defined( 'ABSPATH' ) || exit; // Exist if accessed directly.

require_once __DIR__ . '/includes/class-metabox.php';

/**
 * Initializing plugin.
 *
 * @since 1.0.0
 * @return Object Plugin object.
 */
function wp_metabox() {
    return new Wp_Mata_Box( __FILE__, '1.0.1' );
}
wp_metabox();