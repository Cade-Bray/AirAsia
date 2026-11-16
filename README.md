# AirAsia Mileage Rewards Program

The AirAsia Mileage Rewards web application implements the Gift Card rewards management component, allowing users to browse, view, add, update, and delete gift cards with server-side validation, PDO prepared statements, and safe output escaping.

## Use Case Traceability

| Use case | PHP page or pages | Database table | Test evidence |
| :--- | :--- | :--- | :--- |
| View a List of Gift Cards | card-list.php | GIFTCARD | Loaded card-list.php in the browser to verify all seeded gift card records displayed with images, names, categories, values, and points, grouped by vendor and sorted by value ascending. |
| View Details of One Gift Card | card-details.php | GIFTCARD | Navigated to card-details.php with a valid card_id parameter and confirmed that read-only card details, image, points, and action links were properly rendered. |
| Add a Gift Card | card-add.php | GIFTCARD | Submitted valid Gift Card data and confirmed the new record appeared on card-list.php. |
| Update a Gift Card | card-update.php, card-details.php | GIFTCARD | Submitted modified card details via POST form, verified the record was updated with a prepared statement, and confirmed redirection to card-details.php with the updated information and success alert. |
| Delete a Gift Card | card-delete.php, card-list.php | GIFTCARD | Confirmed deletion of a selected card through the POST confirmation form on card-delete.php and verified the record was removed from the database and list. |

## Project Architecture

- **Database Setup**: SQLite database located at `database/rewards.db`, initialized from `database/schema.sql` and populated with 21 GiftCard records and 5 Account records via `database/seed.sql`.
- **Shared Templates**: Centralized `includes/header.php`, `includes/nav.php`, and `includes/footer.php` included across all application pages via `require_once`.
- **PDO Database Connection**: Unified PDO connection in `includes/db.php` with error exceptions enabled, prepared statements for all dynamic inputs, and custom vendor sorting functions.
- **CRUD Operations**: Implementations for `card-list.php`, `card-details.php`, `card-add.php`, `card-update.php`, and `card-delete.php`.
- **Server-Side Validation and Safe Output**: Centralized validation in `includes/validation.php` covering Card Name, Card Type, Card Value, Points Required, and Card IDs, with universal `htmlspecialchars()` escaping.
- **Validation Test Harness**: Standalone in-memory test harness at `tests/validation-tests.php` validating core use cases and boundary conditions without modifying database records.
