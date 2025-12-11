<?php
/**
 * Uninstall script
 * Fired when the plugin is uninstalled.
 *
 * @package AhoMinna
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('ahominna_version');
delete_option('ahominna_enable_audio');
delete_option('ahominna_enable_video');
delete_option('ahominna_items_per_page');

// Delete custom tables (optional - comment out if you want to preserve data)
global $wpdb;

$progress_table = $wpdb->prefix . 'ahominna_progress';
$quiz_table = $wpdb->prefix . 'ahominna_quiz_results';

$wpdb->query("DROP TABLE IF EXISTS $progress_table");
$wpdb->query("DROP TABLE IF EXISTS $quiz_table");

// Delete all custom post types and their meta
$post_types = array(
    'ahominna_lesson',
    'ahominna_vocabulary',
    'ahominna_grammar',
    'ahominna_dialogue',
    'ahominna_quiz'
);

foreach ($post_types as $post_type) {
    $posts = get_posts(array(
        'post_type' => $post_type,
        'numberposts' => -1,
        'post_status' => 'any'
    ));
    
    foreach ($posts as $post) {
        // Delete post meta
        $wpdb->delete($wpdb->postmeta, array('post_id' => $post->ID));
        // Delete post
        wp_delete_post($post->ID, true);
    }
}

// Delete taxonomies
delete_option('ahominna_level_children');
$wpdb->delete($wpdb->term_taxonomy, array('taxonomy' => 'ahominna_level'));

// Clear any cached data
wp_cache_flush();
