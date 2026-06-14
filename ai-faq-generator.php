<?php
/**
 * Plugin Name:       AI FAQ Generator
 * Plugin URI:        https://sitecroftdev.vercel.app/plugins/ai-faq-generator
 * Description:       Automatically generate SEO-friendly FAQ sections from content with schema support, shortcode support, Gutenberg integration, and modern FAQ display layouts.
 * Version:           1.0.0
 * Requires at least: 7.0
 * Requires PHP:      8.0
 * Author:            Saad
 * Author URI:        https://sitecroft.vercel.app/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ai-faq-generator
 * Domain Path:       /languages
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Global Core Constants using mandatory prefix.
define( 'AIFG_VERSION', '1.0.0' );
define( 'AIFG_PLUGIN_FILE', __FILE__ );
define( 'AIFG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AIFG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Safely load required files if they exist.
 * This is defined BEFORE it is called to prevent any fatal undefined function crashes.
 *
 * @param string $path File path.
 */
function aifg_require_file( $path ) {
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}

// Safely bundle and load all modular plugin layout logic mapping files.
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-activator.php' );
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-deactivator.php' );
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-plugin.php' );
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-admin.php' );
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-settings.php' );
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-generator.php' );
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-faq-set.php' );
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-shortcode.php' );
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-schema.php' );
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-frontend.php' );
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-gutenberg-block.php' );
aifg_require_file( AIFG_PLUGIN_DIR . 'includes/class-ajax.php' );

/**
 * Initialize core runtime engine.
 */
function aifg_run_plugin() {
	$plugin = new AIFG_Plugin();
	$plugin->run();
}
add_action( 'plugins_loaded', 'aifg_run_plugin' );