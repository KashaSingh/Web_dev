<?php
require_once("config.php");
$videoID = $_POST["videoID"];
$userID = $_POST["userID"];
$query = $con->prepare("SELECT COUNT(id) FROM likes WHERE video_id=:videoID AND userID=:userID");
$query->bindParam(":videoID", $videoID);
$query->bindParam(":userID", $userID);
$query->execute();
$sqldata = $query->fetch(PDO::FETCH_ASSOC);
if($sqldata["COUNT(id)"]==1)
{
    $query1 = $con->prepare("DELETE FROM likes WHERE video_id=:videoID AND userID=:userID");
    $query1->bindParam(":videoID", $videoID);
    $query1->bindParam(":userID", $userID);
    $query1->execute();
}
else
{
    $query2 = $con->prepare("INSERT INTO likes (video_id, userID) VALUES (:videoID,:userID)");
    $query2->bindParam(":videoID", $videoID);
    $query2->bindParam(":userID", $userID);
    $query2->execute();
}
?>