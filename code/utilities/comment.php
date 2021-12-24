<?php
require_once("config.php");
$userID = $_POST["userID"];
$videoID = $_POST["videoID"];
$comment = $_POST["comment"];
$query = $con->prepare("INSERT INTO comments (videoID, userID, comment) VALUES (:videoID,:userID, :comment)");
$query->bindParam(":videoID", $videoID);
$query->bindParam(":userID", $userID);
$query->bindParam(":comment", $comment);
$query->execute();
?>