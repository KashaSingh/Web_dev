<?php
class Video
{
    private $con, $videoID,  $userID, $sqldata1, $sqldata2, $sqldata3, $sqldata4;
    public function __construct($con, $videoID, $userID)
    {
        $this->con = $con;
        $this->videoID = $videoID;
        $this->userID = $userID;
        $query1 = $this->con->prepare("SELECT * FROM videos WHERE id=:videoID");
        $query1->bindParam(":videoID", $this->videoID);
        $query3 = $this->con->prepare("SELECT COUNT(id) FROM likes WHERE video_id=:videoID");
        $query3->bindParam(":videoID", $this->videoID);
        $query4 = $this->con->prepare("SELECT COUNT(id) FROM likes WHERE video_id=:videoID AND userID=:userID");
        $query4->bindParam(":videoID", $this->videoID);
        $query4->bindParam(":userID", $this->userID);
        $query1->execute();
        $this->sqldata1 = $query1->fetch(PDO::FETCH_ASSOC);
        $query3->execute();
        $this->sqldata3 = $query3->fetch(PDO::FETCH_ASSOC);
        $query4->execute();
        $this->sqldata4 = $query4->fetch(PDO::FETCH_ASSOC);
        if($userID!=null)
        {
            $queryWatched = $this->con->prepare("INSERT INTO history (video_id, userID) VALUES (:videoID, :userID)");
            $queryWatched->bindParam(":videoID", $this->videoID);
            $queryWatched->bindParam(":userID", $this->userID);
            $queryWatched->execute();
        }
    }
    public function getTitle()
    {
        return $this->sqldata1["title"];
    }
    public function getDescription()
    {
        return $this->sqldata1["description"];
    }
    public function getViews()
    {
        return $this->sqldata1["views"];
    }
    public function getDuration()
    {
        return $this->sqldata1["duration"];
    }
    public function getUploadDate()
    {
        return $this->sqldata1["uploadDate"];
    }
    public function getFilePath()
    {
        return $this->sqldata1["filePath"];
    }
    public function getAuthor()
    {
        return $this->sqldata1["uploadedBy"];
    }
    public function getLikes()
    {
        return $this->sqldata3["COUNT(id)"];
    }
    public function liked()
    {
        return ($this->sqldata4["COUNT(id)"]==1)?true:false;
    }
    public function incrementViews()
    {
        $queryHistory = $this->con->prepare("SELECT COUNT(id) FROM history WHERE video_id=:videoID AND userID=:userID");
        $queryHistory->bindParam(":videoID",$this->videoID);
        $queryHistory->bindParam(":userID",$this->userID);
        $queryHistory->execute();
        $sqldata = $queryHistory->fetch(PDO::FETCH_ASSOC);
        if($sqldata["COUNT(id)"]==1)
        {
            $query = $this->con->prepare("UPDATE videos SET views=views+1 WHERE id=:videoID");
            $query->bindParam(":videoID",$this->videoID);
            $query->execute();
            $this->sqldata1['views'] = $this->sqldata1['views']+1;
        }
    }
}
?>