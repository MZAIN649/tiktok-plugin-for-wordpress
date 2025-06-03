<?php
/**
 * TikTok Lightbox Elementor Widget
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class TikTok_Lightbox_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'tiktok-lightbox';
    }

    public function get_title() {
        return esc_html__('TikTok Lightbox', 'tiktok-lightbox');
    }

    public function get_icon() {
        return 'eicon-video-camera';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['tiktok', 'video', 'lightbox', 'social', 'embed'];
    }

    protected function register_controls() {

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'tiktok-lightbox'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'tiktok_url',
            [
                'label'       => esc_html__('TikTok URL', 'tiktok-lightbox'),
                'type'        => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://www.tiktok.com/@username/video/1234567890', 'tiktok-lightbox'),
                'default'     => [
                    'url' => '',
                ],
                'description' => esc_html__('Enter the full TikTok video URL', 'tiktok-lightbox'),
            ]
        );

        $this->add_control(
            'thumbnail_image',
            [
                'label'   => esc_html__('Custom Thumbnail', 'tiktok-lightbox'),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'description' => esc_html__('Upload a custom thumbnail image. If not provided, a default TikTok-style thumbnail will be used.', 'tiktok-lightbox'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'   => esc_html__('Button Text', 'tiktok-lightbox'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Watch on TikTok', 'tiktok-lightbox'),
            ]
        );

        $this->add_responsive_control(
            'thumbnail_width',
            [
                'label'      => esc_html__('Thumbnail Width', 'tiktok-lightbox'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 100,
                        'max' => 800,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tiktok-thumbnail' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnail_height',
            [
                'label'      => esc_html__('Thumbnail Height', 'tiktok-lightbox'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 50,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 533,
                ],
                'selectors' => [
                    '{{WRAPPER}} .tiktok-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'tiktok-lightbox'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'thumbnail_border',
                'selector' => '{{WRAPPER}} .tiktok-thumbnail',
            ]
        );

        $this->add_responsive_control(
            'thumbnail_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'tiktok-lightbox'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .tiktok-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .tiktok-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'thumbnail_box_shadow',
                'selector' => '{{WRAPPER}} .tiktok-thumbnail',
            ]
        );

        $this->add_responsive_control(
            'thumbnail_margin',
            [
                'label'      => esc_html__('Margin', 'tiktok-lightbox'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .tiktok-lightbox-trigger' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Play Button Style Section
        $this->start_controls_section(
            'play_button_style_section',
            [
                'label' => esc_html__('Play Button', 'tiktok-lightbox'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'play_button_size',
            [
                'label'      => esc_html__('Size', 'tiktok-lightbox'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 30,
                        'max' => 150,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 68,
                ],
                'selectors' => [
                    '{{WRAPPER}} .play-button svg' => 'width: {{SIZE}}{{UNIT}}; height: calc({{SIZE}}{{UNIT}} * 0.7);',
                ],
            ]
        );

        $this->add_control(
            'play_button_color',
            [
                'label'     => esc_html__('Color', 'tiktok-lightbox'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ff0050',
                'selectors' => [
                    '{{WRAPPER}} .play-button svg path:first-child' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style Section
        $this->start_controls_section(
            'button_style_section',
            [
                'label' => esc_html__('Button Text', 'tiktok-lightbox'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .tiktok-button',
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__('Text Color', 'tiktok-lightbox'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .tiktok-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label'     => esc_html__('Background Color', 'tiktok-lightbox'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.9)',
                'selectors' => [
                    '{{WRAPPER}} .tiktok-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__('Padding', 'tiktok-lightbox'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .tiktok-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'tiktok-lightbox'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .tiktok-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if (empty($settings['tiktok_url']['url'])) {
            echo '<p>' . esc_html__('Please enter a TikTok URL', 'tiktok-lightbox') . '</p>';
            return;
        }

        // Extract video ID from URL
        $video_id = $this->extract_video_id($settings['tiktok_url']['url']);
        if (!$video_id) {
            echo '<p>' . esc_html__('Invalid TikTok URL', 'tiktok-lightbox') . '</p>';
            return;
        }

        $thumbnail_url = !empty($settings['thumbnail_image']['url']) 
            ? $settings['thumbnail_image']['url'] 
            : TIKTOK_LIGHTBOX_PLUGIN_URL . 'assets/tiktok-placeholder.jpg';

        $button_text = !empty($settings['button_text']) 
            ? $settings['button_text'] 
            : 'Watch on TikTok';

        ?>
        <div class="tiktok-lightbox-trigger" data-video-id="<?php echo esc_attr($video_id); ?>">
            <div class="tiktok-thumbnail">
                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="TikTok Video" />
                <div class="play-button">
                    <svg width="68" height="48" viewBox="0 0 68 48">
                        <path d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#ff0050"></path>
                        <path d="M45,24L27,14v20" fill="white"></path>
                    </svg>
                </div>
                <div class="tiktok-button"><?php echo esc_html($button_text); ?></div>
            </div>
        </div>
        <?php
    }

    private function extract_video_id($url) {
        // Extract video ID from TikTok URL
        preg_match('/\/video\/(\d+)/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : false;
    }

    protected function content_template() {
        ?>
        <#
        var videoId = '';
        if (settings.tiktok_url.url) {
            var matches = settings.tiktok_url.url.match(/\/video\/(\d+)/);
            if (matches && matches[1]) {
                videoId = matches[1];
            }
        }

        if (!videoId) {
            #><p>Please enter a valid TikTok URL</p><#
            return;
        }

        var thumbnailUrl = settings.thumbnail_image.url || '<?php echo TIKTOK_LIGHTBOX_PLUGIN_URL; ?>assets/tiktok-placeholder.jpg';
        var buttonText = settings.button_text || 'Watch on TikTok';
        #>
        <div class="tiktok-lightbox-trigger" data-video-id="{{ videoId }}">
            <div class="tiktok-thumbnail">
                <img src="{{ thumbnailUrl }}" alt="TikTok Video" />
                <div class="play-button">
                    <svg width="68" height="48" viewBox="0 0 68 48">
                        <path d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#ff0050"></path>
                        <path d="M45,24L27,14v20" fill="white"></path>
                    </svg>
                </div>
                <div class="tiktok-button">{{ buttonText }}</div>
            </div>
        </div>
        <?php
    }
}
?> 