# AhoMINNA - Japanese Learning Plugin for WordPress

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)
![License](https://img.shields.io/badge/license-GPL%20v2-green.svg)

A comprehensive WordPress plugin for teaching Japanese with Minna no Nihongo textbook (50 lessons) designed specifically for Vietnamese learners.

## 📚 Features

### Learning Sections
- **Vocabulary (Từ vựng)**: Interactive flashcard system with flip animations
- **Grammar (Ngữ pháp)**: Structured grammar patterns with Vietnamese explanations
- **Dialogue (Hội thoại)**: Conversation practice with audio and video support
- **Quiz (Trắc nghiệm)**: Multiple-choice quizzes with instant feedback

### Key Functionalities
- 🎴 **Flashcard System**: Learn and review mode with swipe/click navigation
- 🎵 **Audio Support**: Japanese pronunciation for vocabulary and dialogues
- 🎥 **Video Integration**: YouTube embed or direct video upload
- 📊 **Progress Tracking**: Save user progress for each lesson section
- 🎯 **Quiz System**: Track scores and review answers with explanations
- 📱 **Responsive Design**: Optimized for desktop, tablet, and mobile
- 🖥️ **Fullscreen Mode**: Presentation-ready with keyboard shortcuts
- 🌐 **Bilingual**: Japanese-Vietnamese interface

### Admin Features
- Custom post types for lessons, vocabulary, grammar, dialogues, and quizzes
- Bulk import from CSV for vocabulary and quiz questions
- Visual dashboard with statistics
- Easy content management with custom meta boxes
- Settings page for plugin configuration

## 🚀 Installation

1. Upload the `ahominna` folder to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to **AhoMINNA** in the admin menu
4. Start creating lessons!

## 📋 Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Modern web browser with JavaScript enabled

## 🎓 Usage

### Creating Lessons

1. Go to **AhoMINNA > Add New Lesson**
2. Enter lesson title, number, and description
3. Create vocabulary, grammar, dialogues, and quizzes
4. Link them to the lesson using IDs

### Bulk Import

1. Go to **AhoMINNA > Import**
2. Select import type (Vocabulary or Quiz)
3. Choose CSV file with correct format
4. Upload and import

#### Vocabulary CSV Format
```csv
kanji,hiragana,romaji,vietnamese,image_url,audio_url
学生,がくせい,gakusei,học sinh,https://example.com/image.jpg,https://example.com/audio.mp3
```

#### Quiz CSV Format
```csv
question,type,option_a,option_b,option_c,option_d,correct_index,explanation,audio_url
Question text,vocabulary,Option A,Option B,Option C,Option D,0,Explanation here,
```

### Shortcodes

Display lessons on any page or post:

```
[ahominna_lessons]
```

Display a specific lesson:
```
[ahominna_lesson id="1"]
```

Display vocabulary flashcards:
```
[ahominna_vocabulary lesson="1"]
```

Display quiz:
```
[ahominna_quiz lesson="1"]
```

## 🎨 Customization

### CSS Customization
Override styles by adding custom CSS in your theme:
```css
.ahominna-lesson-container {
    /* Your custom styles */
}
```

### Color Scheme
Edit `/public/css/style.css` to change the color variables:
```css
:root {
    --primary-color: #e74c3c;
    --secondary-color: #3498db;
    --accent-color: #f39c12;
}
```

## ⌨️ Keyboard Shortcuts

### Flashcard Mode
- `←` Previous card
- `→` Next card
- `Space` Flip card

### Quiz Mode
- `←` Previous question
- `→` Next question
- `Enter` Submit quiz

### Fullscreen Mode
- `Esc` Exit fullscreen
- `Shift + ←` Previous section
- `Shift + →` Next section

## 📱 Responsive Design

The plugin is fully responsive and optimized for:
- Desktop (1200px+)
- Tablet (768px - 1199px)
- Mobile (< 768px)
- Landscape mode

## 🔧 Technical Details

### Custom Post Types
- `ahominna_lesson` - Lessons
- `ahominna_vocabulary` - Vocabulary items
- `ahominna_grammar` - Grammar patterns
- `ahominna_dialogue` - Conversation dialogues
- `ahominna_quiz` - Quiz questions

### Database Tables
- `{prefix}_ahominna_progress` - User progress tracking
- `{prefix}_ahominna_quiz_results` - Quiz results

### AJAX Actions
- `ahominna_save_progress` - Save learning progress
- `ahominna_submit_quiz` - Submit quiz answers
- `ahominna_get_vocabulary` - Fetch vocabulary data

## 🌍 Internationalization

The plugin is translation-ready. Translation files are located in `/languages/` directory.

Current languages:
- English (default)
- Vietnamese (vi)

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📝 License

This plugin is licensed under the GPL v2 or later.

## 👨‍💻 Author

**DatNong**
- GitHub: [@datnong1210-arch](https://github.com/datnong1210-arch)

## 📞 Support

For support, please open an issue on the [GitHub repository](https://github.com/datnong1210-arch/minna).

## 🎯 Roadmap

- [ ] Mobile app integration
- [ ] Additional quiz types (fill-in-the-blank, matching)
- [ ] Gamification features (badges, streaks)
- [ ] Social sharing
- [ ] Export progress reports
- [ ] Lesson completion certificates

## 📸 Screenshots

Screenshots will be available in the `/assets/` directory.

## ⚡ Performance

- Lazy loading for images
- Optimized CSS and JavaScript
- Minimal database queries
- Caching support

## 🔒 Security

- CSRF protection with nonces
- Input sanitization and validation
- Prepared SQL statements
- Capability checks for admin functions

## 📚 Documentation

For detailed documentation, visit the [Wiki](https://github.com/datnong1210-arch/minna/wiki).

---

Made with ❤️ for Japanese language learners in Vietnam
