<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
class FacultyProcessor
{
    private $con;
    private $mail;
    private $sizeLimit = 5000000;
    private $allowedTypes = array("pdf");
    public function __construct($con, $mail)
    {
        $this->con = $con;
        $this->mail = $mail;
    }
    public function upload()
    {
        $targetDirectory = "../uploads/cv/";
        $filePath = $targetDirectory.uniqid().basename($_FILES["file"]["name"]);
        $filePath = str_replace(" ","", $filePath);
        $isValidData = $this->processData($filePath);
        if(!$isValidData)
        {
            return false;
        }
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $filePath))
        {
            if(!$this->insertVideoData($this->mail, substr($filePath,3)))
            {?>
                <script>
                    $('#uploadWarningCV', window.parent.document).text("Insert query failed");
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
                $('#uploadWarningCV', window.parent.document).text("File too large. Maximum Limit: 5MB");
            </script>
        <?php
            return false;
        }
        else if(!$this->isValidType($fileType))
        {?>
            <script>
                $('#uploadWarningCV', window.parent.document).text("Not a valid file type");
            </script>
        <?php
            return false;
        }
        else if($this->hasError())
        {?>
            <script>
                $('#uploadWarningCV', window.parent.document).text("Some error has occured while uploading this file!");
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
    private function insertVideoData($mail, $filePath)
    {
        $query1 = $this->con->prepare("SELECT cvFilePath FROM users WHERE email=:email");
        $query1->bindParam(":email", $mail);
        $query1->execute();
        $sqldata = $query1->fetch(PDO::FETCH_ASSOC);
        $query1x = $this->con->prepare("SELECT COUNT(id) FROM users WHERE email=:email");
        $query1x->bindParam(":email", $mail);
        $query1x->execute();
        $sqldatax = $query1x->fetch(PDO::FETCH_ASSOC);
        if($sqldatax["COUNT(id)"]==1&&$sqldata["cvFilePath"]!="")
        {
            if(!unlink("../".$sqldata["cvFilePath"]))
            {
                return false;
            }
        }
        $query2 = $this->con->prepare("UPDATE users SET cvFilePath =:filePath WHERE email=:email");
        $query2->bindParam(":filePath", $filePath);
        $query2->bindParam(":email", $mail);
        return $query2->execute();
    }
}
if(!isset($_POST["uploadButton"]))
{
    exit();
}
$facultyProcessor = new FacultyProcessor($con, $_POST["facultyMail"]);
$success = $facultyProcessor->upload();
if($success)
{?>
    <script>
        $('#uploadWarningCV', window.parent.document).text("Upload Successful!");
    </script>
<?php
}?>