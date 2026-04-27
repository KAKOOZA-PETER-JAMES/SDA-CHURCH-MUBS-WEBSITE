# Technical Documentation

## 1. System Summary
This is a Laravel-based church management web system for:
- Registration and member records
- Admin-managed content publishing
- Real-time-like forum/chat features
- Email notification workflows
- Responsive public website pages

## 2. Technology Stack
- Backend: PHP + Laravel
- Frontend: Blade templates, vanilla JavaScript, CSS
- Database: Relational DB via Laravel migrations and Eloquent
- Email: SMTP (Brevo configured)
- Assets: Laravel Mix/Webpack setup

## 3. High-Level Architecture
- Routes layer handles public, forum, and admin endpoints.
- Controllers implement registration, dashboard, forum, and content retrieval logic.
- Models represent registrations, forum messages, and reactions.
- Blade views render pages and email templates.
- Admin content is managed through persistent content store and rendered into frontend pages.

## 4. Core Modules

### 4.1 Registration Module
Responsibilities:
- Validate and store registration data.
- Support admin profile shortcut login.
- Store session identity for UI personalization.
- Trigger confirmation and system emails.

Key components:
- Controller: RegistrationController
- Model: Registration
- Views: registration, registration-confirm, email templates

### 4.2 Admin Dashboard Module
Responsibilities:
- Content editing for multiple site sections.
- Forum moderation (single and bulk deletion).
- Optional bulk email update notifications to subscribed members.

Key components:
- Controller: AdminDashboardController
- View: admin-dashboard
- Content persistence: AdminContentStore

### 4.3 Forum Module
Responsibilities:
- Message creation, listing, edit, delete, reactions
- Attachments support
- Presence and typing indicators
- Token-based ownership checks

Key components:
- Controller: ForumController
- Models: ForumMessage, ForumMessageReaction
- View: forum

### 4.4 Layout and Navigation Module
Responsibilities:
- Shared header/navigation
- Notification menu
- Quick actions and member/admin badge
- Mobile dropdown behavior

Key component:
- View: layouts/site

### 4.5 Email Module
Templates:
- registration-confirmation
- system-registration-alert
- admin-update-notification

Mail classes:
- RegistrationConfirmation
- SystemRegistrationAlert
- AdminUpdateNotification

Config:
- `.env` mail settings
- `config/mail.php`

## 5. Data Model Notes
Likely primary entities:
- registrations
- forum_messages
- forum_message_reactions

Important registration fields:
- full_name
- email
- phone
- category
- wants_updates

## 6. Session and Access Patterns
- `registered_user_name` session key drives user badge/identity.
- `is_admin_dashboard` session key marks admin dashboard session.
- Admin UI controls and navigation links are conditionally rendered based on session state.

## 7. Routing Overview
Representative route groups:
- Public pages: home, ministry, media, events, beliefs, journey, updates
- Registration: create/store/edit/update/confirm/finalize
- Admin dashboard: index/store/logout + forum moderation endpoints
- Forum: stream/messages/store/update/delete/reactions/presence/typing/attachments

## 8. Frontend Behavior Notes
- International phone input uses intl-tel-input.
- Mobile navigation uses tap-friendly dropdown toggles.
- Chat UI supports local persistence for name and token.
- Some pages include dynamic JS rendering and progressive enhancements.

## 9. Deployment and Runtime
Typical Laravel runtime requirements:
- PHP compatible with project composer constraints
- Writable storage and bootstrap cache
- Database and mail credentials configured in `.env`

Common commands:
- `composer install`
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan serve`

## 10. Logging and Observability
- Laravel application logs in `storage/logs`.
- Email failures are captured in logs where try/catch exists.
- For debugging mail without sending, switch mailer to log in `.env`.

## 11. Security Considerations
- Protect `.env` secrets (SMTP credentials, app key).
- Ensure CSRF protection remains enabled on forms/actions.
- Validate all user input at controller boundary.
- Restrict admin actions to authenticated/authorized admin session checks.

## 12. Maintenance Guidelines
- Run regression checks after UI or route changes.
- Keep email templates consistent with branding updates.
- Monitor forum moderation behavior after JS changes.
- Keep dependencies updated and test after upgrades.

## 13. Suggested Future Improvements
- Add role-based auth instead of profile-matching admin login.
- Introduce automated tests for critical flows.
- Add queue-based email sending for scale.
- Extract inline page JS into modular assets for maintainability.
