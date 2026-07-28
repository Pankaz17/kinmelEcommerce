# KINMEL E-Commerce System

Web-based academic e-commerce project for browsing products, managing a shopping cart, placing orders, and administering catalog and order data.

**Stack:** HTML5 · Tailwind CSS · Vanilla JavaScript · PHP 8+ · MySQL/MariaDB  

**Current phase:** Phase 0 — Project Planning and Documentation (no application code yet)

---

## Introduction

**KINMEL E-Commerce System** is a browser-based online store built for academic demonstration. It provides:

- A **customer storefront** for registration, catalog browsing, cart management, and checkout
- An **admin panel** for products, categories, orders, and simple reporting

The system is intentionally implemented with a classic PHP + MySQL architecture so the full request–response flow remains easy to inspect and evaluate.

For full requirements, modules, and phase gates, see [`PROJECT_SPEC.md`](./PROJECT_SPEC.md).

---

## Features Overview

### Customer

- Register / login / logout
- Browse products by category
- View product details
- Add items to cart, update quantities, remove items
- Place orders
- View order history and order details

### Admin

- Secure admin login and dashboard
- Category management (create, update, disable/delete)
- Product management (create, update, disable/delete, assign category)
- Order list, order detail, and status updates
- Basic reports (counts, order status summary, simple sales overview)

### Cross-cutting

- Session-based authentication and role separation (customer vs admin)
- Responsive layout with Tailwind CSS
- Client-side helpers with Vanilla JavaScript
- Server-side logic and persistence with PHP 8+ and MySQL/MariaDB

> Features above describe the **planned** system. Implementation starts after Phase 0 confirmation.

---

## Technology Requirements

| Requirement | Recommended |
|-------------|-------------|
| OS | Windows 10/11 (project developed on Windows) |
| Web server | Apache (via XAMPP, WAMP, or Laragon) |
| PHP | **8.0 or newer** (8.1+ preferred) |
| Database | **MySQL 5.7+** or **MariaDB 10.3+** |
| Browser | Latest Chrome, Edge, or Firefox |
| CSS | Tailwind CSS (CDN for early phases, or build step if introduced later) |
| Editor | VS Code / Cursor (optional) |

**Not required for MVP:** Node.js runtime for the app itself, Composer packages (unless added later), React/Vue, Docker.

---

## Installation Instructions

> **Note:** Application code and database schema are not created in Phase 0. Use these steps once Phases 1–2 exist.

### 1. Prerequisites

1. Install [XAMPP](https://www.apachefriends.org/), [Laragon](https://laragon.org/), or an equivalent Apache + PHP + MySQL stack.
2. Start **Apache** and **MySQL**.
3. Confirm PHP version: `php -v` (must be 8+).

### 2. Project placement

1. Clone or copy this project into your web root, for example:
   - XAMPP: `C:\xampp\htdocs\kinmelEcommerce`
   - Or keep the project at `E:\vibecoding project\4thsem\kinmelEcommerce` and configure a virtual host / alias to that path.
2. Open the project folder in your editor.

### 3. Database setup (after Phase 2)

1. Open phpMyAdmin (or MySQL CLI).
2. Create a database (name TBD in schema docs, e.g. `kinmel_ecommerce`).
3. Import the project SQL schema / seed file when available.
4. Copy or edit `config` settings (host, db name, user, password) to match your local MySQL.

### 4. Run the application (after Phase 1+)

1. Browse to the local URL, e.g. `http://localhost/kinmelEcommerce/` (path depends on placement).
2. Use seeded admin credentials (documented when seed data is added).
3. Register a customer account to test the storefront.

### 5. Tailwind CSS

Early phases may load Tailwind via CDN in the shared layout. If a build pipeline is introduced later, follow the Tailwind install notes added in that phase.

---

## Planned Folder Structure

Structure below is the **intended** layout for later phases. Only documentation files exist after Phase 0.

```text
kinmelEcommerce/
├── PROJECT_SPEC.md          # Full project specification (Phase 0)
├── README.md                # This file
├── assets/                  # Static assets (planned)
│   ├── css/                 # Custom CSS if needed beyond Tailwind
│   ├── js/                  # Vanilla JavaScript modules
│   └── images/              # Product / UI images
├── config/                  # DB and app configuration (planned)
├── includes/                # Shared PHP: header, footer, auth helpers (planned)
├── admin/                   # Admin module pages (planned)
├── customer/                # Customer account / order pages (planned)
├── api/ or actions/         # Form handlers / small endpoints (planned, name TBD)
├── uploads/                 # Uploaded product images (planned)
└── database/                # SQL schema and seed scripts (planned)
```

| Path | Purpose |
|------|---------|
| `PROJECT_SPEC.md` | Requirements, modules, NFRs, phases |
| `README.md` | Setup, features, roadmap |
| `assets/` | Front-end static files |
| `config/` | Environment-specific settings (not committed secrets in real deploys) |
| `includes/` | Reusable PHP fragments and helpers |
| `admin/` | Admin-only screens |
| `customer/` | Logged-in customer screens |
| `database/` | Schema and seed SQL |

Exact filenames may be adjusted in Phase 1 when the skeleton is created.

---

## Development Roadmap

| Phase | Focus | Status |
|-------|--------|--------|
| **0** | Planning docs (`PROJECT_SPEC.md`, `README.md`) | **Complete — awaiting confirmation** |
| **1** | Environment & project skeleton (folders, shared layout, config stubs) | Not started |
| **2** | Database design (ER, SQL schema, seeds, connection) | Not started |
| **3** | Authentication (register, login, logout, roles) | Not started |
| **4** | Admin category & product management | Not started |
| **5** | Customer storefront (catalog, detail, search/filter) | Not started |
| **6** | Cart & checkout / order placement | Not started |
| **7** | Order management & reporting | Not started |
| **8** | Polish, validation hardening, final documentation | Not started |

### Phase gate

Do **not** start Phase 1 until Phase 0 documentation is reviewed and confirmed.

---

## Documentation Index

| Document | Description |
|----------|-------------|
| [PROJECT_SPEC.md](./PROJECT_SPEC.md) | Objectives, problem statement, stack, modules, FR/NFR, DB overview, phases |
| [README.md](./README.md) | Introduction, features, install guide, folder plan, roadmap |

---

## Academic Notice

This project is developed for **educational purposes**. It demonstrates core e-commerce workflows and should not be treated as a production-ready commercial platform without additional security hardening, payment compliance, and operational tooling.

---

## Next Step

**Waiting for confirmation** to proceed to **Phase 1: Environment & Project Skeleton**.
