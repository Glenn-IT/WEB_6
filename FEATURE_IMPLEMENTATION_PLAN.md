# 🛠️ Feature Implementation Plan
## Touch and Care Massage and Spa – Admin Enhancements

> **Date:** April 13, 2026  
> **Branch:** `main`  
> **Developer:** GitHub Copilot

---

## ✅ Bug Fix

### 1. `TransactionOrderController/views/custom.php` – Syntax Error
- **Problem:** A stray `<?php` tag inside an already-open PHP block caused a fatal syntax error.
- **Fix:** Removed the duplicate `<?php` opening tag so the closing brace for the `if($_SESSION["user_type"] == 2)` block is valid.

---

## ✅ New Features

---

### 🖼️ Feature 1 – Dynamic Banner Management

| Item | Detail |
|------|--------|
| **Menu Label** | Banner |
| **Sidebar Icon** | `ti-image` |
| **Route** | `/component/banner/index` |
| **Controller** | `components/BannerController/BannerController.php` |
| **View** | `components/BannerController/views/custom.php` |
| **Modal** | `components/BannerController/views/modal_details.php` |
| **JS** | `components/BannerController/js/custom.js` |
| **Upload folder** | `src/images/banner/uploads/` |

#### What the admin can do:
- ➕ Add a new banner image (upload + optional title + sort order)
- ✏️ Edit an existing banner (replace image, change title/order/status)
- 👁️ Toggle visibility (Visible / Hidden) with one click — **no page reload needed**
- 🗑️ Soft-delete a banner

#### How it works on the frontend:
- `CustomerController::index()` now queries `SELECT * FROM site_banners WHERE is_active=1` and passes the result as `$banners` to the homepage view.
- `customer/views/custom.php` renders the slideshow **dynamically from the DB**, with a static fallback to the five Hero images if the table is empty.

---

### 🎉 Feature 2 – Promo Management

| Item | Detail |
|------|--------|
| **Menu Label** | Promos |
| **Sidebar Icon** | `ti-tag` |
| **Route** | `/component/promo/index` |
| **Controller** | `components/PromoController/PromoController.php` |
| **View** | `components/PromoController/views/custom.php` |
| **Modal** | `components/PromoController/views/modal_details.php` |
| **JS** | `components/PromoController/js/custom.js` |
| **Upload folder** | `src/images/promos/uploads/` |

#### What the admin can do:
- ➕ Add a promo card (title, description, discount label, optional image, **link to a specific service**)
- ✏️ Edit a promo
- 👁️ Toggle visibility per promo
- 🗑️ Soft-delete a promo

#### How it works on the frontend:
- A new **"Current Promotions"** section appears between the banner and the Services carousel.
- Only promos with `is_active = 1` are shown.
- If a promo is linked to a service (`link_service_id`), clicking the card redirects to that service's product page.
- If no active promos exist the section is hidden automatically.

---

### 📖 Feature 3 – About Page Management

| Item | Detail |
|------|--------|
| **Menu Label** | About Page |
| **Sidebar Icon** | `ti-info-alt` |
| **Route** | `/component/about/index` |
| **Controller** | `components/AboutController/AboutController.php` |
| **View** | `components/AboutController/views/custom.php` |

#### What the admin can do:
- Edit the **page title** displayed at the top of the About Us page
- Edit **4 content paragraphs** independently
- Edit **contact details**: address, phone, email
- Edit the **Google Maps embed URL**
- Save everything in one click (no modal needed — single form)

#### How it works on the frontend:
- `CustomerController::about()` now reads all rows from `site_about` and builds an `$about_sections` array keyed by `section_key`.
- `customer/views/about.php` uses `$about_sections['key']` for every text block, with safe fallbacks if the table hasn't been seeded yet.

---

## 🗄️ Database Migration

**File:** `database/migration_banner_promo_about.sql`

Run this SQL file once on both local (XAMPP phpMyAdmin) and the live InfinityFree database.

```sql
-- Three new tables:
-- site_banners   → banner images for the homepage slideshow
-- site_promos    → promotional cards shown below the banner
-- site_about     → key-value store for About Us text content
```

The migration also inserts **seed data**:
- 5 banner rows pointing to the existing `Hero 1–5.jpg` files (so the site looks identical to before).
- All About Us text content copied from the old hard-coded values.

---

## 📁 Files Changed / Created

### New Files
```
components/BannerController/
    BannerController.php
    js/custom.js
    views/custom.php
    views/modal_details.php

components/PromoController/
    PromoController.php
    js/custom.js
    views/custom.php
    views/modal_details.php

components/AboutController/
    AboutController.php
    js/custom.js
    views/custom.php

database/migration_banner_promo_about.sql
```

### Modified Files
```
components/TransactionOrderController/views/custom.php
    → Fixed fatal PHP syntax error (duplicate <?php tag)

libraries/Helper.php
    → Added Banner, Promos, About Page to admin sidebar

components/CustomerController/CustomerController.php
    → index()   – loads $banners and $promos from DB
    → about()   – loads $about_sections from site_about table

components/CustomerController/views/custom.php
    → Banner slideshow now driven by DB with static fallback
    → Promo section added between banner and services carousel

components/CustomerController/views/about.php
    → Paragraphs and contact info now driven by DB content
```

---

## 🚀 Deployment Steps

1. **Run the SQL migration** on your database:
   - Local: open phpMyAdmin → select your DB → Import `database/migration_banner_promo_about.sql`
   - Live: open InfinityFree phpMyAdmin → same steps

2. **Create upload directories** (XAMPP will auto-create on first upload, but you can pre-create):
   ```
   src/images/banner/uploads/
   src/images/promos/uploads/
   ```
   Ensure the web server has write permission on these folders.

3. **Log in as Admin** and navigate to:
   - `Banner` → verify 5 seed banners appear
   - `Promos` → add your first promo
   - `About Page` → edit text content as needed

4. **Check the customer-facing homepage** at `/customer/customer/index` to verify banners and promos display correctly.

---

## 🔒 Access Control

All three new menus are added **only to the admin sidebar** (`user_type != 2` block in `sideBarDetails()`), meaning regular staff/therapist accounts (`user_type == 2`) will not see these menus.
