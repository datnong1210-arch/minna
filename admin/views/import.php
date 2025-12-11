<?php
/**
 * Admin Import View
 *
 * @package AhoMinna
 */

if (!defined('ABSPATH')) {
    exit;
}

// Handle import
$import_result = null;
if (isset($_POST['ahominna_import_submit']) && check_admin_referer('ahominna_import_nonce')) {
    $import_type = sanitize_text_field($_POST['import_type']);
    $lesson_id = intval($_POST['lesson_id']);
    
    if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['import_file'];
        $upload_dir = wp_upload_dir();
        $target_path = $upload_dir['path'] . '/' . basename($file['name']);
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            if ($import_type === 'vocabulary') {
                $import_result = AhoMinna_Importer::import_vocabulary_csv($target_path, $lesson_id);
            } elseif ($import_type === 'quiz') {
                $import_result = AhoMinna_Importer::import_quiz_csv($target_path, $lesson_id);
            }
            
            // Clean up
            unlink($target_path);
        }
    }
}
?>

<div class="wrap ahominna-import">
    <h1><?php _e('Import Data', 'ahominna'); ?></h1>
    
    <?php if ($import_result && !is_wp_error($import_result)) : ?>
        <div class="notice notice-success">
            <p>
                <?php 
                if (isset($import_result['imported'])) {
                    printf(__('Successfully imported %d items.', 'ahominna'), $import_result['imported']);
                } elseif (isset($import_result['questions_count'])) {
                    printf(__('Successfully imported quiz with %d questions.', 'ahominna'), $import_result['questions_count']);
                }
                ?>
            </p>
            <?php if (!empty($import_result['errors'])) : ?>
                <p><?php _e('Errors:', 'ahominna'); ?></p>
                <ul>
                    <?php foreach ($import_result['errors'] as $error) : ?>
                        <li><?php echo esc_html($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php elseif ($import_result && is_wp_error($import_result)) : ?>
        <div class="notice notice-error">
            <p><?php echo esc_html($import_result->get_error_message()); ?></p>
        </div>
    <?php endif; ?>
    
    <div class="ahominna-import-container">
        <div class="import-form-section">
            <h2><?php _e('Import CSV File', 'ahominna'); ?></h2>
            
            <form method="post" enctype="multipart/form-data" class="ahominna-import-form">
                <?php wp_nonce_field('ahominna_import_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="import_type"><?php _e('Import Type', 'ahominna'); ?></label>
                        </th>
                        <td>
                            <select name="import_type" id="import_type" required>
                                <option value=""><?php _e('Select type...', 'ahominna'); ?></option>
                                <option value="vocabulary"><?php _e('Vocabulary', 'ahominna'); ?></option>
                                <option value="quiz"><?php _e('Quiz Questions', 'ahominna'); ?></option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="lesson_id"><?php _e('Lesson Number', 'ahominna'); ?></label>
                        </th>
                        <td>
                            <input type="number" name="lesson_id" id="lesson_id" min="1" max="50" required />
                            <p class="description"><?php _e('Enter the lesson number (1-50)', 'ahominna'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="import_file"><?php _e('CSV File', 'ahominna'); ?></label>
                        </th>
                        <td>
                            <input type="file" name="import_file" id="import_file" accept=".csv" required />
                            <p class="description"><?php _e('Upload a CSV file with the appropriate format', 'ahominna'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="ahominna_import_submit" class="button button-primary" value="<?php _e('Import', 'ahominna'); ?>" />
                </p>
            </form>
        </div>
        
        <div class="import-instructions-section">
            <h2><?php _e('CSV Format Instructions', 'ahominna'); ?></h2>
            
            <div class="instructions-box">
                <h3><?php _e('Vocabulary CSV Format', 'ahominna'); ?></h3>
                <p><?php _e('The vocabulary CSV file should have the following columns:', 'ahominna'); ?></p>
                <table class="wp-list-table widefat">
                    <thead>
                        <tr>
                            <th><?php _e('Column', 'ahominna'); ?></th>
                            <th><?php _e('Description', 'ahominna'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>kanji</td><td><?php _e('Kanji form', 'ahominna'); ?></td></tr>
                        <tr><td>hiragana</td><td><?php _e('Hiragana reading', 'ahominna'); ?></td></tr>
                        <tr><td>romaji</td><td><?php _e('Romaji transcription', 'ahominna'); ?></td></tr>
                        <tr><td>vietnamese</td><td><?php _e('Vietnamese meaning', 'ahominna'); ?></td></tr>
                        <tr><td>image_url</td><td><?php _e('Image URL (optional)', 'ahominna'); ?></td></tr>
                        <tr><td>audio_url</td><td><?php _e('Audio URL (optional)', 'ahominna'); ?></td></tr>
                    </tbody>
                </table>
                
                <h4><?php _e('Example:', 'ahominna'); ?></h4>
                <pre>kanji,hiragana,romaji,vietnamese,image_url,audio_url
学生,がくせい,gakusei,học sinh,https://example.com/image.jpg,https://example.com/audio.mp3</pre>
            </div>
            
            <div class="instructions-box">
                <h3><?php _e('Quiz CSV Format', 'ahominna'); ?></h3>
                <p><?php _e('The quiz CSV file should have the following columns:', 'ahominna'); ?></p>
                <table class="wp-list-table widefat">
                    <thead>
                        <tr>
                            <th><?php _e('Column', 'ahominna'); ?></th>
                            <th><?php _e('Description', 'ahominna'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>question</td><td><?php _e('Question text', 'ahominna'); ?></td></tr>
                        <tr><td>type</td><td><?php _e('vocabulary, grammar, or listening', 'ahominna'); ?></td></tr>
                        <tr><td>option_a</td><td><?php _e('Option A', 'ahominna'); ?></td></tr>
                        <tr><td>option_b</td><td><?php _e('Option B', 'ahominna'); ?></td></tr>
                        <tr><td>option_c</td><td><?php _e('Option C', 'ahominna'); ?></td></tr>
                        <tr><td>option_d</td><td><?php _e('Option D', 'ahominna'); ?></td></tr>
                        <tr><td>correct_index</td><td><?php _e('Correct answer (0-3)', 'ahominna'); ?></td></tr>
                        <tr><td>explanation</td><td><?php _e('Explanation', 'ahominna'); ?></td></tr>
                        <tr><td>audio_url</td><td><?php _e('Audio URL (optional, for listening questions)', 'ahominna'); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
