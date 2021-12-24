<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
$assignmentID = $_POST["existingName"];
$query = $con->prepare("UPDATE examinations SET instanceID=:instanceID, startTime=:startTime, endTime=:endTime WHERE id=:assignmentID");
$query->bindParam(":assignmentID", $assignmentID);
$query->bindParam(":instanceID", $_POST["instanceID"]);
$query->bindParam(":startTime", $_POST["start"]);
$query->bindParam(":endTime", $_POST["end"]);
$query->execute();
?>
<script>
    $('#uploadWarningAssignment', window.parent.document).text("Exam Modified Successfully!");
</script>
<?php
?>