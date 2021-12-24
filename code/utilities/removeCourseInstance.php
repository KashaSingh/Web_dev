<?php
session_start();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
$userID = $_SESSION["userID"];
$courseID = $_POST["addCourseInstance"];
$query = $con->prepare("SELECT COUNT(id) FROM courseinstances WHERE courseCode=:courseID AND userID=:userID");
$query->bindParam(":courseID",$courseID);
$query->bindParam(":userID",$userID);
$query->execute();
$sqldata = $query->fetch(PDO::FETCH_ASSOC);
$queryx = $con->prepare("SELECT id FROM courseinstances WHERE courseCode=:courseID AND userID=:userID");
$queryx->bindParam(":courseID",$courseID);
$queryx->bindParam(":userID",$userID);
$queryx->execute();
$sqldatax = $queryx->fetch(PDO::FETCH_ASSOC);
echo $courseID;
echo $sqldata["COUNT(id)"];
if($sqldata["COUNT(id)"]==1)
{
    $instanceID = $sqldatax["id"];
    $query1 = $con->prepare("DELETE FROM courseinstances WHERE courseCode=:courseID AND userID=:userID");
    $query1->bindParam(":courseID",$courseID);
    $query1->bindParam(":userID",$userID);
    $query1->execute();
    $query2 = $con->prepare("SELECT id FROM videos WHERE course=:courseID and uploadedBy=:userID");
    $query2->bindParam(":courseID",$courseID);
    $query2->bindParam(":userID",$userID);
    $query2->execute();
    $sqldata2 = $query2->fetchAll(PDO::FETCH_ASSOC);
    foreach($sqldata2 as $row)
    {
        $id = $row["id"];
        $query4 = $con->prepare("DELETE FROM notes WHERE videoID=:videoID");
        $query4->bindParam(":videoID",$id);
        $query4->execute();
        $query5 = $con->prepare("DELETE FROM likes WHERE video_id=:videoID");
        $query5->bindParam(":videoID",$id);
        $query5->execute();
        $query6 = $con->prepare("DELETE FROM comments WHERE videoID=:videoID");
        $query6->bindParam(":videoID",$id);
        $query6->execute();
        $query7 = $con->prepare("DELETE FROM history WHERE video_id=:videoID");
        $query7->bindParam(":videoID",$id);
        $query7->execute();
    }
    $query8 = $con->prepare("DELETE FROM includedcontent WHERE instanceID=:instanceID");
    $query8->bindParam(":instanceID",$instanceID);
    $query8->execute();
    $query9 = $con->prepare("SELECT * FROM examinations WHERE instanceID=:instanceID");
    $query9->bindParam(":instanceID",$instanceID);
    $query9->execute();
    $sqldata9 = $query9->fetchAll(PDO::FETCH_ASSOC);
    foreach($sqldata9 as $exam)
    {
        $examID = $exam["id"];
        $query10 = $con->prepare("DELETE FROM questionandanswers WHERE assignment=:examID");
        $query10->bindParam(":examID",$examID);
        $query10->execute();
        $query11 = $con->prepare("DELETE FROM examsubmissions WHERE examID=:examID");
        $query11->bindParam(":examID",$examID);
        $query11->execute();
    }
    $query12 = $con->prepare("DELETE FROM examinations WHERE instanceID=:instanceID");
    $query12->bindParam(":instanceID",$instanceID);
    $query12->execute();
    $query13 = $con->prepare("DELETE FROM videos WHERE course=:courseID and uploadedBy=:userID");
    $query13->bindParam(":courseID",$courseID);
    $query13->bindParam(":userID",$userID);
    $query13->execute();
    ?>
    <script>
        $('#uploadWarningAddCourse', window.parent.document).text("Course deletion successful!");
    </script>
<?php
}
else
{?>
    <script>
        $('#uploadWarningAddCourse', window.parent.document).text("This course was never created!");
    </script>
<?php
}
?>