# Product Purchase System

This project contains a Flutter client and a Laravel API backed by SQLite.
It is a small three-table database system:

```text
products (1) ----< purchases >---- (1) customers
```

## Run the backend

From `backend`:

```powershell
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve --host 0.0.0.0 --port 8000
```

API endpoints:

| Method | Endpoint | Purpose |
|---|---|---|
| GET/POST | `/api/products` | List or create products |
| GET/POST | `/api/customers` | List or create customers |
| GET/POST | `/api/purchases` | List or record purchases |

`POST /api/purchases` accepts `product_id`, `customer_id`, and `quantity`.
The API validates stock and calculates `total_price` in a transaction.

## Run the Flutter app

From the project root:

```powershell
flutter pub get
flutter run -d chrome
```

The Products tab reads from `GET /api/products`; the add-product form posts
to the same API. For an Android emulator, the app automatically uses
`http://10.0.2.2:8000`.

## Presentation content

- **Introduction:** A store tracks products, customers, and purchases.
- **Database diagram:** `products` and `customers` each have a one-to-many
  relationship with `purchases`.
- **Code snippets:** `backend/routes/api.php`,
  `backend/app/Http/Controllers/StoreController.php`, and the migrations.
- **Postman:** Demonstrate product list/create and purchase create requests.
- **Mobile screenshot:** Show the Flutter Products tab and Add Product form.
