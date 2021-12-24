<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
class NewsUploadData
{
    public $title, $no;
    public function __construct($title, $no)
    {
        $this->title = $title;
        $this->no = $no;
    }
}
class NewsProcessor
{
    private $con;
    private $sizeLimit = 5000000;
    private $allowedTypes = array("pdf","php","html");
    public function __construct($con)
    {
        $this->con = $con;
    }
    public function upload($newsUploadData)
    {
        $targetDirectory = "../uploads/news/";
        $filePath = $targetDirectory.uniqid().basename($_FILES["file"]["name"]);
        $filePath = str_replace(" ","", $filePath);
        $isValidData = $this->processData($filePath);
        if(!$isValidData)
        {
            return false;
        }
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $filePath))
        {
            if(!$this->insertVideoData($newsUploadData, substr($filePath,3)))
            {
                ?>
                <script>
                    $('#uploadWarningNews', window.parent.document).text("Insert query failed");
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
                $('#uploadWarningNews', window.parent.document).text("File too large. Maximum Limit: 15MB");
            </script>
        <?php
            return false;
        }
        else if(!$this->isValidType($fileType))
        {?>
            <script>
                $('#uploadWarningNews', window.parent.document).text("Not a valid file type");
            </script>
        <?php
            return false;
        }
        else if($this->hasError())
        {?>
            <script>
                $('#uploadWarningNews', window.parent.document).text("Some error has occured while uploading this file!");
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
        $query = $this->con->prepare("SELECT COUNT(id) FROM news WHERE sno=:sno");
        $query->bindParam(":sno", $uploadData->no);
        $query->execute();
        $sqldata = $query->fetch(PDO::FETCH_ASSOC);
        $queryx = $this->con->prepare("SELECT filePath FROM news WHERE sno=:sno");
        $queryx->bindParam(":sno", $uploadData->no);
        $queryx->execute();
        $sqldatax = $queryx->fetch(PDO::FETCH_ASSOC);
        if($sqldata["COUNT(id)"]==1)
        {
            if(!unlink("../".$sqldatax["filePath"]))
            {
                return false;
            }
            $query1 = $this->con->prepare("UPDATE news SET filePath =:filePath, title=:title WHERE sno=:sno");
            $query1->bindParam(":filePath", $filePath);
            $query1->bindParam(":title", $uploadData->title);
            $query1->bindParam(":sno", $uploadData->no);
            return $query1->execute();
        }
        else
        {
            $query2 = $this->con->prepare("INSERT INTO news (title, sno, filePath) VALUES(:title, :sno, :filePath)");
            $query2->bindParam(":filePath", $filePath);
            $query2->bindParam(":title", $uploadData->title);
            $query2->bindParam(":sno", $uploadData->no);
            return $query2->execute();
        }
    }
}
if(!isset($_POST["uploadButton"]))
{
    exit();
}
$newsUploadData = new NewsUploadData($_POST["newsTitle"], $_POST["newsNo"]);
$newsProcessor = new NewsProcessor($con);
$success = $newsProcessor->upload($newsUploadData);
if($success)
{?>
    <script>
        $('#uploadWarningNews', window.parent.document).text("Upload Successful!");
    </script>
<?php
}?>