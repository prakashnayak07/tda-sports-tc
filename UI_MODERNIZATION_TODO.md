# UI Modernization TODO List

## Overview
This document contains a complete checklist of all 82 template files that need to be converted to modern UI design using Tailwind CSS. Each file should be updated to follow the design patterns outlined in the `UI_LAYOUT_README.md`.

## Progress Tracking
- **Total Files**: 82
- **Completed**: 0
- **In Progress**: 0
- **Remaining**: 82

## Conversion Priority

### 🔥 **CRITICAL PRIORITY** (Start Here)
These files affect the entire application and should be converted first.

#### Base Module (Layout & Error Pages)
- [ ] `module/Base/view/layout/layout.phtml` - **Main application layout**
- [ ] `module/Base/view/layout/layout_updated.phtml` - **Updated layout variant**
- [ ] `module/Base/view/error/404.phtml` - **404 error page**
- [ ] `module/Base/view/error/500.phtml` - **500 error page**

### 🚀 **HIGH PRIORITY** (User-Facing Interface)
These are the main user-facing components that users interact with daily.

#### Frontend Module (Main User Interface)
- [ ] `module/Frontend/view/frontend/index/index.phtml` - **Main homepage**
- [ ] `module/Frontend/view/frontend/index/datepicker.phtml` - **Date picker component**
- [ ] `module/Frontend/view/frontend/index/userpanel.offline.phtml` - **Offline user panel**
- [ ] `module/Frontend/view/frontend/index/userpanel.online.phtml` - **Online user panel**

#### User Module (Authentication & Account)
- [ ] `module/User/view/user/session/login.phtml` - **Login page**
- [ ] `module/User/view/user/session/logout.phtml` - **Logout page**
- [ ] `module/User/view/user/account/registration.phtml` - **User registration**
- [ ] `module/User/view/user/account/registration-confirmation.phtml` - **Registration confirmation**
- [ ] `module/User/view/user/account/settings.phtml` - **User settings**
- [ ] `module/User/view/user/account/bookings.phtml` - **User bookings list**
- [ ] `module/User/view/user/account/bills.phtml` - **User bills**
- [ ] `module/User/view/user/account/password.phtml` - **Password change**
- [ ] `module/User/view/user/account/password-reset.phtml` - **Password reset**
- [ ] `module/User/view/user/account/activation.phtml` - **Account activation**
- [ ] `module/User/view/user/account/activation-resend.phtml` - **Resend activation**

### 📅 **MEDIUM PRIORITY** (Core Booking Functionality)
These handle the main booking and calendar functionality.

#### Calendar Module
- [ ] `module/Calendar/view/calendar/calendar/index.phtml` - **Main calendar view**
- [ ] `module/Calendar/view/calendar/calendar/index.landscape.phtml` - **Landscape calendar**
- [ ] `module/Calendar/view/calendar/calendar/index.portrait.phtml` - **Portrait calendar**

#### Square Module (Booking Interface)
- [ ] `module/Square/view/square/square/index.phtml` - **Square overview**
- [ ] `module/Square/view/square/square/index.free.phtml` - **Free square view**
- [ ] `module/Square/view/square/square/index.occupied.phtml` - **Occupied square view**
- [ ] `module/Square/view/square/square/index.own.phtml` - **Own booking view**
- [ ] `module/Square/view/square/booking/confirmation.phtml` - **Booking confirmation**
- [ ] `module/Square/view/square/booking/cancellation.phtml` - **Booking cancellation**
- [ ] `module/Square/view/square/booking/customization.phtml` - **Booking customization**

#### Event Module
- [ ] `module/Event/view/event/event/index.phtml` - **Event listing**

#### Payment Module
- [ ] `module/Payment/view/payment/stripe/index.phtml` - **Payment interface**

### 🛠️ **LOWER PRIORITY** (Admin Interface)
These are administrative interfaces used by staff/admins.

#### Backend Module - Dashboard
- [ ] `module/Backend/view/backend/index/index.phtml` - **Admin dashboard**
- [ ] `module/Backend/view/backend/index/dashboard.phtml` - **Dashboard widgets**

#### Backend Module - Booking Management
- [ ] `module/Backend/view/backend/booking/index.phtml` - **Booking list**
- [ ] `module/Backend/view/backend/booking/index.datepicker.phtml` - **Admin datepicker**
- [ ] `module/Backend/view/backend/booking/edit.phtml` - **Edit booking**
- [ ] `module/Backend/view/backend/booking/edit-choice.phtml` - **Edit booking choice**
- [ ] `module/Backend/view/backend/booking/edit-mode.phtml` - **Edit booking mode**
- [ ] `module/Backend/view/backend/booking/edit-range.phtml` - **Edit booking range**
- [ ] `module/Backend/view/backend/booking/delete.phtml` - **Delete booking**
- [ ] `module/Backend/view/backend/booking/delete.reservation.phtml` - **Delete reservation**
- [ ] `module/Backend/view/backend/booking/bills.phtml` - **Booking bills**
- [ ] `module/Backend/view/backend/booking/players.phtml` - **Booking players**
- [ ] `module/Backend/view/backend/booking/stats.phtml` - **Booking statistics**

#### Backend Module - Configuration
- [ ] `module/Backend/view/backend/config/index.phtml` - **Config overview**
- [ ] `module/Backend/view/backend/config/behaviour.phtml` - **Behavior settings**
- [ ] `module/Backend/view/backend/config/behaviour-rules.phtml` - **Behavior rules**
- [ ] `module/Backend/view/backend/config/behaviour-status-colors.phtml` - **Status colors**
- [ ] `module/Backend/view/backend/config/help.phtml` - **Help page**
- [ ] `module/Backend/view/backend/config/info.phtml` - **Info page**
- [ ] `module/Backend/view/backend/config/text.phtml` - **Text settings**

#### Backend Module - Square Configuration
- [ ] `module/Backend/view/backend/config-square/index.phtml` - **Square config list**
- [ ] `module/Backend/view/backend/config-square/edit.phtml` - **Edit square**
- [ ] `module/Backend/view/backend/config-square/edit-info.phtml` - **Edit square info**
- [ ] `module/Backend/view/backend/config-square/delete.phtml` - **Delete square**
- [ ] `module/Backend/view/backend/config-square/pricing.phtml` - **Square pricing**
- [ ] `module/Backend/view/backend/config-square/coupon.phtml` - **Square coupons**
- [ ] `module/Backend/view/backend/config-square/product.phtml` - **Square products**
- [ ] `module/Backend/view/backend/config-square/product-edit.phtml` - **Edit product**
- [ ] `module/Backend/view/backend/config-square/product-delete.phtml` - **Delete product**

#### Backend Module - Event Management
- [ ] `module/Backend/view/backend/event/index.phtml` - **Event list**
- [ ] `module/Backend/view/backend/event/index.datepicker.phtml` - **Event datepicker**
- [ ] `module/Backend/view/backend/event/edit.phtml` - **Edit event**
- [ ] `module/Backend/view/backend/event/edit-choice.phtml` - **Edit event choice**
- [ ] `module/Backend/view/backend/event/delete.phtml` - **Delete event**
- [ ] `module/Backend/view/backend/event/stats.phtml` - **Event statistics**

#### Backend Module - User Management
- [ ] `module/Backend/view/backend/user/index.phtml` - **User list**
- [ ] `module/Backend/view/backend/user/index.search.phtml` - **User search**
- [ ] `module/Backend/view/backend/user/edit.phtml` - **Edit user**
- [ ] `module/Backend/view/backend/user/delete.phtml` - **Delete user**
- [ ] `module/Backend/view/backend/user/stats.phtml` - **User statistics**

### 🔧 **SETUP & SERVICE** (System Pages)
These are used during installation and for system information.

#### Service Module
- [ ] `module/Service/view/service/service/help.phtml` - **Service help**
- [ ] `module/Service/view/service/service/info.phtml` - **Service info**
- [ ] `module/Service/view/service/service/status.phtml` - **Service status**

#### Setup Module
- [ ] `module/Setup/view/layout/layout.phtml` - **Setup layout**
- [ ] `module/Setup/view/error/404.phtml` - **Setup 404**
- [ ] `module/Setup/view/error/500.phtml` - **Setup 500**
- [ ] `module/Setup/view/setup/index/index.phtml` - **Setup main**
- [ ] `module/Setup/view/setup/index/complete.phtml` - **Setup complete**
- [ ] `module/Setup/view/setup/index/records.phtml` - **Setup records**
- [ ] `module/Setup/view/setup/index/tables.phtml` - **Setup tables**
- [ ] `module/Setup/view/setup/index/user.phtml` - **Setup user**

## Conversion Guidelines

### For Each File:
1. **Backup Original**: Keep a copy of the original file
2. **Include Tailwind**: Add Tailwind CSS link
3. **Apply Modern Design**: Use Tailwind utility classes
4. **Use Custom Components**: Leverage predefined components from `src/input.css`
5. **Ensure Responsiveness**: Test on mobile, tablet, and desktop
6. **Maintain Functionality**: Don't break existing PHP logic
7. **Test Thoroughly**: Verify all features work after conversion

### Design Patterns to Follow:
- **Mobile-First**: Start with mobile design, then scale up
- **Consistent Spacing**: Use Tailwind spacing scale (p-4, m-6, etc.)
- **Color Harmony**: Use the custom color palette
- **Typography**: Follow the typography guidelines
- **Accessibility**: Ensure ARIA labels and keyboard navigation

### Resources:
- `UI_LAYOUT_README.md` - Complete UI guidelines
- `TAILWIND_INTEGRATION.md` - Tailwind setup details
- `src/input.css` - Custom component definitions
- `tailwind.config.js` - Tailwind configuration

## Testing Checklist

For each converted file, verify:
- [ ] **Visual Design**: Matches modern UI standards
- [ ] **Responsiveness**: Works on all screen sizes
- [ ] **Functionality**: All PHP features work correctly
- [ ] **Performance**: Page loads quickly
- [ ] **Accessibility**: Screen reader friendly
- [ ] **Browser Compatibility**: Works in major browsers

## Notes

### Development Commands:
```bash
# Watch for changes during development
npm run build-css

# Build for production
npm run build-css-prod
```

### File Naming Convention:
- Keep original file names
- Add `.backup` extension for backups
- Document changes in git commits

### Common Patterns:
- Forms: Use `space-y-6` for field spacing
- Buttons: Use predefined `btn-booking` classes
- Cards: Use `card-booking` components
- Tables: Use responsive table patterns
- Navigation: Follow header patterns

---

**Start Date**: January 2025  
**Target Completion**: [Set your target date]  
**Last Updated**: January 2025

## Quick Start
1. Begin with the **CRITICAL PRIORITY** files
2. Test each file after conversion
3. Move to **HIGH PRIORITY** user-facing files
4. Continue with **MEDIUM PRIORITY** booking functionality
5. Finish with **LOWER PRIORITY** admin interfaces

Remember: Quality over speed. It's better to do fewer files well than to rush through many files with poor results.
