<?php
include_once "utils/utils.php";
require_once "utils/DBConnection.php";
require_once "objects/Item.php";
require_once "objects/Cart.php";
require_once "objects/Session.php";

session_start();

//check login status, send to login page if not logged in
$hash = $_SESSION['hash'] ?? "";
$userStatement = $pdo->query("SELECT userID, username, role FROM EEUser WHERE EEUser.password = '$hash';");
$userStatement->setFetchMode(PDO::FETCH_ASSOC);

$currentUserData = $userStatement->fetch();

if(!$currentUserData)
{
    header("Location: LoginPage.php");
    exit;
}

//if theres a cart in the session, grab that. otherwise, make a new cart object
$cart = $_SESSION['cart'] ?? new Cart();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esoteric Emporium</title>
    <link rel="stylesheet" href="css/main_page.css">
    <link href='https://unpkg.com/css.gg@2.0.0/icons/css/shopping-cart.css' rel='stylesheet'>
</head>
<body>
    <nav class="navbar">
        <img source="img/logo.jpg">
        <input class="menu-btn" type="checkbox" id="menu-btn">
        <label class="menu-icon" for="menu-btn"><span class="nav-icon"></span></label>
        <ul class="menu">
            <li><a href="MainPage.php">Home</a></li>
            <li><a href="ProductPage.php">Products</a></li>
        </ul>
        <a href="CartPage.php"><i class="gg-shopping-cart"></i></a>
    </nav>

    <section>
    <section id="head">
      
    </section>
    <section id="feature-content">
        <div class="img">
            <img src="img/logo.png">
            <h2>[Gateway to the Extraordinary]</h2>
        </div>
    </section>
    </section>
</body>
</html>