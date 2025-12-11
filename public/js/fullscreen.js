/**
 * Fullscreen Functionality
 * @package AhoMinna
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        var isFullscreen = false;
        var lessonContainer = $('.ahominna-lesson-container');
        
        // ============ Toggle Fullscreen ============
        $('#fullscreen-toggle').on('click', function() {
            if (!isFullscreen) {
                enterFullscreen();
            } else {
                exitFullscreen();
            }
        });
        
        function enterFullscreen() {
            var elem = lessonContainer[0];
            
            // Try different fullscreen methods
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            } else {
                // Fallback - use CSS fullscreen
                lessonContainer.addClass('fullscreen');
                $('body').css('overflow', 'hidden');
            }
            
            isFullscreen = true;
            $('#fullscreen-toggle .dashicons')
                .removeClass('dashicons-fullscreen-alt')
                .addClass('dashicons-fullscreen-exit-alt');
        }
        
        function exitFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else {
                // Fallback
                lessonContainer.removeClass('fullscreen');
                $('body').css('overflow', '');
            }
            
            isFullscreen = false;
            $('#fullscreen-toggle .dashicons')
                .removeClass('dashicons-fullscreen-exit-alt')
                .addClass('dashicons-fullscreen-alt');
        }
        
        // ============ Detect Fullscreen Changes ============
        $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange msfullscreenchange', function() {
            var fullscreenElement = document.fullscreenElement || 
                                   document.webkitFullscreenElement || 
                                   document.mozFullScreenElement || 
                                   document.msFullscreenElement;
            
            if (!fullscreenElement) {
                isFullscreen = false;
                lessonContainer.removeClass('fullscreen');
                $('#fullscreen-toggle .dashicons')
                    .removeClass('dashicons-fullscreen-exit-alt')
                    .addClass('dashicons-fullscreen-alt');
            }
        });
        
        // ============ Keyboard Shortcuts ============
        $(document).on('keydown', function(e) {
            // ESC to exit fullscreen
            if (e.key === 'Escape' && isFullscreen) {
                exitFullscreen();
            }
            
            // F11 alternative (F key)
            if (e.key === 'f' && e.ctrlKey) {
                e.preventDefault();
                $('#fullscreen-toggle').click();
            }
        });
        
        // ============ Presentation Mode Navigation ============
        var presentationSections = ['vocabulary', 'grammar', 'dialogue', 'quiz'];
        var currentSectionIndex = 0;
        
        function navigatePresentation(direction) {
            if (!isFullscreen) return;
            
            if (direction === 'next') {
                currentSectionIndex = (currentSectionIndex + 1) % presentationSections.length;
            } else if (direction === 'prev') {
                currentSectionIndex = (currentSectionIndex - 1 + presentationSections.length) % presentationSections.length;
            }
            
            var section = presentationSections[currentSectionIndex];
            $('.nav-tab[data-section="' + section + '"]').click();
        }
        
        // ============ Keyboard Navigation in Fullscreen ============
        $(document).on('keydown', function(e) {
            if (!isFullscreen) return;
            
            // Arrow keys for section navigation
            if (e.key === 'ArrowRight' && e.shiftKey) {
                e.preventDefault();
                navigatePresentation('next');
            } else if (e.key === 'ArrowLeft' && e.shiftKey) {
                e.preventDefault();
                navigatePresentation('prev');
            }
            
            // Space for next section
            if (e.key === ' ' && e.shiftKey) {
                e.preventDefault();
                navigatePresentation('next');
            }
        });
        
        // ============ Touch Gestures for Fullscreen ============
        var touchStartX = 0;
        var touchStartY = 0;
        var touchEndX = 0;
        var touchEndY = 0;
        
        if (lessonContainer.length) {
            lessonContainer[0].addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
                touchStartY = e.changedTouches[0].screenY;
            }, false);
            
            lessonContainer[0].addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                touchEndY = e.changedTouches[0].screenY;
                handlePresentationSwipe();
            }, false);
        }
        
        function handlePresentationSwipe() {
            if (!isFullscreen) return;
            
            var swipeThreshold = 100;
            var horizontalDiff = touchEndX - touchStartX;
            var verticalDiff = Math.abs(touchEndY - touchStartY);
            
            // Only handle horizontal swipes
            if (verticalDiff < 50) {
                if (horizontalDiff < -swipeThreshold) {
                    // Swipe left - next section
                    navigatePresentation('next');
                } else if (horizontalDiff > swipeThreshold) {
                    // Swipe right - previous section
                    navigatePresentation('prev');
                }
            }
        }
        
        // ============ Update Current Section Index ============
        $('.nav-tab').on('click', function() {
            var section = $(this).data('section');
            currentSectionIndex = presentationSections.indexOf(section);
            if (currentSectionIndex === -1) currentSectionIndex = 0;
        });
        
        // ============ Fullscreen Styling Adjustments ============
        function adjustFullscreenLayout() {
            if (isFullscreen) {
                lessonContainer.addClass('fullscreen');
                
                // Adjust font sizes for presentation
                $('.lesson-title').css('font-size', '48px');
                $('.section-header h2').css('font-size', '36px');
            } else {
                lessonContainer.removeClass('fullscreen');
                
                // Reset font sizes
                $('.lesson-title').css('font-size', '');
                $('.section-header h2').css('font-size', '');
            }
        }
        
        // Listen for fullscreen toggle
        $('#fullscreen-toggle').on('click', function() {
            setTimeout(adjustFullscreenLayout, 100);
        });
        
    });
    
})(jQuery);
