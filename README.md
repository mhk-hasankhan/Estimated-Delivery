# VendorEstimatedDelivery — Shopware 5 Plugin

Displays an estimated delivery date range on product detail pages and the checkout confirmation page, calculated from configurable processing days, cutoff hours, and holiday exclusions.

---

## Features

- Delivery date window shown on product page and checkout
- Configurable processing days and order cutoff hour
- Weekend and public holiday exclusion
- Admin panel (ExtJS) to manage all settings per shop
- Lightweight — no external dependencies

---

## Requirements

- Shopware 5.6.x or higher
- PHP 7.4+ / 8.x

---

## Installation

1. Copy the plugin folder into your Shopware installation:

```bash
cp -r VendorEstimatedDelivery custom/plugins/VendorEstimatedDelivery
```

2. Install and activate via CLI:

```bash
php bin/console sw:plugin:refresh
php bin/console sw:plugin:install --activate VendorEstimatedDelivery
php bin/console sw:cache:clear
```

Or via **Shopware Backend → Plugin Manager → Installed** — find the plugin, click Install, then Activate.

---

## Configuration

Navigate to: **Backend → Plugin Manager → VendorEstimatedDelivery → Settings**

| Setting | Default | Description |
|---|---|---|
| Enable plugin | Yes | Master on/off toggle |
| Label text | `Estimated delivery` | Text shown before the date |
| Date format (PHP) | `D, d M` | PHP date() format string |
| Processing days | `1` | Business days before dispatch |
| Order cutoff hour | `14` | Orders after this hour count as next-day |
| Skip weekends | Yes | Exclude Sat/Sun from delivery calculation |
| Holidays | _(empty)_ | Comma-separated dates: `2025-12-25, 2026-01-01` |

---

## Plugin Structure

```
VendorEstimatedDelivery/
├── VendorEstimatedDelivery.php          # Plugin bootstrap (install, events)
├── plugin.xml                           # Plugin metadata
├── Components/
│   └── DeliveryHelper.php               # Date calculation logic + config loader
├── Controllers/
│   └── Backend/
│       └── EstimatedDeliveryConfig.php  # AJAX load/save for admin form
└── Views/
    ├── backend/estimated_delivery/
    │   ├── app.js                        # ExtJS app bootstrap
    │   └── app/
    │       ├── controller/main.js        # Save action controller
    │       └── view/main/form.js         # Admin config form
    └── frontend/estimated_delivery/
        ├── detail.tpl                    # Product page badge (Smarty)
        ├── checkout.tpl                  # Checkout page block (Smarty)
        └── estimated_delivery.css        # Frontend styles
```

---

## How It Works

1. On install, a config table `s_plugin_estimated_delivery_config` is created with defaults.
2. The plugin hooks into `PostDispatchSecure_Frontend_Detail` and `PostDispatchSecure_Frontend_Checkout`.
3. `DeliveryHelper` reads the shop config, calculates the delivery window from today — skipping weekends and holidays, adding an extra day if placed after the cutoff hour.
4. The result is assigned to the Smarty view as `$estimatedDelivery` and rendered in the templates.
5. Admin settings are saved/loaded via AJAX to the backend controller.

---

## Template Customisation

Override templates in your theme — copy and edit:

```
themes/Frontend/<YourTheme>/frontend/estimated_delivery/detail.tpl
themes/Frontend/<YourTheme>/frontend/estimated_delivery/checkout.tpl
```

Override CSS by adding styles after the plugin stylesheet in your theme's `_resources/styles/` directory.

---

## Uninstall

```bash
php bin/console sw:plugin:uninstall VendorEstimatedDelivery
php bin/console sw:cache:clear
```

The database table is dropped on uninstall unless **Keep user data** is checked in the Plugin Manager.

---

## License

MIT
