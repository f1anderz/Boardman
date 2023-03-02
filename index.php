<?php
// headers for CORS policy
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Credentials: true");

// setting type of content to json
header("Content-type: application/json");

// including in file connection to database and all requests
require_once("database/connection.php");
require_once("database/api_key.php");
require_once("requests/index.php");

// setting connection
$connection = $database_connect;
// creating variables required for API work
// setting request method
$request_method = $_SERVER["REQUEST_METHOD"];
// getting params from request string
$params = explode("/", $_GET["request"]);
// setting resource identifier and id
$resource = $params[0];
$id = $params[1];
$data_modifier = $params[2];
// getting api key from request headers
$api_key = $_SERVER["HTTP_X_API_KEY"];

// verifying api key with hashed value written in file
if (password_verify($api_key, $key)) {
// switching through request methods
    switch ($request_method) {
        case "GET":
            // switching through resource identifiers
            switch ($resource) {
                case "users":
                    if (isset($id)) {
                        get_user($connection, $id);
                    } else {
                        get_users($connection);
                    }
                    break;
            }
            break;
        case "POST":
            // switching through resource identifiers
            switch ($resource) {
                case "login":
                    login_user($connection, $_POST);
                    break;
            }
            break;
        case "PATCH":
            // switching through resource identifiers
            switch ($resource) {
                case "users":
                    switch ($data_modifier) {
                        case "password":
                            $data = file_get_contents("php://input");
                            $data = json_decode($data, true);
                            change_password($connection, $id, $data);
                            break;
                    }
                    break;
            }
            break;
        case "DELETE":
            // switching through resource identifiers
            switch ($resource) {

            }
            break;
    }
} else { // if key is not verified, calling a function to say that client is not authorized
    noKey();
}