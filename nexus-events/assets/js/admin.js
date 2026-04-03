/**
 * Nexus Events Admin JavaScript
 */

(function($) {
    'use strict';
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        initDatePicker();
        initTimePicker();
        initEventValidation();
        initQuickEdit();
        initMediaUpload();
    });
    
    /**
     * Initialize date picker for event date field
     */
    function initDatePicker() {
        if ($.datepicker) {
            $('#nexus_event_date').datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: 0,
                showButtonPanel: true,
                changeMonth: true,
                changeYear: true,
                beforeShow: function(input, inst) {
                    $('#ui-datepicker-div').addClass('nexus-datepicker');
                }
            });
        }
    }
    
    /**
     * Initialize time picker for event time field
     */
    function initTimePicker() {
        $('#nexus_event_time').on('focus', function() {
            $(this).attr('type', 'time');
        });
        
        $('#nexus_event_time').on('blur', function() {
            if (!$(this).val()) {
                $(this).attr('type', 'text');
            }
        });
    }
    
    /**
     * Initialize form validation
     */
    function initEventValidation() {
        $('#post').on('submit', function(e) {
            if ($('#post_type').val() === 'nexus_event') {
                const eventDate = $('#nexus_event_date').val();
                const eventTime = $('#nexus_event_time').val();
                const eventHost = $('#nexus_event_host').val();
                
                // Clear previous errors
                $('.nexus-event-meta input').removeClass('error');
                $('.error-message').remove();
                
                let hasError = false;
                
                // Validate required fields
                if (!eventDate) {
                    showFieldError('#nexus_event_date', 'Event datum is verplicht');
                    hasError = true;
                }
                
                if (!eventTime) {
                    showFieldError('#nexus_event_time', 'Event tijd is verplicht');
                    hasError = true;
                }
                
                if (!eventHost) {
                    showFieldError('#nexus_event_host', 'Host naam is verplicht');
                    hasError = true;
                }
                
                // Validate date is not in the past
                if (eventDate) {
                    const selectedDate = new Date(eventDate);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (selectedDate < today) {
                        showFieldError('#nexus_event_date', 'Event datum kan niet in het verleden liggen');
                        hasError = true;
                    }
                }
                
                if (hasError) {
                    e.preventDefault();
                    scrollToFirstError();
                }
            }
        });
    }
    
    /**
     * Initialize quick edit functionality
     */
    function initQuickEdit() {
        const $wpInlineEdit = inlineEditPost.edit;
        
        inlineEditPost.edit = function(id) {
            $wpInlineEdit.apply(this, arguments);
            
            const postId = typeof(id) === 'object' ? parseInt(this.getId(id)) : id;
            
            if (this.type === 'nexus_event') {
                const $editRow = $('#edit-' + postId);
                
                // Add custom fields to quick edit
                const rowData = $('#inline_' + postId);
                
                // Get current values
                const eventDate = rowData.find('.event_date').text();
                const eventTime = rowData.find('.event_time').text();
                const eventHost = rowData.find('.event_host').text();
                
                // Create custom fields
                const customFields = `
                    <div class="nexus-event-quick-edit">
                        <label>
                            <span class="title">Event Datum</span>
                            <span class="input-text-wrap">
                                <input type="date" name="nexus_event_date" class="ptitle" value="${eventDate}">
                            </span>
                        </label>
                        <label>
                            <span class="title">Event Tijd</span>
                            <span class="input-text-wrap">
                                <input type="time" name="nexus_event_time" class="ptitle" value="${eventTime}">
                            </span>
                        </label>
                        <label>
                            <span class="title">Host</span>
                            <span class="input-text-wrap">
                                <input type="text" name="nexus_event_host" class="ptitle" value="${eventHost}">
                            </span>
                        </label>
                    </div>
                `;
                
                // Insert custom fields
                $editRow.find('.inline-edit-col-right').append(customFields);
            }
        };
    }
    
    /**
     * Initialize media upload for featured image
     */
    function initMediaUpload() {
        // Custom media upload button if needed
        $('.nexus-upload-image').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const $input = $('#' + $button.data('input'));
            
            let frame;
            
            if (frame) {
                frame.open();
                return;
            }
            
            frame = wp.media({
                title: 'Selecteer Event Afbeelding',
                button: {
                    text: 'Gebruik deze afbeelding'
                },
                multiple: false
            });
            
            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                $input.val(attachment.url);
                
                // Update preview if exists
                const $preview = $('#' + $button.data('preview'));
                if ($preview.length) {
                    $preview.attr('src', attachment.url);
                }
            });
            
            frame.open();
        });
    }
    
    /**
     * Show field error
     */
    function showFieldError(selector, message) {
        const $field = $(selector);
        $field.addClass('error');
        
        const $error = $('<span class="error-message">' + message + '</span>');
        $field.after($error);
    }
    
    /**
     * Scroll to first error field
     */
    function scrollToFirstError() {
        const $firstError = $('.nexus-event-meta input.error').first();
        if ($firstError.length) {
            $('html, body').animate({
                scrollTop: $firstError.offset().top - 100
            }, 500);
            $firstError.focus();
        }
    }
    
    /**
     * Auto-save draft functionality
     */
    function initAutoSave() {
        let autoSaveTimer;
        
        $('.nexus-event-meta input').on('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                if ($('#post_ID').val() && $('#post_status').val() === 'draft') {
                    // Trigger WordPress auto-save
                    $('#post').submit();
                }
            }, 30000); // Auto-save after 30 seconds of inactivity
        });
    }
    
    /**
     * Initialize character counter for description
     */
    function initCharacterCounter() {
        const $description = $('#content');
        const $counter = $('<div class="character-counter">0 tekens</div>');
        
        if ($description.length) {
            $description.after($counter);
            
            function updateCounter() {
                const length = $description.val().length;
                $counter.text(length + ' tekens');
                
                if (length > 500) {
                    $counter.addClass('warning');
                } else {
                    $counter.removeClass('warning');
                }
            }
            
            $description.on('input', updateCounter);
            updateCounter();
        }
    }
    
    // Initialize additional features
    initAutoSave();
    initCharacterCounter();
    
})(jQuery);
