# Kerajinan E-Commerce System

A comprehensive Laravel-based e-commerce platform specifically designed for handcraft businesses, featuring advanced sales analytics, promotion management, and professional reporting capabilities.

## 🚀 Features

### Core E-Commerce

- **Product Management**: Complete CRUD operations with categories, images, and inventory tracking
- **Order Processing**: Full order lifecycle from creation to completion
- **Payment Verification**: Manual payment confirmation system with proof uploads
- **User Management**: Customer and admin user management
- **Promotion System**: Discount management with flexible rules and scheduling

### Advanced Analytics

- **Sales Reports**: Comprehensive sales analytics with filtering by date, product, category
- **Export Capabilities**: Professional PDF and Excel exports with styling
- **Dashboard Analytics**: Real-time statistics and performance metrics
- **Revenue Tracking**: Detailed financial reporting and trends

### Admin Interface

- **Responsive Design**: Mobile-first admin panel with Bootstrap 5
- **Consistent Layouts**: Standardized page headers with breadcrumbs and actions
- **Data Tables**: Advanced filtering, sorting, and pagination
- **Rich UI Components**: Select2, SweetAlert2, DataTables integration

## 🛠 Technology Stack

- **Framework**: Laravel 11+ (PHP 8.1+)
- **Database**: MySQL 8.0+
- **Frontend**: Bootstrap 5.3, jQuery, Font Awesome 6
- **Export Libraries**: Laravel Excel, DomPDF
- **UI Components**: DataTables, Select2, SweetAlert2
- **Build Tools**: Vite  
  ✅ **Enhanced user experience with modern UI/UX patterns**  
  ✅ **Complete authentication system with role-based access control**

## 🆕 **Latest Updates (January 2026)**

- ✅ **Indonesian Language Localization**: Complete admin interface translation
- ✅ **DataTables Integration**: Enhanced category management with advanced table features
- ✅ **Professional Category CRUD**: Full category management system with validation
- ✅ **Export Functionality**: Excel, PDF, and Print export options
- ✅ **Responsive Design**: Mobile-optimized admin interface
- ✅ **Enhanced UX**: Modal confirmations, tooltips, and professional interactions

---

## 🚀 **Quick Start**

### **Prerequisites**

- XAMPP (MySQL + Apache) or similar LAMP stack
- PHP 8.2+
- Composer
- Node.js & NPM

### **Installation & Setup**

1. **Start XAMPP**
    - Start MySQL and Apache services

2. **Database Setup**

    ```bash
    # Create database 'kerajinan' in phpMyAdmin or MySQL
    CREATE DATABASE kerajinan;
    ```

3. **Environment Configuration**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Update `.env` with your database credentials:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=kerajinan
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4. **Install Dependencies & Setup**

    ```bash
    composer install
    npm install
    npm run build
    ```

5. **Database Migration & Seeding**

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

6. **Start Development Server**

    ```bash
    php artisan serve
    ```

    **🌐 Application URL**: http://127.0.0.1:8000

---

## 🔑 **Login Credentials**

### **Admin Access**

- **URL**: http://127.0.0.1:8000/login
- **Username**: `admin`
- **Password**: `password`
- **Role**: Full system administration access

### **Sample Users**

- **Usernames**: `johndoe`, `janedoe`, `mikejohnson`, etc.
- **Password**: `password` (for all sample users)
- **Role**: Regular customer access

---

## 🛍️ **Seeded Sample Data**

The application comes with realistic Indonesian handicraft sample data:

- **24 Users** (1 admin + 23 customers)
- **40 Product Categories** (Keramik, Tekstil, Perhiasan, Ukiran, etc.)
- **30 Handicraft Products** (Batik, Keramik, Perhiasan, Tas Kulit, etc.)
- **12 Promotional Campaigns** (Active, expired, and scheduled promotions)

---

## ✨ **Complete Feature Set**

### **🛒 E-commerce Core**

- ✅ **Product Catalog** - Advanced filtering, search, pagination
- ✅ **Shopping Cart** - Session-based cart management
- ✅ **Order System** - Complete checkout with payment proof upload
- ✅ **User Authentication** - Role-based access control
- ✅ **User Profiles** - Profile management with AJAX updates

### **🎯 Advanced Features**

- ✅ **Promotional System** - Time-based discounts with conflict detection
- ✅ **Database Notifications** - Real-time order status updates
- ✅ **Shipping Tracking** - Order tracking with `nomor_resi`
- ✅ **Payment Management** - Admin payment confirmation/rejection
- ✅ **Testimonial System** - Product reviews and ratings

### **👨‍💼 Admin Dashboard**

- ✅ **Order Management** - Complete order lifecycle management
- ✅ **Payment Processing** - Payment proof verification
- ✅ **Promotion Management** - Professional promotion CRUD interface
- ✅ **User Management** - User account administration
- ✅ **Product Management** - Product and category administration
- ✅ **Category Management** - Advanced DataTables-powered category system
- ✅ **Notification Center** - System-wide notification management
- ✅ **Indonesian Localization** - Complete admin interface in Indonesian
- ✅ **Export Functions** - Excel, PDF, and Print capabilities

### **🎨 Professional UI**

- ✅ **Glassmorphism Design** - Modern, professional aesthetic
- ✅ **Bootstrap 5.3** - Responsive, mobile-first design
- ✅ **DataTables Integration** - Advanced table functionality with sorting, pagination, search
- ✅ **AJAX Integration** - Smooth user interactions
- ✅ **Indonesian Localization** - Complete interface translation
- ✅ **Professional Layouts** - Separate admin and auth layouts
- ✅ **Export Capabilities** - Excel, PDF, and Print functions
- ✅ **Modal Interactions** - Professional confirmation dialogs
- ✅ **Tooltips & Icons** - Enhanced user experience with FontAwesome icons

---

## 🏗️ **Architecture Overview**

### **Controllers & Responsibilities**

| Controller                 | Responsibilities                                     |
| -------------------------- | ---------------------------------------------------- |
| **AuthController**         | Login, register, forgot password, profile management |
| **AdminController**        | Order management, payment confirmation, user admin   |
| **ProdukController**       | Product/category CRUD, promotion management          |
| **TransaksiController**    | Cart, checkout, user order management                |
| **FrontendController**     | Product catalog, search, filtering, homepage         |
| **NotificationController** | Notification management with AJAX endpoints          |

### **Database Schema**

| Table           | Purpose            | Key Features                                 |
| --------------- | ------------------ | -------------------------------------------- |
| `users`         | User accounts      | Role-based access (`admin`/`user`)           |
| `kategori`      | Product categories | Hierarchical organization                    |
| `produk`        | Products           | Stock management, promotional pricing        |
| `promosi`       | Promotions         | Time-based discounts with conflict detection |
| `pesanan`       | Orders             | Status tracking, payment proof, shipping     |
| `detailpesanan` | Order items        | Order line items with promotional prices     |
| `testimoni`     | Reviews            | Product testimonials and ratings             |
| `notifications` | System alerts      | Database-driven notification system          |

### **Key Models & Features**

- **Produk Model**: Advanced promotional price calculations, stock management
- **Promosi Model**: Automatic discount application, conflict detection algorithms
- **Pesanan Model**: Complete order lifecycle, shipping integration
- **User Model**: Extended with notification relationships

---

## 🛠️ **Development & Testing Commands**

### **Database Operations**

```bash
# Fresh migration with sample data (includes 40 categories, 30 products)
php artisan migrate:fresh --seed

# Migration status
php artisan migrate:status

# Rollback migrations
php artisan migrate:rollback

# Test admin user creation
php artisan tinker --execute="echo 'Admin user: '; print_r(App\Models\User::where('role', 'admin')->first()->toArray());"
```

### **Category Management Testing**

```bash
# Test category seeding
php artisan tinker --execute="echo 'Total categories: ' . App\Models\Kategori::count();"

# Test category with products
php artisan tinker --execute="echo 'Categories with products: '; App\Models\Kategori::withCount('produk')->get()->each(function($k) { echo $k->nama_kategori . ': ' . $k->produk_count . ' products\n'; });"
```

### **Admin Interface Testing**

Access these URLs to test the enhanced admin features:

- **Dashboard**: `http://127.0.0.1:8000/admin/dashboard`
- **Category Management**: `http://127.0.0.1:8000/admin/kategori`
- **Category Creation**: `http://127.0.0.1:8000/admin/kategori/create`
- **DataTables Features**: Test sorting, searching, pagination, export functions

### **Cache Management**

```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear
```

### **Development Tools**

```bash
# Interactive shell
php artisan tinker

# View all routes
php artisan route:list

# Generate new components
php artisan make:controller NewController
php artisan make:model NewModel
php artisan make:migration create_new_table
```

---

## 📱 **Application Routes**

### **Public Routes**

- `/` - Homepage with featured products
- `/products` - Product catalog with search/filtering
- `/categories` - Category browsing
- `/login` - User login
- `/register` - User registration

### **Authenticated User Routes**

- `/profile` - User profile management
- `/cart` - Shopping cart
- `/checkout` - Order checkout
- `/orders` - Order history
- `/notifications` - User notifications

### **Admin Routes**

- `/admin/dashboard` - Indonesian-localized admin dashboard
- `/admin/orders` - Order management with status tracking
- `/admin/products` - Product management
- `/admin/kategori` - **Enhanced category management with DataTables**
    - `/admin/kategori/create` - Add new categories
    - `/admin/kategori/{id}` - View category details
    - `/admin/kategori/{id}/edit` - Edit categories
- `/admin/promotions` - Promotion management
- `/admin/users` - User administration

---

## 🔧 **Configuration**

### **Environment Variables**

```env
# Application
APP_NAME="Kerajinan E-commerce"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kerajinan
DB_USERNAME=root
DB_PASSWORD=

# File Storage (for product images)
FILESYSTEM_DISK=public
```

### **File Storage Setup**

```bash
# Create symbolic link for public storage
php artisan storage:link
```

---

## 📊 **Current Application Features**

### **🏪 Indonesian-Localized Admin Interface**

The admin interface is fully localized in Indonesian language:

- **Dashboard**: Indonesian labels, status indicators, and navigation
- **Category Management**: Complete CRUD operations with DataTables
- **Professional Forms**: Validation messages in Indonesian
- **Status Indicators**: Order status, payment status in Indonesian
- **Navigation Menu**: Fully translated sidebar and breadcrumbs

### **📋 Advanced Category Management**

**URL**: `/admin/kategori` - Enhanced with DataTables integration

**Features**:

- ✅ **DataTables Integration** - Advanced sorting, pagination, search
- ✅ **Indonesian Language** - Complete localization
- ✅ **Export Functions** - Excel, PDF, Print capabilities
- ✅ **Responsive Design** - Mobile-optimized interface
- ✅ **CRUD Operations** - Create, Read, Update, Delete with validation
- ✅ **Modal Confirmations** - Professional delete confirmations
- ✅ **Real-time Search** - Instant category filtering
- ✅ **Bulk Actions** - Multiple selection operations
- ✅ **Professional UI** - Glassmorphism design with modern interactions

**Category Management Pages**:

1. **Index** (`/admin/kategori`) - DataTables-powered listing
2. **Create** (`/admin/kategori/create`) - Professional form with validation
3. **Show** (`/admin/kategori/{id}`) - Detailed category view with statistics
4. **Edit** (`/admin/kategori/{id}/edit`) - Edit form with change tracking

## 📊 **Sample Data Details**

### **🗂️ Enhanced Category System (40 Categories)**

The application includes comprehensive Indonesian handicraft categories with DataTables management:

**Traditional Categories**:

- Keramik (Ceramics) - Traditional pottery and ceramic arts
- Tekstil (Textiles) - Batik, weaving, traditional fabrics
- Perhiasan (Jewelry) - Traditional and modern jewelry
- Ukiran (Carvings) - Wood, stone, and artistic carvings
- Tas & Dompet (Bags & Wallets) - Leather and traditional bags
- Dekorasi Rumah (Home Decor) - Traditional home decorations

**Modern Categories**:

- Aksesoris Fashion (Fashion Accessories)
- Perlengkapan Dapur (Kitchen Accessories)
- Mainan Tradisional (Traditional Toys)
- Peralatan Musik (Musical Instruments)

**Management Features**:

- DataTables-powered listing with search and sort
- Professional category creation and editing forms
- Category statistics and product count tracking
- Export capabilities (Excel, PDF, Print)
- Complete Indonesian localization

### **Sample Products (30 items)**

- Batik Tulis Solo Premium - Rp 450,000
- Vas Keramik Motif Tradisional - Rp 125,000
- Set Mangkuk Keramik Warna-Warni - Rp 89,000
- Ukiran Topeng Bali - Rp 350,000
- Dompet Kulit Asli Handmade - Rp 165,000
- And more authentic Indonesian handicrafts...

### **Promotional Campaigns (12 items)**

- Active promotions (15-25% discounts)
- Expired promotions (historical data)
- Scheduled promotions (future campaigns)

---

## 🚀 **Deployment Considerations**

### **Production Setup**

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Configure proper database credentials
4. Set up SSL certificates
5. Configure web server (Apache/Nginx)
6. Set up proper file permissions

### **Security**

- All forms include CSRF protection
- User input validation and sanitization
- Role-based access control implemented
- Password hashing with bcrypt
- SQL injection protection via Eloquent ORM

---

## 🤝 **Contributing**

This is a complete, production-ready application. For contributions:

1. Fork the repository
2. Create a feature branch
3. Follow Laravel coding standards
4. Write comprehensive tests
5. Submit pull request with detailed description

---

## 📋 **Changelog**

### **Version 1.0.0 (January 2026) - Complete System**

**Core E-commerce Implementation**:

- ✅ Complete e-commerce platform implementation
- ✅ Advanced promotional system with conflict detection
- ✅ Database seeding with realistic Indonesian handicraft data
- ✅ Notification system implementation
- ✅ Shipping tracking integration
- ✅ Payment management system

**Enhanced Admin Interface**:

- ✅ **Indonesian Language Localization** - Complete admin interface translation
- ✅ **Professional Category Management** - Full CRUD system with modern UI
- ✅ **DataTables Integration** - Advanced table functionality with search, sort, pagination
- ✅ **Export Capabilities** - Excel, PDF, and Print export functions
- ✅ **Glassmorphism Design** - Professional modern aesthetic
- ✅ **Responsive Design** - Mobile-optimized admin interface
- ✅ **Enhanced UX** - Modal confirmations, tooltips, professional interactions

**Category Management System**:

- ✅ **DataTables-powered listing** - `/admin/kategori`
- ✅ **Professional creation form** - `/admin/kategori/create`
- ✅ **Detailed category view** - `/admin/kategori/{id}`
- ✅ **Advanced edit interface** - `/admin/kategori/{id}/edit`
- ✅ **Validation & error handling** - Complete form validation
- ✅ **Indonesian localization** - All text and messages in Indonesian

---

## 📄 **License**

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🎯 **Project Summary**

A **complete, professional-grade Laravel e-commerce platform** specifically designed for Indonesian handicraft businesses. Features modern UI/UX, comprehensive Indonesian-localized admin tools, advanced promotional systems, enhanced category management with DataTables integration, and is ready for immediate deployment with realistic sample data.

**Latest Enhancements**:

- ✅ **Complete Indonesian Localization** - Professional admin interface translation
- ✅ **Advanced Category Management** - DataTables-powered CRUD system
- ✅ **Export Functions** - Excel, PDF, Print capabilities
- ✅ **Enhanced User Experience** - Modal confirmations, tooltips, responsive design
- ✅ **Professional Forms** - Validation, error handling, change tracking

**Perfect for**:

- Indonesian handicraft businesses seeking professional e-commerce solutions
- Artisan marketplaces requiring advanced category management
- Cultural product stores needing localized admin interfaces
- Traditional craft e-commerce platforms with modern functionality
- Educational projects demonstrating advanced Laravel development patterns

**Technical Highlights**:

- Laravel 12 with modern PHP 8.2+ features
- Bootstrap 5.3 with glassmorphism design
- DataTables integration with Indonesian localization
- Professional admin interface with complete translation
- Advanced export capabilities (Excel, PDF, Print)
- Responsive design optimized for mobile devices
