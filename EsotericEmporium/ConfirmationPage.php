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
<html lang = "en">
    <head>
        <meta charset="UTF-8">
        <title>Esoteric Emporium</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/main_page.css">
        <link href='https://unpkg.com/css.gg@2.0.0/icons/css/shopping-cart.css' rel='stylesheet'>
    </head>
    <body>
        <div id="page">
            <header>
            </header>
            <div class="page3">
                <div id="content">
                    <h3>YOUR ORDER HAS BEEN PROCESSED</h3>
                    <form class="shipping">
                        <p>Your items will arrive in 14-21 business days.</p>
                        <h3 id="thanks">Thanks for your order, <?= $currentUserData["username"] ?>!</h3>
                        <p id="subtotalList" class="selector"></p>
                        <p id="subtotal" class="selector"></p>
                        <p id="tax" class="selector"></p>
                        <h3 id="total" class="selector"></h3>
                    </form>
                </div>
            </div>
            <p id="container">
            <a href="ProductPage.php"><button type="button" id="new" name="new">Back to Shopping</button></a>
            </p>
        </div>
   </body>
</html>