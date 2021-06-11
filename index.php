<?php
require 'vendor/autoload.php';

session_start();

$loader = new Twig\Loader\FilesystemLoader('views');
$twig = new Twig\Environment($loader);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = mysqli_connect($_ENV['HOST'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DATABASE']);


if($_SESSION['username'] && !$_SESSION['errors']){

    $result_category = mysqli_query($db, "SELECT * FROM category");
    $categories = mysqli_fetch_all($result_category);

    $result_status = mysqli_query($db, "SELECT * FROM status");
    $statuses = mysqli_fetch_all($result_status);

    $article_query = "SELECT title, description, status.status_name, users.username, category.category_name, articles.id FROM articles JOIN status ON articles.status_id = status.id JOIN users ON articles.user_id = users.id JOIN category ON articles.category_id = category.id";
    $result_articles = mysqli_query($db, $article_query);
    $articles = mysqli_fetch_all($result_articles);

    echo $twig->render('articles/article_list.html.twig',array(
            'username' => $_SESSION['username'],
            'categories' => $categories,
            'statuses' => $statuses,
            'articles' => $articles
        ),
    );
}

else{
    if($_SESSION['errors']){
        echo $twig->render('errors/error_validate.html.twig',array(
                'errors' => $_SESSION['errors'])
        );
    }
    else{
        if($_GET['login'] == true){
            echo $twig->render('registration/login.html.twig');
        }
        else{
            echo $twig->render('registration/register.html.twig');
        }

    }
}







