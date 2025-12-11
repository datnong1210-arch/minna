<?php
/**
 * Quiz Section Template
 *
 * @package AhoMinna
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get quiz for this lesson
$args = array(
    'post_type' => 'ahominna_quiz',
    'posts_per_page' => 1,
    'meta_query' => array(
        array(
            'key' => '_lesson_id',
            'value' => isset($lesson_id) ? $lesson_id : get_the_ID(),
            'compare' => '='
        )
    ),
);

$quiz_query = new WP_Query($args);
?>

<div class="ahominna-quiz-section">
    <div class="section-header">
        <h2><?php _e('Trắc nghiệm - Quiz', 'ahominna'); ?></h2>
    </div>
    
    <?php if ($quiz_query->have_posts()) : ?>
        
        <?php while ($quiz_query->have_posts()) : 
            $quiz_query->the_post();
            $quiz_id = get_the_ID();
            $questions_json = get_post_meta($quiz_id, '_questions', true);
            $questions = json_decode($questions_json, true);
        ?>
        
        <?php if ($questions && is_array($questions)) : ?>
            
            <div class="quiz-container" data-quiz-id="<?php echo $quiz_id; ?>" data-lesson-id="<?php echo isset($lesson_id) ? $lesson_id : get_the_ID(); ?>">
                
                <!-- Quiz Start Screen -->
                <div class="quiz-start-screen">
                    <div class="quiz-info">
                        <div class="quiz-icon">
                            <span class="dashicons dashicons-clipboard"></span>
                        </div>
                        <h3><?php printf(__('Bài kiểm tra có %d câu hỏi', 'ahominna'), count($questions)); ?></h3>
                        <p><?php _e('Hãy chọn đáp án đúng cho mỗi câu hỏi. Bạn sẽ thấy kết quả sau khi hoàn thành.', 'ahominna'); ?></p>
                    </div>
                    <button class="quiz-start-btn button-primary">
                        <?php _e('Bắt đầu làm bài', 'ahominna'); ?>
                    </button>
                </div>
                
                <!-- Quiz Questions -->
                <div class="quiz-questions" style="display: none;">
                    <?php foreach ($questions as $q_index => $question) : ?>
                        <div class="quiz-question" data-question-index="<?php echo $q_index; ?>" style="<?php echo $q_index === 0 ? 'display: block;' : 'display: none;'; ?>">
                            
                            <div class="question-header">
                                <span class="question-number"><?php printf(__('Câu %d/%d', 'ahominna'), $q_index + 1, count($questions)); ?></span>
                                <span class="question-type badge"><?php echo esc_html($question['type']); ?></span>
                            </div>
                            
                            <?php if (isset($question['audio_url']) && $question['audio_url']) : ?>
                                <div class="question-audio">
                                    <button class="question-audio-btn" data-audio="<?php echo esc_url($question['audio_url']); ?>">
                                        <span class="dashicons dashicons-controls-play"></span>
                                        <?php _e('Nghe', 'ahominna'); ?>
                                    </button>
                                    <audio class="question-audio-player" src="<?php echo esc_url($question['audio_url']); ?>"></audio>
                                </div>
                            <?php endif; ?>
                            
                            <div class="question-text">
                                <p><?php echo esc_html($question['question']); ?></p>
                            </div>
                            
                            <div class="question-options">
                                <?php foreach ($question['options'] as $opt_index => $option) : ?>
                                    <label class="option-label">
                                        <input 
                                            type="radio" 
                                            name="question_<?php echo $q_index; ?>" 
                                            value="<?php echo $opt_index; ?>"
                                            data-question="<?php echo $q_index; ?>"
                                        />
                                        <span class="option-text">
                                            <span class="option-letter"><?php echo chr(65 + $opt_index); ?>.</span>
                                            <?php echo esc_html($option); ?>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="question-navigation">
                                <?php if ($q_index > 0) : ?>
                                    <button class="quiz-nav-btn prev-question-btn">
                                        <span class="dashicons dashicons-arrow-left-alt2"></span>
                                        <?php _e('Câu trước', 'ahominna'); ?>
                                    </button>
                                <?php endif; ?>
                                
                                <?php if ($q_index < count($questions) - 1) : ?>
                                    <button class="quiz-nav-btn next-question-btn">
                                        <?php _e('Câu sau', 'ahominna'); ?>
                                        <span class="dashicons dashicons-arrow-right-alt2"></span>
                                    </button>
                                <?php else : ?>
                                    <button class="quiz-submit-btn button-primary">
                                        <?php _e('Nộp bài', 'ahominna'); ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Quiz Progress -->
                <div class="quiz-progress" style="display: none;">
                    <div class="progress-text">
                        <span class="answered-count">0</span> / <span class="total-count"><?php echo count($questions); ?></span>
                        <?php _e('câu đã trả lời', 'ahominna'); ?>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 0%"></div>
                    </div>
                </div>
                
                <!-- Quiz Results -->
                <div class="quiz-results" style="display: none;">
                    <div class="results-header">
                        <div class="results-score">
                            <div class="score-circle">
                                <div class="score-value">0%</div>
                            </div>
                            <h3 class="score-message"></h3>
                        </div>
                    </div>
                    
                    <div class="results-details">
                        <div class="results-stats">
                            <div class="stat-item">
                                <span class="stat-label"><?php _e('Đúng', 'ahominna'); ?></span>
                                <span class="stat-value correct-count">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label"><?php _e('Sai', 'ahominna'); ?></span>
                                <span class="stat-value wrong-count">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label"><?php _e('Tổng', 'ahominna'); ?></span>
                                <span class="stat-value total-count">0</span>
                            </div>
                        </div>
                        
                        <div class="results-answers"></div>
                    </div>
                    
                    <div class="results-actions">
                        <button class="quiz-retry-btn button-primary">
                            <span class="dashicons dashicons-update"></span>
                            <?php _e('Làm lại', 'ahominna'); ?>
                        </button>
                        <button class="quiz-review-btn button-secondary">
                            <span class="dashicons dashicons-visibility"></span>
                            <?php _e('Xem đáp án', 'ahominna'); ?>
                        </button>
                    </div>
                </div>
                
            </div>
            
        <?php endif; ?>
        
        <?php endwhile; wp_reset_postdata(); ?>
        
    <?php else : ?>
        <div class="no-content-message">
            <p><?php _e('Chưa có trắc nghiệm cho bài này.', 'ahominna'); ?></p>
        </div>
    <?php endif; ?>
</div>

<!-- Store questions data for JavaScript -->
<?php if (isset($questions) && $questions) : ?>
<script type="application/json" id="quiz-questions-data">
<?php echo json_encode($questions); ?>
</script>
<?php endif; ?>
