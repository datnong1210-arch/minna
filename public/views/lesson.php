<?php
/**
 * Single Lesson Template
 *
 * @package AhoMinna
 */

get_header();

while (have_posts()) : the_post();
    $lesson_number = get_post_meta(get_the_ID(), '_lesson_number', true);
    $vocabulary_ids = get_post_meta(get_the_ID(), '_vocabulary_ids', true);
    $grammar_ids = get_post_meta(get_the_ID(), '_grammar_ids', true);
    $dialogue_ids = get_post_meta(get_the_ID(), '_dialogue_ids', true);
    $quiz_ids = get_post_meta(get_the_ID(), '_quiz_ids', true);
?>

<div class="ahominna-lesson-container" id="lesson-<?php echo esc_attr($lesson_number); ?>">
    
    <!-- Fullscreen Button -->
    <button class="ahominna-fullscreen-btn" id="fullscreen-toggle" title="<?php _e('Fullscreen Mode', 'ahominna'); ?>">
        <span class="dashicons dashicons-fullscreen-alt"></span>
    </button>
    
    <!-- Lesson Header -->
    <div class="ahominna-lesson-header">
        <div class="lesson-number-badge">
            <?php printf(__('Bài %d', 'ahominna'), $lesson_number); ?>
        </div>
        <h1 class="lesson-title"><?php the_title(); ?></h1>
        <?php if (get_the_excerpt()) : ?>
            <p class="lesson-description"><?php echo esc_html(get_the_excerpt()); ?></p>
        <?php endif; ?>
    </div>
    
    <!-- Navigation Tabs -->
    <nav class="ahominna-lesson-nav">
        <button class="nav-tab active" data-section="vocabulary">
            <span class="dashicons dashicons-translation"></span>
            <?php _e('Từ vựng', 'ahominna'); ?>
        </button>
        <button class="nav-tab" data-section="grammar">
            <span class="dashicons dashicons-welcome-learn-more"></span>
            <?php _e('Ngữ pháp', 'ahominna'); ?>
        </button>
        <button class="nav-tab" data-section="dialogue">
            <span class="dashicons dashicons-admin-comments"></span>
            <?php _e('Hội thoại', 'ahominna'); ?>
        </button>
        <button class="nav-tab" data-section="quiz">
            <span class="dashicons dashicons-yes-alt"></span>
            <?php _e('Trắc nghiệm', 'ahominna'); ?>
        </button>
    </nav>
    
    <!-- Lesson Content Sections -->
    <div class="ahominna-lesson-content">
        
        <!-- Vocabulary Section -->
        <section id="section-vocabulary" class="lesson-section active">
            <?php 
            $lesson_id = get_the_ID();
            include AHOMINNA_PLUGIN_DIR . 'public/views/vocabulary.php'; 
            ?>
        </section>
        
        <!-- Grammar Section -->
        <section id="section-grammar" class="lesson-section">
            <?php include AHOMINNA_PLUGIN_DIR . 'public/views/grammar.php'; ?>
        </section>
        
        <!-- Dialogue Section -->
        <section id="section-dialogue" class="lesson-section">
            <?php include AHOMINNA_PLUGIN_DIR . 'public/views/dialogue.php'; ?>
        </section>
        
        <!-- Quiz Section -->
        <section id="section-quiz" class="lesson-section">
            <?php include AHOMINNA_PLUGIN_DIR . 'public/views/quiz.php'; ?>
        </section>
        
    </div>
    
    <!-- Progress Bar -->
    <div class="ahominna-progress-bar">
        <div class="progress-fill" style="width: 0%"></div>
    </div>
    
</div>

<?php
endwhile;

get_footer();
?>
