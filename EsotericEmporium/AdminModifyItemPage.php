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

//get the selected item
$statement = $pdo->query( "SELECT * FROM Item WHERE Item.itemID = $itemID");
$statement->setFetchMode(PDO::FETCH_CLASS, "Item");

//get the Item object retrieved 
$item = $statement->fetch();

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

    $selected = array_keys($_POST)[0];

    if(isset($_POST["Cancel"])) //redirect to the items page
    {
        header("Location: AdminItemPage.php");
        exit;
    }

    if(isset($_POST["Save"])) //save changes 
    {
        //get the changes
        $name = htmlspecialchars(($_POST["name"] == "" ? "Item" : $_POST["name"])) ?? "Item";
        $description = htmlspecialchars(($_POST["description"] == "" ? "No description." : $_POST["description"])) ?? "No description.";
        $price = (float)$_POST["price"] ?? 9.99;

        //if there is a new image, update that. otherwise, dont
        if($_FILES["image"]["tmp_name"] != null)
        {
            $target_dir = "img/";
            $filename = basename($_FILES["image"]["name"]);
            $target_file = ($target_dir . $filename);

            //save the uploaded file
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

            //apply the changes to the Item in the database
            $pdo->query( "UPDATE Item
                        SET itemName = '$name', itemDescription = '$description', itemPrice = $price, itemImageName = '$filename'
                        WHERE Item.itemID = $itemID");
        }
        else
        {
            //apply the changes to the Item in the database
            $pdo->query( "UPDATE Item
            SET itemName = '$name', itemDescription = '$description', itemPrice = $price
            WHERE Item.itemID = $itemID");
        }

        header("Location: AdminItemPage.php");
        exit;
    }
}

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
        <form method="POST" action="AdminModifyItemPage.php" enctype="multipart/form-data">
        <h2>Modify Item</h2>
        <table>
            <tr>
                <th class="slim">
                    NAME
                </th>
                <td class="slim"> 
                <p> <textarea rows="1" cols="100" name="name"><?= htmlspecialchars($item->getItemName(), ENT_QUOTES, 'UTF-8', false); ?></textarea></p>
                </td>
            </tr>
            <tr>
                <th>
                    DESCRIPTION
                </th>
                <td> 
                    <p> <textarea rows="10" cols="100" name="description"><?= htmlspecialchars($item->getItemDescription(), ENT_QUOTES, 'UTF-8', false); ?></textarea></p>
                </td>
            </tr>
            <tr>
                <th>
                    PRICE
                </th>
                <td> 
                    <p> <input type="number" name="price" value="<?= htmlspecialchars($item->getItemPrice(), ENT_QUOTES, 'UTF-8', false); ?>"> </p>
                </td>
            </tr>
            <tr>
                <th>
                    IMAGE (JPEG)
                </th>
                <td> 
                    <?= $item->getImage(100) ?>
                    <p> <input type="file" name="image"> </p>
                </td>
            </tr>
            <td colspan="2"> 
                <p> <input type="submit" value="Save" name="Save"> <input type="submit" value="Cancel" name="Cancel"> </p>
            </td>
            </tr>
        </table>
        </form> 

        </section>
    </body>
</html>