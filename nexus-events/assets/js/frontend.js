/**
 * Nexus Events Frontend JavaScript
 */

(function($) {
    'use strict';
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        initEventFilters();
        initEventSharing();
        initEventCountdown();
        initEventRegistration();
    });
    
    /**
     * Initialize event filtering functionality
     */
    function initEventFilters() {
        $('.nexus-event-filter').on('change', function() {
            const filterType = $(this).data('filter');
            const filterValue = $(this).val();
            
            // Show loading state
            $('.nexus-events-container').addClass('loading');
            
            // Make AJAX request to filter events
            $.ajax({
                url: nexusEvents.ajaxurl,
                type: 'POST',
                data: {
                    action: 'nexus_filter_events',
                    nonce: nexusEvents.nonce,
                    filter_type: filterType,
                    filter_value: filterValue
                },
                success: function(response) {
                    if (response.success) {
                        $('.nexus-events-container').html(response.data.html);
                    } else {
                        console.error('Filter error:', response.data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                },
                complete: function() {
                    $('.nexus-events-container').removeClass('loading');
                }
            });
        });
    }
    
    /**
     * Initialize event sharing functionality
     */
    function initEventSharing() {
        $('.nexus-share-event').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const eventUrl = $button.data('url');
            const eventTitle = $button.data('title');
            
            if (navigator.share) {
                // Use native share API if available
                navigator.share({
                    title: eventTitle,
                    url: eventUrl
                }).catch(function(error) {
                    console.log('Share cancelled:', error);
                });
            } else {
                // Fallback: copy to clipboard
                copyToClipboard(eventUrl, function() {
                    showNotification($button, 'Link gekopieerd!');
                });
            }
        });
    }
    
    /**
     * Initialize countdown timers for events
     */
    function initEventCountdown() {
        $('.nexus-event-countdown').each(function() {
            const $countdown = $(this);
            const eventDate = new Date($countdown.data('date'));
            
            function updateCountdown() {
                const now = new Date();
                const timeUntil = eventDate - now;
                
                if (timeUntil <= 0) {
                    $countdown.text('Event is begonnen');
                    return;
                }
                
                const days = Math.floor(timeUntil / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeUntil % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeUntil % (1000 * 60 * 60)) / (1000 * 60));
                
                let countdownText = '';
                if (days > 0) countdownText += days + 'd ';
                if (hours > 0) countdownText += hours + 'u ';
                countdownText += minutes + 'm';
                
                $countdown.text(countdownText);
            }
            
            updateCountdown();
            setInterval(updateCountdown, 60000); // Update every minute
        });
    }
    
    /**
     * Initialize event registration functionality
     */
    function initEventRegistration() {
        $('.nexus-register-event').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const eventId = $button.data('event-id');
            const isRegistering = !$button.hasClass('registered');
            
            $button.prop('disabled', true);
            
            $.ajax({
                url: nexusEvents.ajaxurl,
                type: 'POST',
                data: {
                    action: 'nexus_event_registration',
                    nonce: nexusEvents.nonce,
                    event_id: eventId,
                    register: isRegistering
                },
                success: function(response) {
                    if (response.success) {
                        if (isRegistering) {
                            $button.addClass('registered').text('Uitschrijven');
                            showNotification($button, 'Succesvol ingeschreven!');
                        } else {
                            $button.removeClass('registered').text('Inschrijven');
                            showNotification($button, 'Uitgeschreven');
                        }
                        
                        // Update participant count if available
                        const $count = $('.nexus-participant-count');
                        if ($count.length) {
                            $count.text(response.data.participant_count);
                        }
                    } else {
                        showNotification($button, response.data.message || 'Er is een fout opgetreden', 'error');
                    }
                },
                error: function() {
                    showNotification($button, 'Er is een fout opgetreden', 'error');
                },
                complete: function() {
                    $button.prop('disabled', false);
                }
            });
        });
    }
    
    /**
     * Copy text to clipboard
     */
    function copyToClipboard(text, callback) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(callback).catch(function() {
                fallbackCopyTextToClipboard(text, callback);
            });
        } else {
            fallbackCopyTextToClipboard(text, callback);
        }
    }
    
    /**
     * Fallback method for copying text
     */
    function fallbackCopyTextToClipboard(text, callback) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            callback();
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
        }
        
        document.body.removeChild(textArea);
    }
    
    /**
     * Show notification message
     */
    function showNotification($element, message, type = 'success') {
        const notification = $('<div>')
            .addClass('nexus-notification nexus-notification-' + type)
            .text(message)
            .css({
                position: 'absolute',
                top: $element.offset().top - 40,
                left: $element.offset().left,
                zIndex: 1000,
                padding: '8px 12px',
                borderRadius: '4px',
                fontSize: '14px',
                fontWeight: '600',
                backgroundColor: type === 'success' ? '#10b981' : '#ef4444',
                color: 'white',
                boxShadow: '0 2px 4px rgba(0,0,0,0.1)'
            });
        
        $('body').append(notification);
        
        // Animate in
        notification.css('opacity', 0).animate({ opacity: 1 }, 200);
        
        // Remove after 3 seconds
        setTimeout(function() {
            notification.animate({ opacity: 0 }, 200, function() {
                notification.remove();
            });
        }, 3000);
    }
    
    /**
     * Lazy load event images
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img.lazy').forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }
    
    // Initialize lazy loading
    initLazyLoading();
    
})(jQuery);
