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


$itemID = $_SESSION["selectedItemID"] ?? 1;

if(!$currentUserData)
{
    header("Location: LoginPage.php");
    exit;
}

//if the current user is not an admin, send them back to the main page
if($currentUserData["role"] != "Administrator")
{
    header("Location: MainPage.php");
    exit;
}


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

    //if an admin clicked the restock all button
    else if(isset($_POST["Reset"]))
    {
        $pdo->query( "UPDATE Item SET itemHidden = False");  
        header("Location: AdminItemPage.php");
        exit;
        return;
    }

    $selected = array_keys($_POST)[0];

    if($_POST[$selected] == "Hide / Unhide") //redirect to the view the selected item's modify page
    {
        $_SESSION["selectedItemID"] = $selected;

        $pdo->query( "UPDATE Item SET itemHidden = NOT itemHidden WHERE Item.itemID = $selected");
        
        header("Location: AdminItemPage.php");
        exit;
    }

    if($_POST[$selected] == "View / Modify") //redirect to the view the selected item's modify page
    {
        $_SESSION["selectedItemID"] = $selected;
        
        header("Location: AdminModifyItemPage.php");
        exit;
    }

    if($_POST[$selected] == "Delete") //delete the selected item, then redirect back to the items list 
    {
        $pdo->query( "DELETE FROM Item WHERE itemID = $selected");
    
        header("Location: AdminItemPage.php");
        exit;
    }

    if($_POST[$selected] == "Add New Item") //redirect to new item page
    {
        header("Location: AdminAddItemPage.php");
        exit;
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

        <section id="head">
        <form action="AdminItemPage.php" method="POST">
            <h2> <?= "Hello, " . $currentUserData["role"] . " \"" . $currentUserData["username"] . "\"!" ?> </h2>
            <input type="submit" name="LogoutUser" value="Logout"/>
        </form>
        </section>
        <section id="feature-content">
        
        <table border="1">
        <p>
        <a href="AdminAddItemPage.php"><input type="button" value="Create New Item"></a>
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
        </p>
        <th> ITEM ID </th>
        <th> NAME </th>
        <th> DESCRIPTION </th>
        <th> PRICE </th>
        <th> IMAGE </th>
        <th> STATUS </th>
        <?php
        //dynamically display a list of all items in stock
        foreach($items as $item)
        {
            ?>
                <tr>
                    <td>
                        <p> <?= htmlspecialchars($item->getItemID(), ENT_QUOTES, 'UTF-8', false); ?> </p>
                    </td>
                    <td>
                        <p> <?= htmlspecialchars($item->getItemName(), ENT_QUOTES, 'UTF-8', false); ?> </p>
                    </td>
                    <td>
                        <p> <?= htmlspecialchars($item->getItemDescription(), ENT_QUOTES, 'UTF-8', false); ?> </p>
                    </td>
                    <td>
                        <p> <?= htmlspecialchars("$" . $item->getItemPrice(), ENT_QUOTES, 'UTF-8', false); ?> </p>
                    </td>
                    <td>
                        <?= $item->getImage(100) ?>
                    </td>
                    <td>
                        <p> <?= ($item->getItemHidden()) ? "Hidden" : "In Stock" ?> </p>
                    </td>
                    <td> 
                        <form method = "POST">
                            <input type="submit" id="hide/unhide" name=<?= $item->getItemID() ?> value="Hide / Unhide">
                        </form> 
                    </td>
                    <td> 
                        <form method = "POST">
                            <input type="submit" id="view/modify" name=<?= $item->getItemID() ?> value="View / Modify">
                        </form> 
                    </td>
                    <td> 
                        <form method = "POST">
                            <input type="submit" id="delete" name=<?= $item->getItemID() ?> value="Delete">
                        </form> 
                    </td>
                </tr>
            <?php
            }
        ?>
        </table>
        </section>
    </body>
</html>