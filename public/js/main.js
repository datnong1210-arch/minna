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
        
        // ============ Quiz Functionality ============
        var quizData = null;
        var userAnswers = {};
        
        // Load quiz data
        if ($('#quiz-questions-data').length) {
            try {
                quizData = JSON.parse($('#quiz-questions-data').text());
            } catch(e) {
                console.error('Failed to parse quiz data:', e);
            }
        }
        
        // Start quiz
        $('.quiz-start-btn').on('click', function() {
            $('.quiz-start-screen').hide();
            $('.quiz-questions').show();
            $('.quiz-progress').show();
            updateQuizProgress();
        });
        
        // Quiz navigation
        $('.prev-question-btn').on('click', function() {
            var currentQuestion = $(this).closest('.quiz-question');
            var prevQuestion = currentQuestion.prev('.quiz-question');
            
            if (prevQuestion.length) {
                currentQuestion.hide();
                prevQuestion.show();
            }
        });
        
        $('.next-question-btn').on('click', function() {
            var currentQuestion = $(this).closest('.quiz-question');
            var nextQuestion = currentQuestion.next('.quiz-question');
            
            if (nextQuestion.length) {
                currentQuestion.hide();
                nextQuestion.show();
            }
        });
        
        // Track answers
        $('.question-options input[type="radio"]').on('change', function() {
            var questionIndex = $(this).data('question');
            var answer = parseInt($(this).val());
            userAnswers[questionIndex] = answer;
            updateQuizProgress();
        });
        
        // Update quiz progress
        function updateQuizProgress() {
            var totalQuestions = $('.quiz-question').length;
            var answeredCount = Object.keys(userAnswers).length;
            var percentage = (answeredCount / totalQuestions) * 100;
            
            $('.answered-count').text(answeredCount);
            $('.quiz-progress .progress-fill').css('width', percentage + '%');
        }
        
        // Submit quiz
        $('.quiz-submit-btn').on('click', function() {
            var totalQuestions = $('.quiz-question').length;
            var answeredCount = Object.keys(userAnswers).length;
            
            if (answeredCount < totalQuestions) {
                var confirmMessage = ahominna.i18n ? ahominna.i18n.confirm_submit : 'Bạn chưa trả lời hết các câu hỏi. Bạn có muốn nộp bài không?';
                if (!confirm(confirmMessage)) {
                    return;
                }
            }
            
            submitQuiz();
        });
        
        // Submit quiz to server
        function submitQuiz() {
            var quizContainer = $('.quiz-container');
            var quizId = quizContainer.data('quiz-id');
            var lessonId = quizContainer.data('lesson-id');
            
            $.ajax({
                url: ahominna.ajax_url,
                type: 'POST',
                data: {
                    action: 'ahominna_submit_quiz',
                    nonce: ahominna.nonce,
                    quiz_id: quizId,
                    lesson_id: lessonId,
                    answers: JSON.stringify(userAnswers)
                },
                success: function(response) {
                    if (response.success) {
                        showQuizResults(response.data);
                    } else {
                        alert('Có lỗi xảy ra: ' + response.data.message);
                    }
                },
                error: function() {
                    alert('Không thể nộp bài. Vui lòng thử lại.');
                }
            });
        }
        
        // Show quiz results
        function showQuizResults(data) {
            $('.quiz-questions').hide();
            $('.quiz-progress').hide();
            $('.quiz-results').show();
            
            // Update score
            $('.score-value').text(data.percentage + '%');
            $('.correct-count').text(data.score);
            $('.wrong-count').text(data.total - data.score);
            $('.total-count').text(data.total);
            
            // Score message
            var message = '';
            if (data.percentage >= 90) {
                message = 'Xuất sắc! 🎉';
            } else if (data.percentage >= 70) {
                message = 'Tốt lắm! 👍';
            } else if (data.percentage >= 50) {
                message = 'Khá tốt! 😊';
            } else {
                message = 'Cần cố gắng thêm! 💪';
            }
            $('.score-message').text(message);
            
            // Show detailed results
            if (data.results && data.results.length) {
                displayDetailedResults(data.results);
            }
        }
        
        // Display detailed results
        function displayDetailedResults(results) {
            var html = '<div class="results-list">';
            
            results.forEach(function(result, index) {
                var question = quizData[result.question_index];
                var isCorrect = result.is_correct;
                var icon = isCorrect ? '✓' : '✗';
                var className = isCorrect ? 'correct' : 'incorrect';
                
                html += '<div class="result-item ' + className + '">';
                html += '<div class="result-header">';
                html += '<span class="result-icon">' + icon + '</span>';
                html += '<span class="result-question-num">Câu ' + (index + 1) + '</span>';
                html += '</div>';
                html += '<div class="result-question">' + question.question + '</div>';
                html += '<div class="result-answer">';
                html += '<div class="user-answer">Bạn chọn: ' + (result.user_answer !== -1 ? String.fromCharCode(65 + result.user_answer) : 'Không trả lời') + '</div>';
                html += '<div class="correct-answer">Đáp án đúng: ' + String.fromCharCode(65 + result.correct_answer) + '</div>';
                html += '</div>';
                if (result.explanation) {
                    html += '<div class="result-explanation">' + result.explanation + '</div>';
                }
                html += '</div>';
            });
            
            html += '</div>';
            $('.results-answers').html(html);
        }
        
        // Retry quiz
        $('.quiz-retry-btn').on('click', function() {
            userAnswers = {};
            $('.question-options input[type="radio"]').prop('checked', false);
            $('.quiz-results').hide();
            $('.quiz-start-screen').show();
        });
        
        // Review quiz
        $('.quiz-review-btn').on('click', function() {
            $('.results-answers').slideToggle();
            var btn = $(this);
            if ($('.results-answers').is(':visible')) {
                btn.html('<span class="dashicons dashicons-hidden"></span> Ẩn đáp án');
            } else {
                btn.html('<span class="dashicons dashicons-visibility"></span> Xem đáp án');
            }
        });
        
        // ============ Initialize ============
        updateProgress();
        
    });
    
})(jQuery);
