<?php
require_once("../utilities/config.php");
session_start();
$_SESSION["userID"] = $_POST["ID"];
$_SESSION["userRole"] = $_POST["role"];
$_SESSION["userMail"] = $_POST["email"];
$userID = $_POST["ID"];
$userName = $_POST["name"];
$userPicURL = $_POST["ImageURL"];
$userMail = $_POST["email"];
$userRole = $_POST["role"];
$userAccount = new Account($con);
if(!$userAccount->check($userID, $userMail, $userPicURL,$userRole))
{
    $userAccount->createAccount($userID, $userName, $userPicURL, $userMail, $userRole);
}
class Account
{
    private $con;
    public function __construct($con)
    {
        $this->con=$con;
    }
    public function check($userID, $userMail, $userPicURL,$userRole)
    {
        $query=$this->con->prepare("SELECT * FROM users WHERE id=:userID");
        $query->bindParam(":userID", $userID);
        $query->execute();
        if($query->rowCount()==0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    public function createAccount($userID, $userName, $userPicURL, $userMail, $userRole)
    {
        $query=$this->con->prepare("INSERT INTO users (id, name, email, imageURL, role) VALUES (:id, :name, :email, :picURL, :role)");
        $query->bindParam(":id", $userID);
        $query->bindParam(":name", $userName);
        $query->bindParam(":email", $userMail);
        $query->bindParam(":picURL", $userPicURL);
        $query->bindParam(":role", $userRole);
        $query->execute();
    }
}
?>