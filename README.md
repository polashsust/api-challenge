# Laravel API Challenge — Steps 1 to 6 Completed

This is a complete implementation of the specified REST API with procedural database integration and token-based access control.

## System Stack

- **Laravel**: 12.12.0
- **PHP**: 8.2.12 (ZTS Visual C++ 2019 x64)
- **Database**: MariaDB 10.4.32
- **Web Server**: Laravel built-in (`php artisan serve`)
- **Environment**: Windows (XAMPP)

## Features Implemented

### Step 1: Get Current Server Time
- Endpoint: `GET /api/time`
- Uses stored procedure `CALL GetServerTime()`

### Step 2: Feature Testing
- PHPUnit test suite included
- Run tests with: `php artisan test`

### Step 3: Token-Based Access Control
- Tokens validated using `validate_apikey` stored procedure
- Token must exist in `api_apikey` and be within the valid range from `vorgaben_zeitraum`

### Step 4: Get Active FlagBits
- Endpoint: `POST /api/flagbits`
- Uses stored procedure `CALL get_flagbits_for_transaction(?)`
- Filters transactions by API user's `vertrag_id` unless `ist_masterkey = 1`

### Step 5: Set a FlagBit
- Endpoint: `POST /api/flagbit/set`
- Protected: Requires `ist_masterkey = 1`
- Uses procedure `stamd_aendern_erstellen_flagbit_ref` with `modus = 1`

### Step 6: Remove a FlagBit
- Endpoint: `POST /api/flagbit/remove`
- Protected: Requires `ist_masterkey = 1`
- Uses procedure `stamd_aendern_erstellen_flagbit_ref` with `modus = 2`

The following MySQL stored procedures are required (found in `database/procedures.sql`):

- `GetServerTime()` — returns current server time
- `validate_apikey(apiKey)` — validates token from api_apikey table
- `get_flagbits_for_transaction(trans_id)` — fetches active flagbits for a transaction
- `stamd_aendern_erstellen_flagbit_ref(...)` — sets or removes flagbit for a data record

Please run the SQL in `database/procedures.sql` before using the API.

## API Testing with Postman

You can test all endpoints using the included Postman collection:

 `postman_collection.json`

1. Import into Postman
2. Use a valid Bearer token from the `api_apikey` table
3. Run each step (1–6) as defined

## Testing

Run:
```bash
php artisan test
