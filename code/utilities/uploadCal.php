<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
class CalendarUploadData
{
    public $calendarTitle, $calendarUploadOption;
    public function __construct($title, $uploadOption)
    {
        $this->calendarTitle = $title;
        $this->calendarUploadOption = $uploadOption;
    }
}
class CalendarProcessor
{
    private $con;
    private $sizeLimit = 5000000;
    private $allowedTypes = array("pdf");
    public function __construct($con)
    {
        $this->con = $con;
    }
    public function upload($calendarUploadData)
    {
        $targetDirectory = "../uploads/calendars/";
        $filePath = $targetDirectory.uniqid().basename($_FILES["file"]["name"]);
        $filePath = str_replace(" ","", $filePath);
        $isValidData = $this->processData($filePath);
        if(!$isValidData)
        {
            return false;
        }
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $filePath))
        {
            if(!$this->insertVideoData($calendarUploadData, substr($filePath,3)))
            {
                ?>
                <script>
                    $('#uploadWarningCal', window.parent.document).text("Insert query failed");
                </script>
            <?php
                return false;
            }
        }
        return true;
    }
    private function processData($filePath)
    {
        $fileType = pathInfo($filePath, PATHINFO_EXTENSION);
        if(!$this->isValidSize())
        {?>
            <script>
                $('#uploadWarningCal', window.parent.document).text("File too large. Maximum Limit: 10MB");
            </script>
        <?php
            return false;
        }
        else if(!$this->isValidType($fileType))
        {?>
            <script>
                $('#uploadWarningCal', window.parent.document).text("Not a valid file type");
            </script>
        <?php
            return false;
        }
        else if($this->hasError())
        {?>
            <script>
                $('#uploadWarningCal', window.parent.document).text("Some error has occured while uploading this file!");
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
        if($uploadData->calendarUploadOption=="new")
        {
            $query1 = $this->con->prepare("UPDATE calendars SET calendarType='archive' WHERE calendarType='current'");
            $query1->execute();
            $query2 = $this->con->prepare("INSERT INTO calendars (title, calendarType, filePath)
                                        VALUES(:title, 'current', :filePath)");
            $query2->bindParam(":title", $uploadData->calendarTitle);
            $query2->bindParam(":filePath", $filePath);
            return $query2->execute();
        }
        else if($uploadData->calendarUploadOption=="change")
        {
            $query1 = $this->con->prepare("SELECT * FROM calendars WHERE calendarType='current'");
            $query1->execute();
            $sqldata1 = $query1->fetch(PDO::FETCH_ASSOC);
            $query2 = $this->con->prepare("DELETE FROM calendars WHERE calendarType='current'");
            $query2->execute();
            if(!unlink("../".$sqldata1["filePath"]))
            {
                return false;
            }
            $query3 = $this->con->prepare("INSERT INTO calendars (title, calendarType, filePath)
                                        VALUES(:title, 'current', :filePath)");
            $query3->bindParam(":title", $uploadData->calendarTitle);
            $query3->bindParam(":filePath", $filePath);
            return $query3->execute();
        }
    }
}
if(!isset($_POST["uploadButton"]))
{
    exit();
}
$calendarUploadData = new CalendarUploadData($_POST["CalendarTitle"], $_POST["uploadOption"]);
$calendarProcessor = new CalendarProcessor($con);
$success = $calendarProcessor->upload($calendarUploadData);
if($success)
{?>
    <script>
        $('#uploadWarningCal', window.parent.document).text("Upload Successful!");
    </script>
<?php
}?>