<?php
require_once(__DIR__ . "/../../config/database.php");

class LoginController
{
    private $pdo;

    public function __construct($pdo = null)
    {
        if ($pdo === null) {
            $database = new Database();
            $pdo = $database->getConnection();
        }
        $this->pdo = $pdo;
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Get POST data
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            return json_encode([
                "status" => "error",
                "message" => "Invalid input data."
            ]);
        }

        $email = strtolower(trim($data['email'] ?? ''));
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            return json_encode([
                "status" => "error",
                "message" => "A valid email and password are required."
            ]);
        }

        // Check if user exists
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(["email" => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'] ?? 'customer';

            return json_encode([
                "status" => "ok",
                "message" => "Login successful."
            ]);
        }

        http_response_code(401);
        return json_encode([
            "status" => "error",
            "message" => "Invalid email or password."
        ]);
    }
}
