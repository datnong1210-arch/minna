<?php
/**
 * Register custom post types
 *
 * @package AhoMinna
 */

class AhoMinna_Post_Types {
    
    /**
     * Register all custom post types
     */
    public function register_post_types() {
        $this->register_lesson_post_type();
        $this->register_vocabulary_post_type();
        $this->register_grammar_post_type();
        $this->register_dialogue_post_type();
        $this->register_quiz_post_type();
    }
    
    /**
     * Register Lesson post type
     */
    private function register_lesson_post_type() {
        $labels = array(
            'name'                  => __('Lessons', 'ahominna'),
            'singular_name'         => __('Lesson', 'ahominna'),
            'menu_name'             => __('Minna Lessons', 'ahominna'),
            'add_new'               => __('Add New', 'ahominna'),
            'add_new_item'          => __('Add New Lesson', 'ahominna'),
            'edit_item'             => __('Edit Lesson', 'ahominna'),
            'new_item'              => __('New Lesson', 'ahominna'),
            'view_item'             => __('View Lesson', 'ahominna'),
            'search_items'          => __('Search Lessons', 'ahominna'),
            'not_found'             => __('No lessons found', 'ahominna'),
            'not_found_in_trash'    => __('No lessons found in Trash', 'ahominna'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => 'lesson'),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => false,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-book-alt',
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'show_in_rest'          => true,
        );
        
        register_post_type('ahominna_lesson', $args);
    }
    
    /**
     * Register Vocabulary post type
     */
    private function register_vocabulary_post_type() {
        $labels = array(
            'name'                  => __('Vocabulary', 'ahominna'),
            'singular_name'         => __('Vocabulary', 'ahominna'),
            'menu_name'             => __('Vocabulary', 'ahominna'),
            'add_new'               => __('Add New', 'ahominna'),
            'add_new_item'          => __('Add New Vocabulary', 'ahominna'),
            'edit_item'             => __('Edit Vocabulary', 'ahominna'),
            'new_item'              => __('New Vocabulary', 'ahominna'),
            'view_item'             => __('View Vocabulary', 'ahominna'),
            'search_items'          => __('Search Vocabulary', 'ahominna'),
            'not_found'             => __('No vocabulary found', 'ahominna'),
            'not_found_in_trash'    => __('No vocabulary found in Trash', 'ahominna'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => 'edit.php?post_type=ahominna_lesson',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'vocabulary'),
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'menu_position'         => null,
            'supports'              => array('title', 'thumbnail', 'custom-fields'),
            'show_in_rest'          => true,
        );
        
        register_post_type('ahominna_vocabulary', $args);
    }
    
    /**
     * Register Grammar post type
     */
    private function register_grammar_post_type() {
        $labels = array(
            'name'                  => __('Grammar', 'ahominna'),
            'singular_name'         => __('Grammar', 'ahominna'),
            'menu_name'             => __('Grammar', 'ahominna'),
            'add_new'               => __('Add New', 'ahominna'),
            'add_new_item'          => __('Add New Grammar', 'ahominna'),
            'edit_item'             => __('Edit Grammar', 'ahominna'),
            'new_item'              => __('New Grammar', 'ahominna'),
            'view_item'             => __('View Grammar', 'ahominna'),
            'search_items'          => __('Search Grammar', 'ahominna'),
            'not_found'             => __('No grammar found', 'ahominna'),
            'not_found_in_trash'    => __('No grammar found in Trash', 'ahominna'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => 'edit.php?post_type=ahominna_lesson',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'grammar'),
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'menu_position'         => null,
            'supports'              => array('title', 'editor', 'custom-fields'),
            'show_in_rest'          => true,
        );
        
        register_post_type('ahominna_grammar', $args);
    }
    
    /**
     * Register Dialogue post type
     */
    private function register_dialogue_post_type() {
        $labels = array(
            'name'                  => __('Dialogues', 'ahominna'),
            'singular_name'         => __('Dialogue', 'ahominna'),
            'menu_name'             => __('Dialogues', 'ahominna'),
            'add_new'               => __('Add New', 'ahominna'),
            'add_new_item'          => __('Add New Dialogue', 'ahominna'),
            'edit_item'             => __('Edit Dialogue', 'ahominna'),
            'new_item'              => __('New Dialogue', 'ahominna'),
            'view_item'             => __('View Dialogue', 'ahominna'),
            'search_items'          => __('Search Dialogues', 'ahominna'),
            'not_found'             => __('No dialogues found', 'ahominna'),
            'not_found_in_trash'    => __('No dialogues found in Trash', 'ahominna'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => 'edit.php?post_type=ahominna_lesson',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'dialogue'),
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'menu_position'         => null,
            'supports'              => array('title', 'editor', 'custom-fields'),
            'show_in_rest'          => true,
        );
        
        register_post_type('ahominna_dialogue', $args);
    }
    
    /**
     * Register Quiz post type
     */
    private function register_quiz_post_type() {
        $labels = array(
            'name'                  => __('Quizzes', 'ahominna'),
            'singular_name'         => __('Quiz', 'ahominna'),
            'menu_name'             => __('Quizzes', 'ahominna'),
            'add_new'               => __('Add New', 'ahominna'),
            'add_new_item'          => __('Add New Quiz', 'ahominna'),
            'edit_item'             => __('Edit Quiz', 'ahominna'),
            'new_item'              => __('New Quiz', 'ahominna'),
            'view_item'             => __('View Quiz', 'ahominna'),
            'search_items'          => __('Search Quizzes', 'ahominna'),
            'not_found'             => __('No quizzes found', 'ahominna'),
            'not_found_in_trash'    => __('No quizzes found in Trash', 'ahominna'),
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => 'edit.php?post_type=ahominna_lesson',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'quiz'),
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'menu_position'         => null,
            'supports'              => array('title', 'custom-fields'),
            'show_in_rest'          => true,
        );
        
        register_post_type('ahominna_quiz', $args);
    }
    
    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Register lesson level taxonomy
        $labels = array(
            'name'              => __('Lesson Levels', 'ahominna'),
            'singular_name'     => __('Lesson Level', 'ahominna'),
            'search_items'      => __('Search Levels', 'ahominna'),
            'all_items'         => __('All Levels', 'ahominna'),
            'edit_item'         => __('Edit Level', 'ahominna'),
            'update_item'       => __('Update Level', 'ahominna'),
            'add_new_item'      => __('Add New Level', 'ahominna'),
            'new_item_name'     => __('New Level Name', 'ahominna'),
            'menu_name'         => __('Levels', 'ahominna'),
        );
        
        register_taxonomy('ahominna_level', array('ahominna_lesson'), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'lesson-level'),
            'show_in_rest'      => true,
        ));
    }
}
