# Testing Documentation

## 1. Purpose
This document defines how to test the SDA CHURCH MUBS system end-to-end, including registration, admin dashboard, forum/chat, mobile navigation, email notifications, and content pages.

## 2. Scope
The following modules are in scope:
- Public pages (Home, Ministry, Media, Events, About, Updates, Our Journey, Our Beliefs)
- Registration flow and confirmation flow
- Admin authentication via registration profile
- Admin dashboard content management and forum moderation
- Live forum/chat
- Notification/menu behavior on mobile and desktop
- Email notification pipeline (member, system/admin, update subscribers)

## 3. Test Environments
- Local: PHP built-in server or Laravel serve
- Browser set: Chrome, Edge, Firefox
- Mobile: Chrome DevTools device emulation + at least one physical phone
- Mail provider: Brevo SMTP (or log driver for local testing)

## 4. Prerequisites
- PHP dependencies installed: `composer install`
- JS dependencies installed (if needed): `npm install`
- Environment configured: `.env`
- App key generated: `php artisan key:generate`
- Database migrated: `php artisan migrate`
- Server running: `php artisan serve`

## 5. Test Data
Use at least these test personas:
- Member with updates enabled
- Member with updates disabled
- Admin profile:
  - Full Name: SDA CHURCH MUBS
  - Email: mubssdachurch@gmail.com

## 6. Functional Test Cases

### 6.1 Registration
1. Open registration page.
2. Submit valid member data.
3. Verify redirect to confirmation and successful save.
4. Verify user badge appears in navbar after registration.
5. Verify forum name auto-fills from registered name.
Expected:
- Record saved in registrations table.
- Success message shown.
- Registered identity reflected in UI.

### 6.2 Admin Access
1. Enter admin profile credentials on registration form.
2. Submit form.
Expected:
- Admin dashboard opens.
- Admin name/badge appears in navbar.
- Clicking admin name/badge opens dashboard.

### 6.3 Admin Content Save
1. Update fields in multiple admin panels.
2. Save dashboard content.
Expected:
- Changes persisted.
- Success feedback shown.
- Frontend pages reflect updates.

### 6.4 Admin Forum Moderation
1. Open admin dashboard forum section.
2. Delete one message.
3. Delete all messages.
Expected:
- No MethodNotAllowed errors.
- Message count updates.
- Removed content no longer appears in forum.

### 6.5 Forum/Chat
1. Post text message.
2. Post message with attachment.
3. Reply to message.
4. Edit own message.
5. Delete own message.
6. React to message.
Expected:
- Messages sync and render correctly.
- Presence/typing indicators update.
- Attachment open/download links work.

### 6.6 Mobile Navigation
1. Open menu on small viewport.
2. Tap Ministry, Media, Events, About.
Expected:
- Opens on single tap.
- No double-tap required.

### 6.7 Our Beliefs Mobile Layout
1. Open Our Beliefs on small viewport.
2. Inspect top hero card text visibility.
Expected:
- Full heading and paragraph visible.
- No clipping/truncation.

### 6.8 Phone Input UX
1. Check registration phone field alignment with neighboring fields.
2. Check country dropdown availability.
3. Check admin phone inputs for country dropdown.
Expected:
- Layout aligned.
- Country dropdown visible and selectable.

### 6.9 Email Notifications
1. Register member.
2. Verify registration confirmation email to member.
3. Verify system alert email to admin/system recipient.
4. Publish admin update.
5. Verify update emails only to opted-in members.
Expected:
- Correct recipients and content.
- Branded header appears in member emails.

## 7. Non-Functional Testing

### 7.1 Responsiveness
- Verify major pages at 360px, 390px, 768px, 1024px, desktop.
- Confirm no horizontal overflow or clipped critical actions.

### 7.2 Basic Performance
- Measure first load and interaction responsiveness on mid-range mobile.
- Ensure no severe lag in forum render/update cycles.

### 7.3 Accessibility Smoke
- Keyboard navigation for core controls.
- Focus visibility on buttons/links.
- ARIA labels present for modal/menus where applicable.

## 8. Regression Checklist
Run after each major change:
- Registration submit + confirmation
- Admin login + dashboard save
- Chat post/edit/delete/reactions
- Mobile menu one-tap open
- Email send flows
- Homepage and About pages visual sanity

## 9. Defect Template
Use this format for bugs:
- ID
- Title
- Environment
- Preconditions
- Steps to Reproduce
- Actual Result
- Expected Result
- Severity
- Attachments (screenshots/logs)

## 10. Exit Criteria
Testing cycle passes when:
- All critical and high defects are resolved.
- Core journeys (registration, admin, forum, email) pass in target browsers.
- Mobile UX issues are not blocking normal usage.

