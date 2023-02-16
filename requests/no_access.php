<?php
// response for unauthorized clients
function noKey()
{
    // setting response code to 401
    http_response_code(401);
    // creating response array with error
    $response = [
        "status" => false,
        "message" => "No API authorization"
    ];
    // encoding response and printing it
    echo json_encode($response);
}