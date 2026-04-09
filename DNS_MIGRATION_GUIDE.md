# 🌐 DNS Migration Guide — Switching to touchandcarespa.online

**Project:** CARE MASSAGE AND SPA (WEB_6)  
**From:** `https://touchandcarespa.great-site.net`  
**To:** `https://touchandcarespa.online`  
**Date:** April 9, 2026

---

## ✅ Quick Answer

**Yes — you need to switch.** Once DNS propagates for `touchandcarespa.online`, the only thing you need to change is **ONE line** in your `.env` file on the server. Everything else (routing, assets, links, database) stays exactly the same.

---

## 🔍 How to Check if DNS Has Propagated

Before doing anything, verify DNS is ready:

1. Go to **https://dnschecker.org/#A/touchandcarespa.online**
2. You should see green checkmarks ✅ from **most locations worldwide**
3. The IP should match InfinityFree's IP (the same IP your `great-site.net` URL uses)

> ⏳ DNS takes **24–48 hours** from when you changed nameservers on Hostinger. If most checks are green, you're ready.

---

## 🔧 What Needs to Change — ONE File Only

### File: `.env` on the server

This is the **only change required**. All links, assets, CSS, JS, and routes all use `$_ENV['URL_HOST']` so changing this one value switches the entire site.

**How to edit it:**

#### Option A — InfinityFree File Manager (Easiest)

1. Log in to **InfinityFree cPanel** → **File Manager**
2. Navigate to `htdocs/` → find `.env`
3. Right-click → **Edit**
4. Find this line:
   ```
   URL_HOST=https://touchandcarespa.great-site.net/
   ```
5. Change it to:
   ```
   URL_HOST=https://touchandcarespa.online/
   ```
6. Save

#### Option B — FileZilla FTP

1. Download `.env` from server (`htdocs/.env`)
2. Edit locally — change the `URL_HOST` line
3. Re-upload and overwrite

---

## 📋 Full `.env` After Migration

Here is what your server `.env` should look like after switching:

```env
# ================================================================
# APP DETAILS
# ================================================================
APP_NAME="CARE MASSAGE AND SPA"
APP_EMAIL="skinclinic@gmail.com"
APP_NUMBER="012 345 6789"
APP_ADDRESS="Diversion Road Nursery Highway San Gabriel, Tuguegarao City"
APP_DEV=""
APP_DEBUG=false

# ✅ SWITCHED TO LIVE DOMAIN
URL_HOST=https://touchandcarespa.online/

# ================================================================
# DATABASE CONFIGURATION
# ================================================================
DBUSER=if0_41612282
DBPWD=gRseIvmismLy
DBHOST=sql100.infinityfree.com
DBNAME=if0_41612282_touchandcarespa

# ================================================================
# SMS CREDENTIALS (Movider)
# ================================================================
MOVIDER_KEY=2H7GtWOeyWYMff0XzK7en5zEdy6
MOVIDER_SECRET=m0hv1Nw4C0949gsL9RGVRIp75QomqWsLqD5fjpjB
MOVIDER_FROM=MVDSMS
MOVIDER_URL_BALANCE=https://api.movider.co/v1/balance
MOVIDER_URL_SMS=https://api.movider.co/v1/sms

# ================================================================
# EMAIL (PHPMailer via Gmail App Password)
# ================================================================
EMAIL_EMAIL="touchandcaremassageandspa00@gmail.com"
EMAIL_PASSWORD="Touch123456789"
EMAIL_APP_PASSWORD="qxvryweqrfsakfxx"
```

> ⚠️ **IMPORTANT:** Always keep `https://` — InfinityFree is HTTPS-only. Never use `http://`.

---

## 🧪 Post-Migration Test Checklist

After updating `URL_HOST`, go through this full checklist:

### 🌐 Pages

- [ ] `https://touchandcarespa.online/` — redirects correctly
- [ ] `https://touchandcarespa.online/auth` — admin login page loads with full CSS/JS
- [ ] `https://touchandcarespa.online/customer/customer/index` — customer homepage loads

### 🔐 Authentication

- [ ] Admin can log in at `/auth` with `mgaducio@gmail.com`
- [ ] Customer can log in at `/customer/customer/index`
- [ ] Forgot Password → OTP email arrives (Gmail)
- [ ] OTP code works → password reset completes
- [ ] New customer can register → verification OTP email arrives
- [ ] Logout works and redirects correctly

### 🖼️ Assets

- [ ] CSS loads (no unstyled page)
- [ ] JavaScript works (buttons, modals, AJAX calls)
- [ ] Images load (product images, profile images, spa photos)
- [ ] No mixed content warnings in browser console (F12 → Console)

### 📋 Admin Functions

- [ ] Dashboard loads (`/component/dashboard/index`)
- [ ] Booking list loads (`/component/transaction-bidding/index`)
- [ ] Orders list loads (`/component/transaction-order/index`)
- [ ] Messages page loads (`/component/messages/index`)
- [ ] Therapist management loads
- [ ] Inventory report loads
- [ ] Settings page loads

### 👤 Customer Functions

- [ ] Browse services/products
- [ ] Place a booking (bidding flow)
- [ ] View My Orders
- [ ] Send a message to admin
- [ ] Upload payment proof

### 📧 Notifications

- [ ] Booking confirmation email sent to customer
- [ ] Admin notification email on new booking
- [ ] SMS notification (Movider)

### 📁 File Uploads

- [ ] Image upload works (product images)
- [ ] Payment proof upload works
- [ ] Uploaded files are accessible via URL

---

## 🔄 Update Your Local `production_ready/.env`

Also update your local copy so it's in sync:

**File:** `c:\xampp\htdocs\WEB_6\production_ready\.env`

Change:

```
URL_HOST=https://touchandcarespa.great-site.net/
```

To:

```
URL_HOST=https://touchandcarespa.online/
```

---

## ⚠️ What Does NOT Need to Change

Everything else stays the same — no re-upload needed:

| Item              | Why No Change Needed                                      |
| ----------------- | --------------------------------------------------------- |
| `.htaccess`       | Already has `RewriteBase /` — works for any domain        |
| All PHP files     | All use `$_ENV['URL_HOST']` dynamically                   |
| Database          | No domain stored in DB (we cleaned hardcoded URLs in SQL) |
| `vendor/`         | Dependencies don't change                                 |
| CSS/JS files      | Served from relative paths inside `public/`               |
| Session handling  | Domain-independent                                        |
| FTP credentials   | Same server, same account                                 |
| MySQL credentials | Same DB, same host                                        |

---

## 🆘 If Something Breaks After Switching

### Site shows no CSS/JS

→ Check `URL_HOST` has `https://` not `http://`  
→ Open browser DevTools (F12) → Console tab → look for mixed content errors

### Redirects go to wrong URL

→ Confirm `URL_HOST=https://touchandcarespa.online/` (with trailing slash)

### 500 error

→ Temporarily set `APP_DEBUG=true` in `.env` → refresh → read the error → fix → set back to `false`

### Domain not resolving yet

→ Check https://dnschecker.org/#A/touchandcarespa.online  
→ If not green yet, wait more — DNS can take up to 48 hours  
→ Continue using `https://touchandcarespa.great-site.net` in the meantime

---

## 📌 Summary — What To Do Step by Step

```
1. Check DNS:  https://dnschecker.org/#A/touchandcarespa.online
               → Wait until most locations show green ✅

2. Edit .env on server (InfinityFree File Manager or FileZilla):
               URL_HOST=https://touchandcarespa.online/

3. Test the site:  https://touchandcarespa.online/auth
                   https://touchandcarespa.online/customer/customer/index

4. Run through the checklist above ☑️

5. Update your local production_ready/.env to match

6. Done! ✅ — Your site is now fully live on touchandcarespa.online
```

---

_Created: April 9, 2026_  
_Project: CARE MASSAGE AND SPA — WEB_6_
