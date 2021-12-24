<?php
session_start();
require_once("config.php");
$assignmentName = $_POST["assignmentName"];
$instanceID = $_POST["instanceID"];
$question = $_POST["question"];
$optionA = $_POST["optionA"];
$optionB = $_POST["optionB"];
$optionC = $_POST["optionC"];
$optionD = $_POST["optionD"];
$answer = $_POST["answer"];
$query = $con->prepare("SELECT id FROM examinations WHERE instanceID=:instanceID AND name=:assignmentName");
$query->bindParam(":instanceID", $instanceID);
$query->bindParam(":assignmentName",$assignmentName);
$query->execute();
$sqldata = $query->fetch(PDO::FETCH_ASSOC);
$query1 = $con->prepare("INSERT INTO questionandanswers (assignment, question, optionA, optionB, optionC, optionD, answer) VALUES (:assignmentID, :question, :optionA, :optionB, :optionC, :optionD, :answer)");
$query1->bindParam(":assignmentID", $sqldata["id"]);
$query1->bindParam(":question",$question);
$query1->bindParam(":optionA",$optionA);
$query1->bindParam(":optionB",$optionB);
$query1->bindParam(":optionC",$optionC);
$query1->bindParam(":optionD",$optionD);
$query1->bindParam(":answer",$answer);
$query1->execute();
?>