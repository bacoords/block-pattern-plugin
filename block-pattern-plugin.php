<?php
/**
 * Plugin Name:       Block Pattern Plugin
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       block-pattern-plugin
 *
 * @package           wpdev
 */

namespace wpdev\block_pattern_plugin;

define( 'BLOCK_PATTERN_PLUGIN_VERSION', '0.1.0' );
define( 'BLOCK_PATTERN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BLOCK_PATTERN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


require plugin_dir_path( __FILE__ ) . 'inc/admin.php';
require plugin_dir_path( __FILE__ ) . 'inc/editor.php';
require plugin_dir_path( __FILE__ ) . 'inc/export.php';
