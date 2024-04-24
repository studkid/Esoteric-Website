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

//if the current user is an admin, send them to the admin item page
if($currentUserData["role"] == "Administrator")
{
    header("Location: AdminItemPage.php");
    exit;
}

//if theres a cart in the session, grab that. otherwise, make a new cart object
$cart = $_SESSION['cart'] ?? new Cart();


if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    //if the user is logging out
    if(isset($_POST["LogoutUser"]))
    {
        echo "logged out";
        $session = new Session();
        $session->shutDown();
        header("Location: LoginPage.php");
        exit;
        return;
    }

    //if an admin clicked the restock button
    else if(isset($_POST["Reset"]))
    {
        $pdo->query( "UPDATE Item SET itemHidden = False");  
        header("Location: ProductPage.php");
        exit;
        return;
    }
    else
    {
        //add the item to the cart

        //get id of the selected item
        $id = array_keys($_POST)[0];

        //SQL to get the item from the database that has that id
        $statement = $pdo->query( "SELECT * FROM item WHERE itemID = $id");
        $statement->setFetchMode(PDO::FETCH_CLASS, "Item");
        
        //execute the SQL statement and store it in a variable as an Item object
        $item = $statement->fetchAll()[0];

        //add the Item object to the cart
        $cart->AddItem($item);

        //set the item to Hidden in the database
        $pdo->query( "UPDATE Item 
        SET itemHidden = True
        WHERE Item.itemID = $id");  

        //put the updated data back in the session
        $_SESSION['cart'] = $cart;

        //go to the cart
        header("Location: cartPage.php");
    }
}

//get a list of all items in the database
$statement = $pdo->query( "SELECT * FROM Item ORDER BY itemID");
$statement->setFetchMode(PDO::FETCH_CLASS, "Item");
$items = $statement->fetchAll();

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Esoteric Emporium</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/product_page.css">
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

        <section id="head">
        <form action="ProductPage.php" method="POST">
            <h2> <?= "Hello, " . $currentUserData["role"] . " \"" . $currentUserData["username"] . "\"!" ?> </h2>
            <input type="submit" name="LogoutUser" value="Logout"/>
            <a href="CartPage.php"><input type="button" value="My Cart"></a>
            <?php
            //if the current user is an Admin, show a button that allows them to restock all items
            if($currentUserData["role"] == "Administrator")
            { 
            ?>
                <form method = "POST">
                    <input type="submit" id="reset" name="Reset" value="Restock All Items">
                </form> 
            <?php 
            } 
            ?>
        </form>
        </section>
        <section id="feature-content">

        
        <table>
        <!--<th> NAME </th>-->
        <!--<th> DESCRIPTION </th>-->
        <!--<th> PRICE </th>-->
        <?php
            //dynamically display a list of all items in stock
            foreach($items as $item)
            {
                //items set to 'hidden' are omitted
                if($item->getItemHidden() == false)
                {
                ?>
                    <tr>
                        <td>
                             <p> <b> <?= htmlspecialchars($item->getItemName(), ENT_QUOTES, 'UTF-8', false); ?> </b> </p>
                        </td>
                        <td>
                             <p> <?= htmlspecialchars($item->getItemDescription(), ENT_QUOTES, 'UTF-8', false); ?> </p>
                        </td>
                        <td>
                             <p> <?= htmlspecialchars("$" . $item->getItemPrice(), ENT_QUOTES, 'UTF-8', false); ?> </p>
                        </td>
                        <td class="img">
                            <?= $item->getImage(100) ?>
                        </td>
                        <td> 
                            <form method = "POST">
                                <input type="submit" id="submit" name=<?= $item->getItemID() ?> value="Add to Cart">
                            </form> 
                        </td>
                    </tr>
                <?php
                }
            }
        ?>
        </table>
        </section>
    </body>
</html>
