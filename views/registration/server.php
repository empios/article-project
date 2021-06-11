<?php
session_start();

$username = "";
$email    = "";
$errors = array();
$db = mysqli_connect($_ENV['HOST'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);

function validate_form($data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

var_dump($_POST);
if(isset($_POST['update_article'])){
    $test_title = validate_form($_POST['title']);
    $test_description = validate_form($_POST['description']);

    $title = mysqli_real_escape_string($db, $test_title);
    $description = mysqli_real_escape_string($db, $test_description);
    $status = mysqli_real_escape_string($db, $_POST['status']);
    $category = mysqli_real_escape_string($db, $_POST['category']);
    $article_id = mysqli_real_escape_string($db, $_POST['article_id']);

    $result = mysqli_query($db, "UPDATE articles SET title='$title', description='$description', status_id='$status', category_id='$category' WHERE id = '$article_id'");
    header('location: /index.php');
    exit();
}

if(isset($_POST['id'])){
    $article_id = $_POST['id'];
    $result = mysqli_query($db, "DELETE FROM articles WHERE id = ".$article_id);
    header('location: /index.php');
    exit();
}

if(isset($_POST['add_article'])) {
    $test_title = validate_form($_POST['title']);
    $test_description = validate_form($_POST['description']);

    $title = mysqli_real_escape_string($db, $test_title);
    $description = mysqli_real_escape_string($db, $test_description);
    $status = mysqli_real_escape_string($db, $_POST['status']);
    $category = mysqli_real_escape_string($db, $_POST['category']);

    if($title == ""){array_push($errors, "Title is empty");}
    if($description == ""){array_push($errors, "Description is empty");}
    if (count($errors) == 0){
        $username = $_SESSION['username'];
        $user_check_query = "SELECT id FROM users WHERE username='$username' LIMIT 1";
        $result = mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($result);
        $user_id = $user['id'];
        $query = "INSERT INTO articles (title, description, status_id, user_id, category_id) VALUES('$title', '$description', '$status', '$user_id', '$category')";
        mysqli_query($db, $query);
    }
    else{
        $_SESSION['errors'] = $errors;
    }
    header('location: /index.php');
    exit();

}

if (isset($_POST['reg_user'])) {

    $test_username = validate_form($_POST['username']);
    $test_email = validate_form($_POST['email']);
    $test_password_1 = validate_form($_POST['password_1']);
    $test_password_2 = validate_form($_POST['password_2']);

    $username = mysqli_real_escape_string($db, $test_username);
    $email = mysqli_real_escape_string($db, $test_email);
    $password_1 = mysqli_real_escape_string($db, $test_password_1);
    $password_2 = mysqli_real_escape_string($db, $test_password_2);

    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }
    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }
        if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
        }
    }

    if (count($errors) == 0) {
        $password = md5($password_1);
        $query = "INSERT INTO users (username, email, password) VALUES('$username', '$email', '$password')";
        mysqli_query($db, $query);
        $_SESSION['username'] = $username;
        $_SESSION['errors'] = null;
        $_SESSION['success'] = "You are now logged in";
    }
    else{
        $_SESSION['errors'] = $errors;
    }
    header('location: /index.php');
    exit();
}

if (isset($_POST['login_user'])) {
    $test_username = validate_form($_POST['username']);
    $test_password = validate_form($_POST['password']);

    $username = mysqli_real_escape_string($db, $test_username);
    $password = mysqli_real_escape_string($db, $test_password);

    if (empty($username)) {
        array_push($errors, "Username is required");
        $_SESSION['errors'] = $errors;
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
        $_SESSION['errors'] = $errors;
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";

        }else {
            array_push($errors, "Wrong username/password combination");
            $_SESSION['errors'] = $errors;
        }
        header('location: /index.php');
        exit();
    }
}



