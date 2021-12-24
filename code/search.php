<?php
session_start();
require_once("utilities/config.php");
if(!isset($_GET["term"]))
{
    exit();
}
$page = new Page($con);
echo $page->renderPage();
class Page
{
    private $con;
    public function __construct($con)
    {
        $this->con = $con;
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
        $term = $_GET["term"];
        $searchWords = explode(" ", $term);
        $result="";
        $coursesSearched = array();
        $videosSearched = array();
        $query4 = $this->con->prepare("SELECT * FROM courseinstances WHERE courseCode LIKE '%$term%' OR EXISTS(SELECT * FROM videos WHERE course=courseinstances.courseCode  AND (title LIKE '%$term%' OR description LIKE '%$term%')) OR EXISTS(SELECT * FROM institutecourses WHERE code=courseinstances.courseCode AND name LIKE '%$term%') OR EXISTS(SELECT * FROM additionalcourses WHERE code=courseinstances.courseCode AND name LIKE '%$term%') ORDER BY learners");
        $query4->execute();
        $sqldata4 = $query4->fetchAll(PDO::FETCH_ASSOC);
        foreach($sqldata4 as $row4)
        {
            array_push($coursesSearched, $row4["id"]);
        }
        $query5 = $this->con->prepare("SELECT * FROM videos WHERE title LIKE '%$term%' OR description LIKE '%$term%' ORDER BY views DESC");
        $query5->execute();
        $sqldata5 = $query5->fetchAll(PDO::FETCH_ASSOC);
        foreach($sqldata5 as $row5)
        {
            array_push($videosSearched, $row5["id"]);
        }
        foreach($searchWords as $search)
        {
            $query4 = $this->con->prepare("SELECT * FROM courseinstances WHERE courseCode LIKE '%$search%' OR EXISTS(SELECT * FROM videos WHERE course=courseinstances.courseCode  AND (title LIKE '%$search%' OR description LIKE '%$search%')) OR EXISTS(SELECT * FROM institutecourses WHERE code=courseinstances.courseCode AND name LIKE '%$search%') OR EXISTS(SELECT * FROM additionalcourses WHERE code=courseinstances.courseCode AND name LIKE '%$search%') ORDER BY learners");
            $query4->execute();
            $sqldata4 = $query4->fetchAll(PDO::FETCH_ASSOC);
            foreach($sqldata4 as $row4)
            {
                array_push($coursesSearched, $row4["id"]);
            }
            $query5 = $this->con->prepare("SELECT * FROM videos WHERE title LIKE '%$search%' OR description LIKE '%$search%' ORDER BY views DESC");
            $query5->execute();
            $sqldata5 = $query5->fetchAll(PDO::FETCH_ASSOC);
            foreach($sqldata5 as $row5)
            {
                array_push($videosSearched, $row5["id"]);
            }
        }
        $coursesSearched = array_unique($coursesSearched);
        $videosSearched = array_unique($videosSearched);
        $searchResults="";
        foreach($coursesSearched as $id)
        {
            $query5 = $this->con->prepare("SELECT * FROM courseinstances WHERE id=:id");
            $query5->bindParam(":id", $id);
            $query5->execute();
            $sqldata5 = $query5->fetch(PDO::FETCH_ASSOC);
            $courseCode = $sqldata5["courseCode"];
            $learners = $sqldata5["learners"];
            $learnerPhrase = ($learners!=1)?"Learners":"Learner";
            $query6 = $this->con->prepare("SELECT * FROM users WHERE id=:id");
            $query6->bindParam(":id", $sqldata5["userID"]);
            $query6->execute();
            $sqldata6 = $query6->fetch(PDO::FETCH_ASSOC);
            $creator = $sqldata6["name"];
            $query7 = $this->con->prepare("SELECT COUNT(id) FROM institutecourses WHERE code=:code");
            $query7->bindParam(":code", $sqldata5["courseCode"]);
            $query7->execute();
            $sqldata7 = $query7->fetch(PDO::FETCH_ASSOC);
            $query7x = $this->con->prepare("SELECT name FROM institutecourses WHERE code=:code");
            $query7x->bindParam(":code", $sqldata5["courseCode"]);
            $query7x->execute();
            $sqldata7x = $query7x->fetch(PDO::FETCH_ASSOC);
            if($sqldata7["COUNT(id)"]==0)
            {
                $query8 = $this->con->prepare("SELECT name FROM additionalcourses WHERE code=:code");
                $query8->bindParam(":code", $sqldata5["courseCode"]);
                $query8->execute();
                $sqldata8 = $query8->fetch(PDO::FETCH_ASSOC);
                $courseTitle = $sqldata8["name"];
            }
            else
            {
                $courseTitle = $sqldata7x["name"];
            }
            $searchResults.="<div class='faq'>
                                <h4 class='faq-title'>$courseTitle</h3>
                                <p class='faq-text'>By $creator</p>
                                <p class='faq-text' id='faq-code'>$courseCode</lop>
                                <p class='faq-text'>$learners $learnerPhrase</p>
                                <button type='button' style='display:none;' class='btn btn-primary includeContent'>Fetch Course Content</button>
                                <div class='alert alert-primary contentAdded' role='alert' style='display:none;'>
                                    Course Content included in <strong>Learn</strong>
                                </div>
                                <button class='faq-toggle'>
                                    <img class='fas fa-chevron-down' src='https://img.icons8.com/clouds/100/000000/chevron-down.png'/>
                                    <img class='fas fa-times' src='https://img.icons8.com/clouds/100/000000/close-window.png'/>
                                </button>
                                <p id='instanceID' style='display:none;'>$id</p>
                            </div>";
        }
        $searchResults.="</div>
                        <div class='video-app'>
                            <div class='videos'>";
        $result="";
        foreach($videosSearched as $id)
        {
            $query = $this->con->prepare("SELECT * FROM videos WHERE id=:id");
            $query->bindParam(":id", $id);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $title = $row["title"];
            $duration = $row["duration"];
            $views = $this->numeric($row["views"]);
            $viewPhrase = ($row["views"]!=1)?"views":"view";
            $videoPath = $row["filePath"];
            $videoID = $row["id"];
            $result = "<div class='video'>
                            <div class='video-time'>$duration</div>
                            <video muted>
                                <source src='$videoPath' type='video/mp4'>
                            </video>
                            <div class='video-content'>$title</div>
                            <div class='view'>$views $viewPhrase</div>
                            <form method='POST' action='https://acadgenix.ga/watch.php?videoID=$videoID'>
                                <input type='submit' id='videoClick'>
                            </form>
                        </div>";
            $searchResults.=$result;
        }
        $searchResults.="</div>";
        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <title>Search - $term</title>
            <meta charset='UTF-8'>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css' rel='stylesheet'
            integrity='sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1' crossorigin='anonymous'>
            <link type='text/css' href='acadgenix.css' rel='stylesheet'>
            <link type = 'image/icon type' href='assets/images/PlainLogo.png' rel='icon'>
            <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js'
            integrity='sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW' crossorigin='anonymous'></script>
            <script type='text/javascript' src='acadgenix.js'></script>
        </head>
        <body>
            <div id='pageContainer'>
                <div id='head'>
                    <a class='logo' href='https://acadgenix.ga/'>
                        <img src='assets/images/PlainLogoFull.png' title='AcadGenix' alt='AcadGenix Logo'/>
                    </a>
                    <div class='searchBarContainer'>
                        <form action='search.php' method='GET' accept-data='utf-8' enctype='utf-8'>
                            <input type='text' id='searchBar' name='term' value='$term' required>
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
                            <li id='home-option' class='active'>
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
                            <li id='exam-dashboard-option' style='display:none;'>
                                <div>
                                    <img src='https://img.icons8.com/metro/52/000000/exam.png' title='Exam Dashboard' alt='Exam Icon'/>
                                    <span>Exam Dashboard</span>
                                </div>
                            </li>
                            <li id='exam-writing-option'>
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
                        <div id='search-results'>
                            <h2>Search Results...</h2>
                            <div class='faq-container'>
                            $searchResults
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='nav-bar'>
                <ul>
                    <li id='home-option2' class='active'>
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
                    <li id='exam-dashboard-option2' style='display:none;'>
                        <div>
                            <img src='https://img.icons8.com/metro/52/000000/exam.png' title='Exam Dashboard' alt='Exam Icon'/>
                            <span>Exam Dashboard</span>
                        </div>
                    </li>
                    <li id='exam-writing-option2'>
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
    private function numeric($number)
    {
        $k=0;
        $l=0;
        $result="";
        while(intdiv($number,10)!=0)
        {
            if(($l==0)&&($k!=3))
            {
                $result.=$number%10;
                $k++;
            }
            else if(($l!=0)&&($k!=2))
            {
                $result.=$number%10;
                $k++;
            }
            else if(($l!=0)&&($k==2))
            {
                $result.=','.$number%10;
                $k=1;
            }
            else if(($l==0)&&($k==3))
            {
                $result.=','.$number%10;
                $l=1;
                $k=1;
            }
            $number = intdiv($number,10);
        }
        return strrev($result.$number);
    }
}
?>