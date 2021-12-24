<?php
require_once("config.php");
$query = $con->prepare("INSERT INTO questionandanswers (assignment, question, optionA, optionB, optionC, optionD, answer) VALUES (:assignmentID, :question, :optionA, :optionB, :optionC, :optionD, :answer)");
$query->bindParam(":assignmentID", $_POST["examID"]);
$query->bindParam(":question", $_POST["question"]);
$query->bindParam(":optionA", $_POST["optionA"]);
$query->bindParam(":optionB", $_POST["optionB"]);
$query->bindParam(":optionC", $_POST["optionC"]);
$query->bindParam(":optionD", $_POST["optionD"]);
$query->bindParam(":answer", $_POST["answer"]);
$query->execute();
?>