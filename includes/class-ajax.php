<?php
/**
 * AJAX handlers
 *
 * @package AhoMinna
 */

class AhoMinna_Ajax {
    
    /**
     * Save user progress
     */
    public function save_progress() {
        check_ajax_referer('ahominna_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Please login to save progress', 'ahominna')));
        }
        
        $user_id = get_current_user_id();
        $lesson_id = isset($_POST['lesson_id']) ? intval($_POST['lesson_id']) : 0;
        $section_type = isset($_POST['section_type']) ? sanitize_text_field($_POST['section_type']) : '';
        $progress_data = isset($_POST['progress_data']) ? sanitize_text_field($_POST['progress_data']) : '';
        $completed = isset($_POST['completed']) ? intval($_POST['completed']) : 0;
        
        if (!$lesson_id || !$section_type) {
            wp_send_json_error(array('message' => __('Invalid data', 'ahominna')));
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'ahominna_progress';
        
        // Check if progress exists
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT id FROM $table WHERE user_id = %d AND lesson_id = %d AND section_type = %s",
            $user_id, $lesson_id, $section_type
        ));
        
        if ($existing) {
            // Update existing
            $wpdb->update(
                $table,
                array(
                    'progress_data' => $progress_data,
                    'completed' => $completed,
                    'last_accessed' => current_time('mysql'),
                ),
                array('id' => $existing->id),
                array('%s', '%d', '%s'),
                array('%d')
            );
        } else {
            // Insert new
            $wpdb->insert(
                $table,
                array(
                    'user_id' => $user_id,
                    'lesson_id' => $lesson_id,
                    'section_type' => $section_type,
                    'progress_data' => $progress_data,
                    'completed' => $completed,
                    'last_accessed' => current_time('mysql'),
                ),
                array('%d', '%d', '%s', '%s', '%d', '%s')
            );
        }
        
        wp_send_json_success(array('message' => __('Progress saved', 'ahominna')));
    }
    
    /**
     * Submit quiz
     */
    public function submit_quiz() {
        check_ajax_referer('ahominna_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Please login to submit quiz', 'ahominna')));
        }
        
        $user_id = get_current_user_id();
        $quiz_id = isset($_POST['quiz_id']) ? intval($_POST['quiz_id']) : 0;
        $lesson_id = isset($_POST['lesson_id']) ? intval($_POST['lesson_id']) : 0;
        $answers = isset($_POST['answers']) ? json_decode(stripslashes($_POST['answers']), true) : array();
        
        if (!$quiz_id || !$lesson_id || empty($answers)) {
            wp_send_json_error(array('message' => __('Invalid data', 'ahominna')));
        }
        
        // Get quiz questions
        $questions_json = get_post_meta($quiz_id, '_questions', true);
        $questions = json_decode($questions_json, true);
        
        if (!$questions) {
            wp_send_json_error(array('message' => __('Quiz not found', 'ahominna')));
        }
        
        // Calculate score
        $score = 0;
        $total = count($questions);
        $results = array();
        
        foreach ($questions as $index => $question) {
            $user_answer = isset($answers[$index]) ? intval($answers[$index]) : -1;
            $is_correct = ($user_answer === intval($question['correct']));
            
            if ($is_correct) {
                $score++;
            }
            
            $results[] = array(
                'question_index' => $index,
                'user_answer' => $user_answer,
                'correct_answer' => intval($question['correct']),
                'is_correct' => $is_correct,
                'explanation' => isset($question['explanation']) ? $question['explanation'] : '',
            );
        }
        
        // Save quiz result
        global $wpdb;
        $table = $wpdb->prefix . 'ahominna_quiz_results';
        
        $wpdb->insert(
            $table,
            array(
                'user_id' => $user_id,
                'quiz_id' => $quiz_id,
                'lesson_id' => $lesson_id,
                'score' => $score,
                'total_questions' => $total,
                'answers' => json_encode($answers),
                'completed_at' => current_time('mysql'),
            ),
            array('%d', '%d', '%d', '%d', '%d', '%s', '%s')
        );
        
        wp_send_json_success(array(
            'score' => $score,
            'total' => $total,
            'percentage' => round(($score / $total) * 100, 2),
            'results' => $results,
        ));
    }
    
    /**
     * Get vocabulary for a lesson
     */
    public function get_vocabulary() {
        check_ajax_referer('ahominna_nonce', 'nonce');
        
        $lesson_id = isset($_POST['lesson_id']) ? intval($_POST['lesson_id']) : 0;
        
        if (!$lesson_id) {
            wp_send_json_error(array('message' => __('Invalid lesson ID', 'ahominna')));
        }
        
        $args = array(
            'post_type' => 'ahominna_vocabulary',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_lesson_id',
                    'value' => $lesson_id,
                    'compare' => '='
                )
            ),
        );
        
        $vocab_query = new WP_Query($args);
        $vocabulary = array();
        
        if ($vocab_query->have_posts()) {
            while ($vocab_query->have_posts()) {
                $vocab_query->the_post();
                $post_id = get_the_ID();
                
                $vocabulary[] = array(
                    'id' => $post_id,
                    'kanji' => get_post_meta($post_id, '_kanji', true),
                    'hiragana' => get_post_meta($post_id, '_hiragana', true),
                    'romaji' => get_post_meta($post_id, '_romaji', true),
                    'vietnamese' => get_post_meta($post_id, '_vietnamese', true),
                    'image_url' => get_post_meta($post_id, '_image_url', true),
                    'audio_url' => get_post_meta($post_id, '_audio_url', true),
                );
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success(array('vocabulary' => $vocabulary));
    }
}
