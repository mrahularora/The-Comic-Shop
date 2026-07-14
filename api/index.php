<?php
header("Cache-Control: private, no-cache, no-store, must-revalidate");
header("Content-Type: application/json");

$customUrl = $_GET["url"] ?? "";
$pathdata = explode("/", $customUrl);

$routeClasses = [
    "login",
    "signup"
];

if (in_array($pathdata[0], $routeClasses)) {
    $controllerFile = __DIR__ . "/controllers/{$pathdata[0]}.php";
    if (file_exists($controllerFile)) {
        require_once($controllerFile);
        $routeClassName = ucfirst($pathdata[0]) . "Controller";

        $routeClassInstance = new $routeClassName();

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
