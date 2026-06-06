# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A plain PHP 8 + MySQL (mysqli) restaurant ordering website ("Diafh/Malfooof"), served by Apache/XAMPP. There is
no framework, no Composer/npm build, and no automated test suite — pages are server-rendered `.php` files
included directly by the web server. The UI is Arabic/RTL by default.

The codebase has two halves that share the same database but bootstrap independently:
- **Storefront** — public-facing ordering site at the project root.
- **Control panel (`cp/`)** — admin dashboard ("Diafh") built on the Material Dashboard 2 template.

## Running / developing

- Serve the project root through XAMPP's Apache (`C:\xampp\htdocs\food`) with MySQL running; there is no CLI dev server.
- DB connection settings live in `includes/config.php`, which is **gitignored**. Copy `includes/config.php.example`
  and fill in `$GLOBALS['HOST'/'USERNAME'/'PASSWORD'/'DB']`, plus `JWT_SECRET_KEY`, `channel`, and
  `websocket_base_url` (used by the live-order notification system, see below).
- Database schema/views are not in migrations — apply `views.sql` (drops/recreates SQL views like `orderstable`,
  `itemstable`, `discountstable`, `logstable`) and any pending statements in `new-commands.sql` manually against
  the `food` database when setting up or updating a local copy.
- URL routing is done by `.htaccess`: extensionless URLs rewrite to the matching `.php` file
  (e.g. `/order-online` → `order-online.php`).
- No lint/build/test commands exist. `test.php` at the root is leftover scratch code, not a test suite.

## Storefront architecture (root)

Each public page (`index.php`, `menu.php`, `order-online.php`, `order.php`, `success_order.php`,
`visa_payment.php`, `privacy.php`, `terms.php`, `refund.php`, `social.php`) follows the same bootstrap chain:

1. `include "temps/settings.php"` — opens the DB connection (`includes/conn.php`) and loads `colors_settings`.
2. `include "includes/functions.php"` — the shared helper library (pricing, discounts, items, working hours, etc).
3. `temps/head.php` / `temps/header.php` / `temps/footer.php` / `temps/jslibs.php` — shared layout partials. They
   read `$site_setting` (a `title => value` map of the `website_settings` table, loaded in `includes/conn.php`)
   and `$colors_settings` for theme colors/text/RTL direction.

Front-end JS (`js/main.js`, `js/order-online.js`) drives the cart/order UI and talks to the `ajax/*.php`
endpoints via `$.post`. Cart contents live in `$_SESSION['cart']` (an array of `{item_id, size, count, options[], notes}`),
and helpers like `calc_total_price()`, `calc_item_price()`, `get_total_tax()` in `includes/functions.php` compute
prices/taxes from it consistently — reuse these rather than recomputing totals inline.

Key gating helpers (also in `includes/functions.php`): `is_work()` (checks `work_periods` against the current
weekday/time), `is_disabled()` / `order_disabled_msg()` (the `order_av` / `order_dis_msg` website settings).
Both are checked before allowing an order to be placed.

Translations: `__('key')` looks up `language/ar.php` or `language/en.php` based on `$site_setting['lang']`.

## Control panel architecture (`cp/`)

Admin pages bootstrap via `include 'temps/head.php'` (note: **`cp/temps/head.php`**, distinct from the storefront's),
which chains `../includes/conn.php` → `cp/functions/main.php` (admin helper library) → loads `$site_setting`.
Pages then guard themselves with `if (!is_logged()) header('Location: login.php');`.

- **Auth**: `check_login()`/`is_logged()` in `cp/functions/main.php` compare `$_SESSION['username']`/`password`
  (sha1-hashed) against the `panel_user` table.
- **Permissions**: role-based, via the `roles`/`permissions` tables and `panel_user.role_id`. Gate any
  admin action with `check_user_perm(['some-permission-key'])`; `get_role_permission()` auto-creates missing
  rows defaulted to "denied". **Every permission key must be registered in `get_system_permissions()`** — this
  is the single source of truth for what shows up in the role editor (`cp/edit-role.php`).
- **AJAX endpoints**: `cp/ajax/*.php` mirror each admin page's add/edit/remove/list actions (e.g.
  `add-item.php`/`edit-item.php`/`remove-item.php`, `save-discount.php`, `approve_order.php`/`cancel_order.php`).
- **DataTables server-side processing**: `cp/functions/ssp.class.php` + `cp/tables/*.php` (e.g. `itemsData.php`,
  `ordersData.php`, `discountsData.php`) feed the listing tables on pages like `show-items.php`/`show-order.php`.
- **Activity log**: `logg($page, $log)` writes to the `logging` table (surfaced via `cp/logs.php` / `logstable`).
- **UI**: built on the "Material Dashboard 2" Creative Tim template vendored in `cp/material/`.

## Payments

Payment provider is selected at runtime via the `selected_payment_method_providor` row in `website_settings`
(`qnb` or `paymob`). Each provider lives in `payments/<provider>/` and exposes a `generatePayment($order_id, $amount)`
that calls out to the gateway and returns a session/redirect payload; `paymob` additionally has a `webhook.php`
that validates the HMAC signature (`validate_webhook`) before finalizing the order.

Card-payment orders are first staged in `visa_orders_req` / `visa_cart_req` / `visa_options_req`. Both the
webhook and the shared return-URL handler `visa_payment.php` call `save_visa_order($operation_id)`
(in `includes/functions.php`), which copies the staged rows into the live `food_orders` / `food_order_cart` /
`food_order_options` tables exactly once (guarded by `visa_orders_req.status`) and fires a real-time notification.

## Real-time order notifications (websocket)

A separate websocket service (configured by `$GLOBALS['websocket_base_url']`, e.g. `http://localhost:3000`,
and a `$GLOBALS['channel']` name) receives authenticated push notifications for new/approved orders.
`generateJWT()` signs a short-lived HS256 token with `$GLOBALS['JWT_SECRET_KEY']`, and `sendWebSocketCurl($data, $event, $token)`
POSTs it to `<websocket_base_url>/<event>` (events used: `notify-order`, `approve-order`). `cp/live-order.php`
is the consumer-facing dashboard that connects to this service to show orders as they arrive.

## Standalone job scripts (root)

`job.php`, `discounts_job.php`, `malfooof_job.php` are CLI-invoked scripts (run via `php <script> <secret> ...`,
likely from cron/Task Scheduler) that flip `active`/`price` flags on branches, discounts, and locations. They
authenticate with a shared secret string passed as the first CLI argument — keep that check when modifying them.
`timeout.php` is a scratch endpoint used for testing slow-response handling, not part of the app flow.

## Data layer conventions

- Plain `mysqli_query()` with interpolated SQL everywhere (no prepared statements/ORM). Existing code sanitizes
  numeric IDs with `filter_var($id, FILTER_SANITIZE_NUMBER_INT)` and strings with `mysqli_real_escape_string()`
  before interpolating — follow the same pattern for any new query, and prefer the existing `get_*_info()` /
  `calc_*()` helpers over writing new raw queries when the data is already exposed.
- Site-wide configurable content/copy lives in the `website_settings` key→value table and is loaded once per
  request into `$site_setting`; theme colors are in `colors_settings` → `$colors_settings`.
- Money values support decimals; tax/discount calculations are centralized in `includes/functions.php`
  (`calc_total_price`, `calc_item_price`, `get_total_tax`, `get_discount_values`, `calc_included_items_*`) —
  reuse them so storefront totals and admin/report totals stay consistent.
