<?php
/**
 * Shortcodes
 *
 * @package AhoMinna
 */

class AhoMinna_Shortcodes {
    
    /**
     * Lessons list shortcode
     * Usage: [ahominna_lessons]
     */
    public function lessons_list($atts) {
        $atts = shortcode_atts(array(
            'per_page' => 50,
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
        ), $atts);
        
        $args = array(
            'post_type' => 'ahominna_lesson',
            'posts_per_page' => intval($atts['per_page']),
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'meta_key' => '_lesson_number',
        );
        
        $lessons = new WP_Query($args);
        
        ob_start();
        
        if ($lessons->have_posts()) {
            echo '<div class="ahominna-lessons-grid">';
            while ($lessons->have_posts()) {
                $lessons->the_post();
                $lesson_number = get_post_meta(get_the_ID(), '_lesson_number', true);
                ?>
                <div class="ahominna-lesson-card">
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="lesson-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                        <?php endif; ?>
                        <div class="lesson-content">
                            <h3 class="lesson-number"><?php printf(__('Lesson %d', 'ahominna'), $lesson_number); ?></h3>
                            <h4 class="lesson-title"><?php the_title(); ?></h4>
                            <?php if (get_the_excerpt()) : ?>
                                <p class="lesson-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <?php
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>' . __('No lessons found.', 'ahominna') . '</p>';
        }
        
        return ob_get_clean();
    }
    
    /**
     * Single lesson shortcode
     * Usage: [ahominna_lesson id="1"]
     */
    public function single_lesson($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
        ), $atts);
        
        $lesson_id = intval($atts['id']);
        
        if (!$lesson_id) {
            return '<p>' . __('Invalid lesson ID.', 'ahominna') . '</p>';
        }
        
        $lesson = get_post($lesson_id);
        
        if (!$lesson || $lesson->post_type !== 'ahominna_lesson') {
            return '<p>' . __('Lesson not found.', 'ahominna') . '</p>';
        }
        
        ob_start();
        include AHOMINNA_PLUGIN_DIR . 'public/views/lesson.php';
        return ob_get_clean();
    }
    
    /**
     * Vocabulary section shortcode
     * Usage: [ahominna_vocabulary lesson="1"]
     */
    public function vocabulary_section($atts) {
        $atts = shortcode_atts(array(
            'lesson' => 0,
        ), $atts);
        
        $lesson_id = intval($atts['lesson']);
        
        if (!$lesson_id) {
            return '<p>' . __('Invalid lesson ID.', 'ahominna') . '</p>';
        }
        
        ob_start();
        include AHOMINNA_PLUGIN_DIR . 'public/views/vocabulary.php';
        return ob_get_clean();
    }
    
    /**
     * Quiz section shortcode
     * Usage: [ahominna_quiz lesson="1"]
     */
    public function quiz_section($atts) {
        $atts = shortcode_atts(array(
            'lesson' => 0,
        ), $atts);
        
        $lesson_id = intval($atts['lesson']);
        
        if (!$lesson_id) {
            return '<p>' . __('Invalid lesson ID.', 'ahominna') . '</p>';
        }
        
        ob_start();
        include AHOMINNA_PLUGIN_DIR . 'public/views/quiz.php';
        return ob_get_clean();
    }
}
