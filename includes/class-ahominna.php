<?php
/**
 * The core plugin class
 *
 * @package AhoMinna
 */

class AhoMinna {
    
    /**
     * The loader that's responsible for maintaining and registering all hooks.
     */
    protected $loader;
    
    /**
     * The unique identifier of this plugin.
     */
    protected $plugin_name;
    
    /**
     * The current version of the plugin.
     */
    protected $version;
    
    /**
     * Initialize the plugin
     */
    public function __construct() {
        $this->version = AHOMINNA_VERSION;
        $this->plugin_name = 'ahominna';
        
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    
    /**
     * Load the required dependencies
     */
    private function load_dependencies() {
        // Core classes
        require_once AHOMINNA_PLUGIN_DIR . 'includes/class-post-types.php';
        require_once AHOMINNA_PLUGIN_DIR . 'includes/class-admin.php';
        require_once AHOMINNA_PLUGIN_DIR . 'includes/class-frontend.php';
        require_once AHOMINNA_PLUGIN_DIR . 'includes/class-importer.php';
        require_once AHOMINNA_PLUGIN_DIR . 'includes/class-ajax.php';
        require_once AHOMINNA_PLUGIN_DIR . 'includes/class-shortcodes.php';
    }
    
    /**
     * Define the locale for internationalization
     */
    private function set_locale() {
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
    }
    
    /**
     * Load plugin text domain
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'ahominna',
            false,
            dirname(AHOMINNA_PLUGIN_BASENAME) . '/languages/'
        );
    }
    
    /**
     * Register admin hooks
     */
    private function define_admin_hooks() {
        $post_types = new AhoMinna_Post_Types();
        add_action('init', array($post_types, 'register_post_types'));
        add_action('init', array($post_types, 'register_taxonomies'));
        
        if (is_admin()) {
            $admin = new AhoMinna_Admin();
            add_action('admin_menu', array($admin, 'add_admin_menu'));
            add_action('admin_enqueue_scripts', array($admin, 'enqueue_styles'));
            add_action('admin_enqueue_scripts', array($admin, 'enqueue_scripts'));
            add_action('add_meta_boxes', array($admin, 'add_meta_boxes'));
            add_action('save_post', array($admin, 'save_meta_boxes'), 10, 2);
        }
    }
    
    /**
     * Register public hooks
     */
    private function define_public_hooks() {
        $frontend = new AhoMinna_Frontend();
        add_action('wp_enqueue_scripts', array($frontend, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($frontend, 'enqueue_scripts'));
        add_filter('template_include', array($frontend, 'template_include'));
        
        // AJAX handlers
        $ajax = new AhoMinna_Ajax();
        add_action('wp_ajax_ahominna_save_progress', array($ajax, 'save_progress'));
        add_action('wp_ajax_ahominna_submit_quiz', array($ajax, 'submit_quiz'));
        add_action('wp_ajax_ahominna_get_vocabulary', array($ajax, 'get_vocabulary'));
        
        // Shortcodes
        $shortcodes = new AhoMinna_Shortcodes();
        add_shortcode('ahominna_lessons', array($shortcodes, 'lessons_list'));
        add_shortcode('ahominna_lesson', array($shortcodes, 'single_lesson'));
        add_shortcode('ahominna_vocabulary', array($shortcodes, 'vocabulary_section'));
        add_shortcode('ahominna_quiz', array($shortcodes, 'quiz_section'));
    }
    
    /**
     * Run the plugin
     */
    public function run() {
        // Plugin is initialized in constructor
    }
    
    /**
     * Get plugin name
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }
    
    /**
     * Get plugin version
     */
    public function get_version() {
        return $this->version;
    }
}
