<?php
/**
 * Admin functionality
 *
 * @package AhoMinna
 */

class AhoMinna_Admin {
    
    /**
     * Add admin menu pages
     */
    public function add_admin_menu() {
        // Main dashboard page
        add_menu_page(
            __('AhoMINNA Dashboard', 'ahominna'),
            __('AhoMINNA', 'ahominna'),
            'manage_options',
            'ahominna-dashboard',
            array($this, 'render_dashboard'),
            'dashicons-book-alt',
            6
        );
        
        // Import page
        add_submenu_page(
            'ahominna-dashboard',
            __('Import Data', 'ahominna'),
            __('Import', 'ahominna'),
            'manage_options',
            'ahominna-import',
            array($this, 'render_import_page')
        );
        
        // Settings page
        add_submenu_page(
            'ahominna-dashboard',
            __('Settings', 'ahominna'),
            __('Settings', 'ahominna'),
            'manage_options',
            'ahominna-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Enqueue admin styles
     */
    public function enqueue_styles($hook) {
        if (strpos($hook, 'ahominna') !== false || get_post_type() === 'ahominna_lesson') {
            wp_enqueue_style(
                'ahominna-admin',
                AHOMINNA_PLUGIN_URL . 'admin/css/admin-style.css',
                array(),
                AHOMINNA_VERSION
            );
        }
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_scripts($hook) {
        if (strpos($hook, 'ahominna') !== false || get_post_type() === 'ahominna_lesson') {
            wp_enqueue_media();
            wp_enqueue_script(
                'ahominna-admin',
                AHOMINNA_PLUGIN_URL . 'admin/js/admin-script.js',
                array('jquery'),
                AHOMINNA_VERSION,
                true
            );
            
            wp_localize_script('ahominna-admin', 'ahominnaAdmin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ahominna_admin_nonce'),
            ));
        }
    }
    
    /**
     * Render dashboard page
     */
    public function render_dashboard() {
        include AHOMINNA_PLUGIN_DIR . 'admin/views/dashboard.php';
    }
    
    /**
     * Render import page
     */
    public function render_import_page() {
        include AHOMINNA_PLUGIN_DIR . 'admin/views/import.php';
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        include AHOMINNA_PLUGIN_DIR . 'admin/views/settings.php';
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Lesson meta box
        add_meta_box(
            'ahominna_lesson_details',
            __('Lesson Details', 'ahominna'),
            array($this, 'render_lesson_meta_box'),
            'ahominna_lesson',
            'normal',
            'high'
        );
        
        // Vocabulary meta box
        add_meta_box(
            'ahominna_vocabulary_details',
            __('Vocabulary Details', 'ahominna'),
            array($this, 'render_vocabulary_meta_box'),
            'ahominna_vocabulary',
            'normal',
            'high'
        );
        
        // Grammar meta box
        add_meta_box(
            'ahominna_grammar_details',
            __('Grammar Details', 'ahominna'),
            array($this, 'render_grammar_meta_box'),
            'ahominna_grammar',
            'normal',
            'high'
        );
        
        // Dialogue meta box
        add_meta_box(
            'ahominna_dialogue_details',
            __('Dialogue Details', 'ahominna'),
            array($this, 'render_dialogue_meta_box'),
            'ahominna_dialogue',
            'normal',
            'high'
        );
        
        // Quiz meta box
        add_meta_box(
            'ahominna_quiz_details',
            __('Quiz Questions', 'ahominna'),
            array($this, 'render_quiz_meta_box'),
            'ahominna_quiz',
            'normal',
            'high'
        );
    }
    
    /**
     * Render lesson meta box
     */
    public function render_lesson_meta_box($post) {
        wp_nonce_field('ahominna_lesson_nonce', 'ahominna_lesson_nonce_field');
        
        $lesson_number = get_post_meta($post->ID, '_lesson_number', true);
        $vocabulary_ids = get_post_meta($post->ID, '_vocabulary_ids', true);
        $grammar_ids = get_post_meta($post->ID, '_grammar_ids', true);
        $dialogue_ids = get_post_meta($post->ID, '_dialogue_ids', true);
        $quiz_ids = get_post_meta($post->ID, '_quiz_ids', true);
        ?>
        <div class="ahominna-meta-box">
            <p>
                <label for="lesson_number"><?php _e('Lesson Number (1-50):', 'ahominna'); ?></label>
                <input type="number" id="lesson_number" name="lesson_number" value="<?php echo esc_attr($lesson_number); ?>" min="1" max="50" />
            </p>
            <p>
                <label for="vocabulary_ids"><?php _e('Vocabulary IDs (comma-separated):', 'ahominna'); ?></label>
                <input type="text" id="vocabulary_ids" name="vocabulary_ids" value="<?php echo esc_attr($vocabulary_ids); ?>" class="widefat" />
            </p>
            <p>
                <label for="grammar_ids"><?php _e('Grammar IDs (comma-separated):', 'ahominna'); ?></label>
                <input type="text" id="grammar_ids" name="grammar_ids" value="<?php echo esc_attr($grammar_ids); ?>" class="widefat" />
            </p>
            <p>
                <label for="dialogue_ids"><?php _e('Dialogue IDs (comma-separated):', 'ahominna'); ?></label>
                <input type="text" id="dialogue_ids" name="dialogue_ids" value="<?php echo esc_attr($dialogue_ids); ?>" class="widefat" />
            </p>
            <p>
                <label for="quiz_ids"><?php _e('Quiz IDs (comma-separated):', 'ahominna'); ?></label>
                <input type="text" id="quiz_ids" name="quiz_ids" value="<?php echo esc_attr($quiz_ids); ?>" class="widefat" />
            </p>
        </div>
        <?php
    }
    
    /**
     * Render vocabulary meta box
     */
    public function render_vocabulary_meta_box($post) {
        wp_nonce_field('ahominna_vocabulary_nonce', 'ahominna_vocabulary_nonce_field');
        
        $kanji = get_post_meta($post->ID, '_kanji', true);
        $hiragana = get_post_meta($post->ID, '_hiragana', true);
        $romaji = get_post_meta($post->ID, '_romaji', true);
        $vietnamese = get_post_meta($post->ID, '_vietnamese', true);
        $audio_url = get_post_meta($post->ID, '_audio_url', true);
        $image_url = get_post_meta($post->ID, '_image_url', true);
        $lesson_id = get_post_meta($post->ID, '_lesson_id', true);
        ?>
        <div class="ahominna-meta-box">
            <p>
                <label for="lesson_id"><?php _e('Lesson ID:', 'ahominna'); ?></label>
                <input type="number" id="lesson_id" name="lesson_id" value="<?php echo esc_attr($lesson_id); ?>" />
            </p>
            <p>
                <label for="kanji"><?php _e('Kanji:', 'ahominna'); ?></label>
                <input type="text" id="kanji" name="kanji" value="<?php echo esc_attr($kanji); ?>" class="widefat" />
            </p>
            <p>
                <label for="hiragana"><?php _e('Hiragana:', 'ahominna'); ?></label>
                <input type="text" id="hiragana" name="hiragana" value="<?php echo esc_attr($hiragana); ?>" class="widefat" />
            </p>
            <p>
                <label for="romaji"><?php _e('Romaji:', 'ahominna'); ?></label>
                <input type="text" id="romaji" name="romaji" value="<?php echo esc_attr($romaji); ?>" class="widefat" />
            </p>
            <p>
                <label for="vietnamese"><?php _e('Vietnamese Meaning:', 'ahominna'); ?></label>
                <input type="text" id="vietnamese" name="vietnamese" value="<?php echo esc_attr($vietnamese); ?>" class="widefat" />
            </p>
            <p>
                <label for="audio_url"><?php _e('Audio URL:', 'ahominna'); ?></label>
                <input type="url" id="audio_url" name="audio_url" value="<?php echo esc_attr($audio_url); ?>" class="widefat" />
                <button type="button" class="button ahominna-upload-audio"><?php _e('Upload Audio', 'ahominna'); ?></button>
            </p>
            <p>
                <label for="image_url"><?php _e('Image URL:', 'ahominna'); ?></label>
                <input type="url" id="image_url" name="image_url" value="<?php echo esc_attr($image_url); ?>" class="widefat" />
                <button type="button" class="button ahominna-upload-image"><?php _e('Upload Image', 'ahominna'); ?></button>
            </p>
        </div>
        <?php
    }
    
    /**
     * Render grammar meta box
     */
    public function render_grammar_meta_box($post) {
        wp_nonce_field('ahominna_grammar_nonce', 'ahominna_grammar_nonce_field');
        
        $pattern = get_post_meta($post->ID, '_pattern', true);
        $explanation_vi = get_post_meta($post->ID, '_explanation_vi', true);
        $examples = get_post_meta($post->ID, '_examples', true);
        $lesson_id = get_post_meta($post->ID, '_lesson_id', true);
        ?>
        <div class="ahominna-meta-box">
            <p>
                <label for="lesson_id"><?php _e('Lesson ID:', 'ahominna'); ?></label>
                <input type="number" id="lesson_id" name="lesson_id" value="<?php echo esc_attr($lesson_id); ?>" />
            </p>
            <p>
                <label for="pattern"><?php _e('Grammar Pattern:', 'ahominna'); ?></label>
                <input type="text" id="pattern" name="pattern" value="<?php echo esc_attr($pattern); ?>" class="widefat" />
            </p>
            <p>
                <label for="explanation_vi"><?php _e('Explanation (Vietnamese):', 'ahominna'); ?></label>
                <textarea id="explanation_vi" name="explanation_vi" rows="5" class="widefat"><?php echo esc_textarea($explanation_vi); ?></textarea>
            </p>
            <p>
                <label for="examples"><?php _e('Examples (JSON format):', 'ahominna'); ?></label>
                <textarea id="examples" name="examples" rows="10" class="widefat"><?php echo esc_textarea($examples); ?></textarea>
                <small><?php _e('Format: [{"japanese":"...","vietnamese":"...","highlight":"..."}]', 'ahominna'); ?></small>
            </p>
        </div>
        <?php
    }
    
    /**
     * Render dialogue meta box
     */
    public function render_dialogue_meta_box($post) {
        wp_nonce_field('ahominna_dialogue_nonce', 'ahominna_dialogue_nonce_field');
        
        $dialogue_lines = get_post_meta($post->ID, '_dialogue_lines', true);
        $full_audio_url = get_post_meta($post->ID, '_full_audio_url', true);
        $video_url = get_post_meta($post->ID, '_video_url', true);
        $lesson_id = get_post_meta($post->ID, '_lesson_id', true);
        ?>
        <div class="ahominna-meta-box">
            <p>
                <label for="lesson_id"><?php _e('Lesson ID:', 'ahominna'); ?></label>
                <input type="number" id="lesson_id" name="lesson_id" value="<?php echo esc_attr($lesson_id); ?>" />
            </p>
            <p>
                <label for="dialogue_lines"><?php _e('Dialogue Lines (JSON format):', 'ahominna'); ?></label>
                <textarea id="dialogue_lines" name="dialogue_lines" rows="15" class="widefat"><?php echo esc_textarea($dialogue_lines); ?></textarea>
                <small><?php _e('Format: [{"speaker":"...","japanese":"...","vietnamese":"...","audio_url":"..."}]', 'ahominna'); ?></small>
            </p>
            <p>
                <label for="full_audio_url"><?php _e('Full Audio URL:', 'ahominna'); ?></label>
                <input type="url" id="full_audio_url" name="full_audio_url" value="<?php echo esc_attr($full_audio_url); ?>" class="widefat" />
            </p>
            <p>
                <label for="video_url"><?php _e('Video URL (YouTube or direct):', 'ahominna'); ?></label>
                <input type="url" id="video_url" name="video_url" value="<?php echo esc_attr($video_url); ?>" class="widefat" />
            </p>
        </div>
        <?php
    }
    
    /**
     * Render quiz meta box
     */
    public function render_quiz_meta_box($post) {
        wp_nonce_field('ahominna_quiz_nonce', 'ahominna_quiz_nonce_field');
        
        $questions = get_post_meta($post->ID, '_questions', true);
        $lesson_id = get_post_meta($post->ID, '_lesson_id', true);
        ?>
        <div class="ahominna-meta-box">
            <p>
                <label for="lesson_id"><?php _e('Lesson ID:', 'ahominna'); ?></label>
                <input type="number" id="lesson_id" name="lesson_id" value="<?php echo esc_attr($lesson_id); ?>" />
            </p>
            <p>
                <label for="questions"><?php _e('Questions (JSON format):', 'ahominna'); ?></label>
                <textarea id="questions" name="questions" rows="20" class="widefat"><?php echo esc_textarea($questions); ?></textarea>
                <small><?php _e('Format: [{"question":"...","type":"vocabulary|grammar|listening","options":["A","B","C","D"],"correct":0,"explanation":"...","audio_url":"..."}]', 'ahominna'); ?></small>
            </p>
        </div>
        <?php
    }
    
    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id, $post) {
        // Check nonce and permissions
        $post_type = get_post_type($post_id);
        
        if ($post_type === 'ahominna_lesson') {
            if (!isset($_POST['ahominna_lesson_nonce_field']) || 
                !wp_verify_nonce($_POST['ahominna_lesson_nonce_field'], 'ahominna_lesson_nonce')) {
                return;
            }
            
            if (isset($_POST['lesson_number'])) {
                update_post_meta($post_id, '_lesson_number', sanitize_text_field($_POST['lesson_number']));
            }
            if (isset($_POST['vocabulary_ids'])) {
                update_post_meta($post_id, '_vocabulary_ids', sanitize_text_field($_POST['vocabulary_ids']));
            }
            if (isset($_POST['grammar_ids'])) {
                update_post_meta($post_id, '_grammar_ids', sanitize_text_field($_POST['grammar_ids']));
            }
            if (isset($_POST['dialogue_ids'])) {
                update_post_meta($post_id, '_dialogue_ids', sanitize_text_field($_POST['dialogue_ids']));
            }
            if (isset($_POST['quiz_ids'])) {
                update_post_meta($post_id, '_quiz_ids', sanitize_text_field($_POST['quiz_ids']));
            }
        }
        
        elseif ($post_type === 'ahominna_vocabulary') {
            if (!isset($_POST['ahominna_vocabulary_nonce_field']) || 
                !wp_verify_nonce($_POST['ahominna_vocabulary_nonce_field'], 'ahominna_vocabulary_nonce')) {
                return;
            }
            
            $fields = array('lesson_id', 'kanji', 'hiragana', 'romaji', 'vietnamese', 'audio_url', 'image_url');
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
                }
            }
        }
        
        elseif ($post_type === 'ahominna_grammar') {
            if (!isset($_POST['ahominna_grammar_nonce_field']) || 
                !wp_verify_nonce($_POST['ahominna_grammar_nonce_field'], 'ahominna_grammar_nonce')) {
                return;
            }
            
            if (isset($_POST['lesson_id'])) {
                update_post_meta($post_id, '_lesson_id', sanitize_text_field($_POST['lesson_id']));
            }
            if (isset($_POST['pattern'])) {
                update_post_meta($post_id, '_pattern', sanitize_text_field($_POST['pattern']));
            }
            if (isset($_POST['explanation_vi'])) {
                update_post_meta($post_id, '_explanation_vi', sanitize_textarea_field($_POST['explanation_vi']));
            }
            if (isset($_POST['examples'])) {
                update_post_meta($post_id, '_examples', sanitize_textarea_field($_POST['examples']));
            }
        }
        
        elseif ($post_type === 'ahominna_dialogue') {
            if (!isset($_POST['ahominna_dialogue_nonce_field']) || 
                !wp_verify_nonce($_POST['ahominna_dialogue_nonce_field'], 'ahominna_dialogue_nonce')) {
                return;
            }
            
            if (isset($_POST['lesson_id'])) {
                update_post_meta($post_id, '_lesson_id', sanitize_text_field($_POST['lesson_id']));
            }
            if (isset($_POST['dialogue_lines'])) {
                update_post_meta($post_id, '_dialogue_lines', sanitize_textarea_field($_POST['dialogue_lines']));
            }
            if (isset($_POST['full_audio_url'])) {
                update_post_meta($post_id, '_full_audio_url', sanitize_text_field($_POST['full_audio_url']));
            }
            if (isset($_POST['video_url'])) {
                update_post_meta($post_id, '_video_url', sanitize_text_field($_POST['video_url']));
            }
        }
        
        elseif ($post_type === 'ahominna_quiz') {
            if (!isset($_POST['ahominna_quiz_nonce_field']) || 
                !wp_verify_nonce($_POST['ahominna_quiz_nonce_field'], 'ahominna_quiz_nonce')) {
                return;
            }
            
            if (isset($_POST['lesson_id'])) {
                update_post_meta($post_id, '_lesson_id', sanitize_text_field($_POST['lesson_id']));
            }
            if (isset($_POST['questions'])) {
                update_post_meta($post_id, '_questions', sanitize_textarea_field($_POST['questions']));
            }
        }
    }
}
