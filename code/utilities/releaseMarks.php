<?php
session_start();
require_once("config.php");
$queryx = $con->prepare("UPDATE examinations SET released=1 WHERE id=:examID");
$queryx->bindParam(":examID", $_POST["examID"]);
$queryx->execute();
$query = $con->prepare("SELECT * FROM questionandanswers WHERE assignment=:examID");
$query->bindParam(":examID", $_POST["examID"]);
$query->execute();
$sqldata = $query->fetchAll(PDO::FETCH_ASSOC);
foreach($sqldata as $row)
{
    $query1 = $con->prepare("SELECT * FROM examsubmissions WHERE questionID=:questionID");
    $query1->bindParam(":questionID", $row["id"]);
    $query1->execute();
    $sqldata1 = $query1->fetchAll(PDO::FETCH_ASSOC);
    foreach($sqldata1 as $row1)
    {
        if($row["answer"]==$row1["answer"])
        {
            $query2 = $con->prepare("UPDATE examsubmissions SET correct=1 WHERE userID=:userID AND questionID=:questionID");
            $query2->bindParam(":questionID", $row["id"]);
            $query2->bindParam(":userID", $row1["userID"]);
            $query2->execute();
        }
    }
}
?>