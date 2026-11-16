# AirAsia Mileage Rewards - Test Report

## 1. Executive Summary

This test report documents the verification and quality assurance testing of the AirAsia Mileage Rewards web application. Testing covers server side validation, database CRUD use cases, invalid input handling, and ID security.

All test cases (T01 through T08) have been executed and verified as **PASS**.

---

## 2. Test Execution Matrix

| Test ID | Test Name | Target Component | Status |
| :--- | :--- | :--- | :---: |
| **T01** | Validation test harness | `tests/validation-tests.php` | **PASS** |
| **T02** | View Gift Card List | `card-list.php` | **PASS** |
| **T03** | View Gift Card Details | `card-details.php` | **PASS** |
| **T04** | Add Gift Card | `card-add.php` | **PASS** |
| **T05** | Update Gift Card | `card-update.php`, `card-details.php` | **PASS** |
| **T06** | Delete Gift Card | `card-delete.php`, `card-list.php` | **PASS** |
| **T07** | Invalid or altered input handling | `includes/validation.php`, `card-add.php`, `card-update.php` | **PASS** |
| **T08** | Invalid Gift Card identifier handling | `includes/validation.php`, `card-details.php`, `card-update.php`, `card-delete.php` | **PASS** |

---

## 3. Detailed Test Cases

### T01: Validation Test Harness

- **Test Steps and Input**:
  1. Execute `tests/validation-tests.php` in a web browser and via command line (`php tests/validation-tests.php`).
  2. Verify that in-memory validation functions are tested across core and boundary cases.
  3. Verify that zero database records are inserted, updated, or deleted during execution.
- **Expected Result**:
  All test cases report PASS. Table displays `#`, `Test`, `Expected result`, `Actual result`, and `Status`. Database record count before and after execution remains unchanged. Script returns exit code `0`.
- **Actual Result**:
  14 out of 14 test cases PASSED (5 core tests and 9 boundary tests).Exit code returned `0`.
- **Status**: **PASS**

---

### T02: View Gift Card List

- **Test Steps and Input**:
  1. Open `card-list.php` in the browser.
  2. Verify dynamic rendering of all gift card records from `rewards.db`.
  3. Verify presentation of card image, card name, card category, dollar value, and points required.
  4. Verify grouping by vendor and ascending sort order by dollar value within vendor groups.
  5. Click both the card image and title link for an item.
- **Expected Result**:
  All records are displayed with correct attributes and dedicated images. Cards from the same vendor appear contiguously, sorted from lowest to highest value. Image and title link to `card-details.php?card_id=...`. A link to `card-add.php` is available.
- **Actual Result**:
  Grid rendered all records with accurate images. Contiguous vendor grouping and price sorting verified. Clicking image or title successfully navigated to `card-details.php?card_id=...`.
- **Status**: **PASS**

---

### T03: View Gift Card Details

- **Test Steps and Input**:
  1. Navigate to `card-details.php?card_id=1` and `card-details.php?card_id=12`.
  2. Verify read-only display of card attributes: Card Name, Card Image, Card Type, Dollar Value, and Cost to Redeem.
  3. Verify presence and target URLs of action links:
     - `CARD UPDATE` -> `card-update.php?card_id=...`
     - `DELETE CARD` -> `card-delete.php?card_id=...`
     - `GO BACK` -> `card-list.php`
- **Expected Result**:
  Selected card record is retrieved using a PDO prepared statement with validated `card_id`. Attributes render in a structured read-only wireframe layout with all dynamic content escaped using `htmlspecialchars()`. Action links include the validated card identifier.
- **Actual Result**:
  Card record #1 and #12 loaded with full read only details and image asset. Update, delete, and return links contained the validated identifier.
- **Status**: **PASS**

---

### T04: Add Gift Card

- **Test Steps and Input**:
  1. Navigate to `card-add.php`.
  2. Verify form controls for Card Name, Card Type, Cost to Redeem, and Card Value, with no editable auto generated Card ID field.
  3. Submit the following valid data via POST:
     - Card Name: `Grab Food Delivery`
     - Card Type: `Food & Dining`
     - Points Required: `2500`
     - Card Value: `25.00`
- **Expected Result**:
  Form submission uses HTTP POST. Server validates submitted values. On successful validation, record is inserted into SQLite via PDO prepared statement. Application redirects user to `card-list.php`, where the new card appears in the list.
- **Actual Result**:
  Form submitted via POST, validated without errors, inserted record with `lastInsertId`, and redirected to `card-list.php`. The new gift card appeared in the list with all submitted attributes.
- **Status**: **PASS**

---

### T05: Update Gift Card

- **Test Steps and Input**:
  1. Navigate to `card-update.php?card_id=1`.
  2. Verify form controls are prepopulated with existing database values for Card #1.
  3. Update fields with new values:
     - Card Name: `AirAsia Flight Voucher - Platinum`
     - Card Value: `75.00`
     - Points Required: `7500`
  4. Submit form with HTTP POST.
- **Expected Result**:
  Form processes update with POST. Server validates submitted values. Record is updated in the database with PDO prepared statement. User is redirected to `card-details.php?card_id=1&updated=1`, displaying the updated information and a green success alert banner.
- **Actual Result**:
  Form prepopulated existing values. On submission, server validation passed, prepared statement executed `UPDATE`, and browser was redirected to `card-details.php?card_id=1&updated=1` displaying the updated name, value, points, and success banner.
- **Status**: **PASS**

---

### T06: Delete Gift Card

- **Test Steps and Input**:
  1. Navigate to `card-delete.php?card_id=1` with GET request.
  2. Verify that the GET request displays a confirmation screen and does **not** delete the record.
  3. Verify card identity, attributes, image, warning message, and "CONFIRM DELETE" or "CANCEL" controls.
  4. Click "CONFIRM DELETE" to submit the POST confirmation form.
  5. Check database and `card-list.php` to confirm deletion.
- **Expected result**:
  Card is never deleted through a GET link. Confirmation screen clearly identifies the card to be removed. Deletion executes only upon confirmed POST submission using prepared statement `DELETE FROM GiftCard WHERE cardId = :cardId`. User is redirected to `card-list.php`, and the record no longer exists.
- **Actual Result**:
  GET request loaded confirmation view without modifying the database. Submitting the POST confirmation executed the prepared DELETE statement, redirected to `card-list.php`, and subsequent queries confirmed the record was removed.
- **Status**: **PASS**

---

### T07: Invalid or Altered Input Handling

- **Test Steps and Input**:
  1. Submit `card-add.php` and `card-update.php` with invalid input payloads:
     - Blank Card Name: `cardName = ""`
     - Excessive Card Name Length: `cardName = 101 characters`
     - Invalid/Altered Card Type: `cardType = "HackedCategory123"`
     - Zero Card Value: `cardValue = "0.00"`
     - Negative Card Value: `cardValue = "-15.00"`
     - Non-numeric Card Value: `cardValue = "free"`
     - Zero Points: `points = "0"`
     - Negative Points: `points = "-500"`
     - Decimal Points: `points = "12.50"`
     - XSS Payload: `cardName = "<script>alert('XSS')</script>"`
  2. Verify server-side validation response and HTML output.
- **Expected Result**:
  Server-side validation rejects all invalid payloads regardless of browser HTML5 attributes. No database insertions or updates occur. An error alert banner lists specific error messages. Invalid fields receive `.is-invalid` classes with inline feedback. Valid values are repopulated. Dynamic values are escaped with `htmlspecialchars()` to prevent XSS execution.
- **Actual Result**:
  All invalid payloads were rejected server-side. The red alert banner summarized all field errors, `.is-invalid` styling and inline feedback rendered correctly, submitted values were repopulated, and HTML/script tags were escaped safely (`&lt;script&gt;`). No invalid records entered the database.
- **Status**: **PASS**

---

### T08: Invalid Gift Card Identifier Handling

- **Test Steps and Input**:
  1. Request `card-details.php`, `card-update.php`, and `card-delete.php` with invalid identifiers:
     - Nonexistent ID: `?card_id=9999`
     - Non-integer string: `?card_id=abc`
     - Negative integer: `?card_id=-10`
     - SQL Injection payload: `?card_id=1'%20OR%20'1'='1`
     - Missing parameter: (no query parameter)
- **Expected Result**:
  Identifiers are validated using `validateCardId()`. Any non-positive integer or malicious string fails validation safely. The application handles missing, invalid, or nonexistent records by redirecting to `card-list.php` without throwing uncaught exceptions, leaking database errors, or crashing.
- **Actual Result**:
  `validateCardId()` returned `null` for all malformed, negative, and SQL injection inputs. For all test cases across `card-details.php`, `card-update.php`, and `card-delete.php`, the application safely aborted processing and redirected to `card-list.php`.
- **Status**: **PASS**

---

## 4. Defects Identified, Corrected, and Retested

### Defect 1: Immediate Deletion on GET Request
- **Problem**: `card-delete.php` was originally executing deletion logic directly upon a GET request with a query parameter, violating the requirement that deletions must require confirmation through a POST form and never occur via a GET link.
- **Fix**: Refactored `card-delete.php` into a dedicated confirmation page. When accessed via GET, it displays card details, a warning banner, and a POST form with "CONFIRM DELETE" and "CANCEL" buttons. Deletion logic is restricted to verified `$_SERVER['REQUEST_METHOD'] === 'POST'`.
- **Retest Result**: Loading `card-delete.php?card_id=1` via GET displayed the confirmation view without modifying the database. Submitting the POST form deleted the card and redirected to `card-list.php`. Retest status: **PASS**.

### Defect 2: SQLite Custom Function Callback Registration Order
- **Problem**: When grouping gift cards by vendor using `card_vendor(cardName)`, `PDO::sqliteCreateFunction()` threw a TypeError because the callback function `getCardVendor` was conditionally declared inside a block evaluated after the connection attempt.
- **Fix**: Reordered function declarations in `includes/db.php` so `getCardVendor` is fully defined before `sqliteCreateFunction('card_vendor', 'getCardVendor', 1)` is called.
- **Retest Result**: SQLite queries using `card_vendor(cardName)` executed without errors. Cards successfully grouped contiguously by vendor and sub-sorted by price ascending. Retest status: **PASS**.

### Defect 3: Identifier Parameter Name Inconsistency
- **Problem**: Requirements specified URL query parameters using snake_case (`card_id=...`), whereas internal schema and form fields used camelCase (`cardId`). Pages checking only `$_GET['cardId']` redirected valid requests with `card_id` back to the list page.
- **Fix**: Standardized identifier retrieval across `card-details.php`, `card-update.php`, and `card-delete.php` using `$rawCardId = $_POST['cardId'] ?? $_GET['cardId'] ?? $_POST['card_id'] ?? $_GET['card_id'] ?? null;` and validated it via `validateCardId($rawCardId)`.
- **Retest Result**: Navigating with either `?card_id=1` or `?cardId=1` successfully loads the record. All generated navigation links use the required `card_id` parameter. Retest status: **PASS**.

### Defect 4: Test Harness Modifying Database Records
- **Problem**: Early iterations of `tests/validation-tests.php` executed INSERT, UPDATE, and DELETE queries to verify PDO operations, violating the requirement that the validation test harness must test validation functions without adding, changing, or deleting database records.
- **Fix**: Rewrote `tests/validation-tests.php` as a pure in memory test harness targeting `validateGiftCardData()` and individual validation functions. Added pre- and post-execution row count audits on the `GiftCard` table to verify zero database modifications.
- **Retest Result**: Executed `php tests/validation-tests.php`; all 14 test cases passed with a verified delta of 0 records added, changed, or deleted. Retest status: **PASS**.
