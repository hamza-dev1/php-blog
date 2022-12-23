<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'].'/blog/config/db.php';

// Getting form data
if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['name'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password_confirmation = filter_var($_POST['password_confirmation'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $avatar = $_FILES['avatar'];
    
    // validation
    if (empty($name) || empty($email) || empty($password) || empty($password_confirmation)) {
        $_SESSION['required'] = "This field is required!";
    } 
    if (strlen($password) < 8 || strlen($password_confirmation) < 8) {
        $_SESSION['password_length'] = "Password should be greater than or equal to 8 characters";
    } else {
        if ($password !== $password_confirmation) {
            $_SESSION['password_diff'] = "Confirm Password and password should be the same!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }
    }

    // check if email already exists
    $email_check_query = "SELECT * FROM users WHERE email='$email'";
    if (mysqli_num_rows(mysqli_query($connection, $email_check_query)) > 0) {
        $_SESSION['already_exist'] = 1;
    }

    // avatar

        // rename file
    $random_nbr = time();
    $avatar_name = $random_nbr . $avatar['name'];
    $avatar_tmp_name = $avatar['tmp_name'];
    $avatar_dest_path = $_SERVER['DOCUMENT_ROOT'] . '/blog/images/' . $avatar_name;

        // check if file allowed
    $allowed_format = ['png', 'jpg', 'jpeg'];
    $extention = explode('.', $avatar_name);
    $extention = end($extention);
    if (in_array($extention, $allowed_format)) {
        if ($avatar['size'] < 1000000) {
            move_uploaded_file($avatar_tmp_name, $avatar_dest_path);
        } else {
            $_SESSION['avatar_big_size'] = 1;
        }
    } else {
        $_SESSION['avatar_malformat'] = 1;
    }

    // Redirect if an error occurs
    /*if (isset($_SESSION[''])) {
        // redirect to register page
        //header('location: ' . ROOT_URL . 'views/signup.php');
        //var_dump($_SESSION);
        die();
    } else {
        // insert user into db
        $insert_user_query = "INSERT INTO users (name, email, password, avatar, is_admin) VALUES('$name', '$email', '$hashed_password', '$avatar_name', 0)";
        if (!mysqli_errno($connection)) {
            // redirect to login page after successfully register user
            $_SESSION['register_success'] = "Registration successfully";
            header('location: ' . ROOT_URL . 'views/signin.php');
            //var_dump($_SESSION);
            exit();
        }
    }*/
            $_SESSION['register_success'] = "Registration successfully";
            header('location: ' . ROOT_URL . 'views/signin.php');
            //var_dump($_SESSION);
            exit();

} else {

    header('location: ' . ROOT_URL . 'views/signup.php');

}

?>