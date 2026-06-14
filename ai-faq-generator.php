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

// Autoload class mapping.
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-activator.php' );
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-deactivator.php' );
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-plugin.php' );
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-admin.php' );
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-settings.php' );
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-generator.php' );
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-faq-set.php' );
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-shortcode.php' );
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-schema.php' );
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-frontend.php' );
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-gutenberg-block.php' );
require_ignore_exception( AIFG_PLUGIN_DIR . 'includes/class-ajax.php' );

/**
 * Safe helper to require files.
 *
 * @param string $path File path.
 */
function require_ignore_exception( $path ) {
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}

// Register Lifecycle Activation & Deactivation Hooks.
register_activation_hook( __FILE__, array( 'AIFG_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'AIFG_Deactivator', 'deactivate' ) );

/**
 * Initialize core runtime engine.
 */
function aifg_run_plugin() {
	$plugin = new AIFG_Plugin();
	$plugin->run();
}
add_action( 'plugins_loaded', 'aifg_run_plugin' );