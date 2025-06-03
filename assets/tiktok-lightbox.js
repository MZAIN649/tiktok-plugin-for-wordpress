/**
 * TikTok Lightbox Plugin JavaScript
 */

(function($) {
    'use strict';

    class TikTokLightbox {
        constructor() {
            this.lightbox = null;
            this.currentVideoId = null;
            this.isOpen = false;
            this.init();
        }

        init() {
            this.bindEvents();
            this.createLightboxIfNotExists();
        }

        bindEvents() {
            // Handle trigger clicks
            $(document).on('click', '.tiktok-lightbox-trigger', (e) => {
                e.preventDefault();
                const videoId = $(e.currentTarget).data('video-id');
                if (videoId) {
                    this.openLightbox(videoId);
                }
            });

            // Handle close button
            $(document).on('click', '.tiktok-lightbox-close', (e) => {
                e.preventDefault();
                this.closeLightbox();
            });

            // Handle overlay click
            $(document).on('click', '.tiktok-lightbox-overlay', (e) => {
                e.preventDefault();
                this.closeLightbox();
            });

            // Handle escape key
            $(document).on('keydown', (e) => {
                if (e.keyCode === 27 && this.isOpen) { // ESC key
                    this.closeLightbox();
                }
            });

            // Prevent body scroll when lightbox is open
            $(document).on('wheel touchmove', (e) => {
                if (this.isOpen) {
                    e.preventDefault();
                }
            });
        }

        createLightboxIfNotExists() {
            if ($('#tiktok-lightbox').length === 0) {
                const lightboxHTML = `
                    <div id="tiktok-lightbox" class="tiktok-lightbox">
                        <div class="tiktok-lightbox-overlay"></div>
                        <div class="tiktok-lightbox-content">
                            <button class="tiktok-lightbox-close">&times;</button>
                            <div class="tiktok-lightbox-video">
                                <div id="tiktok-embed-container"></div>
                            </div>
                        </div>
                    </div>
                `;
                $('body').append(lightboxHTML);
            }
            this.lightbox = $('#tiktok-lightbox');
        }

        openLightbox(videoId) {
            if (this.isOpen) {
                this.closeLightbox();
                setTimeout(() => this.openLightbox(videoId), 300);
                return;
            }

            this.currentVideoId = videoId;
            this.isOpen = true;

            // Prevent body scroll
            $('body').addClass('tiktok-lightbox-open');
            $('body').css('overflow', 'hidden');

            // Load TikTok embed
            this.loadTikTokEmbed(videoId);

            // Show lightbox with animation
            this.lightbox.addClass('active');

            // Focus on close button for accessibility
            setTimeout(() => {
                this.lightbox.find('.tiktok-lightbox-close').focus();
            }, 100);
        }

        closeLightbox() {
            if (!this.isOpen) return;

            this.isOpen = false;

            // Add closing animation class
            this.lightbox.addClass('closing');

            // Remove classes after animation
            setTimeout(() => {
                this.lightbox.removeClass('active closing');
                $('body').removeClass('tiktok-lightbox-open');
                $('body').css('overflow', '');
                
                // Clear embed container
                $('#tiktok-embed-container').empty();
                this.currentVideoId = null;
            }, 300);
        }

        loadTikTokEmbed(videoId) {
            const container = $('#tiktok-embed-container');
            container.empty();

            // Create TikTok embed HTML
            const embedHTML = `
                <blockquote class="tiktok-embed" 
                    cite="https://www.tiktok.com/@user/video/${videoId}" 
                    data-video-id="${videoId}" 
                    style="max-width: 100%; min-width: 100%; height: 100%;">
                    <section style="height: 100%; display: flex; align-items: center; justify-content: center;">
                        <div style="color: white; font-size: 18px;">Loading TikTok video...</div>
                    </section>
                </blockquote>
            `;

            container.html(embedHTML);

            // Load TikTok embed script if not already loaded
            this.loadTikTokScript(() => {
                // Reinitialize TikTok embeds
                if (window.tiktokEmbed && typeof window.tiktokEmbed.lib !== 'undefined') {
                    window.tiktokEmbed.lib.render();
                } else if (window.instgrm) {
                    window.instgrm.Embeds.process();
                }
            });
        }

        loadTikTokScript(callback) {
            if (window.tiktokEmbedLoaded) {
                callback();
                return;
            }

            // Check if script is already loaded
            if ($('script[src*="tiktok.com/embed.js"]').length > 0) {
                window.tiktokEmbedLoaded = true;
                callback();
                return;
            }

            // Load TikTok embed script
            const script = document.createElement('script');
            script.src = 'https://www.tiktok.com/embed.js';
            script.async = true;
            script.onload = () => {
                window.tiktokEmbedLoaded = true;
                callback();
            };
            document.head.appendChild(script);
        }

        // Public method to manually open lightbox
        static open(videoId) {
            if (window.tikTokLightboxInstance) {
                window.tikTokLightboxInstance.openLightbox(videoId);
            }
        }

        // Public method to manually close lightbox
        static close() {
            if (window.tikTokLightboxInstance) {
                window.tikTokLightboxInstance.closeLightbox();
            }
        }
    }

    // Initialize when DOM is ready
    $(document).ready(function() {
        window.tikTokLightboxInstance = new TikTokLightbox();
    });

    // Make it available globally
    window.TikTokLightbox = TikTokLightbox;

    // jQuery plugin wrapper
    $.fn.tikTokLightbox = function(options) {
        return this.each(function() {
            const $this = $(this);
            const videoId = $this.data('video-id') || options.videoId;
            
            if (videoId) {
                $this.addClass('tiktok-lightbox-trigger');
                $this.data('video-id', videoId);
            }
        });
    };

})(jQuery);

// CSS for body when lightbox is open
const lightboxStyles = `
    <style>
        body.tiktok-lightbox-open {
            overflow: hidden !important;
            padding-right: 17px; /* Prevent layout shift from scrollbar */
        }
        
        @media (max-width: 768px) {
            body.tiktok-lightbox-open {
                padding-right: 0;
            }
        }
    </style>
`;

// Add styles to head
if (document.head && !document.querySelector('#tiktok-lightbox-body-styles')) {
    const styleElement = document.createElement('div');
    styleElement.id = 'tiktok-lightbox-body-styles';
    styleElement.innerHTML = lightboxStyles;
    document.head.appendChild(styleElement);
} 