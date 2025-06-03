<?php
/**
 * Plugin Name: TikTok Lightbox Plugin
 * Description: A lightbox plugin for TikTok videos with Elementor integration
 * Version: 1.0.0
 * Author: Your Name
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('TIKTOK_LIGHTBOX_VERSION', '1.0.0');
define('TIKTOK_LIGHTBOX_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TIKTOK_LIGHTBOX_PLUGIN_PATH', plugin_dir_path(__FILE__));

class TikTokLightboxPlugin {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('elementor/widgets/widgets_registered', array($this, 'register_elementor_widgets'));
        add_action('elementor/frontend/after_enqueue_styles', array($this, 'enqueue_elementor_styles'));
        add_shortcode('tiktok_lightbox', array($this, 'tiktok_lightbox_shortcode'));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('tiktok-lightbox-css', TIKTOK_LIGHTBOX_PLUGIN_URL . 'assets/tiktok-lightbox.css', array(), TIKTOK_LIGHTBOX_VERSION);
        wp_enqueue_script('tiktok-lightbox-js', TIKTOK_LIGHTBOX_PLUGIN_URL . 'assets/tiktok-lightbox.js', array('jquery'), TIKTOK_LIGHTBOX_VERSION, true);
        wp_enqueue_script('tiktok-embed', 'https://www.tiktok.com/embed.js', array(), null, true);
    }
    
    public function enqueue_elementor_styles() {
        wp_enqueue_style('tiktok-lightbox-css');
        wp_enqueue_script('tiktok-lightbox-js');
    }
    
    public function register_elementor_widgets() {
        if (class_exists('\Elementor\Plugin')) {
            require_once TIKTOK_LIGHTBOX_PLUGIN_PATH . 'elementor/tiktok-lightbox-widget.php';
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \TikTok_Lightbox_Widget());
        }
    }
    
    public function tiktok_lightbox_shortcode($atts) {
        $atts = shortcode_atts(array(
            'url' => '',
            'thumbnail' => '',
            'width' => '300',
            'height' => '533',
            'button_text' => 'Watch on TikTok'
        ), $atts);
        
        if (empty($atts['url'])) {
            return '<p>Please provide a TikTok URL</p>';
        }
        
        $video_id = $this->extract_video_id($atts['url']);
        if (!$video_id) {
            return '<p>Invalid TikTok URL. Please use a valid TikTok video URL.</p>';
        }
        
        return $this->render_tiktok_lightbox($video_id, $atts);
    }
    
    public function extract_video_id($url) {
        // Handle different TikTok URL formats
        $patterns = array(
            '/\/video\/(\d+)/',           // Standard: https://www.tiktok.com/@user/video/1234567890
            '/\/v\/(\d+)/',               // Short: https://vm.tiktok.com/v/1234567890
            '/tiktok\.com\/.*\/(\d+)/',   // Various formats with numbers
            '/(\d{19})/',                 // Direct video ID (19 digits)
        );
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                if (isset($matches[1]) && strlen($matches[1]) >= 15) {
                    return $matches[1];
                }
            }
        }
        
        // Try to extract from share URLs like vm.tiktok.com
        if (strpos($url, 'vm.tiktok.com') !== false || strpos($url, 'vt.tiktok.com') !== false) {
            // For short URLs, we might need to follow redirects
            $video_id = $this->resolve_short_url($url);
            if ($video_id) {
                return $video_id;
            }
        }
        
        return false;
    }
    
    private function resolve_short_url($url) {
        // Simple method to try to extract video ID from short URLs
        // This is a basic implementation - in production you might want to follow redirects
        $parsed = parse_url($url);
        if (isset($parsed['path'])) {
            $path_parts = explode('/', trim($parsed['path'], '/'));
            foreach ($path_parts as $part) {
                if (is_numeric($part) && strlen($part) >= 15) {
                    return $part;
                }
            }
        }
        return false;
    }
    
    public function render_tiktok_lightbox($video_id, $atts = array()) {
        $thumbnail = !empty($atts['thumbnail']) ? $atts['thumbnail'] : TIKTOK_LIGHTBOX_PLUGIN_URL . 'assets/tiktok-placeholder.jpg';
        $button_text = !empty($atts['button_text']) ? $atts['button_text'] : 'Watch on TikTok';
        $width = !empty($atts['width']) ? intval($atts['width']) : 300;
        $height = !empty($atts['height']) ? intval($atts['height']) : 533;
        
        ob_start();
        ?>
        <div class="tiktok-lightbox-trigger" data-video-id="<?php echo esc_attr($video_id); ?>">
            <div class="tiktok-thumbnail" style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;">
                <img src="<?php echo esc_url($thumbnail); ?>" alt="TikTok Video" />
                <div class="play-button">
                    <svg width="68" height="48" viewBox="0 0 68 48">
                        <path d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#ff0050"></path>
                        <path d="M45,24L27,14v20" fill="white"></path>
                    </svg>
                </div>
                <div class="tiktok-button"><?php echo esc_html($button_text); ?></div>
            </div>
        </div>
        <!-- Debug info (remove in production) -->
        <?php if (defined('WP_DEBUG') && WP_DEBUG): ?>
        <div style="font-size: 12px; color: #666; margin-top: 5px;">
            Video ID: <?php echo esc_html($video_id); ?>
        </div>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }
}

// Initialize the plugin
new TikTokLightboxPlugin();

// Add lightbox HTML to footer
add_action('wp_footer', 'tiktok_lightbox_html');
function tiktok_lightbox_html() {
    ?>
    <div id="tiktok-lightbox" class="tiktok-lightbox">
        <div class="tiktok-lightbox-overlay"></div>
        <div class="tiktok-lightbox-content">
            <button class="tiktok-lightbox-close">&times;</button>
            <div class="tiktok-lightbox-video">
                <div id="tiktok-embed-container"></div>
            </div>
        </div>
    </div>
    <?php
} 