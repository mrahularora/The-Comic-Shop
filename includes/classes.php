<?php

// ProductItem class
class ProductItem
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function normalizeImagePath($image)
    {
        return str_replace(['../Rahul/', 'Rahul/'], '', $image);
    }

    private function normalizeProduct($product)
    {
        if ($product && isset($product['image'])) {
            $product['image'] = $this->normalizeImagePath($product['image']);
        }

        return $product;
    }

    private function normalizeProducts($products)
    {
        return array_map([$this, 'normalizeProduct'], $products);
    }

    // Getting all products and randomizing them
    public function getProducts($sort = 'name_asc') {
        $sortOptions = [
            'name' => 'name ASC',
            'name_asc' => 'name ASC',
            'name_desc' => 'name DESC',
            'price' => 'price ASC',
            'price_asc' => 'price ASC',
            'price_desc' => 'price DESC',
            'newest' => 'id DESC',
            'oldest' => 'id ASC',
            'category' => 'category_id ASC, name ASC',
        ];
        $order_by = $sortOptions[$sort] ?? $sortOptions['name_asc'];
        $sql = "SELECT * FROM products ORDER BY $order_by";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $this->normalizeProducts($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getStaticProducts()
    {
        $query = "SELECT * FROM products";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $this->normalizeProducts($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Getting a product by its ID
    public function getProductById($id)
    {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->normalizeProduct($stmt->fetch(PDO::FETCH_ASSOC));
    }

    // Fetching Marvel products (limit by default is 8)
    public function fetchMarvelProducts($limit = 8)
    {
        $sql = "SELECT * FROM products WHERE category_id = '1' ORDER BY RAND() LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $this->normalizeProducts($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Fetching DC products (limit by default is 4)
    public function fetchDcProducts($limit = 4)
    {
        $sql = "SELECT * FROM products WHERE category_id = '2' ORDER BY RAND() LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $this->normalizeProducts($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function fetchOtherProducts($limit = 6)
    {
        $sql = "SELECT * FROM products WHERE category_id = '3' ORDER BY RAND() LIMIT :limit";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $this->normalizeProducts($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function addProduct($name, $description, $long_description, $price, $image, $category_id, $publisher = '', $writer = '', $format = '', $age_rating = '')
    {
        $this->conn->beginTransaction();

        try {
            $stmt = $this->conn->prepare("
                INSERT INTO products (
                    name,
                    description,
                    long_description,
                    price,
                    image,
                    category_id,
                    publisher,
                    writer,
                    format,
                    age_rating
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([$name, $description, $long_description, $price, $this->normalizeImagePath($image), $category_id, $publisher, $writer, $format, $age_rating]);
            $product_id = $this->conn->lastInsertId();
            $this->conn->commit();
            return $product_id;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function deleteProduct($product_id)
    {
        $this->conn->beginTransaction();

        try {
            $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function updateProduct($product_id, $name, $description, $long_description, $price, $image, $category_id, $publisher = '', $writer = '', $format = '', $age_rating = '')
    {
        $this->conn->beginTransaction();

        try {
            $stmt = $this->conn->prepare("
                UPDATE products
                SET 
                    name = ?, 
                    description = ?, 
                    long_description = ?, 
                    price = ?, 
                    image = ?, 
                    category_id = ?,
                    publisher = ?,
                    writer = ?,
                    format = ?,
                    age_rating = ?
                WHERE 
                    id = ?
            ");

            $stmt->execute([$name, $description, $long_description, $price, $this->normalizeImagePath($image), $category_id, $publisher, $writer, $format, $age_rating, $product_id]);
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}

// ShoppingCart class
class ShoppingCart
{
    private $maxQuantity = 5;
    public function addToCart($product_id, $quantity)
    {
        $product_id = (int) $product_id;
        $quantity = (int) $quantity;
        if ($product_id <= 0 || $quantity <= 0) {
            return 'error';
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $current_quantity = $_SESSION['cart'][$product_id];
            $new_quantity = $current_quantity + $quantity;
            if ($new_quantity > $this->maxQuantity) {
                return 'error'; // Exceeds maximum quantity
            }
            $_SESSION['cart'][$product_id] = $new_quantity;
        } else {
            if ($quantity > $this->maxQuantity) {
                return 'error'; // Exceeds maximum quantity
            }
            $_SESSION['cart'][$product_id] = $quantity;
        }
        return 'success'; // Successfully added
    }

    public function getCart()
    {
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    public function removeItem($product_id)
    {
        $product_id = (int) $product_id;
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public function updateQuantity($product_id, $quantity)
    {
        $product_id = (int) $product_id;
        $quantity = (int) $quantity;
        if ($quantity <= 0) {
            $this->removeItem($product_id);
            return 'success'; // Item removed
        } else if ($quantity > $this->maxQuantity) {
            return 'error'; // Exceeds maximum quantity
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
            return 'success'; // Successfully updated
        }
    }

    public function emptyCart()
    {
        $_SESSION['cart'] = [];
    }
}

// Order class
class Order
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function placeOrder($user_id, $cart, $contact, $address, $zip_code)
    {
        if (empty($cart)) {
            throw new InvalidArgumentException('Cannot place an order with an empty cart.');
        }

        $this->db->beginTransaction();
        try {
            // Calculate the total price for the order
            $subtotal = array_sum(array_map(function ($quantity, $product_id) {
                return $this->getProductPrice($product_id) * $quantity;
            }, $cart, array_keys($cart)));
            $total = $subtotal * 1.13;

            // Insert the order
            $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_price, shipping_address, zip_code, contact_number) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $total, $address, $zip_code, $contact]);
            $order_id = $this->db->lastInsertId();

            // Insert order items
            foreach ($cart as $product_id => $quantity) {
                $price = $this->getProductPrice($product_id);
                $stmt = $this->db->prepare("INSERT INTO orderitems (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $product_id, $quantity, $price]);
            }
            $this->db->commit();
            return $order_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function getProductPrice($product_id)
    {
        $stmt = $this->db->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchColumn();
    }

    public function getOrdersByUser($user_id)
    {
        $query = "SELECT
                    o.id,
                    o.total_price,
                    o.created_at,
                    o.shipping_address,
                    o.zip_code,
                    o.contact_number,
                    'Processing' AS order_status,
                    COALESCE(SUM(oi.quantity), 0) AS total_items
                FROM orders o
                LEFT JOIN orderitems oi ON o.id = oi.order_id
                WHERE o.user_id = :user_id
                GROUP BY o.id, o.total_price, o.created_at, o.shipping_address, o.zip_code, o.contact_number
                ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderDetails($order_id, $user_id = null)
    {
        $query = "SELECT 
                    o.id AS order_id,
                    o.user_id AS user_id,
                    u.username AS customer,
                    u.email AS customer_email,
                    o.total_price AS order_total_price,
                    o.shipping_address AS shipping_address,
                    o.zip_code AS zip_code,
                    o.contact_number AS contact_number,
                    o.created_at AS order_date,
                    'Processing' AS order_status,
                    p.id AS product_id,
                    p.name AS product_name,
                    p.description AS product_description,
                    p.image AS product_image,
                    oi.quantity AS product_quantity,
                    oi.price AS product_price
                FROM 
                    orders o
                JOIN 
                    users u ON o.user_id = u.id
                JOIN 
                    orderitems oi ON o.id = oi.order_id
                JOIN 
                    products p ON oi.product_id = p.id
                WHERE 
                    o.id = :order_id";

        if ($user_id !== null) {
            $query .= " AND o.user_id = :user_id";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        if ($user_id !== null) {
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


// Categories class
class Categories
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Getting all categories
    public function getCategories()
    {
        $query = "SELECT * FROM categories ORDER BY id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
