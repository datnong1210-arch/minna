<?php
/**
 * Import functionality for bulk data import
 *
 * @package AhoMinna
 */

class AhoMinna_Importer {
    
    /**
     * Import vocabulary from CSV
     */
    public static function import_vocabulary_csv($file_path, $lesson_id) {
        if (!file_exists($file_path)) {
            return new WP_Error('file_not_found', __('CSV file not found', 'ahominna'));
        }
        
        // Validate lesson_id is within valid range (1-50 for Minna no Nihongo)
        $lesson_id = intval($lesson_id);
        if ($lesson_id < 1 || $lesson_id > 50) {
            return new WP_Error('invalid_lesson_id', __('Lesson ID must be between 1 and 50', 'ahominna'));
        }
        
        $handle = fopen($file_path, 'r');
        if ($handle === false) {
            return new WP_Error('file_open_error', __('Could not open CSV file', 'ahominna'));
        }
        
        $imported = 0;
        $errors = array();
        $header = fgetcsv($handle); // Skip header row
        
        while (($data = fgetcsv($handle)) !== false) {
            // Expected columns: kanji, hiragana, romaji, vietnamese, image_url, audio_url
            if (count($data) < 4) {
                $errors[] = sprintf(__('Invalid row data: %s', 'ahominna'), implode(',', $data));
                continue;
            }
            
            $vocab_data = array(
                'post_title' => sanitize_text_field($data[0]), // Use kanji as title
                'post_type' => 'ahominna_vocabulary',
                'post_status' => 'publish',
            );
            
            $vocab_id = wp_insert_post($vocab_data);
            
            if (is_wp_error($vocab_id)) {
                $errors[] = $vocab_id->get_error_message();
                continue;
            }
            
            // Save meta data
            update_post_meta($vocab_id, '_lesson_id', $lesson_id);
            update_post_meta($vocab_id, '_kanji', sanitize_text_field($data[0]));
            update_post_meta($vocab_id, '_hiragana', sanitize_text_field($data[1]));
            update_post_meta($vocab_id, '_romaji', sanitize_text_field($data[2]));
            update_post_meta($vocab_id, '_vietnamese', sanitize_text_field($data[3]));
            
            if (isset($data[4])) {
                update_post_meta($vocab_id, '_image_url', esc_url_raw($data[4]));
            }
            if (isset($data[5])) {
                update_post_meta($vocab_id, '_audio_url', esc_url_raw($data[5]));
            }
            
            $imported++;
        }
        
        fclose($handle);
        
        return array(
            'success' => true,
            'imported' => $imported,
            'errors' => $errors,
        );
    }
    
    /**
     * Import quiz questions from CSV
     */
    public static function import_quiz_csv($file_path, $lesson_id) {
        if (!file_exists($file_path)) {
            return new WP_Error('file_not_found', __('CSV file not found', 'ahominna'));
        }
        
        // Validate lesson_id is within valid range (1-50 for Minna no Nihongo)
        $lesson_id = intval($lesson_id);
        if ($lesson_id < 1 || $lesson_id > 50) {
            return new WP_Error('invalid_lesson_id', __('Lesson ID must be between 1 and 50', 'ahominna'));
        }
        
        $handle = fopen($file_path, 'r');
        if ($handle === false) {
            return new WP_Error('file_open_error', __('Could not open CSV file', 'ahominna'));
        }
        
        $questions = array();
        $header = fgetcsv($handle); // Skip header row
        
        while (($data = fgetcsv($handle)) !== false) {
            // Expected columns: question, type, option_a, option_b, option_c, option_d, correct_index, explanation, audio_url
            if (count($data) < 8) {
                continue;
            }
            
            $question = array(
                'question' => sanitize_text_field($data[0]),
                'type' => sanitize_text_field($data[1]),
                'options' => array(
                    sanitize_text_field($data[2]),
                    sanitize_text_field($data[3]),
                    sanitize_text_field($data[4]),
                    sanitize_text_field($data[5]),
                ),
                'correct' => intval($data[6]),
                'explanation' => sanitize_text_field($data[7]),
            );
            
            if (isset($data[8]) && !empty($data[8])) {
                $question['audio_url'] = esc_url_raw($data[8]);
            }
            
            $questions[] = $question;
        }
        
        fclose($handle);
        
        // Create quiz post
        $quiz_data = array(
            'post_title' => sprintf(__('Lesson %d Quiz', 'ahominna'), $lesson_id),
            'post_type' => 'ahominna_quiz',
            'post_status' => 'publish',
        );
        
        $quiz_id = wp_insert_post($quiz_data);
        
        if (is_wp_error($quiz_id)) {
            return $quiz_id;
        }
        
        update_post_meta($quiz_id, '_lesson_id', $lesson_id);
        update_post_meta($quiz_id, '_questions', json_encode($questions));
        
        return array(
            'success' => true,
            'quiz_id' => $quiz_id,
            'questions_count' => count($questions),
        );
    }
    
    /**
     * Validate CSV file
     */
    public static function validate_csv($file_path, $type) {
        if (!file_exists($file_path)) {
            return array('valid' => false, 'error' => __('File not found', 'ahominna'));
        }
        
        $handle = fopen($file_path, 'r');
        if ($handle === false) {
            return array('valid' => false, 'error' => __('Could not open file', 'ahominna'));
        }
        
        $header = fgetcsv($handle);
        fclose($handle);
        
        $expected_headers = array();
        
        if ($type === 'vocabulary') {
            $expected_headers = array('kanji', 'hiragana', 'romaji', 'vietnamese', 'image_url', 'audio_url');
        } elseif ($type === 'quiz') {
            $expected_headers = array('question', 'type', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_index', 'explanation', 'audio_url');
        }
        
        if (empty($expected_headers)) {
            return array('valid' => false, 'error' => __('Unknown import type', 'ahominna'));
        }
        
        return array('valid' => true, 'columns' => count($header));
    }
}
