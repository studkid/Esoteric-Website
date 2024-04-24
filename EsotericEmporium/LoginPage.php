<?php
require_once "objects/User.php";
require_once "objects/Session.php";

//if the user is attempting to login
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(isset($_POST["LoginUser"]))
    {
        //create a User object with the input values, then attempt to authenticate using the login() function
        $user = new User($_POST["username"], $_POST["password"]);
        if($user->login())
        {
            $session = new Session();
            $session->create( $user->getUsername(), $user->getRole());
            $loggedIn = true;
            $_SESSION["hash"] = crypt($_POST["password"], "00d6fe4e87fdde69497361f15f6ffee0");
            header("Location: MainPage.php");
            exit;
        }
        else 
        {
            echo "invalid login details";
            $userCreated = true;
        }
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Esoteric Emporium</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/login_page.css">
        <link href='https://unpkg.com/css.gg@2.0.0/icons/css/shopping-cart.css' rel='stylesheet'>
    </head>
    <body id="head">
        <h2>Login</h2>
        <form action="LoginPage.php" method="POST">
            <table>
                <tr>
                    <td>Username: </td>
                    <td><input type="text" name="username" required /></td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><input type="password" name="password" required /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="LoginUser" value="Login User"/></td>
                </tr>
            </table>
        </form>
        <a href="RegisterPage.php">New user? Register here.</a>
    </body>
</html>