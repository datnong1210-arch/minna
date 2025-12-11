<?php
/**
 * Vocabulary Section Template
 *
 * @package AhoMinna
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get vocabulary for this lesson
$args = array(
    'post_type' => 'ahominna_vocabulary',
    'posts_per_page' => 100, // Limit to prevent performance issues
    'meta_query' => array(
        array(
            'key' => '_lesson_id',
            'value' => isset($lesson_id) ? $lesson_id : get_the_ID(),
            'compare' => '='
        )
    ),
    'orderby' => 'menu_order',
    'order' => 'ASC',
);

$vocab_query = new WP_Query($args);
?>

<div class="ahominna-vocabulary-section">
    <div class="section-header">
        <h2><?php _e('Từ vựng - Vocabulary', 'ahominna'); ?></h2>
        <div class="vocab-controls">
            <button class="vocab-mode-btn active" data-mode="learn">
                <?php _e('Học', 'ahominna'); ?>
            </button>
            <button class="vocab-mode-btn" data-mode="review">
                <?php _e('Ôn tập', 'ahominna'); ?>
            </button>
            <button class="vocab-shuffle-btn">
                <span class="dashicons dashicons-randomize"></span>
                <?php _e('Xáo trộn', 'ahominna'); ?>
            </button>
        </div>
    </div>
    
    <?php if ($vocab_query->have_posts()) : ?>
        
        <!-- Flashcard Container -->
        <div class="flashcard-container">
            <div class="flashcard-wrapper">
                <?php 
                $index = 0;
                while ($vocab_query->have_posts()) : 
                    $vocab_query->the_post();
                    $post_id = get_the_ID();
                    $kanji = get_post_meta($post_id, '_kanji', true);
                    $hiragana = get_post_meta($post_id, '_hiragana', true);
                    $romaji = get_post_meta($post_id, '_romaji', true);
                    $vietnamese = get_post_meta($post_id, '_vietnamese', true);
                    $image_url = get_post_meta($post_id, '_image_url', true);
                    $audio_url = get_post_meta($post_id, '_audio_url', true);
                ?>
                
                <div class="flashcard" data-index="<?php echo $index; ?>" <?php echo $index === 0 ? 'style="display: block;"' : 'style="display: none;"'; ?>>
                    <div class="flashcard-inner">
                        <!-- Front Side -->
                        <div class="flashcard-front">
                            <?php if ($image_url) : ?>
                                <div class="flashcard-image">
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($kanji); ?>" loading="lazy" />
                                </div>
                            <?php endif; ?>
                            
                            <div class="flashcard-text">
                                <div class="kanji"><?php echo esc_html($kanji); ?></div>
                                <div class="hiragana"><?php echo esc_html($hiragana); ?></div>
                                <div class="romaji"><?php echo esc_html($romaji); ?></div>
                            </div>
                            
                            <?php if ($audio_url) : ?>
                                <button class="audio-play-btn" data-audio="<?php echo esc_url($audio_url); ?>">
                                    <span class="dashicons dashicons-controls-play"></span>
                                </button>
                            <?php endif; ?>
                            
                            <button class="flashcard-flip-btn">
                                <span class="dashicons dashicons-image-rotate"></span>
                                <?php _e('Xem nghĩa', 'ahominna'); ?>
                            </button>
                        </div>
                        
                        <!-- Back Side -->
                        <div class="flashcard-back">
                            <div class="vietnamese-meaning">
                                <h3><?php _e('Nghĩa tiếng Việt', 'ahominna'); ?></h3>
                                <p><?php echo esc_html($vietnamese); ?></p>
                            </div>
                            
                            <div class="review-text">
                                <div class="kanji"><?php echo esc_html($kanji); ?></div>
                                <div class="hiragana"><?php echo esc_html($hiragana); ?></div>
                            </div>
                            
                            <button class="flashcard-flip-btn">
                                <span class="dashicons dashicons-image-rotate"></span>
                                <?php _e('Xem từ', 'ahominna'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                
                <?php 
                $index++;
                endwhile; 
                wp_reset_postdata();
                ?>
            </div>
            
            <!-- Navigation Controls -->
            <div class="flashcard-controls">
                <button class="flashcard-nav prev-btn" disabled>
                    <span class="dashicons dashicons-arrow-left-alt2"></span>
                    <?php _e('Trước', 'ahominna'); ?>
                </button>
                
                <div class="flashcard-counter">
                    <span class="current-card">1</span> / <span class="total-cards"><?php echo $index; ?></span>
                </div>
                
                <button class="flashcard-nav next-btn">
                    <?php _e('Sau', 'ahominna'); ?>
                    <span class="dashicons dashicons-arrow-right-alt2"></span>
                </button>
            </div>
        </div>
        
        <!-- Vocabulary List View -->
        <div class="vocabulary-list-view" style="display: none;">
            <table class="vocab-table">
                <thead>
                    <tr>
                        <th><?php _e('Kanji', 'ahominna'); ?></th>
                        <th><?php _e('Hiragana', 'ahominna'); ?></th>
                        <th><?php _e('Romaji', 'ahominna'); ?></th>
                        <th><?php _e('Nghĩa', 'ahominna'); ?></th>
                        <th><?php _e('Audio', 'ahominna'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $vocab_query->rewind_posts();
                    while ($vocab_query->have_posts()) :
                        $vocab_query->the_post();
                        $post_id = get_the_ID();
                        $kanji = get_post_meta($post_id, '_kanji', true);
                        $hiragana = get_post_meta($post_id, '_hiragana', true);
                        $romaji = get_post_meta($post_id, '_romaji', true);
                        $vietnamese = get_post_meta($post_id, '_vietnamese', true);
                        $audio_url = get_post_meta($post_id, '_audio_url', true);
                    ?>
                    <tr>
                        <td><?php echo esc_html($kanji); ?></td>
                        <td><?php echo esc_html($hiragana); ?></td>
                        <td><?php echo esc_html($romaji); ?></td>
                        <td><?php echo esc_html($vietnamese); ?></td>
                        <td>
                            <?php if ($audio_url) : ?>
                                <button class="audio-play-btn-small" data-audio="<?php echo esc_url($audio_url); ?>">
                                    <span class="dashicons dashicons-controls-play"></span>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                </tbody>
            </table>
        </div>
        
        <!-- Toggle View Button -->
        <button class="toggle-view-btn">
            <span class="dashicons dashicons-list-view"></span>
            <?php _e('Xem dạng danh sách', 'ahominna'); ?>
        </button>
        
    <?php else : ?>
        <div class="no-content-message">
            <p><?php _e('Chưa có từ vựng cho bài này.', 'ahominna'); ?></p>
        </div>
    <?php endif; ?>
</div>
