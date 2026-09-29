# API Documentation

## Authentication Endpoints

### Login

```http
POST /login
Content-Type: application/json

{
    "username": "admin",
    "password": "password"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Login successful",
    "redirect": "/admin/dashboard"
}
```

### Logout

```http
POST /logout
```

## Admin API Endpoints

### Dashboard

```http
GET /admin/dashboard
Authorization: Required (Admin)
```

### Products

#### Get All Products

```http
GET /admin/produk
Authorization: Required (Admin)
```

#### Create Product

```http
POST /admin/produk
Content-Type: multipart/form-data

{
    "namaProduk": "Product Name",
    "deskripsi": "Product description",
    "harga": 150000,
    "stok": 10,
    "idKategori": 1,
    "gambar": [file]
}
```

#### Update Product

```http
PUT /admin/produk/{id}
Content-Type: multipart/form-data

{
    "namaProduk": "Updated Product Name",
    "deskripsi": "Updated description",
    "harga": 175000,
    "stok": 15,
    "idKategori": 2,
    "gambar": [file] // optional
}
```

#### Delete Product

```http
DELETE /admin/produk/{id}
Authorization: Required (Admin)
```

### Categories

#### Get All Categories

```http
GET /admin/kategori
Authorization: Required (Admin)
```

#### Create Category

```http
POST /admin/kategori
Content-Type: application/json

{
    "namaKategori": "Category Name",
    "deskripsi": "Category description"
}
```

### Orders

#### Get All Orders

```http
GET /admin/orders
Authorization: Required (Admin)
```

#### Get Order Details

```http
GET /admin/orders/{id}
Authorization: Required (Admin)
```

#### Update Order Status

```http
PUT /admin/orders/{id}/status
Content-Type: application/json

{
    "status": "confirmed" // pending, confirmed, shipped, delivered, cancelled
}
```

### Payments

#### Get Pending Payments

```http
GET /admin/payments/pending
Authorization: Required (Admin)
```

#### Verify Payment

```http
POST /admin/payments/{orderId}/verify
Content-Type: application/json

{
    "status": "confirmed", // confirmed, rejected
    "notes": "Payment verified successfully"
}
```

### Promotions

#### Get All Promotions

```http
GET /admin/promosi
Authorization: Required (Admin)
```

#### Create Promotion

```http
POST /admin/promosi
Content-Type: application/json

{
    "namaPromosi": "Promotion Name",
    "deskripsi": "Promotion description",
    "jenisDiskon": "percentage", // percentage, fixed
    "nilaiDiskon": 20,
    "tanggalMulai": "2024-01-01",
    "tanggalBerakhir": "2024-01-31",
    "status": "active"
}
```

#### Toggle Promotion Status

```http
POST /admin/promosi/{id}/toggle-status
Authorization: Required (Admin)
```

### Reports

#### Sales Report

```http
GET /admin/reports/sales
Authorization: Required (Admin)
Query Parameters:
- start_date: 2024-01-01
- end_date: 2024-12-31
- category_id: 1 (optional)
- product_id: 1 (optional)
```

#### Export Sales Report

```http
GET /admin/reports/export/{format}
Authorization: Required (Admin)
Formats: excel, pdf, csv
Query Parameters: Same as sales report
```

### Users

#### Get All Users

```http
GET /admin/users
Authorization: Required (Admin)
```

#### Update User Status

```http
PUT /admin/users/{id}/status
Content-Type: application/json

{
    "status": "active" // active, inactive
}
```

## Response Formats

### Success Response

```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {
        // Response data
    }
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

### Validation Errors

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "namaProduk": ["The product name field is required."],
        "harga": ["The price must be a number."]
    }
}
```

## Status Codes

- **200 OK**: Successful request
- **201 Created**: Resource created successfully
- **400 Bad Request**: Invalid request data
- **401 Unauthorized**: Authentication required
- **403 Forbidden**: Insufficient permissions
- **404 Not Found**: Resource not found
- **422 Unprocessable Entity**: Validation errors
- **500 Internal Server Error**: Server error

## Rate Limiting

API endpoints are rate limited to:

- **60 requests per minute** for authenticated users
- **10 requests per minute** for unauthenticated requests

## CSRF Protection

All POST, PUT, PATCH, DELETE requests require a valid CSRF token:

```html
<meta name="csrf-token" content="{{ csrf_token() }}" />
```

```javascript
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
```

## File Upload Guidelines

### Product Images

- **Allowed formats**: JPG, JPEG, PNG, WebP
- **Maximum size**: 5MB per file
- **Recommended dimensions**: 800x800px minimum
- **Storage path**: `storage/app/public/products/`

### Payment Proofs

- **Allowed formats**: JPG, JPEG, PNG, PDF
- **Maximum size**: 2MB per file
- **Storage path**: `storage/app/public/payments/`

## Pagination

API responses that return multiple items are paginated:

```json
{
    "data": [...],
    "links": {
        "first": "http://example.com/admin/produk?page=1",
        "last": "http://example.com/admin/produk?page=10",
        "prev": null,
        "next": "http://example.com/admin/produk?page=2"
    },
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 150,
        "last_page": 10
    }
}
```

## Search and Filtering

### Products Search

```http
GET /admin/produk?search=batik&category=1&min_price=100000&max_price=500000
```

### Orders Filtering

```http
GET /admin/orders?status=pending&date_from=2024-01-01&date_to=2024-12-31
```

### Advanced Search Example

```javascript
// DataTable search implementation
$("#products-table").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: "/admin/produk/data",
        data: function (d) {
            d.category = $("#category-filter").val();
            d.status = $("#status-filter").val();
            d.min_price = $("#min-price").val();
            d.max_price = $("#max-price").val();
        },
    },
});
```
