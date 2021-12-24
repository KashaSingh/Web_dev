<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
$assignmentID = $_POST["existingName"];
$query1 = $con->prepare("DELETE FROM examinations WHERE id=:assignmentID");
$query1->bindParam(":assignmentID", $assignmentID);
$query1->execute();
$query2 = $con->prepare("DELETE FROM questionandanswers WHERE assignment=:assignmentID");
$query2->bindParam(":assignmentID", $assignmentID);
$query2->execute();
$query3 = $con->prepare("DELETE FROM examsubmissions WHERE examID=:assignmentID");
$query3->bindParam(":assignmentID", $assignmentID);
$query3->execute();
?>
<script>
    $('#uploadWarningAssignment', window.parent.document).text("Exam Cancelled Successfully!");
</script>
<?php
?>