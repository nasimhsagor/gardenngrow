# GardenNGrow — Complete Laravel E-Commerce Project Blueprint

> **Purpose:** This document is a master prompt designed to be fed to Claude (Anthropic AI) in sequential phases to build a complete, production-ready e-commerce platform for an online nursery and plant store.
>
> **How to use:** Copy each phase into Claude as a separate conversation. Complete Phase 1 before starting Phase 2, and so on. Each phase is self-contained with enough context for Claude to produce working code.

---

## Project Identity

| Field              | Value                                                  |
| ------------------ | ------------------------------------------------------ |
| Project Name       | GardenNGrow                                            |
| Domain             | gardenngrow.com                                        |
| Business Type      | Online nursery / plant & gardening e-commerce          |
| Owner Location     | Bangladesh                                             |
| Primary Currency   | BDT (৳) — with multi-currency support architecture     |
| Languages          | Bangla (primary) + English                             |
| Target Audience    | Urban plant lovers, home gardeners, gift buyers in BD  |

---

## Tech Stack & Constraints

```
Framework        : Laravel 11.x (latest stable — NOT "13", which doesn't exist yet)
PHP              : 8.3+
Database         : MySQL 8.0+
Frontend CSS     : Tailwind CSS 3.x + Alpine.js for interactivity
Admin UI         : Filament v3 (Laravel admin panel package)
Auth             : Laravel Breeze (Blade stack) for customer, Filament Shield for admin
Queue            : Laravel Queue with database driver (Redis optional)
Search           : Laravel Scout with Meilisearch driver
File Storage     : Laravel Filesystem (local + S3-compatible for production)
Payment          : SSLCommerz (Bangladesh), Stripe (international) — via strategy pattern
Email            : Laravel Mail with queue, SMTP configurable from admin
Cache            : File driver default, Redis-ready
Testing          : Pest PHP
CI               : GitHub Actions workflow included
Deployment       : Laravel Forge / shared hosting (cpanel) compatible
```

> **Important for Claude:** Always use the latest stable Laravel version (currently 11.x). Do NOT reference Laravel 13 — it does not exist. Use `composer create-project laravel/laravel` without specifying a version to get the latest stable.

---

## Architecture Principles

Follow these patterns strictly in all generated code:

1. **Repository Pattern** — All Eloquent queries go through repository interfaces + concrete implementations. Never call Eloquent directly from controllers.
2. **Service Layer** — Business logic lives in `App\Services\`. Controllers are thin — they call services, services call repositories.
3. **Action Classes** — Single-purpose classes in `App\Actions\` for complex operations (e.g., `PlaceOrderAction`, `ApplyCouponAction`).
4. **Form Request Validation** — Every store/update request uses a dedicated FormRequest class. No inline validation.
5. **API Resources** — Use `JsonResource` / `ResourceCollection` for all API responses, even if views are Blade-based. This keeps the project API-ready.
6. **DTOs** — Use Data Transfer Objects for passing structured data between layers (use `spatie/laravel-data`).
7. **Events & Listeners** — Decouple side effects (emails, notifications, analytics) from main logic using events.
8. **Enums** — Use PHP 8.3 native enums for statuses (OrderStatus, PaymentStatus, etc.).
9. **Policies** — Authorization via Laravel Policies for every model.
10. **Strict Types** — `declare(strict_types=1)` in every PHP file. Type-hint everything.

---

## Development Phases

### PHASE 1: Foundation & Database Architecture

**Prompt for Claude:**

```
You are a senior Laravel architect. Set up the foundation for "GardenNGrow", an
e-commerce platform for plants and gardening products in Bangladesh.

TASK: Generate the following, one file at a time, with complete production-ready code.

1. Project folder structure (show the full tree)
2. All database migrations in the correct order

SCHEMA REQUIREMENTS (generate migrations for ALL of these):

USERS & AUTH:
- users: id, name, email, phone, password, avatar, email_verified_at, phone_verified_at, locale (default 'bn'), is_active, remember_token, timestamps, soft_deletes
- addresses: id, user_id (FK), label (enum: home/office/other), full_name, phone, address_line_1, address_line_2, city, district, division, postal_code, is_default, timestamps
- password_reset_tokens: email, token, created_at

ADMIN & RBAC:
- Use Filament Shield (spatie/laravel-permission under the hood)
- admins: id, name, email, password, avatar, is_super_admin, is_active, timestamps

CATALOG:
- categories: id, parent_id (self-referencing FK, nullable), slug, icon, image, sort_order, is_active, timestamps
- category_translations: id, category_id (FK), locale, name, description
- products: id, category_id (FK), slug, sku, barcode (nullable), price, compare_price (nullable), cost_price (nullable), stock_quantity, low_stock_threshold (default 5), weight_grams (nullable), is_active, is_featured, is_new_arrival, requires_shipping, tax_rate (decimal, default 0), plant_type (enum: indoor/outdoor/both/not_plant, nullable), sunlight (enum: full_sun/partial_shade/full_shade/any, nullable), watering (enum: daily/weekly/biweekly/monthly, nullable), difficulty (enum: beginner/intermediate/expert, nullable), mature_size (varchar, nullable), timestamps, soft_deletes
- product_translations: id, product_id (FK), locale, name, short_description, description, care_instructions (text, nullable), meta_title, meta_description
- product_images: id, product_id (FK), path, alt_text, sort_order, is_primary, timestamps
- product_variants: id, product_id (FK), name (e.g., "Small Pot"), sku, price_modifier (decimal, can be negative), stock_quantity, is_active, timestamps

ORDERS & COMMERCE:
- carts: id, user_id (FK nullable — for guest carts use session), session_id (nullable), coupon_id (FK nullable), timestamps
- cart_items: id, cart_id (FK), product_id (FK), variant_id (FK nullable), quantity, unit_price, timestamps
- orders: id, user_id (FK), order_number (unique, auto-generated like GNG-20240101-XXXX), status (enum: pending/confirmed/processing/shipped/out_for_delivery/delivered/cancelled/returned/refunded), payment_status (enum: unpaid/paid/partially_refunded/refunded), payment_method (enum: cod/sslcommerz/stripe/bkash), subtotal, discount_amount, shipping_amount, tax_amount, total, coupon_id (FK nullable), shipping_address (json), billing_address (json), notes (text nullable), shipped_at, delivered_at, cancelled_at, timestamps, soft_deletes
- order_items: id, order_id (FK), product_id (FK), variant_id (FK nullable), product_name, product_sku, quantity, unit_price, total_price, timestamps
- payments: id, order_id (FK), transaction_id (unique), payment_method, amount, currency (default BDT), status (enum: pending/completed/failed/refunded), gateway_response (json nullable), paid_at (nullable), timestamps

MARKETING:
- coupons: id, code (unique), type (enum: fixed/percentage), value (decimal), min_order_amount (nullable), max_discount_amount (nullable), usage_limit (nullable), used_count (default 0), starts_at, expires_at, is_active, timestamps
- wishlists: id, user_id (FK), product_id (FK), timestamps (unique composite: user_id + product_id)
- newsletters: id, email (unique), is_subscribed, subscribed_at, unsubscribed_at, timestamps

CONTENT:
- reviews: id, user_id (FK), product_id (FK), rating (tinyint 1-5), title (nullable), comment (text nullable), is_approved, admin_reply (text nullable), timestamps
- blog_categories: id, slug, timestamps
- blog_category_translations: id, blog_category_id (FK), locale, name
- blogs: id, blog_category_id (FK), slug, featured_image, author_id (FK to admins), is_published, published_at, timestamps
- blog_translations: id, blog_id (FK), locale, title, excerpt, content (longText), meta_title, meta_description
- banners: id, type (enum: hero_slider/popup/promotional), image, mobile_image (nullable), link (nullable), sort_order, starts_at (nullable), expires_at (nullable), is_active, timestamps
- banner_translations: id, banner_id (FK), locale, title, subtitle, button_text

SETTINGS:
- settings: id, group (varchar), key (varchar, unique), value (text nullable), type (enum: text/textarea/number/boolean/json/image), timestamps
- pages: id, slug (unique), timestamps
- page_translations: id, page_id (FK), locale, title, content (longText), meta_title, meta_description

INDEXING REQUIREMENTS:
- Index all foreign keys
- Index: products.slug, products.sku, products.is_active, products.is_featured
- Index: categories.slug, categories.is_active
- Index: orders.order_number, orders.status, orders.user_id
- Index: coupons.code
- Composite index: product_translations(product_id, locale)
- Composite index: category_translations(category_id, locale)

Generate each migration as a separate file with proper naming convention
(YYYY_MM_DD_HHMMSS_create_xxx_table.php). Use $table->id() for primary keys.
Use constrained foreign keys. Add comments explaining non-obvious columns.
```

---

### PHASE 2: Models, Relationships & Enums

**Prompt for Claude:**

```
Continue building the GardenNGrow Laravel e-commerce project.

CONTEXT: Phase 1 migrations are complete. Now generate ALL Eloquent models.

REQUIREMENTS FOR EVERY MODEL:
- declare(strict_types=1)
- Proper namespace
- All fillable arrays
- All casts (especially enums, decimals, dates, json, booleans)
- All relationships (belongsTo, hasMany, belongsToMany, morphTo, etc.)
- Scopes for common queries (e.g., scopeActive, scopeFeatured, scopePublished)
- Accessors/Mutators where useful (e.g., formatted_price, full_address)
- Use HasFactory trait
- PHPDoc blocks for IDE support

GENERATE THESE ENUMS in App\Enums\:
- OrderStatus (with labels, colors for badges, and transition rules)
- PaymentStatus
- PaymentMethod
- PlantType
- SunlightRequirement
- WateringFrequency
- DifficultyLevel
- CouponType
- BannerType
- AddressLabel
- SettingType

Each enum should implement:
- A label() method returning human-readable text
- A color() method returning a Tailwind CSS color class (for admin badges)
- A static options() method returning [value => label] for form selects

GENERATE THESE MODELS (complete code for each):
User, Address, Admin, Category, CategoryTranslation, Product,
ProductTranslation, ProductImage, ProductVariant, Cart, CartItem,
Order, OrderItem, Payment, Coupon, Wishlist, Newsletter, Review,
BlogCategory, BlogCategoryTranslation, Blog, BlogTranslation,
Banner, BannerTranslation, Setting, Page, PageTranslation

ALSO GENERATE:
- App\Traits\HasTranslations (reusable trait for models with translation tables)
- App\Traits\HasSlug (auto-generates slug from a source field)
- App\Observers\ProductObserver (clear cache on product changes)
- App\Observers\OrderObserver (generate order_number on creating)
```

---

### PHASE 3: Repository & Service Layer

**Prompt for Claude:**

```
Continue GardenNGrow. Models and Enums are done.

TASK: Build the Repository Pattern and Service Layer.

STRUCTURE:
App\Repositories\Contracts\   — Interfaces
App\Repositories\Eloquent\    — Implementations
App\Services\                  — Business logic

GENERATE THESE REPOSITORY INTERFACES + IMPLEMENTATIONS:

1. ProductRepositoryInterface / ProductRepository
   - getActive(filters: array, perPage: int): LengthAwarePaginator
   - getFeatured(limit: int): Collection
   - getNewArrivals(limit: int): Collection
   - getByCategory(categoryId: int, filters: array, perPage: int): LengthAwarePaginator
   - getRelated(product: Product, limit: int): Collection
   - search(query: string, perPage: int): LengthAwarePaginator
   - findBySlug(slug: string): ?Product
   - updateStock(productId: int, quantity: int, decrement: bool): void
   - getLowStock(): Collection

2. CategoryRepositoryInterface / CategoryRepository
   - getTree(): Collection (nested parent-child)
   - getActive(): Collection
   - findBySlug(slug: string): ?Category
   - getWithProductCount(): Collection

3. OrderRepositoryInterface / OrderRepository
   - getByUser(userId: int, perPage: int): LengthAwarePaginator
   - findByOrderNumber(orderNumber: string): ?Order
   - getByStatus(status: OrderStatus): Collection
   - getSalesReport(from: Carbon, to: Carbon): array
   - getRevenueByDate(from: Carbon, to: Carbon): Collection

4. CartRepositoryInterface / CartRepository
   - getOrCreateForUser(userId: ?int, sessionId: ?string): Cart
   - addItem(cart: Cart, productId: int, quantity: int, variantId: ?int): CartItem
   - updateItemQuantity(cartItemId: int, quantity: int): CartItem
   - removeItem(cartItemId: int): void
   - clear(cartId: int): void
   - mergeCarts(sessionCart: Cart, userCart: Cart): Cart

5. CouponRepositoryInterface / CouponRepository
   - findValidByCode(code: string): ?Coupon

GENERATE THESE SERVICES:

1. CartService — manages cart logic, calls CartRepository
2. CheckoutService — orchestrates order placement
   - Method: placeOrder(CartDTO, AddressDTO, paymentMethod): Order
   - Steps: validate stock → calculate totals → create order → decrement stock → clear cart → dispatch events
3. CouponService — validate & apply coupons
4. PaymentService — strategy pattern with drivers (CodDriver, SslCommerzDriver, StripeDriver)
5. ShippingService — calculate shipping based on division/weight
6. ProductFilterService — builds query from filter array (category, price range, plant_type, sunlight, etc.)
7. TranslationService — get/set translations by locale
8. SeoService — generate meta tags, structured data (JSON-LD for Product, BreadcrumbList)
9. NotificationService — wraps email + SMS notifications

ALSO GENERATE:
- App\Providers\RepositoryServiceProvider (bind interfaces to implementations)
- Register it in bootstrap/providers.php

Use constructor injection everywhere. Type-hint interfaces, not concretes.
```

---

### PHASE 4: Controllers, Routes & Form Requests

**Prompt for Claude:**

```
Continue GardenNGrow. Repositories and Services are done.

TASK: Generate all controllers, routes, form requests, and middleware.

CONTROLLER STRUCTURE:
App\Http\Controllers\Frontend\   — Customer-facing
App\Http\Controllers\Api\V1\     — API (for future mobile app)

FRONTEND CONTROLLERS (Blade-based, return views):
1. HomeController — index (hero, featured, new arrivals, bestsellers, reviews, blog)
2. ShopController — index (with filters), show (product detail)
3. CartController — index, addItem, updateItem, removeItem, applyCoupon, removeCoupon
4. CheckoutController — index (show form), store (place order), success, cancel
5. WishlistController — index, toggle (add/remove via AJAX)
6. CompareController — index, toggle
7. ReviewController — store
8. CustomerController — dashboard, orders, orderShow, addresses, profile, updateProfile, changePassword
9. BlogController — index, show
10. PageController — show (for static pages like about, contact, terms)
11. NewsletterController — subscribe
12. SearchController — index (full-text search with Scout)
13. LanguageController — switch (changes locale, stores in session + user preference)
14. PlantQuizController — index (show quiz), result (recommend plants based on answers)

API V1 CONTROLLERS (return JSON Resources):
- ProductController (index, show)
- CategoryController (index, show)
- CartController (full CRUD)
- AuthController (register, login, logout, me)

ROUTES:
Generate complete routes/web.php and routes/api.php with:
- Named routes (every route must have a name)
- Route groups with middleware
- Resource routes where applicable
- Rate limiting on API routes
- Locale prefix middleware (optional /bn/ or /en/ prefix)

FORM REQUESTS (generate all):
- StoreReviewRequest
- CheckoutRequest
- UpdateProfileRequest
- ChangePasswordRequest
- SubscribeNewsletterRequest
- ApplyCouponRequest
- ContactFormRequest

MIDDLEWARE:
- SetLocale — reads locale from session/URL/user preference, sets app locale
- TrackVisitor — logs page views for analytics (async via queue)
- CheckoutGuard — ensures cart is not empty before checkout

Generate clean, complete code for each file.
```

---

### PHASE 5: Frontend Blade Templates & UI

**Prompt for Claude:**

```
Continue GardenNGrow. Controllers and routes are done.

TASK: Generate all Blade templates with Tailwind CSS.

DESIGN SYSTEM:
- Primary: #2D6A4F (forest green)
- Secondary: #52B788 (leaf green)
- Accent: #95D5B2 (mint)
- Earth: #8B6914 (brown, for accents)
- Background: #F8FAF5 (off-white green tint)
- Text: #1B1B1B
- Font: Inter for body, Playfair Display for headings
- Rounded corners, soft shadows, nature-inspired feel
- Mobile-first responsive design
- Subtle CSS animations (fade-in on scroll, hover lifts on cards)

LAYOUT FILES:
1. layouts/app.blade.php — Main layout with:
   - Top bar (language switcher, currency, phone, social links)
   - Navbar (logo, search bar, nav links, wishlist icon + count, cart icon + count, user dropdown)
   - Mobile hamburger menu with slide-out drawer
   - Footer (4 columns: about, quick links, customer service, contact + social icons)
   - WhatsApp floating button
   - Cookie consent banner
   - Newsletter popup (shown once via localStorage)

2. layouts/customer.blade.php — extends app, adds sidebar for customer dashboard

COMPONENT BLADE COMPONENTS (resources/views/components/):
- product-card.blade.php (image, name, price, rating stars, wishlist heart, add-to-cart)
- category-card.blade.php
- review-card.blade.php
- breadcrumb.blade.php
- price-display.blade.php (shows BDT with ৳ symbol, strikethrough for compare_price)
- star-rating.blade.php (1-5 stars, interactive for forms, static for display)
- language-switcher.blade.php
- alert.blade.php (success/error/warning/info)
- empty-state.blade.php (illustration + message for empty cart, wishlist, etc.)
- seo-meta.blade.php (dynamic meta tags, og tags, JSON-LD)

PAGE TEMPLATES:
1. home.blade.php — Hero slider, featured categories grid, new arrivals carousel,
   best sellers grid, seasonal section with parallax bg, customer reviews slider,
   latest blog posts, newsletter CTA
2. shop/index.blade.php — Sidebar filters + product grid with Alpine.js toggle,
   sort dropdown, pagination, active filter chips
3. shop/show.blade.php — Image gallery (thumbnail + zoom), product info,
   variant selector, quantity input, add to cart, tabs (description, care tips,
   reviews, shipping info), related products
4. cart/index.blade.php — Cart table, coupon input, order summary sidebar,
   proceed to checkout
5. checkout/index.blade.php — Two-column: shipping form (left), order summary (right),
   payment method selection, place order button
6. checkout/success.blade.php — Order confirmation with order number, details, timeline
7. customer/dashboard.blade.php — Welcome, recent orders, quick stats
8. customer/orders.blade.php — Orders table with status badges
9. customer/order-show.blade.php — Full order detail with tracking timeline
10. customer/wishlist.blade.php — Grid of wishlisted products
11. customer/addresses.blade.php — Address cards with add/edit/delete/set-default
12. customer/profile.blade.php — Profile edit form
13. blog/index.blade.php — Blog grid with category filter
14. blog/show.blade.php — Blog post with sidebar (recent posts, categories)
15. pages/contact.blade.php — Contact form + map + business info
16. pages/about.blade.php
17. quiz/index.blade.php — "Which plant suits you?" interactive quiz with Alpine.js
18. auth/login.blade.php, register.blade.php, forgot-password.blade.php

Use @section, @yield, @component, @props, @foreach, @if, {{ }} properly.
Use Alpine.js for: cart add/remove AJAX, wishlist toggle, mobile menu, filter toggles,
image gallery, quantity stepper, tabs, quiz state.

Every page must include proper <title>, meta description, og:tags via the seo-meta component.
```

---

### PHASE 6: Admin Panel with Filament v3

**Prompt for Claude:**

```
Continue GardenNGrow. Frontend is done.

TASK: Build the complete admin panel using Filament v3.

SETUP:
- Separate admin guard using 'admins' table
- Admin login at /admin/login
- Filament Shield for roles & permissions
- Custom Filament theme with GardenNGrow branding (green palette)

FILAMENT RESOURCES (generate complete Resource classes with form + table + pages):

1. ProductResource
   - Form: tabs (General, Pricing & Stock, Plant Info, Images, SEO)
   - General tab: name (en + bn), slug, category select, short description (en + bn),
     description (rich editor, en + bn), is_active toggle, is_featured toggle
   - Pricing tab: price, compare_price, cost_price, tax_rate, sku, barcode,
     stock_quantity, low_stock_threshold
   - Plant Info tab: plant_type, sunlight, watering, difficulty, mature_size,
     care_instructions (en + bn)
   - Images tab: repeater with file upload, alt text, sort order, is_primary toggle
   - SEO tab: meta_title, meta_description (en + bn)
   - Table: image thumbnail, name, sku, price, stock (color-coded), category,
     is_active toggle, is_featured toggle
   - Filters: category, plant_type, stock status, active status
   - Bulk actions: activate, deactivate, delete
   - Import/Export actions (CSV)

2. CategoryResource
   - Form: name (en + bn), parent_id (select with tree), slug, image upload,
     icon, sort_order, is_active
   - Table: name, parent, product count, sort order, is_active toggle
   - Reorderable rows

3. OrderResource
   - Form: read-only order details, editable status select, admin notes
   - Table: order_number, customer name, total, status badge, payment status badge,
     payment method, date
   - Filters: status, payment status, date range
   - Custom page: OrderTimeline showing status history
   - Actions: mark as processing, mark as shipped, mark as delivered, cancel, print invoice

4. CustomerResource (based on User model)
   - Table: name, email, phone, orders count, total spent, registered date
   - View page: customer info + order history + addresses

5. CouponResource
   - Form: code, type, value, min_order, max_discount, usage_limit, date range, is_active
   - Table: code, type, value, used/limit, expiry, is_active toggle

6. ReviewResource
   - Table: product, customer, rating (stars), comment preview, is_approved toggle, date
   - Actions: approve, reject, reply

7. BlogResource
   - Form: title (en + bn), slug, category, featured image, content (rich editor, en + bn),
     excerpt (en + bn), is_published, published_at, SEO fields
   - Table: title, category, author, is_published toggle, date

8. BannerResource
   - Form: type, title (en + bn), subtitle (en + bn), image upload, mobile image,
     link, button text (en + bn), sort_order, date range, is_active
   - Table: image preview, title, type badge, active status, date range

9. SettingPage (custom Filament page, NOT a resource)
   - Tabs: General (logo, favicon, company info), Contact (phone, email, address, map),
     Social Media (links), SEO (default meta), Email (SMTP settings),
     Payment (gateway credentials), Shipping (zones & rates),
     Homepage (toggle sections on/off, order sections)
   - All settings stored in settings table as key-value

10. RoleResource (via Shield)
    - Manage roles and assign permissions per resource

DASHBOARD WIDGETS:
- StatsOverview: today's orders, today's revenue, total customers, pending orders
- RevenueChart: line chart of last 30 days revenue (Filament Chart widget)
- LatestOrdersTable: last 10 orders with quick status update
- LowStockAlert: products below threshold
- TopSellingProducts: bar chart of top 10 products this month
- OrdersByStatus: doughnut chart

Generate complete, working Filament code for each resource.
```

---

### PHASE 7: Payment Integration, Events & Notifications

**Prompt for Claude:**

```
Continue GardenNGrow. Admin panel is done.

TASK: Build payment gateway integration, events, listeners, notifications, and queued jobs.

PAYMENT INTEGRATION:

1. Payment Strategy Pattern:
   - App\Contracts\PaymentGateway interface with: initiate(Order), verify(request), refund(Payment)
   - App\Payments\CodGateway — marks as unpaid, no redirect
   - App\Payments\SslCommerzGateway — integrates with SSLCommerz sandbox/live API
     (use library: sslcommerz/laravel or raw API calls)
     Endpoints: /payment/sslcommerz/initiate, /payment/sslcommerz/success,
     /payment/sslcommerz/fail, /payment/sslcommerz/cancel, /payment/sslcommerz/ipn
   - App\Payments\StripeGateway — Stripe Checkout session integration
   - App\Payments\PaymentManager — resolves correct gateway from config

2. Register in config/payment.php with credentials from .env

EVENTS & LISTENERS:

Events:
- OrderPlaced(Order $order)
- OrderStatusChanged(Order $order, OrderStatus $oldStatus, OrderStatus $newStatus)
- PaymentReceived(Payment $payment)
- ProductStockLow(Product $product)
- NewsletterSubscribed(Newsletter $newsletter)
- ReviewSubmitted(Review $review)
- UserRegistered(User $user)

Listeners:
- SendOrderConfirmationEmail (queued)
- SendOrderStatusUpdateEmail (queued)
- SendPaymentReceiptEmail (queued)
- NotifyAdminLowStock (queued)
- SendWelcomeEmail (queued)
- SendNewsletterWelcomeEmail (queued)
- NotifyAdminNewOrder (queued, via Filament notification)
- UpdateProductRatingAverage (sync)
- ClearProductCache (sync)

NOTIFICATIONS (Laravel Notification classes):
- OrderConfirmationNotification (mail channel) — beautiful HTML email with order details
- OrderStatusNotification (mail channel)
- PaymentReceiptNotification (mail channel)
- WelcomeNotification (mail channel)
- LowStockNotification (mail + database channel for admin)

MAIL TEMPLATES:
Use Laravel Markdown mailables with GardenNGrow branding.
Generate mail templates in resources/views/emails/:
- orders/confirmation.blade.php
- orders/status-update.blade.php
- payments/receipt.blade.php
- auth/welcome.blade.php
- newsletter/welcome.blade.php

QUEUED JOBS:
- GenerateInvoicePdf (creates PDF invoice for order)
- ProcessCsvImport (bulk product import)
- CleanExpiredCarts (delete carts older than 7 days)
- GenerateSitemap (creates sitemap.xml)

SCHEDULED TASKS (in routes/console.php):
- Clean expired carts: daily at 2am
- Generate sitemap: daily at 3am
- Send low stock alerts: daily at 8am
- Clear expired coupons: daily at midnight

Generate complete code for all events, listeners, notifications, jobs, and schedules.
```

---

### PHASE 8: Localization, SEO & Extras

**Prompt for Claude:**

```
Continue GardenNGrow. Events, payments, notifications are done.

TASK: Build localization system, SEO, and extra features.

LOCALIZATION:

1. Translation files — generate resources/lang/en/ and resources/lang/bn/ for:
   - general.php (site-wide: home, shop, cart, checkout, search, etc.)
   - product.php (product-related terms)
   - auth.php (login, register, password)
   - order.php (order-related terms)
   - validation.php (form validation messages in Bangla)
   - pagination.php
   - admin.php (admin panel labels)
   Include at least 200 translation keys per file with proper Bangla translations.

2. SetLocale middleware — detect from: URL prefix > session > user preference > browser > default
3. Language switcher component — dropdown with flag icons (BD flag for bn, US/UK flag for en)
4. Translatable Blade directive: @t('key') as alias for __()
5. Admin translation manager — simple page in Filament to edit translation values

SEO:
1. Dynamic meta tags on every page via seo-meta component
2. JSON-LD structured data:
   - Product schema on product pages
   - BreadcrumbList on all pages
   - Organization schema on homepage
   - BlogPosting on blog posts
   - FAQPage on FAQ page
3. Auto-generate sitemap.xml (use spatie/laravel-sitemap)
4. robots.txt configuration
5. Canonical URLs on all pages
6. Open Graph + Twitter Card tags
7. Hreflang tags for en/bn alternate pages

EXTRA FEATURES:

1. Plant Quiz ("Which plant suits you?"):
   - 5 questions: space, sunlight, experience, maintenance, purpose
   - Each answer weighted to plant types
   - Results page shows recommended products from database
   - Built with Alpine.js multi-step form
   - Shareable result URL

2. Delivery Area Checker:
   - Database of Bangladesh divisions + districts + delivery time + shipping cost
   - AJAX checker on product page: "Enter your district to check delivery"
   - Show estimated delivery date and cost

3. WhatsApp Integration:
   - Floating WhatsApp button (bottom-right)
   - Pre-filled message: "Hi, I'm interested in [product name]" on product pages
   - Phone number configurable from admin settings

4. Analytics Ready:
   - Google Analytics 4 (GA4) tag configurable from admin settings
   - Facebook Pixel configurable from admin settings
   - Injected via Blade component, only in production

5. Plant Care Tips:
   - Section on product page showing care instructions
   - Dedicated /plant-care page with tips filtered by plant type
   - Content from product's care_instructions field

Generate complete code for all features above.
```

---

### PHASE 9: Seeders, Testing & Configuration

**Prompt for Claude:**

```
Continue GardenNGrow. All features are built.

TASK: Generate seeders, factory definitions, tests, and configuration.

SEEDERS (generate realistic data):

1. RoleSeeder — create roles: super_admin, admin, staff
2. AdminSeeder — create super admin (admin@gardenngrow.com / password)
3. SettingsSeeder — seed all default settings (logo placeholder, company info, SMTP defaults,
   payment defaults, social links, SEO defaults)
4. CategorySeeder — seed 7 parent categories:
   Indoor Plants, Outdoor Plants, Flower Plants, Pots & Planters,
   Seeds, Fertilizers & Soil, Gardening Tools & Accessories
   With 3-5 child categories each. Both en + bn translations.
5. ProductSeeder — seed 50 products with:
   - Realistic plant names in English and Bangla
   - Prices in BDT (200-5000 range)
   - Random plant attributes (sunlight, watering, difficulty)
   - 2-3 placeholder images per product
   - Random stock quantities
   - Some marked as featured/new arrival
6. CouponSeeder — seed 3 coupons (WELCOME10, PLANT20, FREESHIP)
7. BlogSeeder — seed 6 blog posts about plant care in en + bn
8. BannerSeeder — seed 3 hero banners, 1 popup banner
9. ShippingZoneSeeder — Bangladesh divisions with rates
10. PageSeeder — About Us, Contact, Terms, Privacy Policy, FAQ pages in en + bn

FACTORIES:
Generate factories for: User, Product, Category, Order, Review, Blog, Coupon

PEST TESTS:

Feature Tests:
- HomePageTest — homepage loads, shows featured products, shows categories
- ShopTest — product listing loads, filters work, product detail loads
- CartTest — add item, update quantity, remove item, apply coupon
- CheckoutTest — guest checkout, authenticated checkout, order created correctly
- AuthTest — register, login, logout, forgot password
- WishlistTest — add/remove items
- SearchTest — search returns relevant results
- LocaleTest — switching language changes content

Unit Tests:
- CouponServiceTest — validate, apply, expired coupon, usage limit
- ShippingServiceTest — calculate shipping for different zones
- CartServiceTest — merge carts, calculate totals
- OrderNumberGeneratorTest — format, uniqueness

Generate complete Pest test files with meaningful assertions.

CONFIGURATION FILES:
- config/gardenngrow.php — app-specific config (currency, default locale, pagination sizes, etc.)
- config/payment.php — payment gateway config pulling from .env
- config/shipping.php — shipping zones and rates
- .env.example — complete with all custom env variables documented with comments
```

---

### PHASE 10: Deployment & Documentation

**Prompt for Claude:**

```
Continue GardenNGrow. Everything is built.

TASK: Generate deployment configuration and complete documentation.

DEPLOYMENT FILES:

1. Docker (development):
   - docker-compose.yml (PHP 8.3-FPM, Nginx, MySQL 8, Redis, Meilisearch, Mailpit)
   - Dockerfile for PHP
   - nginx/default.conf

2. GitHub Actions CI/CD:
   - .github/workflows/ci.yml — run tests, lint (Pint), static analysis (Larastan) on PR
   - .github/workflows/deploy.yml — deploy to production on main push

3. Shared Hosting (cPanel):
   - Step-by-step deployment guide for Bangladeshi shared hosts
   - .htaccess configuration
   - Symlink public directory approach

4. VPS Deployment:
   - Laravel Forge setup guide
   - Manual deployment with Nginx + Supervisor + Certbot

DOCUMENTATION — Generate README.md:

# GardenNGrow — E-Commerce Platform

## Table of Contents
1. Overview
2. Features
3. Tech Stack
4. Requirements
5. Installation (Local Development)
6. Installation (Docker)
7. Configuration
   - Environment Variables (complete table of every .env variable with description)
   - Payment Gateways
   - Email/SMTP
   - Search (Meilisearch)
8. Database Setup & Seeding
9. Running Tests
10. Admin Panel Access
11. Localization (Adding new languages)
12. Payment Gateway Setup
    - SSLCommerz (sandbox + live)
    - Stripe
    - bKash (future)
13. Deployment
    - Shared Hosting
    - VPS / Cloud
    - Docker
14. Folder Structure (annotated tree)
15. API Documentation (endpoints, auth, request/response examples)
16. Contributing
17. License

Also generate:
- CHANGELOG.md (v1.0.0 initial release)
- CONTRIBUTING.md
- LICENSE (MIT)
- .editorconfig
- .prettierrc (for Blade/JS formatting)
- phpstan.neon (level 6)
- pint.json (Laravel coding style)
```

---

## Prompt Engineering Tips for Claude

When using these prompts with Claude, follow these practices for best results:

1. **One phase per conversation.** Start a new chat for each phase. Paste the phase prompt and say "Generate all files for this phase. Output each file with its full path and complete code."

2. **If output gets truncated,** say: "Continue from where you left off. You were generating [filename]."

3. **To iterate on a file,** paste the file back and say: "Here's the current [filename]. Modify it to [change]."

4. **Request file trees first.** Before each phase, ask: "Before generating code, show me the file tree for Phase X so I can confirm the structure."

5. **Use Claude's project knowledge feature.** If available, upload earlier phase outputs as project context so Claude maintains consistency.

6. **Validate generated code.** After each phase, run `php artisan route:list`, `php artisan migrate --pretend`, and tests to catch issues early.

7. **Keep a running `composer.json`** and share it between phases so package dependencies stay consistent.

---

## Required Composer Packages

```json
{
    "require": {
        "php": "^8.3",
        "laravel/framework": "^11.0",
        "laravel/breeze": "^2.0",
        "filament/filament": "^3.2",
        "bezhansalleh/filament-shield": "^3.2",
        "spatie/laravel-permission": "^6.0",
        "spatie/laravel-sitemap": "^7.0",
        "spatie/laravel-data": "^4.0",
        "laravel/scout": "^10.0",
        "meilisearch/meilisearch-php": "^1.0",
        "intervention/image-laravel": "^1.0",
        "barryvdh/laravel-dompdf": "^3.0",
        "maatwebsite/excel": "^3.1",
        "sslcommerz/laravel": "^2.0",
        "stripe/stripe-php": "^13.0"
    },
    "require-dev": {
        "pestphp/pest": "^2.0",
        "pestphp/pest-plugin-laravel": "^2.0",
        "larastan/larastan": "^2.0",
        "laravel/pint": "^1.0"
    }
}
```

---

## Quick Reference: Key Decisions

| Decision                | Choice                  | Rationale                                          |
| ----------------------- | ----------------------- | -------------------------------------------------- |
| Admin Panel             | Filament v3             | Fastest to build, excellent DX, great ecosystem    |
| CSS Framework           | Tailwind CSS            | Utility-first, small bundle, great with Laravel    |
| JS Interactivity        | Alpine.js               | Ships with Livewire/Filament, minimal footprint    |
| Auth (Customer)         | Laravel Breeze           | Simple, Blade-native, customizable                 |
| Auth (Admin)            | Filament built-in       | Separate guard, built-in 2FA support               |
| Search                  | Scout + Meilisearch     | Typo-tolerant, fast, faceted filtering             |
| Translations            | DB-based + file-based   | DB for dynamic content, files for static UI text   |
| Payment Primary         | SSLCommerz              | Most popular gateway in Bangladesh                 |
| Image Processing        | Intervention Image      | De facto standard for Laravel                      |
| PDF Generation          | DomPDF                  | Simple invoices, no external dependencies          |
| Excel Import/Export     | Maatwebsite Excel       | Robust, queued imports for large files             |
| Testing                 | Pest                    | Modern, expressive, built for Laravel              |
| Code Style              | Laravel Pint            | Official Laravel formatter                         |
| Static Analysis         | Larastan (PHPStan)      | Catches type errors before runtime                 |

---

*Generated for GardenNGrow — a project by a Bangladeshi entrepreneur building a world-class online plant store.*
