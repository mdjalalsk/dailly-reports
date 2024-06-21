<?php
/*
 * Plugin Name:       Post View Counter
 * Plugin URI:        https://woocopilot.com/plugins/woo-custom-price/
 * Description:       Post View Counter is a robust plugin designed to track and display the number of views for each post on your WordPress site. The plugin records total views, daily views, and monthly views, providing detailed insights into your post performance. It features an easy-to-use interface within the WordPress admin area, where you can see view counts for all posts at a glance. Daily and monthly views are automatically reset to ensure accurate tracking. This plugin is perfect for bloggers, content creators, and website owners who want to understand their audience engagement better.
 * Version:           1.0.1
 * Requires at least: 6.5
 * Requires PHP:      7.2
 * Author:            WooCopilot
 * Author URI:        https://woocopilot.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       post_view_counter
 * Domain Path:       /languages
 */
//
/*
Post View Counter is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Post View Counter is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Post View Counter. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/
defined( 'ABSPATH' ) || exit;

// Including classes.
require_once __DIR__ . '/inc/class-post-view-counter.php';

/**
 * function post view counter.
 *
 * @since 1.0.0
 */
function Post_View_Counter()
{
 return new Post_View_Counter(__FILE__, '1.0.1' );
}
Post_View_Counter();




