 Finexy POS - Modern Point of Sale System

A full-featured, modern, and high-performance **Point of Sale (POS) System** built with **Laravel 11, Tailwind CSS, and Alpine.js**. Designed for retail stores, supermarkets, and restaurants to manage inventory, sales transactions, cashier terminals, receipt printing, and revenue analytics.

---

 Tech Stack & Languages Used

* **Backend Framework:** PHP 8.2+ / Laravel 11
* **Frontend Framework:** Blade Templates, Tailwind CSS (CDN/Vite), Alpine.js
* **Database:** MySQL / PostgreSQL
* **Authentication:** Laravel Breeze, OAuth 2.0 (Google & GitHub Socialite)
* **Icons & Styling:** SVG Vector Icons, Plus Jakarta Sans Font
* **Version Control:** Git & GitHub

---

 Key System Features

**Multi-Role Authentication:** Separate Dashboards & Access Controls for **Admin** and **Cashier Staff**.
**OAuth Integration:** One-click Login with Google & GitHub accounts.
**Category & Inventory Management:** Real-time Product Catalog with Images, SKU tracking, and Auto-Stock deduction upon checkout.
**Interactive POS Terminal:** Live search, Category filters, Dynamic Cart Management, Tax calculation, Discounts, and Change-return calculator.
**Thermal Receipt Printing:** Instant printable customer receipts with browser print/PDF export.
**Sales Reports & Analytics:** Revenue overview, Date-range filters, Top-selling products tracking, and CSV/Excel Export.
**Store Configurations:** Customizable Store Name, Address, Contact Info, Tax Rates, and Currency Symbols.

---

Step 1 to Step 12 Development Architecture

### **Step 1: Environment & Project Setup**
- Laravel 11 installation and environment setup.
- Custom Tailwind CSS styling integration matching the Finexy Dashboard theme.

### **Step 2: Database Migrations & Authentication Setup**
- Users, Categories, Products, Orders, Order Items, and Settings tables creation.
- Laravel Breeze authentication scaffolding.

### **Step 3: Multi-Role Middleware & Access Control**
- Role-based authorization (`admin` vs `staff`).
- Route protection middleware (`CheckRole`).

### **Step 4: OAuth Socialite Authentication**
- Google & GitHub OAuth redirect and callback integration.

### **Step 5: User Profile & Avatar Management**
- Custom avatar picture upload and profile settings update.

### **Step 6: Category Management (CRUD)**
- Active/Inactive status toggles and full category CRUD operations.

### **Step 7: Product & Stock Management (CRUD)**
- Product creation with Image uploading, SKU auto-generation, Price, and Stock limits.

### **Step 8: Interactive POS Cashier Checkout Terminal**
- Live search catalog, cart calculation (Subtotal, 5% Tax, Discount, Total), and AJAX checkout.

### **Step 9: Sales History & Thermal Receipt Printing**
- Order history tracking and thermal print view.

### **Step 10: Reports, Analytics & CSV Export**
- Sales revenue analytics dashboard, top-selling items query, and streamed CSV export.

### **Step 11: Store Configurations & Database Seeders**
- Setting Key-Value storage system and automated database seeder for default Admin, Staff, Categories, and Products.

### **Step 12: Production Optimization & Deployment**
- Security hardening (`APP_DEBUG=false`), route/config/view caching optimization, and production readiness.

---
Local Installation & Setup Guide

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/your-username/laravel-pos-system.git](https://github.com/your-username/laravel-pos-system.git)
   cd laravel-pos-system
