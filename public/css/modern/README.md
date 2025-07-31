# SMK Tennis Academy - Modern UI System

## Overview

This modern UI system transforms the ep-3 Bookingsystem from dated design to modern, enterprise-level UI following current design trends. The system includes a comprehensive design token system, component library, and ApexCharts integration for analytics.

## Features

### 🎨 Design System

- **Design Tokens**: CSS custom properties for consistent theming
- **Typography**: Modern font stack with Inter as primary font
- **Color Palette**: Tennis-themed color scheme with semantic colors
- **Spacing System**: Consistent 4px-based spacing scale
- **Shadow System**: Multiple elevation levels
- **Border Radius**: Consistent rounded corners

### 🧩 Component Library

- **Buttons**: Multiple variants (primary, secondary, ghost, tennis-themed)
- **Cards**: Various card types (stats, feature, pricing, interactive)
- **Forms**: Modern form elements with floating labels
- **Alerts**: Success, warning, error, and info states
- **Badges**: Color-coded status indicators
- **Tables**: Responsive data tables
- **Modals**: Overlay dialogs
- **Toast Notifications**: In-app notifications

### 📊 ApexCharts Integration

- **Chart Types**: Line, bar, pie, area, candlestick, heatmap
- **Responsive**: Mobile-optimized charts
- **Custom Styling**: Tennis-themed chart colors
- **Interactive**: Hover effects and tooltips
- **Export Options**: Download charts as images

### 📱 Responsive Design

- **Mobile-First**: Optimized for all screen sizes
- **Breakpoints**: 640px, 768px, 1024px, 1280px, 1536px
- **Grid System**: 12-column responsive grid
- **Flexible Layouts**: Adaptive component layouts

## File Structure

```
public/css/
├── modern/
│   ├── design-tokens.css      # CSS custom properties
│   ├── base.css              # Base styles and utilities
│   └── README.md             # This documentation
├── components/
│   ├── buttons.css           # Button system
│   ├── cards.css             # Card components
│   └── charts.css            # ApexCharts integration
├── utilities/                # Utility classes
├── themes/                   # Theme variations
└── modern.css               # Main entry point
```

## Usage

### 1. Include the CSS

Add the modern CSS to your layout:

```html
<link rel="stylesheet" href="/css/modern.css" />
```

### 2. Use Design Tokens

```css
.my-component {
  background-color: var(--bg-primary);
  color: var(--text-primary);
  padding: var(--space-6);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-md);
}
```

### 3. Use Components

#### Buttons

```html
<button class="btn btn-primary">Primary Button</button>
<button class="btn btn-tennis btn-lg">Tennis Button</button>
<button class="btn btn-outline-secondary">Outline Button</button>
```

#### Cards

```html
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Card Title</h3>
  </div>
  <div class="card-body">
    <p>Card content goes here</p>
  </div>
</div>
```

#### Stats Cards

```html
<div class="card-stats">
  <div class="card-stats-icon tennis">
    <i class="fas fa-calendar"></i>
  </div>
  <div class="card-stats-value">1,247</div>
  <div class="card-stats-label">Total Bookings</div>
  <div class="card-stats-change positive">
    <i class="fas fa-arrow-up"></i>
    <span>+12.5%</span>
  </div>
</div>
```

### 4. ApexCharts Integration

Include ApexCharts and create charts:

```html
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div id="myChart" class="chart-line"></div>

<script>
  const options = {
    series: [
      {
        name: "Bookings",
        data: [30, 40, 35, 50, 49, 60, 70, 91, 125],
      },
    ],
    chart: {
      type: "area",
      height: 300,
      fontFamily: "Inter, sans-serif",
    },
    colors: ["#16a34a"],
    // ... more options
  };

  const chart = new ApexCharts(document.querySelector("#myChart"), options);
  chart.render();
</script>
```

## Design Tokens

### Colors

#### Primary Palette

- `--primary-50` to `--primary-950`: Blue color scale
- `--tennis-50` to `--tennis-950`: Tennis green scale
- `--neutral-50` to `--neutral-950`: Gray scale

#### Semantic Colors

- `--success-*`: Green for success states
- `--warning-*`: Amber for warnings
- `--error-*`: Red for errors
- `--info-*`: Blue for information

### Typography

#### Font Families

- `--font-family-sans`: Inter, system fonts
- `--font-family-mono`: Monospace fonts

#### Font Sizes

- `--font-size-xs`: 12px
- `--font-size-sm`: 14px
- `--font-size-base`: 16px
- `--font-size-lg`: 18px
- `--font-size-xl`: 20px
- `--font-size-2xl`: 24px
- `--font-size-3xl`: 30px
- `--font-size-4xl`: 36px
- `--font-size-5xl`: 48px
- `--font-size-6xl`: 60px

#### Font Weights

- `--font-weight-light`: 300
- `--font-weight-normal`: 400
- `--font-weight-medium`: 500
- `--font-weight-semibold`: 600
- `--font-weight-bold`: 700
- `--font-weight-extrabold`: 800

### Spacing

#### Space Scale

- `--space-1`: 4px
- `--space-2`: 8px
- `--space-3`: 12px
- `--space-4`: 16px
- `--space-5`: 20px
- `--space-6`: 24px
- `--space-8`: 32px
- `--space-10`: 40px
- `--space-12`: 48px
- `--space-16`: 64px
- `--space-20`: 80px
- `--space-24`: 96px

### Shadows

- `--shadow-xs`: Subtle shadow
- `--shadow-sm`: Small shadow
- `--shadow-md`: Medium shadow
- `--shadow-lg`: Large shadow
- `--shadow-xl`: Extra large shadow
- `--shadow-2xl`: Maximum shadow

### Border Radius

- `--radius-sm`: 4px
- `--radius-md`: 6px
- `--radius-lg`: 8px
- `--radius-xl`: 12px
- `--radius-2xl`: 16px
- `--radius-3xl`: 24px
- `--radius-full`: 9999px

## Utility Classes

### Layout

- `.container`: Responsive container
- `.grid`: CSS Grid layout
- `.flex`: Flexbox layout
- `.grid-cols-*`: Grid columns
- `.gap-*`: Gap spacing

### Spacing

- `.m-*`, `.mt-*`, `.mb-*`, `.ml-*`, `.mr-*`: Margins
- `.p-*`, `.pt-*`, `.pb-*`, `.pl-*`, `.pr-*`: Padding

### Typography

- `.text-*`: Font sizes
- `.font-*`: Font weights
- `.text-center`, `.text-left`, `.text-right`: Text alignment
- `.text-primary`, `.text-secondary`, etc.: Text colors

### Display

- `.hidden`, `.block`, `.inline`, `.inline-block`: Display properties
- `.w-full`, `.h-full`: Width and height

### Borders

- `.border`, `.border-t`, `.border-b`: Border utilities
- `.rounded-*`: Border radius

### Backgrounds

- `.bg-primary`, `.bg-secondary`, `.bg-tertiary`: Background colors

## Browser Support

- Chrome 88+
- Firefox 85+
- Safari 14+
- Edge 88+

## Performance

- CSS custom properties for dynamic theming
- Optimized selectors for better performance
- Minimal CSS output with utility classes
- Responsive images and lazy loading support

## Accessibility

- WCAG 2.1 AA compliant color contrast ratios
- Keyboard navigation support
- Screen reader friendly markup
- Focus indicators for interactive elements
- Semantic HTML structure

## Customization

### Creating Custom Themes

1. Override design tokens in your CSS:

```css
:root {
  --primary-600: #your-custom-color;
  --tennis-600: #your-tennis-color;
}
```

2. Create theme variations:

```css
[data-theme="dark"] {
  --bg-primary: #1a1a1a;
  --text-primary: #ffffff;
  /* ... other dark theme tokens */
}
```

### Adding New Components

1. Create component CSS file in `components/` directory
2. Import in `modern.css`
3. Follow naming conventions and design patterns
4. Include responsive design considerations

## Migration Guide

### From Old Styles

1. Replace old button classes:

   ```html
   <!-- Old -->
   <a class="default-button">Button</a>

   <!-- New -->
   <button class="btn btn-primary">Button</button>
   ```

2. Update card structures:

   ```html
   <!-- Old -->
   <div class="panel">Content</div>

   <!-- New -->
   <div class="card">
     <div class="card-body">Content</div>
   </div>
   ```

3. Use modern form elements:

   ```html
   <!-- Old -->
   <input type="text" class="old-input" />

   <!-- New -->
   <div class="form-group">
     <label class="form-label">Label</label>
     <input type="text" class="form-input" />
   </div>
   ```

## Contributing

1. Follow the established design patterns
2. Use design tokens for consistency
3. Include responsive design
4. Test across different browsers
5. Maintain accessibility standards

## License

This UI system is part of the SMK Tennis Academy booking system.
