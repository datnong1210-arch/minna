<?php
/**
 * Archive Lesson Template
 *
 * @package AhoMinna
 */

get_header();
?>

<div class="ahominna-archive-container">
    <div class="archive-header">
        <h1 class="archive-title"><?php _e('Khóa học tiếng Nhật - Minna no Nihongo', 'ahominna'); ?></h1>
        <p class="archive-description"><?php _e('50 bài học tiếng Nhật từ cơ bản đến nâng cao', 'ahominna'); ?></p>
    </div>
    
    <?php if (have_posts()) : ?>
        
        <div class="ahominna-lessons-grid">
            <?php while (have_posts()) : the_post(); 
                $lesson_number = get_post_meta(get_the_ID(), '_lesson_number', true);
            ?>
                
                <div class="ahominna-lesson-card">
                    <a href="<?php the_permalink(); ?>" class="lesson-card-link">
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="lesson-thumbnail">
                                <?php the_post_thumbnail('medium'); ?>
                                <div class="lesson-number-overlay">
                                    <?php printf(__('Bài %d', 'ahominna'), $lesson_number); ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="lesson-thumbnail no-image">
                                <div class="lesson-number-badge">
                                    <?php printf(__('Bài %d', 'ahominna'), $lesson_number); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="lesson-content">
                            <h3 class="lesson-title"><?php the_title(); ?></h3>
                            
                            <?php if (get_the_excerpt()) : ?>
                                <p class="lesson-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?></p>
                            <?php endif; ?>
                            
                            <div class="lesson-meta">
                                <?php
                                // Count related content
                                $vocab_count = count(get_posts(array(
                                    'post_type' => 'ahominna_vocabulary',
                                    'posts_per_page' => -1,
                                    'meta_query' => array(array('key' => '_lesson_id', 'value' => get_the_ID()))
                                )));
                                ?>
                                <span class="meta-item">
                                    <span class="dashicons dashicons-translation"></span>
                                    <?php printf(__('%d từ vựng', 'ahominna'), $vocab_count); ?>
                                </span>
                            </div>
                            
                            <div class="lesson-action">
                                <span class="learn-btn">
                                    <?php _e('Bắt đầu học', 'ahominna'); ?>
                                    <span class="dashicons dashicons-arrow-right-alt"></span>
                                </span>
                            </div>
                        </div>
                        
                    </a>
                </div>
                
            <?php endwhile; ?>
        </div>
        
        <!-- Pagination -->
        <div class="ahominna-pagination">
            <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => '<span class="dashicons dashicons-arrow-left-alt2"></span> ' . __('Trước', 'ahominna'),
                'next_text' => __('Sau', 'ahominna') . ' <span class="dashicons dashicons-arrow-right-alt2"></span>',
            ));
            ?>
        </div>
        
    <?php else : ?>
        
        <div class="no-lessons-message">
            <div class="no-content-icon">
                <span class="dashicons dashicons-book-alt"></span>
            </div>
            <h2><?php _e('Chưa có bài học nào', 'ahominna'); ?></h2>
            <p><?php _e('Vui lòng quay lại sau khi có bài học mới.', 'ahominna'); ?></p>
        </div>
        
    <?php endif; ?>
</div>

<?php
get_footer();
?>
