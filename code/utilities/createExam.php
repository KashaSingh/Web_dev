<?php
session_start();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
$userID = $_SESSION["userID"];
$assignmentName = $_POST["name"];
$startTime = $_POST["start"];
$endTime = $_POST["end"];
$instanceID = $_POST["instanceID"];
$total = $_POST["total"];
$query = $con->prepare("SELECT COUNT(id) FROM examinations WHERE instanceID=:instanceID AND name=:assignmentName");
$query->bindParam(":instanceID", $instanceID);
$query->bindParam(":assignmentName",$assignmentName);
$query->execute();
$sqldata = $query->fetch(PDO::FETCH_ASSOC);
if($sqldata["COUNT(id)"]==0)
{
    $query1 = $con->prepare("INSERT INTO examinations (instanceID, name, startTime, endTime, noQuestions) VALUES (:instanceID, :assignmentName, :startTime, :endTime, :questions)");
    $query1->bindParam(":instanceID", $instanceID);
    $query1->bindParam(":assignmentName",$assignmentName);
    $query1->bindParam(":startTime",$startTime);
    $query1->bindParam(":endTime",$endTime);
    $query1->bindParam(":questions",$total);
    $query1->execute();
    ?>
    <script>
        $('#uploadWarningAssignment', window.parent.document).text("Exam Created Successfully!");
    </script>
<?php
}
else
{?>
    <script>
        $('#uploadWarningAssignment', window.parent.document).text("Another examination you created has the same name!");
    </script>
<?php
}
?>