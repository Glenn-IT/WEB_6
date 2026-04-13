# Service Type & Address Fixes — Booking List (Admin)

**Last Updated:** April 14, 2026 (Fix 3 added)

---

## Fix 1 — April 13, 2026: Admin Booking List not showing service type / address

**Issue:** Admin Booking List (Booking List page) was not showing:

1. The service type (Walk-in / Home Service / Hotel Service) in the list table
2. The service type badge in the VIEW modal (Booking Details)
3. The correct address/hotel info in the modal's "Client Information" section (always blank)

### Root Cause

Both `index()` and `source()` queries in `TransactionBiddingController` were fetching `u.billing_address`
from the **users** table — which is the user's profile address, not the booking address.

The actual booking fields (`service_type`, `billing_address`, `hotel_name`, `hotel_room`) are stored
in the **`main_order`** table, but were never being selected or displayed.

### Files Changed

- `components/TransactionBiddingController/TransactionBiddingController.php` — SQL queries updated to read `o.service_type`, `o.billing_address`, `o.hotel_name`, `o.hotel_room` from `main_order`
- `components/TransactionBiddingController/views/custom.php` — Added Service Type colored badge column
- `components/TransactionBiddingController/views/modal_details.php` — Added Service Type badge and smart address/hotel display
- `components/TransactionBiddingController/js/custom.js` — Updated DataTables for 8-column table

---

## Fix 2 — April 14, 2026: billing_address not saving to database

**Issue:** When a customer selects **Home Service** and enters their address, it was NOT being saved to the database (`billing_address` column in `main_order` always stayed empty).

### Root Cause — 3 bugs found

#### Bug 1: Duplicate `name="billing_address"` on two inputs

Both the Home Address field AND the Hotel Address field had `name="billing_address"`:

```html
<!-- Home field -->
<input name="billing_address" id="billing_address" />

<!-- Hotel field — WRONG, same name! -->
<input name="billing_address" id="hotel_address" />
```

When the form submitted, the browser sent the first match — which was the Home field. If the Hotel section was selected, both fields existed in the DOM and the empty Home field won.

#### Bug 2: Hidden fields were still submitted

The Home/Hotel sections were hidden with `display:none` but inputs still had `name` attributes, so they were included in the form POST even when invisible — sending empty strings.

#### Bug 3: Hotel address had no unique field name

The hotel address didn't have its own field name, so PHP's `checkout()` couldn't distinguish "hotel address" from "home address".

### Fixes Applied

**`components/CustomerController/views/place_bid_terms.php`**

- Added `disabled` attribute to all address/hotel inputs by default (disabled fields are NOT submitted)
- Renamed hotel address field from `name="billing_address"` → `name="hotel_address"` (unique name)

**`components/CustomerController/js/custom.js` — `applyServiceType()` function**

- Changed show/hide logic to also **enable/disable** inputs:
  - Walk-in → all fields disabled (not submitted)
  - Home → only `#billing_address` enabled + required
  - Hotel → only `#hotel_name`, `#hotel_address`, `#hotel_room` enabled + required

**`components/CustomerController/CustomerController.php` — `checkout()` method**

- Added logic to map `hotel_address` → `billing_address` for hotel service type:

```php
if ($svc_type === 'hotel') {
    $final_billing_address = isset($hotel_address) ? $hotel_address : '';
} else {
    $final_billing_address = isset($billing_address) ? $billing_address : '';
}
```

- Email notification also updated to use `$final_billing_address` and show Hotel Address line for hotel bookings

---

## ⚠️ Prerequisite

Make sure you have already run **`database/migration_service_type.sql`** on your database.  
This adds the `service_type`, `hotel_name`, and `hotel_room` columns to `main_order`.

```sql
ALTER TABLE `main_order`
  ADD COLUMN IF NOT EXISTS `service_type`  VARCHAR(50)  DEFAULT 'walk-in'  AFTER `billing_address`,
  ADD COLUMN IF NOT EXISTS `hotel_name`    VARCHAR(255) DEFAULT NULL        AFTER `service_type`,
  ADD COLUMN IF NOT EXISTS `hotel_room`    VARCHAR(100) DEFAULT NULL        AFTER `hotel_name`;
```

Existing bookings before this migration will default to `walk-in` and show "In-store (Walk-in)".

---

## Fix 3 — April 14, 2026: Customer booking history VIEW still shows "Walk-in" despite correct DB data

**Issue:** Order `841378` is `service_type = home` in the database (confirmed), but the customer's booking
history page VIEW modal still showed "Walk-in".

### Root Cause

There are **two separate `source()` methods** that power the VIEW modal:

| Controller                               | Used by                       | Status before fix                                              |
| ---------------------------------------- | ----------------------------- | -------------------------------------------------------------- |
| `TransactionBiddingController::source()` | Admin Booking List            | ✅ Fixed (Fix 1)                                               |
| `CustomerController::source()`           | Customer booking history page | ❌ Still broken — reading `u.billing_address` from users table |

`CustomerController::source()` (line 373) had its **own SQL query** that was never updated in Fix 1.
It still fetched `u.billing_address` from the `users` table and was missing `o.service_type`,
`o.hotel_name`, `o.hotel_room` entirely — so `$details["service_type"]` was always `null`,
causing the view to fall back to `'walk-in'`.

### Fix Applied

**`components/CustomerController/CustomerController.php` — `source()` method**

Changed `u.billing_address` → `o.service_type`, `o.billing_address`, `o.hotel_name`, `o.hotel_room`:

```sql
-- BEFORE (wrong)
u.billing_address,

-- AFTER (correct)
o.service_type,
o.billing_address,
o.hotel_name,
o.hotel_room,
```

This matches the same fix already applied to `TransactionBiddingController::source()` in Fix 1.
