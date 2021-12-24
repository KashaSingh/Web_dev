<?php
session_start();
require_once("config.php");
$userID = $_SESSION["userID"];
$instanceID = $_POST["instanceID"];
echo $instanceID;
$query = $con->prepare("SELECT COUNT(id) FROM includedcontent WHERE userID=:userID AND instanceID=:instanceID");
$query->bindParam(":userID",$userID);
$query->bindParam(":instanceID",$instanceID);
$query->execute();
$sqldata = $query->fetch(PDO::FETCH_ASSOC);
echo $sqldata["COUNT(id)"];
if($sqldata["COUNT(id)"]==1)
{
    exit();
}
else
{
    $query1 = $con->prepare("SELECT learners FROM courseinstances WHERE id=:instanceID");
    $query1->bindParam(":instanceID",$instanceID);
    $query1->execute();
    $sqldata1 = $query1->fetch(PDO::FETCH_ASSOC);
    $learnersCount = $sqldata1["learners"]+1;
    echo $learnersCount;
    $query2 = $con->prepare("UPDATE courseinstances SET learners=:learners WHERE id=:instanceID");
    $query2->bindParam(":learners",$learnersCount);
    $query2->bindParam(":instanceID",$instanceID);
    $query2->execute();
    $query3 = $con->prepare("INSERT INTO includedcontent (userID, instanceID) VALUES (:userID, :instanceID)");
    $query3->bindParam(":instanceID",$instanceID);
    $query3->bindParam(":userID",$userID);
    $query3->execute();
}
?>