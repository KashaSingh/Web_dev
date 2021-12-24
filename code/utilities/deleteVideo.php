<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
if(is_int(strpos($_POST["videoID"], "=")))
{
    $id = (int)explode("=", $_POST["videoID"])[1];
}
else
{
    $id = $_POST["videoID"];
}
$query = $con->prepare("SELECT COUNT(id) FROM videos WHERE id=:videoID");
$query->bindParam(":videoID",$id);
$query->execute();
$sqldata = $query->fetch(PDO::FETCH_ASSOC);
$queryx = $con->prepare("SELECT filePath FROM videos WHERE id=:videoID");
$queryx->bindParam(":videoID",$id);
$queryx->execute();
$sqldatax = $queryx->fetch(PDO::FETCH_ASSOC);
if($sqldata["COUNT(id)"]==1)
{
    $query2 = $con->prepare("DELETE FROM notes WHERE videoID=:videoID");
    $query2->bindParam(":videoID",$id);
    $query2->execute();
    $query3 = $con->prepare("DELETE FROM likes WHERE video_id=:videoID");
    $query3->bindParam(":videoID",$id);
    $query3->execute();
    $query4 = $con->prepare("DELETE FROM comments WHERE videoID=:videoID");
    $query4->bindParam(":videoID",$id);
    $query4->execute();
    $query5 = $con->prepare("DELETE FROM history WHERE video_id=:videoID");
    $query5->bindParam(":videoID",$id);
    $query5->execute();
    unlink("../".$sqldatax["filePath"]);
    $query6 = $con->prepare("DELETE FROM videos WHERE id=:videoID");
    $query6->bindParam(":videoID",$id);
    $query6->execute();
    ?>
    <script>
        $('#deleteVideoWarning', window.parent.document).text("Deletion of Video Successful!");
    </script>
<?php
}
else
{?>
    <script>
        $('#deleteVideoWarning', window.parent.document).text("No such Video with that ID exists!");
    </script>
<?php
}
?>