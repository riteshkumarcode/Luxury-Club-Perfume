# Luxury Club — "The Magic of Luxury Fragrances"

A production-ready, highly dynamic e-commerce web platform for the luxury perfume brand **Luxury Club**, crafted in **PHP 8.2+ (MVC-lite)** with a custom CSS design system, vanilla ES6 JavaScript modules, GSAP 3 animations, Swiper 11, and Lenis smooth scrolling.

---

## ✦ Key Features

- **Architecture:** Clean MVC-lite architecture with Front Controller, prepared PDO statements, secure session management, and CSRF protection across all forms and AJAX endpoints.
- **Design System:** Custom Dark Luxury & Warm Ivory design system (`app.css`, `components.css`, `pages.css`, `admin.css`) with CSS custom properties, arch geometries, multiply-blended transparent product flacons, Bodoni Moda serif typography, and 1.5px stroke SVG icons.
- **Hero Slider:** Full-bleed Swiper 11 slider with animated text timelines (GSAP), Ken Burns zoom, dynamic background crossfades, pointer parallax, slide tabs with filling progress bars, and giant outline words.
- **Shop & Discovery:** Server-rendered and client-enhanced product filtering by category, price range, size, bestsellers, and sort options.
- **Product Experience (PDP):** Interactive 1.9x hover zoom, category sibling swatches, fragrance pyramid, real-time quantity stepper, PIN code delivery checker, single-open accordions, sticky mobile add-to-bag bar, and recently viewed tracker.
- **Cart & Wishlist:** Session and cookie-persisted shopping bag, slide-out drawer with free shipping threshold progress bar, live toast notifications, and wishlist sync.
- **Checkout & Payments:** Single-page checkout with Indian states selection, Razorpay payment gateway integration, and Cash on Delivery support.
- **Transactional Emails:** Styled HTML order confirmation and admin alert emails powered by PHPMailer.
- **Comprehensive Admin Panel (`/admin`):**
  - Secure rate-limited login (5 attempts / 15 mins).
  - Atelier overview with live statistics and recent orders.
  - Complete CRUD for Products (with live image blend preview & color picker), Categories, and Hero Slides.
  - Order management with fulfillment status workflow, courier tracking numbers, and printable tax invoices.
  - Client inquiry inbox and subscriber CSV export.
  - Comprehensive store settings and client policies editor.
- **SEO & Performance:** Automated JSON-LD (`Organization`, `Product`, `BreadcrumbList`, `FAQPage`), dynamic `/sitemap.xml`, `/robots.txt`, and Open Graph tags.

---

## ✦ Local Development Setup

### 1. Requirements
- PHP 8.2 or higher (with `pdo`, `pdo_sqlite` or `pdo_mysql`, `gd`, `fileinfo`, `intl`, `curl`)
- Composer (or use pre-generated autoloader)

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Configuration
Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```
*(On first run, `DB_CONNECTION=sqlite` is enabled by default for zero-configuration instant local development. For MySQL 8, set `DB_CONNECTION=mysql` and update database credentials).*

### 4. Database Setup
The application automatically checks for tables and seeds the 4 categories, 14 products, 4 hero slides, default settings, and admin user on first visit.

Alternatively, for manual MySQL 8 setup:
```bash
mysql -u root -p -e "CREATE DATABASE luxury_club CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p luxury_club < database/schema.sql
mysql -u root -p luxury_club < database/seed.sql
```

### 5. Launch Local Development Server
```bash
php -S localhost:8000 -t public
```
Open **[http://localhost:8000](http://localhost:8000)** in your browser.

---

## ✦ Admin Portal & Default Credentials

- **Admin URL:** [http://localhost:8000/admin](http://localhost:8000/admin)
- **Default Email:** `admin@luxuryclub.com`
- **Default Password:** `AdminLuxury2026!`

*(Note: The admin panel is protected with rate limiting and secure session regeneration).*

---

## ✦ Deployment to Netlify (Global Jamstack CDN)

The project includes an automatic pre-rendering static engine ([build.php](file:///d:/Luxury%20club%20site/build.php)) and [netlify.toml](file:///d:/Luxury%20club%20site/netlify.toml) configured for 1-click Netlify deployments:

### Option A: Continuous Deployment via Git (GitHub / GitLab / Bitbucket)
1. Push this repository to GitHub.
2. In Netlify Dashboard, click **Add new site** → **Import an existing project** → select your Git repo.
3. Netlify will automatically detect the settings from `netlify.toml`:
   - **Build Command:** `php build.php` (or `npm run build`)
   - **Publish Directory:** `dist`
4. Click **Deploy site**. Netlify will pre-render all 14 product pages, category catalogs, policy pages, sitemap, search indexes, and assets directly to its global Edge CDN!

### Option B: Drag-and-Drop or Netlify CLI
1. Run the local static build generator:
   ```bash
   php build.php
   ```
2. Deploy the generated `dist/` directory:
   - **Drag & drop** the `dist/` folder into [app.netlify.com/drop](https://app.netlify.com/drop)
   - Or deploy via CLI: `npx netlify deploy --prod --dir=dist`

---

## ✦ Deployment to Shared Hosting (cPanel / Apache)

1. **Upload Files:** Upload the entire project directory to your server (outside or inside `public_html`).
2. **Document Root:** Point your domain's Document Root to the `/public` folder.
   - *Fallback:* If your host does not allow modifying the document root, the root `.htaccess` file will automatically forward requests to `/public/`.
3. **Database:** Import `database/schema.sql` and `database/seed.sql` via phpMyAdmin / MySQL CLI.
4. **Environment:** Create `.env` on the server and update your production MySQL, Razorpay, and SMTP credentials.
5. **Permissions:** Ensure `/storage/logs`, `/storage/cache`, and `/public/uploads` are writable (`chmod 755` or `775`).

---

## ✦ File Structure Overview

```
/public                 ← Web root
  index.php             ← Front controller
  .htaccess             ← URL rewrites, gzip, caching
  /assets/css/          ← app.css, components.css, pages.css, admin.css
  /assets/js/           ← app.js, slider.js, cart.js, search.js, product.js, forms.js, motion.js
  /assets/img/products/ ← 14 product images & brand assets
  /uploads/             ← Admin uploaded images (.htaccess protected)
/app
  /core                 ← Router, Database, View, Session, Csrf, Validator, Helpers, Mailer
  /controllers          ← HomeController, ShopController, ProductController, PageController,
                          ContactController, CartController, WishlistController, SearchController,
                          NewsletterController, CheckoutController, Admin/*
  /models               ← Product, Category, Cart, Order, Message, Subscriber, AdminUser, Setting, HeroSlide
  /views
    /layouts            ← main.php, admin.php
    /partials           ← header, footer, announcement, newsletter, cart-drawer,
                          search-overlay, product-card, toast, breadcrumb
    /icons              ← Inline 1.5px SVG partials (crown, bag, heart, etc.)
    /pages              ← home, shop, product, about, contact, faq, cart, wishlist, checkout, order-success, policy, 404
    /admin              ← dashboard, login, products, categories, slides, orders, messages, subscribers, settings
/config                 ← config.php (reads .env)
/database               ← schema.sql, seed.sql
/storage                ← logs/, cache/
```
