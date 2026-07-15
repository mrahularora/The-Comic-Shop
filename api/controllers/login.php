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
        // Start the session
        session_start();

        // Get POST data
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            return json_encode([
                "status" => "error",
                "message" => "Invalid input data."
            ]);
        }

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            http_response_code(400);
            return json_encode([
                "status" => "error",
                "message" => "Email and password are required."
            ]);
        }

        // Check if user exists
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(["email" => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
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
