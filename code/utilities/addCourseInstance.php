<?php
session_start();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
$userID = $_SESSION["userID"];
$courseCode = $_POST["addCourseInstance"];
$query = $con->prepare("SELECT COUNT(id) FROM courseinstances WHERE userID=:userID AND courseCode=:code");
$query->bindParam(":userID",$userID);
$query->bindParam(":code",$courseCode);
$query->execute();
$sqldata = $query->fetch(PDO::FETCH_ASSOC);
if($sqldata["COUNT(id)"]==1)
{?>
    <script>
        $('#uploadWarningAddCourse', window.parent.document).text("Course Already Created!");
    </script>
<?php
}
else
{
    $query1 = $con->prepare("INSERT INTO courseinstances (courseCode, userID) VALUES (:code, :userID)");
    $query1->bindParam(":code",$courseCode);
    $query1->bindParam(":userID",$userID);
    $query1->execute();
    ?>
    <script>
        $('#uploadWarningAddCourse', window.parent.document).text("Course successfully added!");
    </script>
<?php
}
?>