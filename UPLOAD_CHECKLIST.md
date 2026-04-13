# 📤 Live Site Upload Checklist

**Date:** April 14, 2026  
**Purpose:** Files to upload to the live/production server. Upload ONLY these files — no need to re-upload everything.

---

## ✅ HOW TO USE THIS CHECKLIST

1. Go through each section below
2. Upload each file to the **same path** on your live server (InfinityFree `htdocs/`)
3. Run the SQL migrations in **phpMyAdmin** on your live database
4. Tick the checkbox as you go

---

## 🗄️ STEP 1 — Run SQL Migrations First (phpMyAdmin)

> Open phpMyAdmin on your live host → select your database → SQL tab → paste and run each script.

- [ ] **`database/migration_therapist_photo_team.sql`**  
      Adds `photo`, `bio`, `position` columns to `therapist` table + creates `site_team_photo` table  
      _(Skip if already run — use `ADD COLUMN IF NOT EXISTS`)_

- [ ] **`database/migration_service_type.sql`**  
      Adds `service_type`, `hotel_name`, `hotel_room` columns to `main_order` table  
      _(Skip if already run — use `ADD COLUMN IF NOT EXISTS`)_

- [ ] **`database/migration_banner_promo_about.sql`**  
      Creates `banner`, `promo`, and `about_sections` tables for the CMS  
      _(Skip if already run)_

---

## 📁 STEP 2 — Upload PHP Files

### `libraries/`

- [x] `libraries/Helper.php`  
      _(Added Banner, Promo, About sidebar links)_

### `components/CustomerController/`

- [x] `components/CustomerController/CustomerController.php`  
      _(Biggest change — service type modal, checkout save, about page data, source() fix)_
- [x] `components/CustomerController/js/custom.js`  
      _(Service type picker flow, applyServiceType() enable/disable fix)_
- [x] `components/CustomerController/views/about.php`  
      _(Who We Are section, team photo, extract fix, devImage path fix)_
- [x] `components/CustomerController/views/custom.php`  
      _(Dynamic banners and promos on homepage)_
- [x] `components/CustomerController/views/place_bid_terms.php`  
      _(Booking wizard — service type fields, disabled inputs fix)_
- [x] `components/CustomerController/views/service_type_modal.php`  
      _(NEW FILE — Walk-in / Home / Hotel picker modal)_

### `components/TherapistController/`

- [x] `components/TherapistController/TherapistController.php`  
      _(Photo/bio/position upload, team photo methods)_
- [x] `components/TherapistController/js/custom.js`  
      _(DataTables update, team photo upload handler)_
- [x] `components/TherapistController/views/custom.php`  
      _(Photo column in therapist table, team photo upload card)_
- [x] `components/TherapistController/views/modal_details.php`  
      _(Photo/bio/position fields in add/edit form)_

### `components/TransactionBiddingController/`

- [x] `components/TransactionBiddingController/TransactionBiddingController.php`  
      _(SQL queries fixed — fetch service_type, billing_address, hotel fields from main_order)_
- [x] `components/TransactionBiddingController/js/custom.js`  
      _(DataTables 8-column config)_
- [x] `components/TransactionBiddingController/views/custom.php`  
      _(Service Type badge column added)_
- [x] `components/TransactionBiddingController/views/modal_details.php`  
      _(Service type badge + smart address/hotel display in booking detail modal)_

### `components/TransactionOrderController/`

- [x] `components/TransactionOrderController/TransactionOrderController.php`  
      _(SQL SELECT updated to include service_type, hotel_name, hotel_room)_
- [x] `components/TransactionOrderController/js/custom.js`  
      _(DataTables 13-column config)_
- [x] `components/TransactionOrderController/views/custom.php`  
      _(Service Type badge column + Location/Address column)_

### `components/BannerController/` _(NEW — upload entire folder)_

- [x] `components/BannerController/BannerController.php`
- [x] `components/BannerController/js/custom.js`
- [x] `components/BannerController/views/custom.php`
- [x] `components/BannerController/views/modal_details.php`

### `components/PromoController/` _(NEW — upload entire folder)_

- [x] `components/PromoController/PromoController.php`
- [x] `components/PromoController/js/custom.js`
- [x] `components/PromoController/views/custom.php`
- [x] `components/PromoController/views/modal_details.php`

### `components/AboutController/` _(NEW — upload entire folder)_

- [x] `components/AboutController/AboutController.php`
- [x] `components/AboutController/js/custom.js`
- [x] `components/AboutController/views/custom.php`

---

## 📁 STEP 3 — Upload Image Folders (if therapist photos were uploaded)

> Only needed if you uploaded therapist photos or team photos on localhost.

- [x] `src/images/therapist/uploads/` _(all therapist profile photos)_
- [x] `src/images/therapist/team/` _(team/group photos)_

---

## 🔍 STEP 4 — Quick Verification After Upload

Open your live site and check each of these:

| Page                              | What to Check                                          |
| --------------------------------- | ------------------------------------------------------ |
| Admin → Booking List              | Service Type badge column visible (Walk-in/Home/Hotel) |
| Admin → Booking List → VIEW       | Shows correct service type, address/hotel details      |
| Admin → Transaction Orders        | Service Type column + Location column visible          |
| Admin → Therapists                | Photo column, Bio/Position fields in add form          |
| Admin → Sidebar                   | Banner, Promo, About links visible                     |
| Customer → Book Appointment       | Service type picker appears before booking wizard      |
| Customer → Home Service           | Address field appears and saves correctly              |
| Customer → Booking History → VIEW | Correct service type shown (not always "Walk-in")      |
| Customer → About Page             | "Who We Are" section with therapist cards              |
| Customer → Homepage               | Banners and Promos loading from database               |

---

## 📋 Summary Count

| Category                 | Count                                |
| ------------------------ | ------------------------------------ |
| SQL migrations to run    | 3                                    |
| PHP files to upload      | 17                                   |
| NEW folders to upload    | 3 (Banner, Promo, About controllers) |
| Image folders (optional) | 2                                    |
| **Total files**          | **~25**                              |

---

## ⚠️ DO NOT UPLOAD

These are local/dev-only files — **do not upload** to live:

- `db_check.php` _(debug file — already deleted)_
- `db_check2.php` _(debug file)_
- `db_check_order.php` _(debug file — already deleted)_
- `.env` _(contains local DB credentials — live server has its own)_
- `sessions/` folder _(local PHP session files)_
- `*.bat` files _(Windows batch scripts)_
