<?php
function get_all_staff($connection)
{
    $users = mysqli_query($connection, "SELECT staff.id, staff.full_name, staff.nickname, staff.email,
       staff.password, positions.position, avatars.avatar_filename, languages.language_key, staff.dark_mode FROM staff INNER JOIN positions ON staff.position_id = positions.id
           INNER JOIN avatars ON staff.avatar_id = avatars.id INNER JOIN languages ON staff.language_id = languages.id");
    if (mysqli_num_rows($users) == 0) {
        http_response_code(404);
        $response = [
            "status" => false,
            "message" => "No users found"
        ];
    } else {
        $response = array();
        while ($user = mysqli_fetch_assoc($users)) {
            $response[] = $user;
        }
    }
    echo json_encode($response);
}

function get_staff($connection, $id)
{
    $user = mysqli_query($connection, "SELECT staff.id, staff.full_name, staff.nickname, staff.email,
       staff.password, positions.position, avatars.avatar_filename, languages.language_key, staff.dark_mode FROM staff INNER JOIN positions ON staff.position_id = positions.id
           INNER JOIN avatars ON staff.avatar_id = avatars.id INNER JOIN languages ON staff.language_id = languages.id WHERE staff.id = '$id'");
    if (mysqli_num_rows($user) == 0) {
        http_response_code(404);
        $response = [
            "status" => false,
            "message" => "No user found with id = '$id'"
        ];
    } else {
        $response = mysqli_fetch_assoc($user);
    }
    echo json_encode($response);
}

function register_staff($connection, $data)
{
    $full_name = $data["full_name"];
    $nickname = $data['nickname'];
    $email = $data["email"];
    $position_id = $data["position_id"];
    $avatar_id = $data["avatar_id"];
    $language_id = $data["language_id"];
    $dark_mode = $data["dark_mode"];
    $password = password_hash($data["password"], PASSWORD_DEFAULT);
    mysqli_query($connection, "INSERT INTO staff (id,full_name,nickname,email,password,position_id) VALUES (NULL,
                    '$full_name', '$nickname', '$email', '$password', '$position_id', '$avatar_id','$language_id','$dark_mode')");
    http_response_code(201);
    $response = [
        "status" => true,
        "user_id" => mysqli_insert_id($connection)
    ];
    echo json_encode($response);
}

function login_staff($connection, $data)
{
    $email = $data["email"];
    $password = $data["password"];
    $users = mysqli_query($connection, "SELECT staff.id, staff.email, staff.password from staff");
    $user_list = array();
    while ($user = mysqli_fetch_assoc($users)) {
        $user_list[] = $user;
    }
    foreach ($user_list as $user) {
        if ($user["email"] == $email) {
            if (password_verify($password, $user["password"])) {
                http_response_code(200);
                $response = [
                    "status" => true,
                    "staff_id" => $user["id"]
                ];
            } else {
                http_response_code(404);
                $response = [
                    "status" => false,
                    "message" => "Wrong password"
                ];
            }
        } else {
            http_response_code(404);
            $response = [
                "status" => false,
                "message" => "No user with such login"
            ];
        }
        echo json_encode($response);
    }
}

function change_staff_password($connection, $id, $data)
{
    $old_password = $data["old_password"];
    $new_password = password_hash($data["new_password"], PASSWORD_DEFAULT);
    $user = mysqli_fetch_assoc(mysqli_query($connection, "SELECT staff.id, staff.password FROM staff WHERE staff.id = $id"));
    if (password_verify($old_password, $user["password"])) {
        mysqli_query($connection, "UPDATE staff SET staff.password = '$new_password' WHERE staff.id = $id");
        http_response_code(200);
        $response = [
            "status" => true,
            "message" => "Password successfully updated"
        ];
    } else {
        http_response_code(404);
        $response = [
            "status" => false,
            "message" => "Password didn't match"
        ];
    }
    echo json_encode($response);
}