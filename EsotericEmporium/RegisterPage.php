<?php
require_once "objects/User.php";

//if the user is attempting to register 
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(isset($_POST["RegisterUser"]))
    {
        //create a User object with the input values, then attempt to register using the register() function
        $user = new User($_POST["username"], crypt($_POST["password"], "00d6fe4e87fdde69497361f15f6ffee0"));
        if($user->register())
        {
            header("Location: LoginPage.php");
            exit;
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
    <body>
        <h2>Register</h2>
        <form action="RegisterPage.php" method="POST">
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
                    <td colspan="2"><input type="submit" name="RegisterUser" value="Register User"/></td>
                </tr>
            </table>
        </form>
        <a href="LoginPage.php">Already registered? Login.</a>
    </body>
</html>