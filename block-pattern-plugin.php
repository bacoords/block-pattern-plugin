<?php
/**
 * Plugin Name:       Block Pattern Plugin
 * Description:       Utility plugin to handle import/export of plugins to themes.
 * Requires at least: 6.6
 * Requires PHP:      8.1
 * Version:           0.1.0
 * Author:            Brian Coords
 * Author URI:        https://www.briancoords.com
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

if ( 'local' === wp_get_environment_type() ) {
	require plugin_dir_path( __FILE__ ) . 'inc/admin.php';
	require plugin_dir_path( __FILE__ ) . 'inc/export.php';
	require plugin_dir_path( __FILE__ ) . 'inc/import.php';
}



if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require plugin_dir_path( __FILE__ ) . 'inc/wp-cli.php';
}
