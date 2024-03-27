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

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    echo("items purchased");
    $cart->EmptyCart($pdo, false);
    header("Location: ConfirmationPage.php");
    exit;
    return;
}

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
            <div class="page2">
                <div id="content">
                    <!--form to take in the shipping address-->
                    <h3>SHIPPING DETAILS</h3>
                    <form destination="ShippingPage.php" method="POST" class="shipping" name="shippingDetails">
                        <h3>Full Name</h3>
                        <label>First: <input type="text" id="firstName" name="firstName" required></label>
                        <label>Last: <input type="text" id="lastName" name="lastName" required></label>
                        <h3>Payment Information</h3>
                        <label for="ccn">Credit Card Number:</label>
                        <input id="ccn" type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}" autocomplete="cc-number" maxlength="19" placeholder="xxxx xxxx xxxx xxxx" required>
                        <br>
                        <div id="addressInfo">
                        <h3>Address</h3>
                        <label>Street Address: <input type="text" id="address" name="address" required></label>
                        <br>
                        <label>City: <input type="text" id="city" name="city" required></label>
                        <br>
                        <label>State: <input type="text" id="state" name="state" required></label>
                        <br>
                        <label>Zip Code: <input type="number" id="zip" name="zip" value="12345" required></label>
                        <br>
                        <input type="submit" id="submit">
                        </div>
                    </form>
                </div>
            </div>
            <p id="container">
            <a href="ProductPage.php"><button type="button" id="new" name="new">Back to Shopping</button></a>
            </p>
        </div>
   </body>
</html>