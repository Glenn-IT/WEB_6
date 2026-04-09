# 🛠️ Troubleshoot & Changes Log — CARE MASSAGE AND SPA

**Project:** WEB_6 — Touch and Care Massage and Spa  
**Date:** April 8–9, 2026  
**Developer:** Glenn-IT  
**Live Domain:** https://touchandcarespa.online ⏳ DNS propagating  
**Temp URL (working):** https://touchandcarespa.great-site.net ✅

---

## 📋 SUMMARY OF ALL CHANGES MADE

---

## 🔴 PHASE 1 — Pre-Deployment Code Fixes

### 1. `.htaccess` — RewriteBase Fix

- **Problem:** `RewriteBase /WEB_6/` was hardcoded — would break routing on live server
- **Fix:** Added LOCALHOST / PRODUCTION comment block so it's easy to switch
- **Local file:** `c:\xampp\htdocs\WEB_6\.htaccess`
- **Production file:** `production_ready/.htaccess` — uses `RewriteBase /`

```apache
# LOCAL:
RewriteBase /WEB_6/

# PRODUCTION (in production_ready/.htaccess):
RewriteBase /
```

---

### 2. `.env` — All Hardcoded Localhost Values Annotated

- **Problem:** `URL_HOST`, `APP_DEBUG`, `DBUSER`, `DBPWD`, `DBHOST`, `DBNAME` were all set for localhost only
- **Fix:** Added `[LOCALHOST]` / `[PRODUCTION]` inline comments on every value that needs to change
- **File:** `.env`

---

### 3. `database/online_massage.sql` — Hardcoded URLs Removed

- **Problem:** 11 rows in the `message` table contained hardcoded `http://localhost/WEB_6/` URLs
- **Fix:** Replaced all 11 with relative paths (e.g., `customer/customer/index?page=productItem&id=39`)
- **File:** `database/online_massage.sql`

---

### 4. `index.php` — Session Handling Fixed + Debug Mode Added

- **Problem 1:** Session folder used `0777` permissions (insecure), had duplicate `session_start()` calls
- **Fix 1:** Changed to `0755`, single `@session_start()` with proper `is_writable()` check
- **Problem 2:** `APP_DEBUG=true` in `.env` had no effect — PHP errors were never displayed
- **Fix 2:** Added early `.env` reader at the top of `index.php` that sets `display_errors` based on `APP_DEBUG` value
- **File:** `index.php`

```php
// Now reads APP_DEBUG from .env BEFORE autoload
// true  → shows PHP errors (development)
// false → hides PHP errors (production)
```

---

### 5. `production_ready/` Folder — Created

- **Purpose:** Holds production-ready versions of files that differ from localhost
- **Contents:**
  - `production_ready/.htaccess` — `RewriteBase /`
  - `production_ready/.env` — live domain, real DB credentials, `APP_DEBUG=false`
  - `production_ready/README.txt` — instructions
- **Rule:** These files get uploaded to server; local files stay untouched

---

## 🟡 PHASE 2 — Deployment Steps Completed

### Step 1 ✅ — Domain Purchased

- Domain: `touchandcarespa.online` bought on **Hostinger**

### Step 2 ✅ — InfinityFree Hosting Set Up

- Account: `if0_41612282`
- Home dir: `/home/vol18_1/infinityfree.com/if0_41612282`
- Hosting volume: `vol18_1`

### Step 3 ✅ — DNS Pointed to InfinityFree

- Hostinger nameservers changed to:
  ```
  ns1.infinityfree.com
  ns2.infinityfree.com
  ```
- ⏳ DNS propagation still in progress (up to 24–48 hours)

### Step 4 ✅ — Production Files Prepared

- `production_ready/.htaccess` created
- `production_ready/.env` filled with real credentials:
  ```
  DBUSER=if0_41612282
  DBPWD=gRseIvmismLy
  DBHOST=sql100.infinityfree.com
  DBNAME=if0_41612282_touchandcarespa
  URL_HOST=http://touchandcarespa.online/
  APP_DEBUG=false
  ```

### Step 5 ✅ — Database Imported

- Database: `if0_41612282_touchandcarespa`
- Imported via **phpMyAdmin** from `database/online_massage.sql`
- MySQL host: `sql100.infinityfree.com`

### Step 6 ✅ — Files Uploaded via FTP (FileZilla)

- FTP host: `ftpupload.net`
- Username: `if0_41612282`
- Destination: `/home/vol18_1/infinityfree.com/if0_41612282/htdocs/`
- Uploaded: all project folders + `production_ready/.htaccess` and `.env`

---

## 🔴 PHASE 3 — Live Server Troubleshooting

---

### Issue 1 — HTTP 500 Error on First Load

- **Symptom:** `HTTP ERROR 500` on `http://touchandcarespa.great-site.net/`
- **Root Cause:** `APP_DEBUG=false` was silencing all errors; actual crash was invisible
- **Fix:** Added early debug reader in `index.php` (see Phase 1, Fix #4)
- **Result:** After re-uploading `index.php` and setting `APP_DEBUG=true`, errors became visible

---

### Issue 2 — PHP 8.2 Deprecated: Dynamic Property Warnings

- **Symptom:**
  ```
  Deprecated: Creation of dynamic property UserController::$componentHelper is deprecated
  Deprecated: Creation of dynamic property CustomerController::$view is deprecated
  ```
- **Root Cause:** InfinityFree runs **PHP 8.2** — dynamic properties (`$this->view = ...` without declaration) are deprecated in PHP 8.1+ and will error in PHP 9
- **Files Fixed (15 controllers total):**

| Controller                     | Property Added                      |
| ------------------------------ | ----------------------------------- |
| `UserController`               | `protected $componentHelper;`       |
| `CustomerController`           | `protected $view;`                  |
| `UserArchiveController`        | `protected $view;`                  |
| `UserManagementController`     | `protected $view;`                  |
| `TransactionBiddingController` | `protected $view;`                  |
| `TransactionOrderController`   | `protected $view;`                  |
| `TransactionLoanController`    | `protected $view;`                  |
| `CustomerManagementController` | `protected $view;`                  |
| `DashboardController`          | `protected $Name; protected $view;` |
| `InventoryReportController`    | `protected $view;`                  |
| `TherapistController`          | `protected $view;`                  |
| `SizeTypeController`           | `protected $view;`                  |
| `SettingsController`           | `protected $view;`                  |
| `MessagesController`           | `protected $view;`                  |
| `InventoryStockinController`   | `protected $view;`                  |
| `ItemMasterController`         | `protected $view;`                  |
| `ServiceTypeController`        | `protected $view;`                  |
| `BrandTypeController`          | `protected $view;`                  |
| `ColorsTypeController`         | `protected $view;`                  |

- **Fix applied to all:** Added explicit `protected $view;` (or relevant property) declaration inside each class
- **Re-upload required:** `components/` folder → re-uploaded via FTP

---

### Issue 3 — CSS/JS/Images Not Loading (Broken Layout)

- **Symptom:** Page HTML loaded but had no styles — all CSS/JS/images showing `ERR_NAME_NOT_RESOLVED`
- **Root Cause:** All assets use `$_ENV['URL_HOST']` which was set to `http://touchandcarespa.online/` — but DNS hasn't propagated yet so the browser can't resolve that domain
- **Fix (temporary):** In InfinityFree File Manager → edit `.env` on server:
  ```
  URL_HOST=http://touchandcarespa.great-site.net/
  ```
- **Fix (permanent):** Once DNS propagates, change back to:
  ```
  URL_HOST=http://touchandcarespa.online/
  ```
- **Why it works:** All CSS, JS, images, and links go through `$_ENV['URL_HOST']` — changing this one value fixes everything

---

### Issue 4 — DNS Not Propagated Yet

- **Symptom:** `DNS_PROBE_FINISHED_NXDOMAIN` when visiting `http://touchandcarespa.online/`
- **Root Cause:** Nameservers were changed earlier today — DNS propagation takes 24–48 hours
- **Status:** ⏳ Waiting — nothing to fix in code
- **Check progress:** https://dnschecker.org/#A/touchandcarespa.online

---

## 📌 PENDING — After DNS Propagates

> 📄 Full step-by-step guide: see `DNS_MIGRATION_GUIDE.md`

**One change only — edit `.env` on server:**

```
URL_HOST=https://touchandcarespa.online/
```

- [ ] Verify DNS at https://dnschecker.org/#A/touchandcarespa.online
- [ ] Edit `.env` on server: `URL_HOST=https://touchandcarespa.online/`
- [ ] Test `https://touchandcarespa.online/auth` — admin login
- [ ] Test `https://touchandcarespa.online/customer/customer/index`
- [ ] Run full test checklist (see `DNS_MIGRATION_GUIDE.md`)
- [ ] Update local `production_ready/.env` to match
- [ ] Change default passwords (admin123, 123456)

---

## 🔴 PHASE 4 — Email System Fix (April 9, 2026)

### Issue 5 — Gmail SMTP Not Sending (BadCredentials)

- **Symptom:** OTP emails not arriving for registration and forgot password. Code IS saved in DB but email silently fails.
- **Root Cause 1:** `require_once __DIR__ . '../../ComponentHelper/ComponentHelper.php'` in `UserController.php` — missing `/` separator → path was broken → `ComponentHelper` class never loaded for email calls
- **Root Cause 2:** InfinityFree **port 587 is CLOSED**; port **465 is OPEN** — code was using port 587 with STARTTLS
- **Root Cause 3:** Gmail App Password `rigpcxuxchagfsls` was **revoked/expired** by Google → regenerated new one

**Files Fixed:**

| File                                                  | Fix                                                                                                      |
| ----------------------------------------------------- | -------------------------------------------------------------------------------------------------------- |
| `components/UserController/UserController.php` line 3 | `__DIR__ . '../../...'` → `__DIR__ . '/../ComponentHelper/ComponentHelper.php'`                          |
| `components/ComponentHelper/ComponentHelper.php`      | Port 587/STARTTLS → Port 465/SMTPS; Timeout 5s → 30s; Added `AltBody`; Added display name in `setFrom()` |

**App Password Update:**

- Old (revoked): `rigpcxuxchagfsls`
- New (working): `qxvryweqrfsakfxx`
- Updated in: `production_ready/.env`, `.env`, and server `.env`

**Diagnostic Tool Created:**

- File: `email_test.php` — standalone test page, password protected (`?key=test1234`)
- Features: SMTP send test, port connection test, live OTP code viewer from DB, override password field
- ⚠️ **Delete from server after testing** — exposes OTP codes

---

## � PHASE 5 — NULL strtotime() Sweep (April 9, 2026)

### Issue 6 — Multiple NULL strtotime Deprecations

PHP 8.2 throws deprecations when `null` is passed to `strtotime()`. Multiple pages used it without null checks, all from LEFT JOIN queries where dates can be NULL.

| File                                                                                | Fix                                       |
| ----------------------------------------------------------------------------------- | ----------------------------------------- |
| `components/TransactionBiddingController/TransactionBiddingController.php` L268-269 | Added `!empty()` guard on `date`/`time`   |
| `components/TransactionOrderController/views/custom.php` L69                        | Added `!empty()` guard on `created_at`    |
| `components/CustomerController/views/accounts/Orders.php` L22                       | Added `!empty()` guard on `created_at`    |
| `components/CustomerController/CustomerController.php` L930-931                     | Added `!empty()` guard on `$date`/`$time` |

### Issue 7 — `${var}` String Interpolation Deprecated (PHP 8.2)

- **File:** `components/TransactionBiddingController/TransactionBiddingController.php` L507-509
- **Fix:** `"${type}"` → `"{$type}"`

---

## 🔑 Server Credentials Reference

| Item             | Value                                       |
| ---------------- | ------------------------------------------- |
| **Domain**       | `touchandcarespa.online` (Hostinger)        |
| **Temp URL**     | `https://touchandcarespa.great-site.net` ✅ |
| **FTP Host**     | `ftpupload.net`                             |
| **FTP User**     | `if0_41612282`                              |
| **FTP Password** | `gRseIvmismLy`                              |
| **FTP Port**     | `21`                                        |
| **Remote path**  | `/htdocs/`                                  |
| **MySQL Host**   | `sql100.infinityfree.com`                   |
| **MySQL DB**     | `if0_41612282_touchandcarespa`              |
| **MySQL User**   | `if0_41612282`                              |
| **MySQL Pass**   | `gRseIvmismLy`                              |
| **cPanel**       | InfinityFree — account `if0_41612282`       |
| **Gmail**        | `touchandcaremassageandspa00@gmail.com`     |
| **App Password** | `qxvryweqrfsakfxx` ✅ Working               |

---

## 🔑 Admin Login Reference

| Role                    | URL                                           | Email                  | Password          |
| ----------------------- | --------------------------------------------- | ---------------------- | ----------------- |
| Super Admin (localhost) | `http://localhost/WEB_6/auth`                 | `mgaducio@gmail.com`   | `admin123`        |
| Super Admin (live temp) | `https://touchandcarespa.great-site.net/auth` | `mgaducio@gmail.com`   | _(reset via OTP)_ |
| Super Admin (live)      | `https://touchandcarespa.online/auth`         | `mgaducio@gmail.com`   | _(after DNS)_     |
| Customer                | `/customer/customer/index`                    | `itsclient4@gmail.com` | `123456`          |

> ⚠️ **Change all default passwords** (`admin123`, `123456`) before announcing the site publicly!

---

_Log created: April 9, 2026 | Updated: April 9, 2026_  
_Project: CARE MASSAGE AND SPA — WEB_6_  
_Repository: github.com/Glenn-IT/WEB_6_
