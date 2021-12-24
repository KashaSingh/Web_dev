<?php
session_start();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
if(!isset($_POST["uploadButton"]))
{
    exit();
}
$videoUploadData = new VideoUploadData($_POST["VideoTitle"],$_POST["VideoDescription"],$_SESSION["userID"], $_POST["addToCourse"]);
$videoProcessor = new VideoProcessor($con);
$success = $videoProcessor->upload($videoUploadData);
if($success)
{?>
    <script>
        $('#uploadWarning', window.parent.document).text("Upload Successful!");
    </script>
<?php
}
class VideoUploadData
{
    public $title, $description, $uploadedBy, $courseID;
    public function __construct($title, $description, $uploadedBy, $courseID)
    {
        $this->title=$title;
        $this->description=$description;
        $this->uploadedBy=$uploadedBy;
        $this->courseID = $courseID;
    }
}
class VideoProcessor
{
    private $con;
    private $sizeLimit = 1400000000;
    private $allowedTypes = array("mp4");
    public function __construct($con)
    {
        $this->con = $con;
    }
    public function upload($videoUploadData)
    {
        $targetDirectory = "../uploads/videos/";
        $tempFilePath = $targetDirectory.uniqid().basename($_FILES["file"]["name"]);
        $isValidData = $this->processData($tempFilePath);
        if(!$isValidData)
        {
            return false;
        }
        $finalFilePath = $targetDirectory.uniqid().".mp4";
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $finalFilePath))
        {
            if(!$this->insertVideoData($videoUploadData, substr($finalFilePath,3)))
            {?>
                <script>
                    $('#uploadWarning', window.parent.document).text("Insert query failed");
                </script>
            <?php
                return false;
            }
        }
        return true;
    }
    private function processData($filePath)
    {
        $videoType = pathInfo($filePath, PATHINFO_EXTENSION);
        if(!$this->isValidSize())
        {?>
            <script>
                $('#uploadWarning', window.parent.document).text("File too large. Maximum Limit: 1GB");
            </script>
        <?php
            return false;
        }
        else if(!$this->isValidType($videoType))
        {?>
            <script>
                $('#uploadWarning', window.parent.document).text("Only MP4 files are allowed to Upload!");
            </script>
        <?php
            return false;
        }
        else if($this->hasError())
        {?>
            <script>
                $('#uploadWarning', window.parent.document).text("Some error has occured while uploading this video file!");
            </script>
        <?php
            return false;
        }
        return true;
    }
    private function isValidSize()
    {
        return $_FILES["file"]["size"]<=$this->sizeLimit;
    }
    private function isValidType($type)
    {
        $lowercased = strtolower($type);
        return in_array($lowercased, $this->allowedTypes);
    }
    private function hasError()
    {
        return $_FILES["file"]["error"]!=0;
    }
    private function insertVideoData($uploadData, $filePath)
    {
        $query = $this->con->prepare("INSERT INTO videos (title, uploadedBy, description, course, filePath)
                                        VALUES(:title, :uploadedBy, :description, :course, :filePath)");
        $query->bindParam(":title", $uploadData->title);
        $query->bindParam(":uploadedBy", $uploadData->uploadedBy);
        $query->bindParam(":description", $uploadData->description);
        $query->bindParam(":course", $uploadData->courseID);
        $query->bindParam(":filePath", $filePath);
        return $query->execute();
    }
}
?>
