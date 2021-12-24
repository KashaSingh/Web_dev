<?php
session_start();
require_once("config.php");
if(isset($_POST["id"]))
{
    $query = $con->prepare("SELECT * FROM questionandanswers WHERE assignment=:id ORDER BY id");
    $query->bindParam(":id", $_POST["id"]);
    $query->execute();
    $sqldata = $query->fetchAll(PDO::FETCH_ASSOC);
    $data = array();
    $data['question'] = array();
    $data['optionA'] = array();
    $data['optionB'] = array();
    $data['optionC'] = array();
    $data['optionD'] = array();
    foreach($sqldata as $row)
    {
        array_push($data['question'], $row["question"]);
        array_push($data['optionA'], $row["optionA"]);
        array_push($data['optionB'], $row["optionB"]);
        array_push($data['optionC'], $row["optionC"]);
        array_push($data['optionD'], $row["optionD"]);
    }
    echo json_encode($data);
}
?>