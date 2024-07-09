<?php
/*
 * Plugin Name:       AFC Demo
 * Plugin URI:        https://woocopilot.com/plugins/woo-custom-price/
 * Description:       How to use ACF and TGM framework for meta box filed;
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

require_once dirname(__FILE__) . '/libs/class-tgm-plugin-activation.php';
require_once dirname(__FILE__) . '/inc/demo_matabox.php';

function acf_tgm_load_textdomain()
{
    load_plugin_textdomain('acf-demo', false, plugin_basename(dirname(__FILE__)) . '/languages');
}

add_action('plugins_loaded', 'acf_tgm_load_textdomain');

function afc_tgm_register_required_plugins() {
    $plugins = array(
        // This is an example of how to include a plugin from the WordPress Plugin Repository.
        array(
            'name'      => 'Advanced Custom Fields',
            'slug'      => 'advanced-custom-fields',
            'required'  => true,
        ),

    );
    $config = array(
        'id'           => 'afc-demo',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug'  => 'plugins.php',            // Parent menu slug.
        'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

    );
    tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'afc_tgm_register_required_plugins' );
//hide for setting menu options
add_filter('acf/settings/show_admin', '__return_false');


