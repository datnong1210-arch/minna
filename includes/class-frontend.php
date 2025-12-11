<?php
/**
 * Frontend functionality
 *
 * @package AhoMinna
 */

class AhoMinna_Frontend {
    
    /**
     * Enqueue frontend styles
     */
    public function enqueue_styles() {
        if (is_singular('ahominna_lesson') || has_shortcode(get_the_content(), 'ahominna')) {
            wp_enqueue_style(
                'ahominna-style',
                AHOMINNA_PLUGIN_URL . 'public/css/style.css',
                array(),
                AHOMINNA_VERSION
            );
        }
    }
    
    /**
     * Enqueue frontend scripts
     */
    public function enqueue_scripts() {
        if (is_singular('ahominna_lesson') || has_shortcode(get_the_content(), 'ahominna')) {
            // Main script
            wp_enqueue_script(
                'ahominna-main',
                AHOMINNA_PLUGIN_URL . 'public/js/main.js',
                array('jquery'),
                AHOMINNA_VERSION,
                true
            );
            
            // Flashcard script
            wp_enqueue_script(
                'ahominna-flashcard',
                AHOMINNA_PLUGIN_URL . 'public/js/flashcard.js',
                array('jquery', 'ahominna-main'),
                AHOMINNA_VERSION,
                true
            );
            
            // Audio player script
            wp_enqueue_script(
                'ahominna-audio',
                AHOMINNA_PLUGIN_URL . 'public/js/audio-player.js',
                array('jquery', 'ahominna-main'),
                AHOMINNA_VERSION,
                true
            );
            
            // Fullscreen script
            wp_enqueue_script(
                'ahominna-fullscreen',
                AHOMINNA_PLUGIN_URL . 'public/js/fullscreen.js',
                array('jquery', 'ahominna-main'),
                AHOMINNA_VERSION,
                true
            );
            
            // Localize script
            wp_localize_script('ahominna-main', 'ahominna', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ahominna_nonce'),
                'plugin_url' => AHOMINNA_PLUGIN_URL,
                'user_id' => get_current_user_id(),
                'i18n' => array(
                    'confirm_submit' => __('You haven\'t answered all questions. Do you want to submit?', 'ahominna'),
                ),
            ));
        }
    }
    
    /**
     * Custom template include
     */
    public function template_include($template) {
        if (is_singular('ahominna_lesson')) {
            $custom_template = AHOMINNA_PLUGIN_DIR . 'public/views/lesson.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        
        if (is_post_type_archive('ahominna_lesson')) {
            $custom_template = AHOMINNA_PLUGIN_DIR . 'templates/archive-lesson.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        
        return $template;
    }
}
