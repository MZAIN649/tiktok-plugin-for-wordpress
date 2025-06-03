# 🎵 TikTok Lightbox Plugin

A beautiful, responsive WordPress plugin that displays TikTok videos in a full-screen lightbox with seamless Elementor integration.

![TikTok Lightbox Plugin Preview](https://via.placeholder.com/800x400/667eea/ffffff?text=TikTok+Lightbox+Plugin)

## ✨ Features

- **📱 Fully Responsive** - Works perfectly on desktop, tablet, and mobile devices
- **🎨 Highly Customizable** - Control colors, sizes, thumbnails, and button text
- **🔧 Elementor Ready** - Complete integration with Elementor page builder
- **⚡ Fast Loading** - Optimized performance with lazy loading
- **♿ Accessible** - Keyboard navigation and screen reader support
- **🔒 Secure** - Built with WordPress security best practices
- **🎭 Beautiful Animations** - Smooth transitions and hover effects
- **📦 Easy Installation** - Simple drag-and-drop installation

## 🚀 Installation

### Method 1: WordPress Admin Panel
1. Download the plugin zip file
2. Go to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin" and select the zip file
4. Activate the plugin

### Method 2: Manual Installation
1. Extract the plugin files to your `/wp-content/plugins/` directory
2. Activate the plugin through the WordPress admin panel

### Method 3: Direct Upload
1. Upload all files to your WordPress installation directory
2. The plugin will be automatically activated

## 📖 Usage

### 1. WordPress Shortcode

Use the shortcode anywhere in your WordPress content:

```php
[tiktok_lightbox url="https://www.tiktok.com/@username/video/1234567890" button_text="Watch Video"]
```

**Shortcode Parameters:**
- `url` - TikTok video URL (required)
- `thumbnail` - Custom thumbnail image URL (optional)
- `button_text` - Button text (default: "Watch on TikTok")
- `width` - Thumbnail width (default: 300px)
- `height` - Thumbnail height (default: 533px)

### 2. Elementor Widget

1. Open Elementor editor
2. Search for "TikTok Lightbox" in the widget panel
3. Drag the widget to your page
4. Configure settings in the widget panel:
   - **TikTok URL**: Enter the full TikTok video URL
   - **Custom Thumbnail**: Upload your own thumbnail (optional)
   - **Button Text**: Customize the call-to-action text
   - **Styling Options**: Control colors, sizes, borders, and animations

### 3. Direct JavaScript API

For developers who want to trigger the lightbox programmatically:

```javascript
// Open lightbox with video ID
TikTokLightbox.open('7503809700601908488');

// Close lightbox
TikTokLightbox.close();
```

### 4. jQuery Plugin

Use as a jQuery plugin:

```javascript
$('.my-tiktok-element').tikTokLightbox({
    videoId: '7503809700601908488'
});
```

## 🎨 Customization

### CSS Customization

You can customize the appearance by overriding CSS classes:

```css
/* Customize thumbnail appearance */
.tiktok-thumbnail {
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

/* Customize play button */
.play-button svg path:first-child {
    fill: #your-brand-color;
}

/* Customize button text */
.tiktok-button {
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    color: white;
}
```

### Elementor Styling

The Elementor widget includes comprehensive styling options:

- **Thumbnail Styling**: Borders, border radius, box shadows, margins
- **Play Button**: Size and color customization
- **Button Text**: Typography, colors, padding, border radius
- **Responsive Controls**: Different settings for desktop, tablet, and mobile

## 🔧 Technical Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.0 or higher
- **jQuery**: Included with WordPress
- **Elementor**: 3.0 or higher (for widget functionality)

## 📱 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## 🎯 Example TikTok URLs

The plugin works with standard TikTok URLs:

```
https://www.tiktok.com/@username/video/1234567890
https://vm.tiktok.com/shortcode/
```

## 🛠️ Troubleshooting

### Video Not Loading
- Ensure the TikTok URL is valid and publicly accessible
- Check if TikTok's embed script is loading properly
- Verify that the video ID is being extracted correctly

### Lightbox Not Opening
- Check browser console for JavaScript errors
- Ensure jQuery is loaded
- Verify that the plugin CSS and JS files are enqueued

### Elementor Widget Missing
- Make sure Elementor is installed and activated
- Check if the widget is properly registered
- Clear any caching plugins

## 🔒 Security Features

- Input sanitization and validation
- Nonce verification for admin actions
- Proper escaping of output data
- CSRF protection
- SQL injection prevention

## 📈 Performance Optimization

- Lazy loading of TikTok embed scripts
- Minimized CSS and JavaScript
- Efficient DOM manipulation
- Conditional loading of assets
- Responsive image handling

## 🌟 Advanced Features

### Keyboard Navigation
- `ESC` key to close lightbox
- Tab navigation support
- Focus management for accessibility

### Touch Gestures (Mobile)
- Tap overlay to close
- Swipe gestures (coming soon)
- Pinch to zoom (coming soon)

### Developer Hooks

```php
// Customize lightbox HTML
add_filter('tiktok_lightbox_html', 'custom_lightbox_html');

// Modify video embed
add_filter('tiktok_lightbox_embed', 'custom_embed_code', 10, 2);

// Add custom CSS classes
add_filter('tiktok_lightbox_classes', 'add_custom_classes');
```

## 📄 License

This plugin is licensed under the GPL v2 or later.

## 🤝 Contributing

We welcome contributions! Please feel free to submit pull requests or report issues.

## 📞 Support

For support and questions:
- Create an issue on GitHub
- Contact us through WordPress.org
- Visit our documentation site

## 📝 Changelog

### Version 1.0.0
- Initial release
- Basic lightbox functionality
- Elementor widget integration
- Responsive design
- Accessibility features

---

**Made with ❤️ for the WordPress community** 