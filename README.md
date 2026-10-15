<h1 align="center">🛍️ Vendora — Multi-Vendor E-Commerce Platform</h1>

<p align="center">
  A full-featured multi-vendor e-commerce platform built with <strong>Laravel 12</strong> and <strong>Bootstrap CSS v5</strong>, supporting three distinct user roles: Super Admin, Admin/Merchant, and Customer.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12"/>
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2"/>
  <img src="https://img.shields.io/badge/Bootstrap-v5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap CSS"/>
  <img src="https://img.shields.io/badge/Vite-7-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite"/>
</p>

---

## 📌 Overview

**Vendora** is a production-ready multi-vendor e-commerce system where merchants can register and manage their own product listings after admin approval. The platform features a clean customer-facing storefront alongside a powerful admin dashboard, all powered by a clean Laravel 12 architecture.

---

## ✨ Core Features

### 🔐 Authentication & Authorization

- **Dual authentication system** — separate guards for the dashboard (`dashboard` guard for Admins) and the storefront (`ecomm` guard for Customers/Merchants)
- **Role-Based Access Control (RBAC)** with three roles: `super admin`, `admin`, and `merchant`
- **Laravel Gates & Policies** — fine-grained authorization using `ProductPolicy` and custom Gate definitions (`show-dashboard`, `delete-access`, `update-access`, `merchant-update-product`)
- **Merchant access to dashboard** — approved merchants log in through the same dashboard login and get access to their product management panel
- **Middleware protection** on all dashboard and storefront routes (`AuthAdminMiddleware`, `AuthEcommMiddleware`)
- Password change with current-password verification
- Profile image upload with old image cleanup

---

### 👥 User Roles & Capabilities

| Feature | Super Admin | Admin | Merchant | Customer |
|---|---|---|---|---|
| Manage Admins (CRUD) | ✅ | ❌ | ❌ | ❌ |
| Manage All Products | ✅ | ✅ | Own only | ❌ |
| Manage Categories | ✅ | ✅ | ❌ | ❌ |
| Approve / Reject Merchants | ✅ | ✅ | ❌ | ❌ |
| View Customers & Spend | ✅ | ✅ | ❌ | ❌ |
| View Messages | ✅ | ✅ | ❌ | ❌ |
| Receive Notifications | ✅ | ✅ | ❌ | ❌ |
| Add to Cart / Wishlist | ❌ | ❌ | ❌ | ✅ |
| Browse & Shop | ❌ | ❌ | ❌ | ✅ |

---

### 🏪 Merchant System

- Customers can **apply to become a merchant** — a merchant record is created with a `pending` status
- Admins **approve or reject** merchants from the dashboard
- On approval/rejection, **automated emails** are dispatched via Laravel Events & Listeners:
  - `MerchantApproved` event → `SendMailToMerchantApproved` listener → `MerchantApprovedMail`
  - `MerchantRejected` event → `SendMailToMerchantRejected` listener → `MerchantRejectedMail`
- Approved merchants gain **dashboard access** and can manage only their own products (enforced via Gate)
- Merchant can be demoted back to customer by destroying their merchant record

---

### 🛒 E-Commerce Storefront

- **Home page** with latest products, best-sellers section, and category listings — all **cached for 1 hour** using `Cache::put()` and `Cache::memo()`
- **Shop page** with:
  - Real-time **search by product name**
  - **Filter by category**
  - **Sort options**: Newest, Price (Low → High), Price (High → Low), Most Popular
  - **Pagination** (12 products per page) with preserved query string
- **Product details page** with full description and images
- **Quick view** modal for fast product preview
- **Category browsing** page

---

### 🛍️ Shopping Cart

- Add products to cart with **quantity tracking**
- Update item quantities from the cart page
- Remove individual items or **clear the entire cart**
- Cart **persists in the database** (linked to authenticated user)
- Cart count visible across the storefront

---

### ❤️ Wishlist

- Add/remove products to a personal wishlist
- **Move all wishlist items to cart** in one click
- **Clear entire wishlist** at once
- Wishlisted products are visually highlighted on the shop and home pages (server-side `wishlistedIds` check)

---

### 📦 Product Management

- Full **CRUD** for products from the dashboard
- **Multiple image support** per product (via `Image` model and `saveImg`/`deleteImg` helpers)
- Automatic **category product count** tracking on create/delete
- Product fields: name, price, discount, stock count, sold quantity, description, category, merchant
- **Cache invalidation** on every product create/update/delete (`products.latest`, `best_products_sold`, `all_categories`)

---

### 🗂️ Category Management

- Full **CRUD** for categories with automatic `num_products` counter
- Categories with zero products are hidden from the storefront

---

### 👤 Customer Management (Admin Panel)

- View all registered customers with:
  - Cart item count and wishlist count
  - **Total amount spent** (calculated dynamically from cart × product price)
- Add new customers directly from the dashboard (with optional role assignment)
- Delete customer accounts with image cleanup

---

### 🔔 Notifications System

- **Database + email notifications** using Laravel's built-in Notifiable system
- Admins receive a database notification when a new customer registers (`RegisterClient` notification)
- Notification center page showing: total notifications, unread count, notification content
- **Mark as read** functionality per notification

---

### 📩 Messages / Contact System

- Customers can send contact messages from the storefront
- Messages are stored in the database (`user_messages` table)
- Dashboard page displays all messages with **read/unread status**
- Admins can mark messages as viewed or **delete** them

---

### 👨‍💼 Admin Profile

- Personal profile page for the logged-in admin/staff member
- **AJAX-based profile image upload** (returns JSON with new image URL)
- Secure password change with current-password check and confirmation

---

### 📧 Email System

- **Event-driven mailable architecture** using Laravel Events, Listeners, and Mailables
- `UserRegistered` event → `SendMailOnUserRegistered` listener → `UserAddedMail`
- `MerchantApproved` event → `MerchantApprovedMail`
- `MerchantRejected` event → `MerchantRejectedMail`
- All mail classes are queue-ready

---

### ⚡ Performance & Caching

- Homepage data (latest products, best-sellers, categories) cached for **1 hour**
- `Cache::memo()` used for in-request memoization of category queries
- Cache is **automatically invalidated** on any product or category change
- Eager loading (`with()`) used throughout to prevent N+1 queries

---

## 🏗️ Architecture & Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 (PHP 8.2) |
| Frontend Styling | Bootstrap CSS v5 & HTML & JQuery |
| Build Tool | Vite 7 |
| HTTP Client | Axios |
| Database | MySQL |
| Testing | Pest v3 + PestPlugin for Laravel |
| Code Style | Laravel Pint |
| Dev Server | Laravel Sail / XAMPP |

### Key Laravel Features Used

- **Multiple Authentication Guards** (`dashboard`, `ecomm`)
- **Gates & Policies** for RBAC
- **Events & Listeners** (event-driven architecture)
- **Notifications** (database + mail channels)
- **Mailables** with queued support
- **Form Requests** for validation (`ProductAddRequest`, `AdminAddRequest`, etc.)
- **Eloquent Relationships** (HasOne, HasMany, BelongsTo)
- **Cache** (store, retrieve, invalidate)
- **Route Groups & Named Routes**
- **Middleware** (custom auth middleware per guard)
- **File Storage** (`storage_path`, `move_uploaded_file`)
- **Pagination** with query string preservation

---

## 🗃️ Database Schema

```
admins          — Dashboard staff (name, email, password, role, gender, phone, location, age, img)
ecomm_users     — Storefront users (first_name, last_name, email, phone, password, image, role)
merchents       — Merchant profiles (user_id, status, approved_at, rejected_at, rejected_reason)
cats            — Product categories (name, num_products, ...)
products        — Product listings (name, price, discount, count, sold_quantity, cat_id, merchent_id)
images          — Product images (product_id, img_name)
carts           — Cart items (user_id, product_id, count)
whilists        — Wishlist items (user_id, product_id)
user_messages   — Contact form messages
notifications   — Laravel database notifications
```

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL or SQLite

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/your-username/laravel-vendora-ecommerce.git
cd laravel-vendora-ecommerce

# 2. Run the setup script (installs deps, generates key, migrates, builds assets)
composer run setup

# 3. Configure environment
cp .env.example .env
# Edit .env with your DB credentials and mail settings
```

### Development Server

```bash
# Starts Laravel, queue worker, Pail log viewer, and Vite simultaneously
composer run dev
```

### Running Tests

```bash
php artisan test --compact
```

---

## 📁 Project Structure

```
app/
├── Events/          # MerchantApproved, MerchantRejected, UserRegistered
├── Listeners/       # Email dispatch listeners
├── Mail/            # Mailables (approved, rejected, user added)
├── Models/          # Admin, EcommUser, Merchent, Product, Cat, Cart, Whilist, Image
├── Notifications/   # RegisterClient (db + mail)
├── Policies/        # ProductPolicy
├── Http/
│   ├── Controllers/ # AdminController, ProductController, EcommController, etc.
│   ├── Middleware/  # AuthAdminMiddleware, AuthEcommMiddleware
│   └── Requests/    # Form request validators
resources/views/
├── Dashboard/       # Admin & merchant panel views
└── Ecommerce/       # Customer-facing storefront views
routes/
├── dashboard.php    # Protected dashboard routes
└── web.php          # Storefront routes
```

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).
