<?php
/**
 * Grammar Section Template
 *
 * @package AhoMinna
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get grammar for this lesson
$args = array(
    'post_type' => 'ahominna_grammar',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => '_lesson_id',
            'value' => isset($lesson_id) ? $lesson_id : get_the_ID(),
            'compare' => '='
        )
    ),
);

$grammar_query = new WP_Query($args);
?>

<div class="ahominna-grammar-section">
    <div class="section-header">
        <h2><?php _e('Ngữ pháp - Grammar', 'ahominna'); ?></h2>
    </div>
    
    <?php if ($grammar_query->have_posts()) : ?>
        
        <div class="grammar-list">
            <?php 
            $grammar_index = 1;
            while ($grammar_query->have_posts()) : 
                $grammar_query->the_post();
                $post_id = get_the_ID();
                $pattern = get_post_meta($post_id, '_pattern', true);
                $explanation_vi = get_post_meta($post_id, '_explanation_vi', true);
                $examples_json = get_post_meta($post_id, '_examples', true);
                $examples = json_decode($examples_json, true);
            ?>
            
            <div class="grammar-item" data-grammar-index="<?php echo $grammar_index; ?>">
                <div class="grammar-header">
                    <h3>
                        <span class="grammar-number"><?php echo $grammar_index; ?>.</span>
                        <span class="grammar-pattern"><?php echo esc_html($pattern); ?></span>
                    </h3>
                    <button class="grammar-toggle-btn">
                        <span class="dashicons dashicons-arrow-down-alt2"></span>
                    </button>
                </div>
                
                <div class="grammar-content">
                    <!-- Pattern Display -->
                    <div class="grammar-pattern-display">
                        <div class="pattern-box">
                            <?php echo esc_html($pattern); ?>
                        </div>
                    </div>
                    
                    <!-- Explanation -->
                    <?php if ($explanation_vi) : ?>
                        <div class="grammar-explanation">
                            <h4><?php _e('Giải thích', 'ahominna'); ?></h4>
                            <p><?php echo nl2br(esc_html($explanation_vi)); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Examples -->
                    <?php if ($examples && is_array($examples)) : ?>
                        <div class="grammar-examples">
                            <h4><?php _e('Ví dụ', 'ahominna'); ?></h4>
                            <?php foreach ($examples as $example) : ?>
                                <div class="example-item">
                                    <div class="example-japanese">
                                        <?php 
                                        if (isset($example['highlight'])) {
                                            echo str_replace(
                                                esc_html($example['highlight']), 
                                                '<mark>' . esc_html($example['highlight']) . '</mark>', 
                                                esc_html($example['japanese'])
                                            );
                                        } else {
                                            echo esc_html($example['japanese']);
                                        }
                                        ?>
                                    </div>
                                    <div class="example-vietnamese">
                                        <?php echo esc_html($example['vietnamese']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php 
            $grammar_index++;
            endwhile; 
            wp_reset_postdata();
            ?>
        </div>
        
    <?php else : ?>
        <div class="no-content-message">
            <p><?php _e('Chưa có ngữ pháp cho bài này.', 'ahominna'); ?></p>
        </div>
    <?php endif; ?>
</div>
