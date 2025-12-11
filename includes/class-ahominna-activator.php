<?php
/**
 * Fired during plugin activation
 *
 * @package AhoMinna
 */

class AhoMinna_Activator {
    /**
     * Plugin activation tasks
     */
    public static function activate() {
        // Create custom database tables
        self::create_tables();
        
        // Flush rewrite rules for custom post types
        flush_rewrite_rules();
        
        // Set default options
        self::set_default_options();
    }
    
    /**
     * Create custom database tables
     */
    private static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        // Progress tracking table
        $progress_table = $wpdb->prefix . 'ahominna_progress';
        $progress_sql = "CREATE TABLE IF NOT EXISTS $progress_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            lesson_id bigint(20) NOT NULL,
            section_type varchar(50) NOT NULL,
            progress_data longtext,
            completed tinyint(1) DEFAULT 0,
            last_accessed datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY lesson_id (lesson_id)
        ) $charset_collate;";
        
        // Quiz results table
        $quiz_table = $wpdb->prefix . 'ahominna_quiz_results';
        $quiz_sql = "CREATE TABLE IF NOT EXISTS $quiz_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            quiz_id bigint(20) NOT NULL,
            lesson_id bigint(20) NOT NULL,
            score int(11) NOT NULL,
            total_questions int(11) NOT NULL,
            answers longtext,
            completed_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY quiz_id (quiz_id),
            KEY lesson_id (lesson_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($progress_sql);
        dbDelta($quiz_sql);
    }
    
    /**
     * Set default plugin options
     */
    private static function set_default_options() {
        $defaults = array(
            'ahominna_version' => AHOMINNA_VERSION,
            'ahominna_enable_audio' => 1,
            'ahominna_enable_video' => 1,
            'ahominna_items_per_page' => 10,
        );
        
        foreach ($defaults as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
    }
}
