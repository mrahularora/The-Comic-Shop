<?php
require_once(__DIR__ . "/../../config/database.php");

class SignupController
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function signup()
    {
        // Get POST data
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Invalid input data."
            ]);
            return;
        }

        $name = trim($data['name'] ?? '');
        $email = strtolower(trim($data['email'] ?? ''));
        $password = $data['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Name, email, and password are required."
            ]);
            return;
        }

        if (!preg_match('/^[A-Za-z][A-Za-z0-9 _-]{2,49}$/', $name)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Username must be 3-50 characters and start with a letter."
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "A valid email address is required."
            ]);
            return;
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Password must be at least 8 characters and include uppercase, lowercase, and a number."
            ]);
            return;
        }

        // Check if email or username already exists
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email OR username = :username");
        $stmt->execute([
            "email" => $email,
            "username" => $name
        ]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            http_response_code(409);
            echo json_encode([
                "status" => "error",
                "message" => "User with the given email or username already exists."
            ]);
            return;
        }

        // Hash the password and insert new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:name, :email, :password)");
        try {
            $stmt->execute([
                "name" => $name,
                "email" => $email,
                "password" => $hashedPassword
            ]);
            http_response_code(201);
            echo json_encode([
                "status" => "ok",
                "message" => "User registered successfully."
            ]);
        } catch (PDOException $e) {
            // Handle any database-related errors
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Registration failed. Please try again."
            ]);
        }
    }
}
