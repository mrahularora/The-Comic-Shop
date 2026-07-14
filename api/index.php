<?php
// Cache settings
header("Cache-Control: private, no-cache, no-store, must-revalidate");
header("Content-Type: application/json");

// Get the requested URL
$customUrl = $_GET["url"] ?? "";
$pathdata = explode("/", $customUrl);

// Debugging output
error_log("Requested URL: " . $customUrl);
error_log("Path Data: " . print_r($pathdata, true));

// Allowed routes for login and signup
$routeClasses = [
    "login",
    "signup"
];

// Check if the route is valid
if (in_array($pathdata[0], $routeClasses)) {
    // Include the appropriate controller file
    $controllerFile = "controllers/{$pathdata[0]}.php";
    if (file_exists($controllerFile)) {
        require_once($controllerFile);
        $routeClassName = ucfirst($pathdata[0]) . "Controller";

        // Create an instance of the class
        $routeClassInstance = new $routeClassName();

        // Handle different HTTP methods for login and signup
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($pathdata[0] == "login") {
                echo $routeClassInstance->login();
            } else if ($pathdata[0] == "signup") {
                $result = $routeClassInstance->signup();
                if ($result !== null) {
                    echo $result;
                }
            }
        } else {
            http_response_code(405);
            echo json_encode([
                "status" => "error",
                "message" => "Method Not Allowed"
            ]);
        }
    } else {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Controller file not found."
        ]);
    }
} else {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid endpoint."
    ]);
}
