# AvA Club Snippet — Smart Admin Dashboard for HTML Snippets

## 🌍 Overview
**Goal:** Build a modern, dark-themed admin dashboard that allows the owner to control and monitor HTML pages where a small snippet code is embedded.  
The system should let the admin:
- Generate unique snippet codes to add to any HTML page.
- Track visitors with accurate analytics (IP, country, region, device, OS, bot detection).
- Manage redirects, timed forwards, and downloadable file links.
- Control max downloads, expiration, and redirect rules.
- Verify snippet presence in external HTML pages.
- Manage everything from a **dark neon-styled dashboard** with analytics and settings.
- Include an **automatic installation wizard** that runs on first setup (for cPanel or shared hosting).

---

## 🖥️ Tech Stack
**Frontend:** React + TailwindCSS + TypeScript  
**Backend:** Node.js (Express + TypeScript)  
**Database:** PostgreSQL or MySQL (auto-detect via setup wizard)  
**Cache:** Redis (optional, for download counters)  
**Auth:** JWT + bcrypt  
**Deploy:** Docker (optional) or CPanel ZIP installer  
**Analytics Enrichment:** IP Geolocation API (via env key)  
**Notifications (optional):** Telegram Bot API  

---

## 🎨 Design & UX
- **Theme:** Dark mode with glowing neon borders and accents  
- **Primary Colors:**
  - Background: `#0a0a0a`
  - Neon Accent: `#00ffff`
  - Secondary: `#ff007f`
- **UI Elements:**
  - Glassmorphism panels (`backdrop-blur`, `bg-opacity-30`)
  - Rounded corners (`rounded-2xl`)
  - Neon outlines (`shadow-[0_0_10px_#00ffff]`)
  - Hover transitions with glow
- **Dashboard pages:**
  1. Login / Register  
  2. Overview  
  3. Pages (with snippet code + verification)  
  4. Visitors Analytics  
  5. Download Manager  
  6. Notifications (Telegram)  
  7. Global Settings  

---

## ⚙️ Features
### 🔑 Authentication
- Secure register/login with bcrypt password hashing
- JWT authentication with refresh tokens
- Role management (admin/viewer)

### 📄 Page Management
- Add tracked pages with: Title, Domain, URL, Snippet Token
- Redirects (delay + target URL)
- “Check snippet” button to verify embedding
- Edit or delete existing pages

### 🧩 Snippet Code
Lightweight JS snippet to embed:
```html
<script>
(function(){
  var t="SITE_TOKEN";
  var s=document.createElement("script");
  s.async=true;
  s.src="https://yourdomain.com/static/snippet.js";
  s.onload=function(){
    window.SnippetControl && SnippetControl.init({site:t});
  };
  document.head.appendChild(s);
})();
</script>
```
- Sends visitor data via `/api/event`
- Handles redirects, link expiry, and file downloads
- Detects OS, device, and bots

### 👥 Visitor Tracking
- Tracks:
  - IP (hashed)
  - Country, region, city
  - Device type, browser, OS
  - Timestamp
- Filters & charts by country/device/date
- CSV export support

### 📥 Download Links
- Multiple downloadable URLs
- Max downloads, expiry date/time
- Redirect after download
- Auto-disable expired links

### ⏰ Redirect Control
- Per-page delay + redirect target
- Editable in dashboard

### 🔔 Notifications
- Telegram alerts for:
  - New visitors
  - Downloads reaching limit

### 🧠 Analytics Dashboard
- Overview cards:
  - Total Pages
  - Total Visitors
  - Active Downloads
  - Expired Links
- Interactive charts

---

## 🧱 API Endpoints
| Method | Route | Description |
|--------|--------|-------------|
| POST | /auth/register | Register admin |
| POST | /auth/login | Login user |
| GET | /api/sites | List sites |
| POST | /api/sites | Add site |
| GET | /api/sites/:id/snippet | Get snippet |
| POST | /api/sites/:id/verify | Check snippet |
| GET | /api/sites/:id/visitors | List visitors |
| POST | /api/event | Visitor beacon |
| GET | /dl/:id | Handle secure download |
| POST | /api/sites/:id/downloads | Add download |
| PATCH | /api/sites/:id/downloads/:id | Edit download |
| POST | /api/telegram-config | Add Telegram config |
| GET | /api/stats | Dashboard metrics |

---

## 🧩 Database Schema
- **users** — id, email, password_hash, role  
- **sites** — id, title, domain, url, token, redirect_url, delay, active  
- **visitors** — id, site_id, ip_hash, country, os, device, is_bot, created_at  
- **downloads** — id, site_id, url, max_downloads, count, expiry, redirect_url  
- **settings** — id, key, value  

---

## 🔐 Security
- HTTPS enforced  
- Bcrypt hashing  
- Input validation  
- Rate limiting  
- Optional IP anonymization  
- Secure cookie handling  

---

## 🧪 Tests
- **Jest** for backend  
- **Cypress** for frontend  
Tests for:
- Auth flow  
- Snippet verification  
- Visitor tracking  
- Download expiration  
- Redirect logic  

---

## 🚀 Deployment
### Docker Option
- `docker-compose up --build` runs backend, frontend, Postgres, and Redis.  
- `.env.example` with required environment keys.  

### CPanel / Shared Hosting Option
Includes a **Web Installation Wizard** (see below).

---

## 🧰 Installation Wizard (Installer System)
A built-in `install.php` or `install.js` (depending on hosting type) should run when the app is first uploaded and accessed.

### 🪄 How It Works
When the admin visits the site for the first time, it automatically redirects to `/install`.

#### Page 1: Welcome
- Shows logo + message:
  > “Welcome to SnippetControl Setup! Let’s get your dashboard online.”
- Button: **Start Installation**

#### Page 2: Server Requirements Check
- Check ✅ PHP/Node version  
- Check ✅ Writable folders  
- Check ✅ Database connection possible  
- If missing dependencies → show ❗ with instructions

#### Page 3: Database Setup
Form fields:
- DB Host  
- DB Name  
- DB Username  
- DB Password  
- DB Type (PostgreSQL/MySQL)  
After submission:
- Automatically write to `.env`  
- Run migrations  
- Show ❗ messages:
  - ❗ “Please make sure your database exists before proceeding.”
  - ❗ “Contact your hosting provider if unsure about DB credentials.”

#### Page 4: Create Admin Account
Form:
- Email  
- Password  
- Confirm Password  
After saving:
- Creates initial user and JWT secret

#### Page 5: App Configuration
Form for:
- Site URL  
- Telegram Bot Token (optional)  
- Geo API Key (optional)  
- Enable Redis cache (yes/no)  
After saving → update `.env`

#### Page 6: Finish
- Show:
  - ✅ Installation complete
  - Admin login URL (e.g., `/admin`)
  - Credentials summary
- Button: **Go to Dashboard**

#### Extra Features
- Lock installer after completion (create `.installed` flag file)
- If `.installed` exists → auto-redirect to `/admin/login`
- Include uninstall/reset option in settings.

---

## 📜 Installer File Details
**Installer Folder:** `/installer`  
**Main File:** `install.js` or `install.php`  
**Purpose:** Allow quick setup on shared hosts (like cPanel).  

**Key Notes with “❗” warnings for users:**
1. ❗ Before installation, create your database manually in cPanel’s MySQL Wizard.  
2. ❗ Upload all files from the ZIP to your hosting root (usually `/public_html`).  
3. ❗ Make sure file permissions allow writing `.env` and migrations.  
4. ❗ If you see “permission denied,” set folder permissions to 755.  
5. ❗ After successful installation, **delete the `/installer` folder for security.**

---

## 🧭 Deliverables
- `/backend` — Express + Prisma backend  
- `/frontend` — React + Tailwind dark neon UI  
- `/installer` — Web setup wizard (PHP or Node)  
- `/migrations` — DB scripts  
- `.env.example` — Variables template  
- `docker-compose.yml`  
- Tests folder  
- README.md — with manual + cPanel setup guide  

---

## ✅ Acceptance
- Works on both Docker and cPanel  
- First-time access triggers installer wizard  
- Installer writes `.env` and migrates DB  
- Admin created successfully  
- Snippet verified and logs visitors  
- Dashboard uses dark neon design  
- Redirects, downloads, analytics, notifications all work
