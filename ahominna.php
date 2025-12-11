<?php
/**
 * Plugin Name: AhoMINNA
 * Plugin URI: https://github.com/datnong1210-arch/minna
 * Description: Hệ thống học tiếng Nhật với 50 bài Minna no Nihongo - Japanese Learning System with Minna no Nihongo 50 lessons for Vietnamese learners
 * Version: 1.0.0
 * Author: DatNong
 * Author URI: https://github.com/datnong1210-arch
 * Text Domain: ahominna
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('AHOMINNA_VERSION', '1.0.0');
define('AHOMINNA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AHOMINNA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('AHOMINNA_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_ahominna() {
    require_once AHOMINNA_PLUGIN_DIR . 'includes/class-ahominna-activator.php';
    AhoMinna_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_ahominna() {
    require_once AHOMINNA_PLUGIN_DIR . 'includes/class-ahominna-deactivator.php';
    AhoMinna_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_ahominna');
register_deactivation_hook(__FILE__, 'deactivate_ahominna');

/**
 * The core plugin class.
 */
require_once AHOMINNA_PLUGIN_DIR . 'includes/class-ahominna.php';

/**
 * Begins execution of the plugin.
 */
function run_ahominna() {
    $plugin = new AhoMinna();
    $plugin->run();
}
run_ahominna();
