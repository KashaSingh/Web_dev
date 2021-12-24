<?php
session_start();
require_once("config.php");
if(isset($_POST["examID"])&&isset($_POST["answers"]))
{
    $examID = $_POST["examID"];
    $answers = $_POST["answers"];
}
else
{
    exit();
}
$n=1;
foreach($answers as $answer)
{
    $query1 = $con->prepare("SELECT * FROM (SELECT * FROM questionandanswers WHERE assignment=:examID ORDER BY id ASC LIMIT $n) AS qa ORDER BY id DESC LIMIT 1");
    $query1->bindParam(":examID", $examID);
    $query1->execute();
    $n++;
    $sqldata1 = $query1->fetch(PDO::FETCH_ASSOC);
    $questionID = $sqldata1["id"];
    $query2 = $con->prepare("INSERT INTO examsubmissions (examID, userID, questionID, answer) VALUES (:examID, :userID, :questionID, :answer)");
    $query2->bindParam(":examID", $examID);
    $query2->bindParam(":userID", $_SESSION["userID"]);
    $query2->bindParam(":answer", $answer);
    $query2->bindParam(":questionID", $questionID);
    $query2->execute();
}
?>