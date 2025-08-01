# Tailwind CSS Integration Guide

## Installation Complete ✅

Tailwind CSS has been successfully installed in your ep-3 Bookingsystem project.

## Files Created:
- `package.json` - Node.js dependencies
- `tailwind.config.js` - Tailwind configuration
- `src/input.css` - Source CSS file
- `public/css/tailwind.css` - Generated Tailwind CSS file

## Usage

### 1. Include Tailwind CSS in your templates

Add this line to your main layout template (likely in `module/Base/view/layout/layout.phtml` or similar):

```html
<link rel="stylesheet" href="/css/tailwind.css">
```

### 2. Custom Component Classes Available

The following custom classes are ready to use:

- `.btn-booking` - Styled button for booking actions
- `.card-booking` - Card component for booking information
- `.header-booking` - Header styling
- `.calendar-cell` - Basic calendar cell
- `.calendar-cell-occupied` - Occupied time slot styling
- `.calendar-cell-available` - Available time slot styling

### 3. Development Workflow

**For development (with file watching):**
```bash
npm run build-css
```

**For production (minified):**
```bash
npm run build-css-prod
```

### 4. Example Usage

```html
<!-- Booking button -->
<button class="btn-booking">Book Now</button>

<!-- Booking card -->
<div class="card-booking">
    <h3 class="text-xl font-semibold mb-4">Court Booking</h3>
    <p class="text-gray-600">Available time slots for today</p>
</div>

<!-- Calendar grid -->
<div class="grid grid-cols-7 gap-1">
    <div class="calendar-cell calendar-cell-available">9:00 AM</div>
    <div class="calendar-cell calendar-cell-occupied">10:00 AM</div>
</div>
```

### 5. Utility Classes

You can now use all Tailwind utility classes:
- Layout: `flex`, `grid`, `container`
- Spacing: `p-4`, `m-2`, `space-x-4`
- Colors: `bg-blue-500`, `text-red-600`
- Typography: `text-lg`, `font-bold`
- And many more!

### 6. Custom Colors

Custom colors matching your theme:
- `bg-booking-primary` (dark gray #333)
- `bg-booking-secondary` (light gray #cbcbcb)
- `bg-booking-accent` (medium gray #666)
- `bg-booking-light` (very light gray #f1f1f1)
- `bg-booking-dark` (dark gray #808080)

## Next Steps

1. Add the Tailwind CSS link to your main layout template
2. Start using Tailwind classes in your existing templates
3. Gradually migrate from custom CSS to Tailwind utilities
4. Use the custom component classes for booking-specific elements

## File Watching

To automatically rebuild CSS when you make changes, run:
```bash
npm run build-css
```

This will watch for changes and rebuild automatically.