<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?php
require_once("config.php");
class CourseUploadData
{
    public $name, $code, $type;
    public function __construct($name, $code, $type)
    {
        $this->name = $name;
        $this->code = $code;
        $this->type = $type;
    }
}
class CourseProcessor
{
    private $con;
    private $sizeLimit = 5000000;
    private $allowedTypes = array("pdf");
    public function __construct($con)
    {
        $this->con = $con;
    }
    public function upload($courseUploadData)
    {
        $targetDirectory = "../uploads/syllabus/";
        $filePath = $targetDirectory.uniqid().basename($_FILES["file"]["name"]);
        $filePath = str_replace(" ","", $filePath);
        $isValidData = $this->processData($filePath);
        if(!$isValidData)
        {
            return false;
        }
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $filePath))
        {
            if(!$this->insertVideoData($courseUploadData, substr($filePath,3)))
            {
                ?>
                <script>
                    $('#uploadWarningCourse', window.parent.document).text("Insert query failed/Course already Exists");
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
                $('#uploadWarningCourse', window.parent.document).text("File too large. Maximum Limit: 5MB");
            </script>
        <?php
            return false;
        }
        else if(!$this->isValidType($fileType))
        {?>
            <script>
                $('#uploadWarningCourse', window.parent.document).text("Not a valid file type");
            </script>
        <?php
            return false;
        }
        else if($this->hasError())
        {?>
            <script>
                $('#uploadWarningCourse', window.parent.document).text("Some error has occured while uploading this file!");
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
        $query = $this->con->prepare("SELECT COUNT(id) FROM institutecourses WHERE code=:code");
        $query->bindParam(":code", $uploadData->code);
        $query->execute();
        $sqldata = $query->fetch(PDO::FETCH_ASSOC);
        if($sqldata["COUNT(id)"]==1)
        {
            return false;
        }
        else
        {
            $query1 = $this->con->prepare("INSERT INTO institutecourses (name, code, type, filePath) 
                                        VALUES(:name, :code, :type, :filePath)");
            $query1->bindParam(":name", $uploadData->name);
            $query1->bindParam(":code", $uploadData->code);
            $query1->bindParam(":type", $uploadData->type);
            $query1->bindParam(":filePath", $filePath);
            return $query1->execute();
        }
    }
}
class CourseModifier
{
    private $con;
    private $sizeLimit = 5000000;
    private $allowedTypes = array("pdf");
    public function __construct($con)
    {
        $this->con = $con;
    }
    public function modify($courseCode)
    {
        $targetDirectory = "../uploads/syllabus/";
        $filePath = $targetDirectory.uniqid().basename($_FILES["file"]["name"]);
        $filePath = str_replace(" ","", $filePath);
        $isValidData = $this->processData($filePath);
        if(!$isValidData)
        {
            return false;
        }
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $filePath))
        {
            if(!$this->insertVideoData($courseCode,substr($filePath,3)))
            {
                ?>
                <script>
                    $('#uploadWarningCourse', window.parent.document).text("Insert query failed/Course doesn't exist");
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
                $('#uploadWarningCourse', window.parent.document).text("File too large. Maximum Limit: 5MB");
            </script>
        <?php
            return false;
        }
        else if(!$this->isValidType($fileType))
        {?>
            <script>
                $('#uploadWarningCourse', window.parent.document).text("Not a valid file type");
            </script>
        <?php
            return false;
        }
        else if($this->hasError())
        {?>
            <script>
                $('#uploadWarningCourse', window.parent.document).text("Some error has occured while uploading this file!");
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
    private function insertVideoData($courseCode, $filePath)
    {
        $query = $this->con->prepare("SELECT COUNT(id) FROM institutecourses WHERE code=:code");
        $query->bindParam(":code", $courseCode);
        $query->execute();
        $sqldata = $query->fetch(PDO::FETCH_ASSOC);
        $queryx = $this->con->prepare("SELECT filePath FROM institutecourses WHERE code=:code");
        $queryx->bindParam(":code", $courseCode);
        $queryx->execute();
        $sqldatax = $queryx->fetch(PDO::FETCH_ASSOC);
        if($sqldata["COUNT(id)"]==0)
        {
            return false;
        }
        else
        {
            if(!unlink("../".$sqldatax["filePath"]))
            {
                return false;
            }
            $query1 = $this->con->prepare("UPDATE institutecourses SET filePath=:filePath WHERE code=:code");
            $query1->bindParam(":code", $courseCode);
            $query1->bindParam(":filePath", $filePath);
            return $query1->execute();
        }
    }
}
class CourseRemover
{
    public function __construct($con)
    {
        $this->con = $con;
    }
    public function remove($courseCode)
    {
        $query = $this->con->prepare("SELECT COUNT(id) FROM institutecourses WHERE code=:code");
        $query->bindParam(":code", $courseCode);
        $query->execute();
        $sqldata = $query->fetch(PDO::FETCH_ASSOC);
        $queryx = $this->con->prepare("SELECT filePath FROM institutecourses WHERE code=:code");
        $queryx->bindParam(":code", $courseCode);
        $queryx->execute();
        $sqldatax = $queryx->fetch(PDO::FETCH_ASSOC);
        if($sqldata["COUNT(id)"]==0)
        {?>
            <script>
                $('#uploadWarningCourse', window.parent.document).text("Insert query failed/ no such course exists!");
            </script>
        <?php
            return false;
        }
        else
        {
            if(!unlink("../".$sqldatax["filePath"]))
            {
                return false;
            }
            $query1 = $this->con->prepare("DELETE FROM institutecourses WHERE code=:code");
            $query1->bindParam(":code", $courseCode);
            return $query1->execute();
        }
    }
}
if(!isset($_POST["uploadButton"]))
{
    exit();
}
$courseUploadData = new CourseUploadData($_POST["courseName"], $_POST["courseCode"],$_POST["courseType"]);
if($_POST["courseOperation"]=='Add')
{
    $courseProcessor = new CourseProcessor($con);
    $success = $courseProcessor->upload($courseUploadData);
}
else if($_POST["courseOperation"]=='Modify')
{
    $courseModifier = new CourseModifier($con);
    $success = $courseModifier->modify($_POST["courseCode"]);
}
else if($_POST["courseOperation"]=='Remove')
{
    $courseRemover = new CourseRemover($con);
    $success = $courseRemover->remove($_POST["courseCode"]);
}
if($success)
{?>
    <script>
        $('#uploadWarningCourse', window.parent.document).text("Upload Successful!");
    </script>
<?php
}?>