/**
 * Main JavaScript
 * @package AhoMinna
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // ============ Section Navigation ============
        $('.nav-tab').on('click', function() {
            var section = $(this).data('section');
            
            // Update active tab
            $('.nav-tab').removeClass('active');
            $(this).addClass('active');
            
            // Show corresponding section
            $('.lesson-section').removeClass('active');
            $('#section-' + section).addClass('active');
            
            // Update progress
            updateProgress();
            
            // Save progress if user is logged in
            if (ahominna.user_id) {
                saveProgress(section);
            }
        });
        
        // ============ Progress Tracking ============
        function updateProgress() {
            var totalSections = $('.lesson-section').length;
            var activeSections = $('.lesson-section.active').length;
            var percentage = (activeSections / totalSections) * 25; // Each section 25%
            
            $('.ahominna-progress-bar .progress-fill').css('width', percentage + '%');
        }
        
        function saveProgress(section) {
            $.ajax({
                url: ahominna.ajax_url,
                type: 'POST',
                data: {
                    action: 'ahominna_save_progress',
                    nonce: ahominna.nonce,
                    lesson_id: getLessonId(),
                    section_type: section,
                    progress_data: JSON.stringify({section: section}),
                    completed: 0
                }
            });
        }
        
        function getLessonId() {
            var container = $('.ahominna-lesson-container');
            if (container.length) {
                var id = container.attr('id');
                return id ? id.replace('lesson-', '') : 0;
            }
            return 0;
        }
        
        // ============ Smooth Scroll ============
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 500);
            }
        });
        
        // ============ Loading Animation ============
        function showLoading() {
            if (!$('.ahominna-loading').length) {
                $('body').append('<div class="ahominna-loading"><div class="spinner"></div></div>');
            }
        }
        
        function hideLoading() {
            $('.ahominna-loading').remove();
        }
        
        // Make functions globally available
        window.ahominnaShowLoading = showLoading;
        window.ahominnaHideLoading = hideLoading;
        
        // ============ Keyboard Navigation ============
        $(document).on('keydown', function(e) {
            // Only handle if not in input fields
            if ($(e.target).is('input, textarea, select')) {
                return;
            }
            
            // Arrow keys for flashcard navigation
            if ($('.flashcard-container:visible').length) {
                if (e.key === 'ArrowLeft') {
                    $('.prev-btn').click();
                } else if (e.key === 'ArrowRight') {
                    $('.next-btn').click();
                } else if (e.key === ' ') {
                    e.preventDefault();
                    $('.flashcard.active .flashcard-flip-btn').click();
                }
            }
            
            // Arrow keys for quiz navigation
            if ($('.quiz-questions:visible').length) {
                if (e.key === 'ArrowLeft') {
                    $('.prev-question-btn:visible').click();
                } else if (e.key === 'ArrowRight') {
                    $('.next-question-btn:visible').click();
                } else if (e.key === 'Enter') {
                    $('.quiz-submit-btn:visible').click();
                }
            }
        });
        
        // ============ Initialize ============
        updateProgress();
        
    });
    
})(jQuery);
