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

            // Show loading message
            container.html(`
                <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: white; font-size: 18px; flex-direction: column;">
                    <div style="margin-bottom: 20px;">Loading TikTok video...</div>
                    <div style="width: 40px; height: 40px; border: 3px solid rgba(255,255,255,0.3); border-top: 3px solid white; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                </div>
                <style>
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                </style>
            `);

            // Load TikTok embed script first, then create embed
            this.loadTikTokScript(() => {
                this.createTikTokEmbed(videoId, container);
            });
        }

        createTikTokEmbed(videoId, container) {
            // Clear container
            container.empty();

            // Try multiple embed methods for better compatibility
            this.tryEmbedMethods(videoId, container);
        }

        tryEmbedMethods(videoId, container) {
            // Method 1: Standard TikTok embed
            const embedHTML1 = `
                <blockquote class="tiktok-embed" 
                    cite="https://www.tiktok.com/@user/video/${videoId}" 
                    data-video-id="${videoId}"
                    data-embed-from="embed_page"
                    style="max-width: 100%; min-width: 100%; height: 100%; margin: 0;">
                    <section style="height: 100%; display: flex; align-items: center; justify-content: center;">
                        <a target="_blank" 
                           title="TikTok Video" 
                           href="https://www.tiktok.com/@user/video/${videoId}"
                           style="color: white; text-decoration: none;">
                            <div style="color: white; font-size: 18px;">Loading TikTok video...</div>
                        </a>
                    </section>
                </blockquote>
            `;

            container.html(embedHTML1);

            // Initialize TikTok embeds
            setTimeout(() => {
                if (window.tiktokEmbed) {
                    // TikTok's official embed script
                    if (window.tiktokEmbed.lib && typeof window.tiktokEmbed.lib.render === 'function') {
                        window.tiktokEmbed.lib.render();
                    }
                } else {
                    // Fallback: Try to reinitialize script
                    this.reinitializeTikTokScript(videoId, container);
                }
            }, 500);

            // Fallback after 3 seconds if embed doesn't load
            setTimeout(() => {
                if (container.find('iframe').length === 0) {
                    console.log('TikTok embed failed, trying alternative method...');
                    this.tryAlternativeEmbed(videoId, container);
                }
            }, 3000);
        }

        tryAlternativeEmbed(videoId, container) {
            // Alternative method: Direct iframe embed
            const iframeHTML = `
                <iframe 
                    src="https://www.tiktok.com/embed/v2/${videoId}?lang=en-US"
                    width="100%" 
                    height="100%" 
                    frameborder="0" 
                    scrolling="no" 
                    allowfullscreen
                    style="border: none; border-radius: 12px;">
                </iframe>
            `;
            
            container.html(iframeHTML);

            // If iframe also fails, show error message
            setTimeout(() => {
                const iframe = container.find('iframe')[0];
                if (iframe) {
                    iframe.onerror = () => {
                        this.showEmbedError(videoId, container);
                    };
                } else {
                    this.showEmbedError(videoId, container);
                }
            }, 2000);
        }

        showEmbedError(videoId, container) {
            const errorHTML = `
                <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: white; text-align: center; flex-direction: column; padding: 20px;">
                    <div style="font-size: 18px; margin-bottom: 20px;">Unable to load TikTok video</div>
                    <div style="font-size: 14px; margin-bottom: 20px; opacity: 0.8;">The video might be private or restricted</div>
                    <a href="https://www.tiktok.com/@user/video/${videoId}" 
                       target="_blank" 
                       style="background: #ff0050; color: white; padding: 12px 24px; border-radius: 25px; text-decoration: none; font-weight: bold;">
                        View on TikTok
                    </a>
                </div>
            `;
            container.html(errorHTML);
        }

        reinitializeTikTokScript(videoId, container) {
            // Remove existing script
            $('script[src*="tiktok.com/embed.js"]').remove();
            window.tiktokEmbed = undefined;
            window.tiktokEmbedLoaded = false;

            // Reload script
            this.loadTikTokScript(() => {
                setTimeout(() => {
                    if (window.tiktokEmbed && window.tiktokEmbed.lib) {
                        window.tiktokEmbed.lib.render();
                    }
                }, 1000);
            });
        }

        loadTikTokScript(callback) {
            if (window.tiktokEmbedLoaded && window.tiktokEmbed) {
                callback();
                return;
            }

            // Check if script is already loaded
            if ($('script[src*="tiktok.com/embed.js"]').length > 0 && window.tiktokEmbed) {
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
                setTimeout(callback, 500); // Give script time to initialize
            };
            script.onerror = () => {
                console.error('Failed to load TikTok embed script');
                callback(); // Continue with fallback methods
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