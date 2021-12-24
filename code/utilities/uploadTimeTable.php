<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
class ScheduleProcessor
{
    private $con, $year;
    private $sizeLimit = 5000000;
    private $allowedTypes = array("pdf");
    public function __construct($con, $year)
    {
        $this->con = $con;
        $this->year = $year;
    }
    public function upload()
    {
        $targetDirectory = "../uploads/schedules/";
        $filePath = $targetDirectory.uniqid().basename($_FILES["file"]["name"]);
        $filePath = str_replace(" ","", $filePath);
        $isValidData = $this->processData($filePath);
        if(!$isValidData)
        {
            return false;
        }
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $filePath))
        {
            if(!$this->insertVideoData($this->year, substr($filePath,3)))
            {
                ?>
                <script>
                    $('#uploadWarningSchedule', window.parent.document).text("Insert query failed");
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
                $('#uploadWarningSchedule', window.parent.document).text("File too large. Maximum Limit: 10MB");
            </script>
        <?php
            return false;
        }
        else if(!$this->isValidType($fileType))
        {?>
            <script>
                $('#uploadWarningSchedule', window.parent.document).text("Not a valid file type");
            </script>
        <?php
            return false;
        }
        else if($this->hasError())
        {?>
            <script>
                $('#uploadWarningSchedule', window.parent.document).text("Some error has occured while uploading this file!");
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
    private function insertVideoData($year, $filePath)
    {
        $query = $this->con->prepare("SELECT COUNT(id) FROM schedules WHERE year=:year");
        $query->bindParam(":year", $year);
        $query->execute();
        $sqldata = $query->fetch(PDO::FETCH_ASSOC);
        $queryx = $this->con->prepare("SELECT filePath FROM schedules WHERE year=:year");
        $queryx->bindParam(":year", $year);
        $queryx->execute();
        $sqldatax = $queryx->fetch(PDO::FETCH_ASSOC);
        if($sqldata["COUNT(id)"]==1)
        {
            $query1 = $this->con->prepare("DELETE FROM schedules WHERE year=:year");
            $query1->bindParam(":year", $year);
            $query1->execute();
            if(!unlink("../".$sqldatax["filePath"]))
            {
                return false;
            }
            $query2 = $this->con->prepare("INSERT INTO schedules (year, filePath)
                                        VALUES(:year, :filePath)");
            $query2->bindParam(":year", $year);
            $query2->bindParam(":filePath", $filePath);
            return $query2->execute();
        }
        else
        {
            $query3 = $this->con->prepare("INSERT INTO schedules (year, filePath) VALUES(:year, :filePath)");
            $query3->bindParam(":year", $year);
            $query3->bindParam(":filePath", $filePath);
            return $query3->execute();
        }
    }
}
if(!isset($_POST["uploadButton"]))
{
    exit();
}
$scheduleProcessor = new ScheduleProcessor($con, $_POST["year"]);
$success = $scheduleProcessor->upload();
if($success)
{?>
    <script>
        $('#uploadWarningSchedule', window.parent.document).text("Upload Successful!");
    </script>
<?php
}?>