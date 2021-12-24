<?php
require_once("config.php");
$query = $con->prepare("DELETE FROM questionandanswers WHERE id=:questionID");
$query->bindParam(":questionID", ltrim($_POST["questionID"], 'Q'));
$query->execute();
$query1 = $con->prepare("DELETE FROM examsubmissions WHERE questionID=:questionID");
$query1->bindParam(":questionID", ltrim($_POST["questionID"], 'Q'));
$query1->execute();
?>