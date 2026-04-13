# Service Type & Address Fixes — Booking List (Admin)

**Date:** April 13, 2026  
**Issue:** Admin Booking List (Booking List page) was not showing:
1. The service type (Walk-in / Home Service / Hotel Service) in the list table
2. The service type badge in the VIEW modal (Booking Details)
3. The correct address/hotel info in the modal's "Client Information" section (always blank)

---

## Root Cause

Both `index()` and `source()` queries in `TransactionBiddingController` were fetching `u.billing_address`
from the **users** table — which is the user's profile address, not the booking address.

The actual booking fields (`service_type`, `billing_address`, `hotel_name`, `hotel_room`) are stored
in the **`main_order`** table (added by the `migration_service_type.sql` migration), but they were
never being selected or displayed.

---

## Files Changed

### 1. `components/TransactionBiddingController/TransactionBiddingController.php`

**Both `index()` and `source()` SQL queries updated** — changed `u.billing_address` to the correct
fields from `main_order`:

```sql
-- BEFORE (wrong — reads user profile address, always blank for most users)
u.billing_address

-- AFTER (correct — reads booking-level fields from main_order)
o.service_type,
o.billing_address,
o.hotel_name,
o.hotel_room,
```

---

### 2. `components/TransactionBiddingController/views/custom.php`

**Booking List table:**
- Added new **"Service Type"** column (between Therapist Name and Date & Time)
- Shows colored badge:
  - 🟢 **Walk-in** (green)
  - 🔵 **Home Service** (blue)
  - 🟣 **Hotel Service** (purple)
- Fixed empty-state row to use a single `colspan="8"` instead of 7 empty `<td>` tags

---

### 3. `components/TransactionBiddingController/views/modal_details.php`

**Booking Details section (top half of modal):**
- Added **Service Type** badge display after the Time field
- Conditionally shows address/hotel info right in the booking details block:
  - **Home Service** → shows "Home Address" textarea
  - **Hotel Service** → shows "Hotel Name" + "Room No." side by side
  - **Walk-in** → nothing extra shown (handled in Client Info section)

**Client Information section (bottom of modal):**
- Replaced the static blank "Address" input with smart conditional display:
  - **Home Service** → shows `Home Address` textarea with the `billing_address` value
  - **Hotel Service** → shows `Hotel Name` + `Room No.` fields
  - **Walk-in** → shows a read-only input with text "In-store (Walk-in)"

---

### 4. `components/TransactionBiddingController/js/custom.js`

- Added `language: { emptyTable: "NO RECORD FOUND!" }` to DataTables config
- Added `columnDefs` to disable ordering on Service Type (col 4) and Action (col 7) columns

---

## How It Looks After Fix

### Booking List Table
| ID | Order No. | Customer Name | Therapist | **Service Type** | Date & Time | Status | Action |
|----|-----------|---------------|-----------|------------------|-------------|--------|--------|
| 69 | 777656 | Glenard P. | Any Professional | 🟣 Hotel Service | 2026-04-14 10:30 AM | PROCESSED | VIEW / COMPLETE / DECLINE |

### VIEW Modal — Booking Details
```
Status:         PROCESSED
Order No:       777656
Date Scheduled: 2026-04-14
Time:           10:30 AM
Service Type:   [🟣 Hotel Service]
Hotel Name:     Grand Hyatt
Room No.:       305
Therapist Name: Any Professional
Number of Client: 2
```

### VIEW Modal — Client Information
```
Name:       Glenard Pagurayan
Hotel Name: Grand Hyatt          Room No.: 305
Contact No: 09157609077
Gender:     MALE
```
*(For Walk-in: Address shows "In-store (Walk-in)")*
*(For Home Service: Address shows the home address entered during booking)*

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
