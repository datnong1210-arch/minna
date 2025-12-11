<?php
/**
 * Admin Dashboard View
 *
 * @package AhoMinna
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap ahominna-dashboard">
    <h1><?php _e('AhoMINNA Dashboard', 'ahominna'); ?></h1>
    
    <div class="ahominna-dashboard-grid">
        <!-- Stats Cards -->
        <div class="ahominna-stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <span class="dashicons dashicons-book-alt"></span>
                </div>
                <div class="stat-content">
                    <h3><?php 
                        $lesson_count = wp_count_posts('ahominna_lesson');
                        echo $lesson_count->publish; 
                    ?></h3>
                    <p><?php _e('Lessons', 'ahominna'); ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <span class="dashicons dashicons-translation"></span>
                </div>
                <div class="stat-content">
                    <h3><?php 
                        $vocab_count = wp_count_posts('ahominna_vocabulary');
                        echo $vocab_count->publish; 
                    ?></h3>
                    <p><?php _e('Vocabulary', 'ahominna'); ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <span class="dashicons dashicons-admin-comments"></span>
                </div>
                <div class="stat-content">
                    <h3><?php 
                        $dialogue_count = wp_count_posts('ahominna_dialogue');
                        echo $dialogue_count->publish; 
                    ?></h3>
                    <p><?php _e('Dialogues', 'ahominna'); ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <span class="dashicons dashicons-yes-alt"></span>
                </div>
                <div class="stat-content">
                    <h3><?php 
                        $quiz_count = wp_count_posts('ahominna_quiz');
                        echo $quiz_count->publish; 
                    ?></h3>
                    <p><?php _e('Quizzes', 'ahominna'); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="ahominna-quick-actions">
            <h2><?php _e('Quick Actions', 'ahominna'); ?></h2>
            <div class="action-buttons">
                <a href="<?php echo admin_url('post-new.php?post_type=ahominna_lesson'); ?>" class="button button-primary button-hero">
                    <span class="dashicons dashicons-plus"></span>
                    <?php _e('Add New Lesson', 'ahominna'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=ahominna-import'); ?>" class="button button-secondary button-hero">
                    <span class="dashicons dashicons-upload"></span>
                    <?php _e('Import Data', 'ahominna'); ?>
                </a>
                <a href="<?php echo admin_url('edit.php?post_type=ahominna_lesson'); ?>" class="button button-secondary button-hero">
                    <span class="dashicons dashicons-list-view"></span>
                    <?php _e('View All Lessons', 'ahominna'); ?>
                </a>
            </div>
        </div>
        
        <!-- Recent Lessons -->
        <div class="ahominna-recent-lessons">
            <h2><?php _e('Recent Lessons', 'ahominna'); ?></h2>
            <?php
            $recent_lessons = new WP_Query(array(
                'post_type' => 'ahominna_lesson',
                'posts_per_page' => 5,
                'orderby' => 'date',
                'order' => 'DESC',
            ));
            
            if ($recent_lessons->have_posts()) {
                echo '<table class="wp-list-table widefat fixed striped">';
                echo '<thead><tr>';
                echo '<th>' . __('Lesson', 'ahominna') . '</th>';
                echo '<th>' . __('Number', 'ahominna') . '</th>';
                echo '<th>' . __('Status', 'ahominna') . '</th>';
                echo '<th>' . __('Date', 'ahominna') . '</th>';
                echo '</tr></thead><tbody>';
                
                while ($recent_lessons->have_posts()) {
                    $recent_lessons->the_post();
                    $lesson_number = get_post_meta(get_the_ID(), '_lesson_number', true);
                    echo '<tr>';
                    echo '<td><a href="' . get_edit_post_link() . '">' . get_the_title() . '</a></td>';
                    echo '<td>' . esc_html($lesson_number) . '</td>';
                    echo '<td>' . get_post_status() . '</td>';
                    echo '<td>' . get_the_date() . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody></table>';
                wp_reset_postdata();
            } else {
                echo '<p>' . __('No lessons yet. Create your first lesson!', 'ahominna') . '</p>';
            }
            ?>
        </div>
        
        <!-- Help & Documentation -->
        <div class="ahominna-help">
            <h2><?php _e('Help & Documentation', 'ahominna'); ?></h2>
            <div class="help-content">
                <h3><?php _e('Getting Started', 'ahominna'); ?></h3>
                <ol>
                    <li><?php _e('Create lessons (1-50) for Minna no Nihongo', 'ahominna'); ?></li>
                    <li><?php _e('Add vocabulary, grammar, dialogues, and quizzes for each lesson', 'ahominna'); ?></li>
                    <li><?php _e('Use bulk import for faster data entry', 'ahominna'); ?></li>
                    <li><?php _e('Use shortcodes to display lessons on your pages', 'ahominna'); ?></li>
                </ol>
                
                <h3><?php _e('Available Shortcodes', 'ahominna'); ?></h3>
                <ul>
                    <li><code>[ahominna_lessons]</code> - <?php _e('Display all lessons', 'ahominna'); ?></li>
                    <li><code>[ahominna_lesson id="1"]</code> - <?php _e('Display specific lesson', 'ahominna'); ?></li>
                    <li><code>[ahominna_vocabulary lesson="1"]</code> - <?php _e('Display vocabulary flashcards', 'ahominna'); ?></li>
                    <li><code>[ahominna_quiz lesson="1"]</code> - <?php _e('Display quiz', 'ahominna'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
