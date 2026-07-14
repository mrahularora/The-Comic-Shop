# The Comic Shop

A PHP/MySQL comic book store built for XAMPP. Customers can browse comics, add items to a cart, sign up, log in, place orders, and download generated PDF invoices. Admin users can add, edit, and delete products.

## Features

- Product catalog with Marvel, DC, and other comic categories
- Cart with quantity limits
- User signup/login API
- Checkout with order storage
- PDF invoice generation with FPDF
- Admin product management

## Tech Stack

- PHP 8
- MySQL
- PDO
- FPDF
- HTML, CSS, JavaScript

## Setup

1. Copy the project into your XAMPP `htdocs` folder.
2. Create a MySQL database named `comic_book_store`.
3. Import or create the required tables: `users`, `categories`, `products`, `orders`, and `orderitems`.
4. Start Apache and MySQL in XAMPP.
5. Open `http://localhost/The-Comic-Shop/`.

Database settings live in `database.php`.

## Notes

- Generated invoice PDFs are written to `pdf/` and ignored by Git.
- Product uploads are stored in `images/products/`.

## License

MIT
