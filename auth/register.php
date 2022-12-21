<?php

require $_SERVER['DOCUMENT_ROOT'].'/blog/config/db.php';

// Getting form data
if (isset($_POST['submit'])) {
    //$name = filter_var($_POST['name']);
} else {

    header('location: ' . ROOT_URL . 'views/signup.php');

}


?>