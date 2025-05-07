# Laravel API Challenge — Steps 1 to 6 Completed

This is a complete implementation of the specified REST API with procedural database integration and token-based access control.

## ✅ Features Implemented

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

---

## Database Procedures Used

- `GetServerTime` → returns current `NOW()` from the DB
- `get_flagbits_for_transaction` → returns all active flagbits for a given transaction
- `validate_apikey` → checks if a token exists and is valid in current time window
- `stamd_aendern_erstellen_flagbit_ref` → central logic to set or remove flagbits

---

## Testing

Run:
```bash
php artisan test
