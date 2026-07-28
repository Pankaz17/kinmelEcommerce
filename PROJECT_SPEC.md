# KINMEL E-Commerce System — Project Specification

**Document type:** Phase 0 — Project Planning and Documentation  
**Project nature:** Academic web application (4th semester)  
**Status:** Planning only — no application code in this phase  

---

## 1. Project Title

**KINMEL E-Commerce System**

A web-based online shopping platform designed for academic demonstration of full-stack web development using HTML5, Tailwind CSS, Vanilla JavaScript, PHP 8+, and MySQL/MariaDB.

---

## 2. Project Objective

To design and develop a functional e-commerce web application that allows customers to browse products, manage a shopping cart, and place orders, while enabling administrators to manage products, categories, orders, and view basic reports.

The project aims to demonstrate:

- Clear separation between customer and admin functionality
- Server-side processing with PHP and relational data storage with MySQL/MariaDB
- Responsive UI using HTML5 and Tailwind CSS
- Client-side interaction using Vanilla JavaScript
- Structured software development through phased delivery

---

## 3. Project Nature

| Attribute | Description |
|-----------|-------------|
| Type | Academic web application |
| Delivery | Browser-based system (no native mobile app) |
| Scope | Teaching/demonstration project with core e-commerce flows |
| Architecture style | Traditional multi-page PHP application with shared layout and modular includes |
| Authentication | Session-based login for customers and administrators |

This is **not** a commercial production system. Security, scalability, and payment gateway integration are covered at an academic level only.

---

## 4. Problem Statement

Local and small retail sellers often lack a simple, understandable online storefront. Students and academic evaluators also need a complete, inspectable example of how an e-commerce system is structured—from database design to user and admin workflows.

**KINMEL E-Commerce System** addresses this by providing:

1. A customer-facing store where users can register, browse catalog items, add products to a cart, and place orders.
2. An admin panel where authorized staff can manage products, categories, and orders, and view summary reports.
3. A documented, phased implementation suitable for academic evaluation and further enhancement.

Without such a system, product browsing, order tracking, and inventory/catalog management remain manual and fragmented.

---

## 5. Target Users

| User role | Description | Primary goals |
|-----------|-------------|---------------|
| **Customer (Guest)** | Unregistered visitor | Browse products and categories; view product details |
| **Customer (Registered)** | Logged-in shopper | Manage cart, place orders, view order history, update profile |
| **Administrator** | Store manager / system admin | Manage products & categories, process orders, view reports |
| **Academic evaluator** | Teacher / examiner | Review documentation, code structure, and demonstrated features |

---

## 6. Technology Stack

| Layer | Technology | Role |
|-------|------------|------|
| Markup | **HTML5** | Page structure and semantic content |
| Styling | **Tailwind CSS** | Utility-first responsive styling |
| Client logic | **Vanilla JavaScript** | UI interactions, form validation helpers, cart UX (no heavy frameworks) |
| Server | **PHP 8+** | Routing-style page scripts, business logic, sessions, DB access |
| Database | **MySQL / MariaDB** | Persistent storage for users, products, categories, cart, orders |
| Local stack (dev) | XAMPP / WAMP / Laragon (or equivalent) | Apache + PHP + MySQL/MariaDB for local development |

**Explicitly out of scope for the core stack:** React/Vue/Angular, Node.js backends, ORM-heavy frameworks, and third-party payment SDKs (unless added later as enhancements).

---

## 7. Main Modules

### 7.1 Customer Module

- User registration and login
- Profile view / basic profile update
- Product browsing and search/filter (as implemented)
- Product detail view
- Session-aware navigation (guest vs logged-in)

### 7.2 Admin Module

- Secure admin login
- Dashboard overview (counts / recent activity)
- Access control: admin-only pages
- Navigation to product, category, order, and reporting sections

### 7.3 Product Management

- Create, read, update, and deactivate/delete products (admin)
- Product fields such as name, description, price, stock, image, category, status
- Customer-facing product listing and detail pages

### 7.4 Category Management

- Create, read, update, and delete/disable categories (admin)
- Assign products to categories
- Category-based browsing on the storefront

### 7.5 Cart System

- Add / update / remove cart items
- Persist cart for logged-in users (database) and/or session for guests (as designed in later phases)
- Cart summary (subtotal, item count)
- Proceed-to-checkout entry point

### 7.6 Order Management

- Place order from cart (customer)
- Store order header and line items
- Order status workflow (e.g., Pending → Confirmed → Shipped → Delivered / Cancelled)
- Customer order history and order detail
- Admin order list, detail, and status updates

### 7.7 Reporting

- Admin summary reports such as:
  - Total products / categories / customers / orders
  - Orders by status
  - Basic sales summary (e.g., orders/revenue over a period)
- Simple tables or printable/exportable views (CSV/print optional)

---

## 8. Functional Requirements

### 8.1 Authentication & Users

| ID | Requirement |
|----|-------------|
| FR-01 | Guests can register with required fields (e.g., name, email, password). |
| FR-02 | Registered users can log in and log out using session-based authentication. |
| FR-03 | Passwords must be stored using a secure PHP hashing method (e.g., `password_hash`). |
| FR-04 | Admin users can access an admin area; customers cannot. |
| FR-05 | Unauthorized access to admin pages redirects to login or an error page. |

### 8.2 Catalog (Products & Categories)

| ID | Requirement |
|----|-------------|
| FR-06 | Admin can create, update, and remove/disable categories. |
| FR-07 | Admin can create, update, and remove/disable products, including category assignment. |
| FR-08 | Customers can view active products and categories on the storefront. |
| FR-09 | Customers can open a product detail page with price, description, and availability. |
| FR-10 | Product listing supports basic search and/or category filter (minimum one of these). |

### 8.3 Cart & Checkout

| ID | Requirement |
|----|-------------|
| FR-11 | Logged-in customers can add products to the cart (subject to stock rules). |
| FR-12 | Customers can update quantities and remove items from the cart. |
| FR-13 | Cart displays line totals and a cart subtotal. |
| FR-14 | Customers can place an order from a non-empty cart. |
| FR-15 | Successful order creation clears or archives cart items and records order lines. |

### 8.4 Orders & Reporting

| ID | Requirement |
|----|-------------|
| FR-16 | Customers can view their own order history and order details. |
| FR-17 | Admin can list all orders and view order details. |
| FR-18 | Admin can update order status. |
| FR-19 | Admin dashboard/reporting shows summary metrics (counts and/or simple sales figures). |

### 8.5 General UI

| ID | Requirement |
|----|-------------|
| FR-20 | Pages are usable on desktop and reasonably responsive on mobile via Tailwind CSS. |
| FR-21 | Forms provide validation feedback (client-side and/or server-side). |
| FR-22 | Flash/success/error messages inform users of action outcomes. |

---

## 9. Non-Functional Requirements

| ID | Category | Requirement |
|----|----------|-------------|
| NFR-01 | Usability | Clear navigation between storefront and (for admin) management pages. |
| NFR-02 | Performance | Typical catalog and cart pages should respond acceptably on local XAMPP-class hardware. |
| NFR-03 | Security | Session protection for authenticated areas; prepared statements / parameterized queries against SQL injection; basic XSS escaping on output. |
| NFR-04 | Maintainability | Modular PHP includes (config, header, footer, auth helpers); consistent naming. |
| NFR-05 | Compatibility | Modern Chromium/Firefox/Edge browsers; PHP 8+; MySQL 5.7+/MariaDB 10.3+. |
| NFR-06 | Reliability | Order placement should not leave inconsistent cart/order state on success path. |
| NFR-07 | Documentation | Spec, README, and (in later phases) schema/setup notes sufficient for academic evaluation. |
| NFR-08 | Portability | Project should run on common local stacks (XAMPP/WAMP/Laragon) with documented setup. |

---

## 10. Database Overview

High-level entities (logical model — physical schema in a later phase):

| Entity | Purpose | Key relationships |
|--------|---------|-------------------|
| **users** | Customers and admins | Role distinguishes customer vs admin |
| **categories** | Product grouping | One category → many products |
| **products** | Sellable items | Belongs to category; referenced by cart/order items |
| **cart_items** | Active cart lines | User + product (+ quantity) |
| **orders** | Order header | Belongs to user; has status, totals, timestamps |
| **order_items** | Order line items | Belongs to order; snapshots product info/price |
| **optional: settings / reports views** | Config or derived stats | Not required for MVP |

**Design principles:**

- Relational integrity via foreign keys where supported
- Soft-delete or status flags for products/categories preferred over hard deletes when orders reference them
- Store unit price on order items at purchase time (price snapshot)
- Indexes on foreign keys and common lookup fields (email, status, category_id)

Detailed ER diagram, SQL DDL, and seed data will be produced in the database design phase—not in Phase 0.

---

## 11. Future Enhancement Ideas

These are **optional** and outside the minimum academic MVP:

1. Online payment gateway integration (eSewa, Khalti, Stripe, etc.)
2. Product image gallery and richer media management
3. Product reviews and ratings
4. Wishlist / favorites
5. Coupons and discount codes
6. Email notifications for order status
7. Inventory low-stock alerts for admin
8. Advanced analytics (charts, date-range filters)
9. Multi-admin roles and permissions
10. REST API layer for a future SPA or mobile client

---

## 12. Development Phases

| Phase | Name | Deliverables |
|-------|------|--------------|
| **0** | Project Planning & Documentation | `PROJECT_SPEC.md`, `README.md` *(this phase)* |
| **1** | Environment & Project Skeleton | Folder structure, config stubs, shared layout, Tailwind setup notes |
| **2** | Database Design | ER overview, SQL schema, seed data, connection config |
| **3** | Authentication | Register, login, logout, session, role checks |
| **4** | Admin — Categories & Products | Category/product CRUD and admin UI |
| **5** | Customer Storefront | Catalog, product detail, search/filter |
| **6** | Cart & Checkout | Cart operations and order placement |
| **7** | Order Management & Reporting | Customer history, admin orders, basic reports |
| **8** | Polish & Documentation Closeout | Validation hardening, UI polish, final README updates, demo checklist |

**Phase gate:** Each phase should be confirmed before the next begins.

---

## 13. Scope Boundaries (Phase 0)

**In scope for Phase 0**

- Project specification document
- Project README / roadmap

**Out of scope for Phase 0**

- PHP application files
- Database scripts or dumps
- UI pages / Tailwind-built screens
- CRUD or business logic implementation

---

## 14. Document Control

| Field | Value |
|-------|-------|
| Phase | 0 — Planning |
| Last updated | 2026-07-28 |
| Next step | Await confirmation → Phase 1 (Environment & Project Skeleton) |
