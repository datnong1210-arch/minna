/**
 * Audio Player Functionality
 * @package AhoMinna
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        var currentAudio = null;
        
        // ============ Play Audio ============
        function playAudio(url) {
            // Stop current audio if playing
            if (currentAudio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
            }
            
            // Create and play new audio
            currentAudio = new Audio(url);
            currentAudio.play();
            
            return currentAudio;
        }
        
        // ============ Flashcard Audio ============
        $('.audio-play-btn').on('click', function(e) {
            e.stopPropagation();
            var audioUrl = $(this).data('audio');
            
            if (audioUrl) {
                var btn = $(this);
                var icon = btn.find('.dashicons');
                
                // Change icon
                icon.removeClass('dashicons-controls-play').addClass('dashicons-controls-pause');
                btn.addClass('playing');
                
                var audio = playAudio(audioUrl);
                
                audio.onended = function() {
                    icon.removeClass('dashicons-controls-pause').addClass('dashicons-controls-play');
                    btn.removeClass('playing');
                };
                
                audio.onerror = function() {
                    icon.removeClass('dashicons-controls-pause').addClass('dashicons-controls-play');
                    btn.removeClass('playing');
                    console.error('Failed to load audio:', audioUrl);
                };
            }
        });
        
        // ============ Vocabulary List Audio ============
        $('.audio-play-btn-small').on('click', function() {
            var audioUrl = $(this).data('audio');
            
            if (audioUrl) {
                var btn = $(this);
                var icon = btn.find('.dashicons');
                
                // Stop all other audio buttons
                $('.audio-play-btn-small').removeClass('playing');
                $('.audio-play-btn-small .dashicons')
                    .removeClass('dashicons-controls-pause')
                    .addClass('dashicons-controls-play');
                
                // Change icon
                icon.removeClass('dashicons-controls-play').addClass('dashicons-controls-pause');
                btn.addClass('playing');
                
                var audio = playAudio(audioUrl);
                
                audio.onended = function() {
                    icon.removeClass('dashicons-controls-pause').addClass('dashicons-controls-play');
                    btn.removeClass('playing');
                };
                
                audio.onerror = function() {
                    icon.removeClass('dashicons-controls-pause').addClass('dashicons-controls-play');
                    btn.removeClass('playing');
                };
            }
        });
        
        // ============ Dialogue Full Audio ============
        $('.dialogue-play-all').on('click', function() {
            var audioUrl = $(this).data('audio');
            var audio = $('#dialogue-full-audio')[0];
            
            if (audio && audioUrl) {
                var btn = $(this);
                var icon = btn.find('.dashicons');
                
                if (audio.paused) {
                    audio.play();
                    icon.removeClass('dashicons-controls-play').addClass('dashicons-controls-pause');
                    btn.addClass('playing');
                } else {
                    audio.pause();
                    icon.removeClass('dashicons-controls-pause').addClass('dashicons-controls-play');
                    btn.removeClass('playing');
                }
                
                audio.onended = function() {
                    icon.removeClass('dashicons-controls-pause').addClass('dashicons-controls-play');
                    btn.removeClass('playing');
                };
            }
        });
        
        // ============ Dialogue Line Audio ============
        $('.line-audio-btn').on('click', function() {
            var audioUrl = $(this).data('audio');
            var audioElement = $(this).siblings('.line-audio')[0];
            var dialogueLine = $(this).closest('.dialogue-line');
            
            if (audioElement) {
                var btn = $(this);
                var icon = btn.find('.dashicons');
                
                // Stop all other lines
                $('.dialogue-line').removeClass('playing');
                $('.line-audio').each(function() {
                    this.pause();
                    this.currentTime = 0;
                });
                $('.line-audio-btn .dashicons')
                    .removeClass('dashicons-controls-pause')
                    .addClass('dashicons-controls-play');
                
                // Play this line
                if (audioElement.paused) {
                    audioElement.play();
                    icon.removeClass('dashicons-controls-play').addClass('dashicons-controls-pause');
                    dialogueLine.addClass('playing');
                    
                    audioElement.onended = function() {
                        icon.removeClass('dashicons-controls-pause').addClass('dashicons-controls-play');
                        dialogueLine.removeClass('playing');
                    };
                } else {
                    audioElement.pause();
                    icon.removeClass('dashicons-controls-pause').addClass('dashicons-controls-play');
                    dialogueLine.removeClass('playing');
                }
            }
        });
        
        // ============ Quiz Question Audio ============
        $('.question-audio-btn').on('click', function() {
            var audioUrl = $(this).data('audio');
            var audioElement = $(this).siblings('.question-audio-player')[0];
            
            if (audioElement) {
                var btn = $(this);
                var icon = btn.find('.dashicons');
                
                if (audioElement.paused) {
                    audioElement.play();
                    icon.removeClass('dashicons-controls-play').addClass('dashicons-controls-pause');
                    btn.addClass('playing');
                    
                    audioElement.onended = function() {
                        icon.removeClass('dashicons-controls-pause').addClass('dashicons-controls-play');
                        btn.removeClass('playing');
                    };
                } else {
                    audioElement.pause();
                    audioElement.currentTime = 0;
                    icon.removeClass('dashicons-controls-pause').addClass('dashicons-controls-play');
                    btn.removeClass('playing');
                }
            }
        });
        
        // ============ Toggle Translation ============
        $('.toggle-translation-btn').on('click', function() {
            $('.dialogue-line').toggleClass('hide-translation');
            
            var btn = $(this);
            if ($('.dialogue-line').hasClass('hide-translation')) {
                btn.text('Hiện dịch');
            } else {
                btn.text('Ẩn dịch');
            }
        });
        
        // ============ Stop All Audio on Section Change ============
        $('.nav-tab').on('click', function() {
            if (currentAudio) {
                currentAudio.pause();
                currentAudio = null;
            }
            
            // Stop all audio elements
            $('audio').each(function() {
                this.pause();
                this.currentTime = 0;
            });
            
            // Reset all play buttons
            $('.audio-play-btn, .audio-play-btn-small, .line-audio-btn, .question-audio-btn, .dialogue-play-all')
                .removeClass('playing');
            $('.dashicons-controls-pause')
                .removeClass('dashicons-controls-pause')
                .addClass('dashicons-controls-play');
            $('.dialogue-line').removeClass('playing');
        });
        
        // ============ Grammar Toggle ============
        $('.grammar-toggle-btn').on('click', function() {
            var grammarItem = $(this).closest('.grammar-item');
            grammarItem.toggleClass('collapsed');
        });
        
        // Initialize - collapse all grammar items except first
        $('.grammar-item').not(':first').addClass('collapsed');
        
    });
    
})(jQuery);
