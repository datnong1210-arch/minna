<?php
/**
 * Dialogue Section Template
 *
 * @package AhoMinna
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get dialogue for this lesson
$args = array(
    'post_type' => 'ahominna_dialogue',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => '_lesson_id',
            'value' => isset($lesson_id) ? $lesson_id : get_the_ID(),
            'compare' => '='
        )
    ),
);

$dialogue_query = new WP_Query($args);
?>

<div class="ahominna-dialogue-section">
    <div class="section-header">
        <h2><?php _e('Hội thoại - Conversation', 'ahominna'); ?></h2>
    </div>
    
    <?php if ($dialogue_query->have_posts()) : ?>
        
        <?php while ($dialogue_query->have_posts()) : 
            $dialogue_query->the_post();
            $post_id = get_the_ID();
            $dialogue_lines_json = get_post_meta($post_id, '_dialogue_lines', true);
            $dialogue_lines = json_decode($dialogue_lines_json, true);
            $full_audio_url = get_post_meta($post_id, '_full_audio_url', true);
            $video_url = get_post_meta($post_id, '_video_url', true);
        ?>
        
        <div class="dialogue-container">
            
            <!-- Video Section -->
            <?php if ($video_url) : ?>
                <div class="dialogue-video">
                    <?php if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) : 
                        // YouTube embed
                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video_url, $matches);
                        $video_id = isset($matches[1]) ? $matches[1] : '';
                        if ($video_id) :
                    ?>
                        <div class="video-wrapper">
                            <iframe 
                                src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    <?php endif; ?>
                    <?php else : ?>
                        <!-- Direct video -->
                        <video controls class="dialogue-video-player">
                            <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                            <?php _e('Your browser does not support the video tag.', 'ahominna'); ?>
                        </video>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Audio Controls -->
            <?php if ($full_audio_url) : ?>
                <div class="dialogue-audio-controls">
                    <button class="dialogue-play-all" data-audio="<?php echo esc_url($full_audio_url); ?>">
                        <span class="dashicons dashicons-controls-play"></span>
                        <?php _e('Phát toàn bộ', 'ahominna'); ?>
                    </button>
                    <audio id="dialogue-full-audio" src="<?php echo esc_url($full_audio_url); ?>"></audio>
                </div>
            <?php endif; ?>
            
            <!-- Dialogue Lines -->
            <?php if ($dialogue_lines && is_array($dialogue_lines)) : ?>
                <div class="dialogue-lines">
                    <?php foreach ($dialogue_lines as $index => $line) : ?>
                        <div class="dialogue-line" data-line-index="<?php echo $index; ?>">
                            <div class="speaker-info">
                                <div class="speaker-avatar">
                                    <?php echo substr(esc_html($line['speaker']), 0, 1); ?>
                                </div>
                                <div class="speaker-name"><?php echo esc_html($line['speaker']); ?></div>
                            </div>
                            
                            <div class="dialogue-text">
                                <div class="japanese-text"><?php echo esc_html($line['japanese']); ?></div>
                                <div class="vietnamese-text"><?php echo esc_html($line['vietnamese']); ?></div>
                            </div>
                            
                            <?php if (isset($line['audio_url']) && $line['audio_url']) : ?>
                                <button class="line-audio-btn" data-audio="<?php echo esc_url($line['audio_url']); ?>">
                                    <span class="dashicons dashicons-controls-play"></span>
                                </button>
                                <audio class="line-audio" src="<?php echo esc_url($line['audio_url']); ?>"></audio>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Toggle Translation Button -->
            <div class="dialogue-controls">
                <button class="toggle-translation-btn">
                    <span class="dashicons dashicons-translation"></span>
                    <?php _e('Ẩn/Hiện dịch', 'ahominna'); ?>
                </button>
            </div>
            
        </div>
        
        <?php endwhile; wp_reset_postdata(); ?>
        
    <?php else : ?>
        <div class="no-content-message">
            <p><?php _e('Chưa có hội thoại cho bài này.', 'ahominna'); ?></p>
        </div>
    <?php endif; ?>
</div>
