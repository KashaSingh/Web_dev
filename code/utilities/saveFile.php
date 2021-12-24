<?php
require_once("config.php");
$textTitle = $_POST["textTitle"];
$textContent = $_POST["textContent"];
$userID = $_POST["userID"];
$videoID = $_POST["videoID"];
$query = $con->prepare("SELECT COUNT(id) FROM notes WHERE videoID=:videoID AND userID=:userID");
$query->bindParam(":videoID", $videoID);
$query->bindParam(":userID", $userID);
$query->execute();
$sqldata = $query->fetch(PDO::FETCH_ASSOC);
if($sqldata["COUNT(id)"]==1)
{
    $query1 = $con->prepare("UPDATE notes SET title=:title, content=:content WHERE videoID=:videoID AND userID=:userID");
    $query1->bindParam(":title", $textTitle);
    $query1->bindParam(":content", $textContent);
    $query1->bindParam(":videoID", $videoID);
    $query1->bindParam(":userID", $userID);
    $query1->execute();
}
else
{
    $query2 = $con->prepare("INSERT INTO notes (title, content, videoID, userID) VALUES (:title, :content, :videoID,:userID)");
    $query2->bindParam(":title", $textTitle);
    $query2->bindParam(":content", $textContent);
    $query2->bindParam(":videoID", $videoID);
    $query2->bindParam(":userID", $userID);
    $query2->execute();
}
?>