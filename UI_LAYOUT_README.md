# UI Layout System Guide

## Overview
This document provides a comprehensive guide to the modern UI layout system implemented in the EP-3 Booking System. The system uses Tailwind CSS for styling and follows modern design principles for a responsive, accessible, and user-friendly interface.

## Table of Contents
1. [Layout Structure](#layout-structure)
2. [Tailwind CSS Integration](#tailwind-css-integration)
3. [Custom Components](#custom-components)
4. [Color System](#color-system)
5. [Typography](#typography)
6. [Responsive Design](#responsive-design)
7. [Working with Templates](#working-with-templates)
8. [Development Workflow](#development-workflow)
9. [Best Practices](#best-practices)
10. [Troubleshooting](#troubleshooting)

## Layout Structure

### Main Layout Files
- **Primary Layout**: `module/Base/view/layout/layout.phtml`
- **Updated Layout**: `module/Base/view/layout/layout_updated.phtml`
- **Setup Layout**: `module/Setup/view/layout/layout.phtml`

### Layout Hierarchy
```
├── HTML Document
│   ├── Head Section
│   │   ├── Meta Tags
│   │   ├── CSS Links (Tailwind + Custom)
│   │   └── JavaScript Includes
│   ├── Body
│   │   ├── Header Navigation
│   │   ├── Main Content Area
│   │   │   ├── Sidebar (if applicable)
│   │   │   └── Content Container
│   │   └── Footer
│   └── Scripts
```

## Tailwind CSS Integration

### CSS Files Location
- **Compiled Tailwind**: `public/css/tailwind.css`
- **Source Input**: `src/input.css`
- **Legacy CSS**: `public/css/default.css`

### Including Tailwind in Templates
```php
<?= $this->headLink()->appendStylesheet($this->basePath('/css/tailwind.css')) ?>
```

### Build Commands
```bash
# Development (with watch mode)
npm run build-css

# Production (minified)
npm run build-css-prod
```

## Custom Components

### Button Components
```html
<!-- Primary Button -->
<button class="btn-booking">Book Now</button>

<!-- Secondary Button -->
<button class="btn-booking-secondary">Cancel</button>

<!-- Success Button -->
<button class="btn-booking-success">Confirm</button>

<!-- Danger Button -->
<button class="btn-booking-danger">Delete</button>
```

### Card Components
```html
<!-- Standard Card -->
<div class="card-booking">
    <div class="card-booking-header">
        <h3>Card Title</h3>
    </div>
    <div class="card-booking-body">
        <p>Card content goes here</p>
    </div>
</div>
```

### Header Components
```html
<!-- Main Header -->
<header class="header-booking">
    <div class="header-booking-content">
        <!-- Header content -->
    </div>
</header>
```

### Calendar Components
```html
<!-- Available Calendar Cell -->
<div class="calendar-cell-available">Available</div>

<!-- Occupied Calendar Cell -->
<div class="calendar-cell-occupied">Occupied</div>

<!-- Selected Calendar Cell -->
<div class="calendar-cell-selected">Selected</div>
```

## Color System

### Primary Colors
```css
--booking-primary: #3b82f6      /* Blue */
--booking-secondary: #6b7280    /* Gray */
--booking-accent: #10b981       /* Green */
--booking-light: #f8fafc        /* Light Gray */
--booking-dark: #1f2937         /* Dark Gray */
```

### Usage in Tailwind
```html
<!-- Using custom colors -->
<div class="bg-booking-primary text-white">Primary Background</div>
<div class="text-booking-secondary">Secondary Text</div>
<div class="border-booking-accent">Accent Border</div>
```

### Status Colors
```html
<!-- Success -->
<div class="bg-green-500 text-white">Success</div>

<!-- Warning -->
<div class="bg-yellow-500 text-white">Warning</div>

<!-- Error -->
<div class="bg-red-500 text-white">Error</div>

<!-- Info -->
<div class="bg-blue-500 text-white">Info</div>
```

## Typography

### Font Families
- **Primary**: Arial, sans-serif
- **Monospace**: Consolas, monospace

### Heading Styles
```html
<h1 class="text-4xl font-bold text-booking-dark mb-4">Main Title</h1>
<h2 class="text-3xl font-semibold text-booking-dark mb-3">Section Title</h2>
<h3 class="text-2xl font-medium text-booking-dark mb-2">Subsection</h3>
```

### Text Styles
```html
<p class="text-base text-booking-secondary leading-relaxed">Body text</p>
<span class="text-sm text-gray-600">Small text</span>
<strong class="font-semibold text-booking-dark">Bold text</strong>
```

## Responsive Design

### Breakpoints
```css
sm: 640px    /* Small devices */
md: 768px    /* Medium devices */
lg: 1024px   /* Large devices */
xl: 1280px   /* Extra large devices */
2xl: 1536px  /* 2X large devices */
```

### Responsive Classes
```html
<!-- Responsive grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Grid items -->
</div>

<!-- Responsive text -->
<h1 class="text-2xl md:text-3xl lg:text-4xl">Responsive Heading</h1>

<!-- Responsive spacing -->
<div class="p-4 md:p-6 lg:p-8">Content</div>
```

## Working with Templates

### Template Structure
```php
<?php
// Template: module/Frontend/view/frontend/index/index.phtml
?>

<!-- Include Tailwind CSS -->
<?= $this->headLink()->appendStylesheet($this->basePath('/css/tailwind.css')) ?>

<!-- Main container -->
<div class="min-h-screen bg-booking-light">
    <!-- Header -->
    <header class="header-booking">
        <!-- Header content -->
    </header>
    
    <!-- Main content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Page content -->
    </main>
    
    <!-- Footer -->
    <footer class="bg-booking-dark text-white py-8">
        <!-- Footer content -->
    </footer>
</div>
```

### Form Styling
```html
<!-- Form container -->
<form class="space-y-6 bg-white p-6 rounded-lg shadow-md">
    <!-- Input field -->
    <div class="form-group">
        <label class="block text-sm font-medium text-booking-dark mb-2">
            Field Label
        </label>
        <input type="text" 
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-booking-primary focus:border-transparent">
    </div>
    
    <!-- Submit button -->
    <button type="submit" class="btn-booking w-full">
        Submit
    </button>
</form>
```

### Table Styling
```html
<div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Header
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    Data
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

## Development Workflow

### 1. Setting Up Development Environment
```bash
# Install dependencies
npm install

# Start development with watch mode
npm run build-css
```

### 2. Making Changes
1. Edit template files (`.phtml`)
2. Use Tailwind utility classes
3. Add custom components if needed
4. Test responsiveness

### 3. Building for Production
```bash
# Build optimized CSS
npm run build-css-prod
```

### 4. Testing
- Test on different screen sizes
- Verify accessibility
- Check browser compatibility

## Best Practices

### 1. Use Utility-First Approach
```html
<!-- Good: Utility classes -->
<div class="bg-white p-6 rounded-lg shadow-md">

<!-- Avoid: Custom CSS when utilities exist -->
<div class="custom-card-style">
```

### 2. Maintain Consistency
- Use the predefined color palette
- Follow spacing conventions
- Use consistent component patterns

### 3. Responsive Design
- Always consider mobile-first design
- Test on multiple screen sizes
- Use responsive utilities

### 4. Accessibility
```html
<!-- Good: Accessible button -->
<button class="btn-booking" 
        aria-label="Book tennis court"
        role="button">
    Book Now
</button>

<!-- Good: Accessible form -->
<label for="email" class="sr-only">Email Address</label>
<input id="email" type="email" class="form-input">
```

### 5. Performance
- Use production build for live sites
- Minimize custom CSS
- Leverage Tailwind's purging

## Module-Specific Guidelines

### Frontend Module
- Focus on user experience
- Use clear call-to-action buttons
- Implement intuitive navigation

### Backend Module
- Prioritize functionality
- Use data tables for listings
- Implement clear form validation

### Calendar Module
- Ensure touch-friendly interface
- Use clear visual indicators
- Implement smooth interactions

### User Module
- Focus on form usability
- Provide clear feedback
- Ensure secure appearance

## Troubleshooting

### Common Issues

#### 1. Styles Not Loading
```php
// Check if Tailwind CSS is included
<?= $this->headLink()->appendStylesheet($this->basePath('/css/tailwind.css')) ?>
```

#### 2. Custom Components Not Working
- Verify `src/input.css` includes your components
- Rebuild CSS: `npm run build-css-prod`

#### 3. Responsive Issues
- Test with browser dev tools
- Check breakpoint usage
- Verify mobile-first approach

#### 4. Color Issues
- Use defined color variables
- Check Tailwind config for custom colors

### Debug Commands
```bash
# Check if Tailwind is working
npm run build-css -- --verbose

# Verify file changes
git status

# Check for syntax errors
npm run build-css-prod
```

## File Structure Reference

```
sports/
├── src/
│   └── input.css                 # Tailwind source
├── public/css/
│   ├── tailwind.css             # Compiled Tailwind
│   └── default.css              # Legacy styles
├── module/
│   ├── Base/view/layout/        # Main layouts
│   ├── Frontend/view/           # User interface
│   ├── Backend/view/            # Admin interface
│   └── [Other modules]/view/    # Module templates
├── tailwind.config.js           # Tailwind configuration
└── package.json                 # Node dependencies
```

## Getting Help

### Resources
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Responsive Design Guide](https://tailwindcss.com/docs/responsive-design)
- [Customization Guide](https://tailwindcss.com/docs/configuration)

### Support
- Check existing templates for examples
- Review `TAILWIND_INTEGRATION.md` for setup details
- Test changes in development environment first

---

**Last Updated**: January 2025
**Version**: 1.0.0