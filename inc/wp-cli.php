<?php
/**
 * Import functions. Much of this code was forked from the Pattern Editor plugin.
 *
 * @see https://github.com/blockifywp/pattern-editor
 *
 * @package block-pattern-plugin
 */

namespace wpdev\block_pattern_plugin\wpcli;

use WP_CLI;


/**
 * Import block patterns.
 */
function import_block_patterns_command() {
	\wpdev\block_pattern_plugin\import\import_block_patterns();

	WP_CLI::success( 'Block patterns imported. I assume.' );
}

\WP_CLI::add_command( 'wpdev block-pattern import', __NAMESPACE__ . '\import_block_patterns_command' );


/**
 * Export block patterns.
 */
function export_block_patterns_command() {

	// Needs batch export function first.

	// \wpdev\block_pattern_plugin\export\export_block_patterns();

	WP_CLI::success( 'Block patterns exported (ok this does not actually exist).' );
}

\WP_CLI::add_command( 'wpdev block-pattern export', __NAMESPACE__ . '\export_block_patterns_command' );
