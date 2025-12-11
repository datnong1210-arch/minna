/**
 * Flashcard Functionality
 * @package AhoMinna
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        var currentCard = 0;
        var totalCards = $('.flashcard').length;
        var shuffled = false;
        var cardOrder = [];
        
        // Initialize card order
        for (var i = 0; i < totalCards; i++) {
            cardOrder.push(i);
        }
        
        // ============ Card Navigation ============
        function showCard(index) {
            if (index < 0 || index >= totalCards) return;
            
            currentCard = index;
            
            // Hide all cards
            $('.flashcard').hide().removeClass('active');
            
            // Show current card
            var actualIndex = shuffled ? cardOrder[index] : index;
            $('.flashcard[data-index="' + actualIndex + '"]').show().addClass('active');
            
            // Update counter
            $('.current-card').text(index + 1);
            $('.total-cards').text(totalCards);
            
            // Update button states
            $('.prev-btn').prop('disabled', index === 0);
            $('.next-btn').prop('disabled', index === totalCards - 1);
            
            // Reset flip state
            $('.flashcard.active').removeClass('flipped');
        }
        
        $('.prev-btn').on('click', function() {
            if (currentCard > 0) {
                showCard(currentCard - 1);
            }
        });
        
        $('.next-btn').on('click', function() {
            if (currentCard < totalCards - 1) {
                showCard(currentCard + 1);
            }
        });
        
        // ============ Card Flip ============
        $('.flashcard-flip-btn').on('click', function() {
            $(this).closest('.flashcard').toggleClass('flipped');
        });
        
        // ============ Shuffle ============
        $('.vocab-shuffle-btn').on('click', function() {
            shuffled = true;
            
            // Fisher-Yates shuffle
            for (var i = cardOrder.length - 1; i > 0; i--) {
                var j = Math.floor(Math.random() * (i + 1));
                var temp = cardOrder[i];
                cardOrder[i] = cardOrder[j];
                cardOrder[j] = temp;
            }
            
            // Reset to first card
            showCard(0);
            
            // Visual feedback
            $(this).addClass('shuffling');
            setTimeout(function() {
                $('.vocab-shuffle-btn').removeClass('shuffling');
            }, 300);
        });
        
        // ============ Mode Switch ============
        $('.vocab-mode-btn').on('click', function() {
            $('.vocab-mode-btn').removeClass('active');
            $(this).addClass('active');
            
            var mode = $(this).data('mode');
            
            if (mode === 'learn') {
                // Show Japanese first
                $('.flashcard').removeClass('flipped');
            } else if (mode === 'review') {
                // Start with Vietnamese (flipped)
                $('.flashcard').addClass('flipped');
            }
        });
        
        // ============ Swipe Support (Touch) ============
        var touchStartX = 0;
        var touchEndX = 0;
        
        $('.flashcard-wrapper').on('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        $('.flashcard-wrapper').on('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            var swipeThreshold = 50;
            
            if (touchEndX < touchStartX - swipeThreshold) {
                // Swipe left - next card
                $('.next-btn').click();
            }
            
            if (touchEndX > touchStartX + swipeThreshold) {
                // Swipe right - previous card
                $('.prev-btn').click();
            }
        }
        
        // ============ Toggle View ============
        $('.toggle-view-btn').on('click', function() {
            var $flashcardView = $('.flashcard-container');
            var $listView = $('.vocabulary-list-view');
            
            if ($listView.is(':visible')) {
                $listView.hide();
                $flashcardView.show();
                $(this).html('<span class="dashicons dashicons-list-view"></span> Xem dạng danh sách');
            } else {
                $flashcardView.hide();
                $listView.show();
                $(this).html('<span class="dashicons dashicons-format-gallery"></span> Xem dạng thẻ');
            }
        });
        
        // ============ Keyboard Shortcuts ============
        $(document).on('keydown', function(e) {
            if (!$('.flashcard-container:visible').length) return;
            
            // Space or Enter to flip
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                $('.flashcard.active .flashcard-flip-btn').first().click();
            }
            
            // Arrow keys for navigation (handled in main.js but reinforced here)
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                $('.prev-btn').click();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                $('.next-btn').click();
            }
        });
        
        // ============ Initialize ============
        if (totalCards > 0) {
            showCard(0);
        }
        
    });
    
})(jQuery);
