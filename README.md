# AvA Club Snippet - Smart Admin Dashboard

Modern dark-neon control center for tracking HTML snippet embeds, visitors, downloads, and instant notifications.

## Project Structure

`
backend-php/     Lightweight PHP 8 API (PDO + custom router)
frontend/        React + Tailwind dashboard (dark neon UI)
installer/       Shared-host friendly installation wizard
migrations/      Legacy SQL scripts (Node version)
tests/           Cypress/Jest scaffolding (legacy)
.env.example     Legacy Node env template (kept for reference)
`

## Quick Start (PHP Stack)

### Requirements
- PHP 8.0+
- MySQL 5.7+/MariaDB 10+
- npm (for building the React frontend)
- Apache/Nginx with URL rewriting (Apache on cPanel works out of the box)

### Local Preview
1. Duplicate ackend-php/.env.example to ackend-php/.env and set MySQL credentials plus SITE_URL.
2. Run migrations: php backend-php/scripts/migrate.php
3. Seed an admin user: php backend-php/scripts/seed-admin.php admin@example.com ChangeMe123!
4. Build the dashboard: cd frontend && npm install && npm run build
5. Serve locally (one option): php -S localhost:8000 index.php and open http://localhost:8000.

### Installer Flow (cPanel / Shared Hosting)
- Upload the entire project (including ackend-php/, rontend/dist, .htaccess, index.php, and installer/) to your hosting root (e.g. /public_html).
- Visit https://yourdomain.com/installer/install.php.
- Wizard steps:
  1. Welcome
  2. Requirement check (PHP, PDO MySQL, permissions)
  3. Database credentials
  4. Admin account creation
  5. App configuration (SITE_URL, optional API keys)
  6. Finish & lock (.installed created)
- The installer writes ackend-php/.env, runs migrations, and seeds the admin user automatically. Remove /installer afterwards for security.

## Key Features
- JWT authentication with refresh tokens and role enforcement
- Page & snippet management with verification checks
- Visitor analytics enriched via optional Geo IP lookups
- Download manager with limits, expirations, and redirect support
- Telegram notifications for new visitors / download milestones
- Dark neon dashboard with glassmorphism UI components

## Snippet Embed
`html
<script>
(function(){
  var t = 'SITE_TOKEN';
  var s = document.createElement('script');
  s.async = true;
  s.src = 'https://yourdomain.com/static/snippet.js';
  s.onload = function(){
    window.SnippetControl && window.SnippetControl.init({ site: t });
  };
  document.head.appendChild(s);
})();
</script>
`

## Deployment Notes
- Ensure ackend-php/.env contains the correct MySQL host (usually localhost on cPanel) and URL-encoded passwords (replace @ with %40).
- index.php routes API traffic to ackend-php/public/index.php and serves the React build from rontend/dist.
- Re-run php backend-php/scripts/migrate.php after schema updates.
- To reset the installer, delete .installed (not recommended in production) and revisit /installer/install.php.

## Repository Setup & Git Flow
1. Create a remote repository (GitHub, GitLab, etc.) and note the HTTPS/SSH URL.
2. A .gitignore is included to skip 
ode_modules, build artifacts, and secrets.
3. Initial push:
   `ash
   git init
   git add .
   git commit -m "Initial PHP backend + React dashboard"
   git branch -M main
   git remote add origin <your-repo-url>
   git push -u origin main
   `
4. Cloning on a new server:
   `ash
   git clone <your-repo-url> ava-snippet
   cd ava-snippet
   cp backend-php/.env.example backend-php/.env   # fill credentials
   php backend-php/scripts/migrate.php
   php backend-php/scripts/seed-admin.php admin@example.com StrongPass123!
   cd frontend && npm install && npm run build
   `
   Upload (or deploy) the resulting folder to your host, leaving 
ode_modules behind.

## Legacy Assets
The original Node/TypeScript backend and Docker configuration remain for reference but are no longer needed for the PHP/cPanel deployment.
