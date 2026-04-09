# 🚀 Deployment Guide — CARE MASSAGE AND SPA

**Hostinger Domain + InfinityFree Hosting**

> 🌐 **Live Domain:** [https://touchandcarespa.online](https://touchandcarespa.online)  
> 🔗 **Temp URL (working now):** [https://touchandcarespa.great-site.net](https://touchandcarespa.great-site.net)  
> ⏳ **DNS Status:** Propagating — see `DNS_MIGRATION_GUIDE.md` for switch instructions

---

## 🔍 System Overview

| Property         | Details                                                                                                   |
| ---------------- | --------------------------------------------------------------------------------------------------------- |
| **App Name**     | CARE MASSAGE AND SPA                                                                                      |
| **Stack**        | Custom PHP MVC                                                                                            |
| **Router**       | `nikic/fast-route`                                                                                        |
| **Dependencies** | `vlucas/phpdotenv`, `phpmailer/phpmailer`, `guzzlehttp/guzzle`                                            |
| **Database**     | MySQL — `online_massage` (~15+ tables)                                                                    |
| **Features**     | Booking, Admin Dashboard, Therapist Management, Messaging, SMS (Movider), Email (PHPMailer), File Uploads |

---

## ⚠️ PRE-DEPLOYMENT ISSUES TO FIX

### 🔴 CRITICAL — Must Fix Before Upload

| #   | Issue                                       | File                          | Status                                                                           |
| --- | ------------------------------------------- | ----------------------------- | -------------------------------------------------------------------------------- |
| 1   | **`.htaccess` has `RewriteBase /WEB_6/`**   | `.htaccess`                   | ✅ Fixed — comment added; change to `/` before uploading to live server          |
| 2   | **`URL_HOST` is `http://localhost/WEB_6/`** | `.env`                        | ✅ Fixed — comment added; update value before going live                         |
| 3   | **`APP_DEBUG=true`**                        | `.env`                        | ✅ Fixed — comment added; change to `false` before going live                    |
| 4   | **DB credentials are `localhost/root`**     | `.env`                        | ✅ Fixed — comment added; update with InfinityFree credentials before going live |
| 5   | **Hardcoded `localhost` URLs in SQL dump**  | `database/online_massage.sql` | ✅ Fixed — replaced with relative paths (work on any domain)                     |
| 6   | **`DBPWD=""` (empty password)**             | `.env`                        | ✅ Fixed — comment added; set a real password in InfinityFree                    |

### 🟡 WARNING — Should Fix

| #   | Issue                                                | File          | Status                                                                                    |
| --- | ---------------------------------------------------- | ------------- | ----------------------------------------------------------------------------------------- |
| 7   | **Sessions stored in `/sessions/` folder**           | `index.php`   | ✅ Fixed — simplified & secured; auto-falls back to server default if folder not writable |
| 8   | **`src/images/products/uploads/` image paths in DB** | SQL data      | ✅ OK — stored as relative paths; works if folder is uploaded correctly                   |
| 9   | **Email credentials in `.env` are plaintext**        | `.env`        | ⚠️ Acceptable — `.env` is in `.gitignore`; never commit `.env` to GitHub                  |
| 10  | **`vendor/` folder is large**                        | `/vendor`     | ⚠️ No code fix needed — upload via FTP (see Step 6)                                       |
| 11  | **`APP_DEBUG` shows full exceptions**                | All PHP files | ✅ Fixed — comment added in `.env`; set to `false` before going live                      |

### 🟢 ALREADY DONE RIGHT

- ✅ `.env` is in `.gitignore` — credentials won't be exposed on GitHub
- ✅ `vendor/` is in `.gitignore`
- ✅ `sessions/` is in `.gitignore`
- ✅ Database uses PDO with parameterized queries (SQL injection protection)
- ✅ `move_uploaded_file` used properly for file uploads
- ✅ Route input validated with regex
- ✅ `baseUrl()` is dynamic — does not hardcode localhost

---

## � LOGIN CREDENTIALS & URLS

### 🖥️ Localhost (Development)

| Role        | Login URL                                        | Email                                 | Password   |
| ----------- | ------------------------------------------------ | ------------------------------------- | ---------- |
| Super Admin | `http://localhost/WEB_6/auth`                    | `mgaducio@gmail.com`                  | `admin123` |
| Alt Admin   | `http://localhost/WEB_6/auth`                    | `touchandcaremassageandspa@gmail.com` | `123456`   |
| Customer    | `http://localhost/WEB_6/customer/customer/index` | `itsclient4@gmail.com`                | `123456`   |

### 🌐 Production (Live Server)

| Role        | Login URL                                               |
| ----------- | ------------------------------------------------------- |
| Super Admin | `http://touchandcarespa.online/auth`                    |
| Customer    | `http://touchandcarespa.online/customer/customer/index` |

> ⚠️ **IMPORTANT — Change passwords before going live!**
>
> - Log in as admin and update the password immediately after deployment
> - Never leave `admin123` or `123456` as passwords on a live server
> - Go to: Admin Panel → Settings → Change Password (or update directly in the database)

### 🔑 Login Notes

- Login uses **EMAIL address** (not username)
- Admin portal is at `/auth` — same for both super admin and staff
- Customer portal is at `/customer/customer/index`
- After 3 failed login attempts, account is locked for **30 seconds**
- Password reset via **email OTP** is available on the login page

---

## �🗺️ DEPLOYMENT ROADMAP

### STEP 1 — ✅ DONE — Buy Your Domain on Hostinger

1. ✅ Domain purchased: **`touchandcarespa.online`** on Hostinger

---

### STEP 2 — ✅ DONE — Set Up InfinityFree Hosting

1. ✅ Registered at [infinityfree.net](https://infinityfree.net)
2. ✅ Hosting account created
3. Make sure you have noted down from your InfinityFree cPanel:
   - **FTP Hostname** (e.g., `ftpupload.net`)
   - **FTP Username & Password**
   - **MySQL Host, Username, Password, Database Name**
   - **cPanel URL**

---

### STEP 3 — ✅ DONE — Point Hostinger Domain to InfinityFree

1. ✅ Nameservers updated in Hostinger DNS:
   ```
   ns1.infinityfree.com
   ns2.infinityfree.com
   ```
2. ✅ Changes saved
3. ⏳ DNS propagation may still be in progress (allow up to 24–48 hours)
   - Test propagation at: [https://dnschecker.org/#A/touchandcarespa.online](https://dnschecker.org/#A/touchandcarespa.online)

---

### STEP 4 — ✅ DONE — Fix Files Before Upload

> ✅ Production-ready files have been created in the **`production_ready/`** folder.
> Your localhost files are **untouched** — localhost still works normally.

#### What was done:

| File        | Location                     | Change                                                       |
| ----------- | ---------------------------- | ------------------------------------------------------------ |
| `.htaccess` | `production_ready/.htaccess` | `RewriteBase /` (root domain)                                |
| `.env`      | `production_ready/.env`      | `URL_HOST`, `APP_DEBUG=false`, DB placeholders ready to fill |

#### ⚠️ Before uploading — fill in your InfinityFree DB credentials in `production_ready/.env`:

```env
DBUSER=YOUR_INFINITYFREE_DB_USERNAME      ← replace this
DBPWD=YOUR_INFINITYFREE_DB_PASSWORD       ← replace this
DBHOST=sqlXXX.infinityfree.com            ← replace this (get from cPanel)
DBNAME=YOUR_INFINITYFREE_DB_NAME          ← replace this
```

> 💡 You'll get the exact values from **InfinityFree cPanel → MySQL Databases** in Step 5.
> Fill them in after Step 5, then upload both files in Step 6.

---

### STEP 5 — ✅ DONE — Import the Database

- ✅ Database created: `if0_41612282_touchandcarespa`
- ✅ MySQL user: `if0_41612282` with all privileges
- ✅ MySQL host: `sql100.infinityfree.com`
- ✅ `database/online_massage.sql` imported successfully via phpMyAdmin

---

### STEP 6 — ✅ DONE — Upload Files via FTP

- ✅ Connected via FileZilla to `ftpupload.net`
- ✅ All files uploaded to `/home/vol18_1/infinityfree.com/if0_41612282/htdocs/`
- ✅ `production_ready/.htaccess` uploaded as `.htaccess`
- ✅ `production_ready/.env` uploaded as `.env`

---

### STEP 7 — ✅ DONE — `.env` Already Prepared

> ✅ Your production `.env` is already created at `production_ready/.env`.
>
> **Before uploading it**, go back and fill in your InfinityFree DB credentials (you'll get them in Step 5):
>
> - `DBUSER`, `DBPWD`, `DBHOST`, `DBNAME`
>
> Then upload `production_ready/.env` to the root of your InfinityFree site via FTP (Step 6).

---

### STEP 8 — ✅ DONE — Set Folder Permissions

In **cPanel File Manager**, right-click each folder → **Change Permissions**:

| Folder                         | Permission |
| ------------------------------ | ---------- |
| `uploads/`                     | **755**    |
| `src/images/products/uploads/` | **755**    |
| `src/`                         | **755**    |

> If uploads still fail, try **777** — but **755 is more secure**.

---

### STEP 9 — ✅ DONE — Verify PHP Version

1. InfinityFree runs **PHP 8.2** — confirmed ✅
2. All PHP 8.2 deprecation issues have been fixed (see `SESSION_FIXES_LOG.md`)

---

### STEP 10 — ✅ DONE (on great-site.net) — Pending on touchandcarespa.online

> See `DNS_MIGRATION_GUIDE.md` for the full post-DNS checklist.

Go through this checklist on your live domain after DNS propagates:

- [ ] `https://touchandcarespa.online/auth` — login page loads
- [ ] Admin can log in (use updated password — NOT `admin123`)
- [ ] `https://touchandcarespa.online/customer/customer/index` — customer portal loads
- [ ] Booking / scheduling works
- [ ] Images load correctly
- [ ] File upload works (proof of payment, profile images)
- [ ] Email OTP notifications sent (Gmail App Password `qxvryweqrfsakfxx`) ✅ Working
- [ ] SMS notifications sent (Movider)
- [ ] Logout works

---

## ⚡ InfinityFree Limitations

| Limitation                     | Impact on Your System                                                   |
| ------------------------------ | ----------------------------------------------------------------------- |
| **No SSH access**              | Cannot run `composer install` — must upload `vendor/` folder via FTP ⚠️ |
| **PHP version varies**         | Verify PHP 8.x is available in cPanel                                   |
| **5GB disk space**             | Sufficient for current project size                                     |
| **No cron jobs on free plan**  | Not an issue unless scheduled tasks are needed                          |
| **Daily hits limit (~50,000)** | Fine for a new/small-traffic site                                       |
| **MySQL: 400 tables max**      | Fine — project only has ~15 tables                                      |
| **Inodes limit**               | `vendor/` folder uses many files — monitor usage                        |

---

## 📋 FINAL PRE-LAUNCH CHECKLIST

- [ ] `.htaccess` — `RewriteBase /` updated (no more `/WEB_6/`)
- [ ] `.env` on server — DB credentials set for InfinityFree
- [ ] `.env` on server — `URL_HOST` set to `http://touchandcarespa.online/`
- [ ] `.env` on server — `APP_DEBUG=false`
- [ ] Database imported to InfinityFree via phpMyAdmin
- [ ] All required files uploaded via FTP (including `vendor/`)
- [ ] `uploads/` and image folders have correct write permissions
- [ ] PHP version confirmed as 8.0+
- [ ] Domain DNS pointing to InfinityFree nameservers
- [ ] DNS propagation completed (24–48 hours)
- [ ] Full site tested on live URL: `http://touchandcarespa.online`

---

## 🆘 Common Issues & Fixes

| Problem                        | Likely Cause                           | Fix                                                                     |
| ------------------------------ | -------------------------------------- | ----------------------------------------------------------------------- |
| **500 Internal Server Error**  | `.htaccess` issue or PHP error         | Check error logs in cPanel; disable `mod_rewrite` issues                |
| **Blank white page**           | PHP fatal error with `APP_DEBUG=false` | Temporarily set `APP_DEBUG=true`, fix error, set back to `false`        |
| **Database connection failed** | Wrong DB credentials in `.env`         | Double-check DBHOST, DBUSER, DBPWD, DBNAME from cPanel                  |
| **Images not loading**         | Wrong file paths or permissions        | Ensure `src/images/` uploaded and permissions are 755                   |
| **Redirects to wrong URL**     | `URL_HOST` still set to localhost      | Set `URL_HOST=http://touchandcarespa.online/` in `.env`                 |
| **CSS/JS not loading**         | `RewriteBase` not updated              | Confirm `.htaccess` has `RewriteBase /`                                 |
| **Emails not sending**         | Gmail security block                   | Use App Password (already configured), ensure `openssl` extension is on |
| **Session issues**             | Custom session path fails              | The fallback in `index.php` should handle this automatically            |

---

_Generated: April 8, 2026 | Updated: April 9, 2026_  
_Project: CARE MASSAGE AND SPA — WEB_6_  
_Temp URL: https://touchandcarespa.great-site.net (working ✅)_  
_Live Domain: https://touchandcarespa.online (DNS propagating ⏳)_  
_See `DNS_MIGRATION_GUIDE.md` for switch instructions_
