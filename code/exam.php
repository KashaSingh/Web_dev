<?php
session_start();
require_once("utilities/config.php");
if(!isset($_SESSION["userID"]))
{
    exit();
}
else if($_SESSION["userRole"]=="Faculty")
{
    exit();
}
if(!isset($_GET["examID"]))
{
    exit();
}
$examID = $_GET["examID"];
$page = new Page($con, $examID);
echo $page->renderPage();
class Page
{
    private $con, $examID;
    public function __construct($con, $examID)
    {
        $this->con = $con;
        $this->examID = $examID;
    }
    public function renderPage()
    {
        $query1 = $this->con->prepare("SELECT * FROM calendars WHERE calendarType='current'");
        $query1->execute();
        $sqldata1 = $query1->fetch(PDO::FETCH_ASSOC);
        $calendarTitle = $sqldata1["title"];
        $calendar = $sqldata1["filePath"];
        $query2 = $this->con->prepare("SELECT * FROM schedules ORDER BY year");
        $query2->execute();
        $sqldata2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $schedule1 = $schedule2 = $schedule3 = $schedule4 = "";
        foreach($sqldata2 as $row)
        {
            if($row["year"]==1)
            {
                $schedule1 = $row["filePath"];
            }
            else if($row["year"]==2)
            {
                $schedule2 = $row["filePath"];
            }
            else if($row["year"]==3)
            {
                $schedule3 = $row["filePath"];
            }
            else
            {
                $schedule4 = $row["filePath"];
            }
        }
        $schedules = $schedule1."?".$schedule2."?".$schedule3."?".$schedule4;
        $query3 = $this->con->prepare("SELECT * FROM news ORDER BY sno");
        $query3->execute();
        $sqldata3 = $query3->fetchAll(PDO::FETCH_ASSOC);
        $title1 = $title2 = $title3 = $title4 = $title5 = "";
        $link1 = $link2 = $link3 = $link4 = $link5 = "";
        foreach($sqldata3 as $row)
        {
            if($row["sno"]==1)
            {
                $title1 = $row["title"];
                $link1 = $row["filePath"];
            }
            else if($row["sno"]==2)
            {
                $title2 = $row["title"];
                $link2 = $row["filePath"];
            }
            else if($row["sno"]==3)
            {
                $title3 = $row["title"];
                $link3 = $row["filePath"];
            }
            else if($row["sno"]==4)
            {
                $title4 = $row["title"];
                $link4 = $row["filePath"];
            }
            else
            {
                $title5 = $row["title"];
                $link5 = $row["filePath"];
            }
        }
        $query4 = $this->con->prepare("SELECT * FROM examinations WHERE id=:examID");
        $query4->bindParam(":examID", $this->examID);
        $query4->execute();
        $sqldata4 = $query4->fetch(PDO::FETCH_ASSOC);
        $ExamName = $sqldata4["name"];
        $query5 = $this->con->prepare("SELECT COUNT(id) FROM examsubmissions WHERE userID=:userID AND examID=:examID");
        $query5->bindParam(":examID", $this->examID);
        $query5->bindParam(":userID", $_SESSION["userID"]);
        $query5->execute();
        $sqldata5 = $query5->fetch(PDO::FETCH_ASSOC);
        $currentDate = date("Y-m-d h:i:s");
        if($sqldata5["COUNT(id)"]!=0)
        {
            $content = "<div class='quiz-container' id='quiz'>
                            <h2>You have attempted the exam. Your score will be shown in the Examinations Page!</h2>
                            <button class='AlreadyAnswered' onclick=\"location = 'https://acadgenix.ga/assignment.php'\">Go to Examinations Page</button>
                        </div>";
        }
        else if($sqldata4["endTime"]<=$currentDate)
        {
            $content = "<div class='quiz-container' id='quiz'>
                            <h2>You have missed the exam! Try to be punctual!</h2>
                            <button class='AlreadyAnswered' onclick=\"location = 'https://acadgenix.ga/assignment.php'\">Go to Examinations Page</button>
                        </div>";
        }
        else
        {
            $endTime = $sqldata4["endTime"];
            $content="<div class='quiz-container' id='quiz'>
                        <div class='quiz-header'>
                            <h2 id='question'>Question Text</h2>
                            <ul>
                                <li>
                                    <input type='radio' id='A' name='answer' class='answer'>
                                    <label id='a_text' for='A'></label>
                                </li>
                                <li>
                                    <input type='radio' id='B' name='answer' class='answer'>
                                    <label id='b_text' for='B'></label>
                                </li>
                                <li>
                                    <input type='radio' id='C' name='answer' class='answer'>
                                    <label id='c_text' for='C'></label>
                                </li>
                                <li>
                                    <input type='radio' id='D' name='answer' class='answer'>
                                    <label id='d_text' for='D'></label>
                                </li>
                            </ul>
                        </div>
                        <button id='submit'>Submit</button>
                    </div>
                    <p id='examEndTime' style='display:none;'>$endTime</p>";
        }
        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <title>$ExamName</title>
            <meta charset='UTF-8'>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css' rel='stylesheet'
            integrity='sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1' crossorigin='anonymous'>
            <link type='text/css' href='acadgenix.css' rel='stylesheet'>
            <link type = 'image/icon type' href='assets/images/PlainLogo.png' rel='icon'>
            <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js'
            integrity='sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW' crossorigin='anonymous'></script>
            <script type='text/javascript' src='acadgenix.js'></script>
            <script type='text/javascript' src='exam.js'></script>
        </head>
        <body>
            <div id='pageContainer'>
                <div id='head'>
                    <a class='logo' href='https://acadgenix.ga/'>
                        <img src='assets/images/PlainLogoFull.png' title='AcadGenix' alt='AcadGenix Logo'/>
                    </a>
                    <div class='searchBarContainer'>
                        <form action='search.php' method='GET' accept-data='utf-8' enctype='utf-8'>
                            <input type='text' id='searchBar' name='term' placeholder='Search AcadGenix' required>
                            <button id='searchButton'>
                                <img src='https://img.icons8.com/pastel-glyph/64/000000/search--v2.png' title='Search' alt='Search Icon'/>
                            </button>
                        </form>
                    </div>
                    <div class='userInfo1'>
                        <p id='user'>Username:</p>
                        <p id='mail'>G-mail:</p>
                    </div>
                    <div class='userInfo2'>
                        <p id='userName'></p>
                        <p id='userMail'></p>
                    </div>
                    <div class='userPicture'>
                        <a href='loginPage/loginPage.php'>
                            <img id='profilePicture' src='' title='' alt='User DP'/>
                        </a>
                    </div>
                </div>
                <div id='navIcon'>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div id='sideNav' style='display:none;'>
                    <div class='nav-bar'>
                        <ul>
                            <li id='home-option'>
                                <div>
                                    <img src='https://img.icons8.com/metro/52/000000/home.png' title='Home' alt='Home icon'/>
                                    <span>Home</span>
                                </div>
                            </li>
                            <li id='create-content-option'>
                                <div>
                                    <img src='https://img.icons8.com/ios-filled/50/000000/training.png' title='Create Content' alt='Couse Create Icon'/>
                                    <span>Create Content</span>
                                </div>
                            </li>
                            <li id='learn-option'>
                                <div>
                                    <img src='https://img.icons8.com/ios-filled/50/000000/book-reading.png' title='Learn' alt='Learn Icon'/>
                                    <span>Learn</span>
                                </div>
                            </li>
                            <li class='active' id='exam-writing-option'>
                                <div>
                                    <img src='https://img.icons8.com/metro/52/000000/exam.png' title='Examinations' alt='Exam Icon'/>
                                    <span>Examinations</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <svg xmlns='http://www.w3.org/2000/svg' version='1.1'>
                        <defs>
                            <filter id='old-goo'>
                                <feGaussianBlur in='SourceGraphic' stdDeviation='10' result='blur' />
                                <feColorMatrix in='blur' mode='matrix' values='1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7' result='goo' />
                                <feBlend in='SourceGraphic' in2='goo' />
                            </filter>
                            <filter id='fancy-goo'>
                                <feGaussianBlur in='SourceGraphic' stdDeviation='10' result='blur' />
                                <feColorMatrix in='blur' mode='matrix' values='1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9' result='goo' />
                                <feComposite in='SourceGraphic' in2='goo' operator='atop'/>
                            </filter>
                        </defs>
                    </svg>
                    <div class='latest-news'>
                        <div id='header'>
                            <img src='https://img.icons8.com/fluent/96/000000/news.png'/>
                            <div id='News'>
                                News and Announcements
                            </div>
                        </div>
                        <div id='partition'>
                        </div>
                        <div id='actualNews'>
                            <a class='text-decoration-none' href='$link1' target='_blank'>$title1</a>
                        </div>
                        <div id='partitionBetweenNews'>
                        </div>
                        <div id='actualNews'>
                            <a class='text-decoration-none' href='$link2' target='_blank'>$title2</a>
                        </div>
                        <div id='partitionBetweenNews'>
                        </div>
                        <div id='actualNews'>
                            <a class='text-decoration-none' href='$link3' target='_blank'>$title3</a>
                        </div>
                        <div id='partitionBetweenNews'>
                        </div>
                        <div id='actualNews'>
                            <a class='text-decoration-none' href='$link4' target='_blank'>$title4</a>
                        </div>
                        <div id='partitionBetweenNews'>
                        </div>
                        <div id='actualNews'>
                            <a class='text-decoration-none' href='$link5' target='_blank'>$title5</a>
                        </div>
                    </div>
                    <div class='academic-calendar'>
                        <img src='https://img.icons8.com/fluent/96/000000/calendar-1.png'/>
                        <div id='calendar'>
                        <a href='$calendar' target='_blank'>$calendarTitle</a>
                        </div>
                    </div>
                    <div class='time-table'>
                        <img src='https://img.icons8.com/fluent/96/000000/overtime.png'/>
                        <div id='schedule'>
                        <a href='$schedules' target='_blank'>Time Tables</a>
                        </div>
                    </div>
                </div>
                <div id='sideNavDisabling' data-backdrop='static' data-keyboard='false'>
                </div>
                <div id='main'>
                    <div id='content'>
                        <div id='exam'>
                            $content
                        </div>
                    </div>
                </div>
            </div>
            <div class='nav-bar'>
                <ul>
                    <li id='home-option2'>
                        <div>
                            <img src='https://img.icons8.com/metro/52/000000/home.png' title='Home' alt='Home icon'/>
                            <span>Home</span>
                        </div>
                    </li>
                    <li id='create-content-option2'>
                        <div>
                            <img src='https://img.icons8.com/ios-filled/50/000000/training.png' title='Create Content' alt='Couse Create Icon'/>
                            <span>Create Content</span>
                        </div>
                    </li>
                    <li id='learn-option2'>
                        <div>
                            <img src='https://img.icons8.com/ios-filled/50/000000/book-reading.png' title='Learn' alt='Learn Icon'/>
                            <span>Learn</span>
                        </div>
                    </li>
                    <li class='active' id='exam-writing-option2'>
                        <div>
                            <img src='https://img.icons8.com/metro/52/000000/exam.png' title='Examinations' alt='Exam Icon'/>
                            <span>Examinations</span>
                        </div>
                    </li>
                </ul>
            </div>
        </body>
        </html>";
    }
}
?>