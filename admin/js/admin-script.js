/**
 * Admin Scripts
 * @package AhoMinna
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Media uploader for audio
        $('.ahominna-upload-audio').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var inputField = button.prev('input');
            
            var mediaUploader = wp.media({
                title: 'Select Audio File',
                button: {
                    text: 'Use this audio'
                },
                library: {
                    type: 'audio'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                inputField.val(attachment.url);
            });
            
            mediaUploader.open();
        });
        
        // Media uploader for images
        $('.ahominna-upload-image').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var inputField = button.prev('input');
            
            var mediaUploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use this image'
                },
                library: {
                    type: 'image'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                inputField.val(attachment.url);
            });
            
            mediaUploader.open();
        });
        
        // Import type change - show relevant instructions
        $('#import_type').on('change', function() {
            var type = $(this).val();
            $('.instructions-box').hide();
            
            if (type === 'vocabulary') {
                $('.instructions-box:first').show();
            } else if (type === 'quiz') {
                $('.instructions-box:last').show();
            }
        });
        
        // Show first instruction by default
        if ($('#import_type').length) {
            $('.instructions-box:first').show();
            $('.instructions-box:not(:first)').hide();
        }
        
    });
    
})(jQuery);
