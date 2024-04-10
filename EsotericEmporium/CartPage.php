<?php
include_once "utils/utils.php";
require_once "utils/DBConnection.php";
require_once "objects/Item.php";
require_once "objects/Cart.php";
require_once "objects/Session.php";

session_start();

//if theres a cart in the session, grab that. otherwise, make a new cart object
$cart = $_SESSION['cart'] ?? new Cart();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $cart->EmptyCart($pdo, true);
}
?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        <meta charset="UTF-8">
        <title>Esoteric Emporium</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/cart_page.css">
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
        <br><br><br><br><br>
        <a href="ProductPage.php" class="back">Back To Shopping</a>
        <section id="feature-content">
        <br><br>
        <?php
        //dynamically display a list of items in the cart
        $total = 0;
        foreach($cart->getItems() as $item)
        {
            $subtotal = $item->getItemPrice();
            ?>
                <table>
                    <tr>
                        <td rowspan="4"> <?= $item->getImage(200) ?> </td>
                        <td> <b> <?= $item->getItemName() ?> </b> </td>
                    </tr>
                    <tr>
                        <td> <?= "$" . number_format($item->getItemPrice(), 2) ?> </td>
                    </tr>
                    <tr>
                        <td> <?= $item->getItemDescription() ?> </td>
                    </tr>
                </table>
            <?php
            $total += $subtotal; 
        }
        echo "<hr><h2><br>Total: <b>$" . number_format($total, 2) . "</b></h2><br>"; 
        ?>
        <?php
        //if the cart isn't empty, show a button to allow the user to go to the purchase page
        if($cart->GetItems() != [])
        {
        ?>
        </section>
        <div class="selection">
        <form method="post">
            <input type="submit" id="submit" name="0" value="Empty Cart">
        </form>
            <a href="ShippingPage.php"><input type="button" value="Proceed to Purchase"></a>
        <?php
        }

        ?>
        </div>
        <br><br>

        <a href="ProductPage.php" class="back">Back To Shopping</a>
   </body>
</html>