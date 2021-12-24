<?php
session_start();
require_once("utilities/video.php");
require_once("utilities/config.php");
if(!isset($_GET['videoID']))
{
    exit();
}
$userID = isset($_SESSION["userID"])?$_SESSION["userID"]:null;
$video = new Video($con, $_GET['videoID'],$userID);
$videoPlayer = new VideoPlayer($video, $con);
$videoInfo = new VideoInfo($video);
$notepad = new Notepad($con);
$commentBox = new CommentPanel($con, $_GET['videoID']);
echo $videoPlayer->create();
echo $videoInfo->create();
echo $notepad->create();
echo $commentBox->create();
class VideoPlayer
{
    private $video, $con;
    public function __construct($video, $con)
    {
        $this->video = $video;
        $this->con = $con;
    }
    public function create()
    {
        $title = $this->video->getTitle();
        $filePath = $this->video->getFilePath();
        $creator = $this->video->getAuthor();
        $query = $this->con->prepare("SELECT * FROM users WHERE id=:userID");
        $query->bindParam(":userID", $creator);
        $query->execute();
        $sqldata = $query->fetch(PDO::FETCH_ASSOC);
        $creatorPic = $sqldata["imageURL"];
        $creatorName = $sqldata["name"];
        $creatorProfile = ($sqldata["cvFilePath"]!=null)?$sqldata["cvFilePath"]:"#";
        $this->video->incrementViews();
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
        if(isset($_SESSION["userID"]))
        {
            if(isset($_SESSION["userRole"])=="Faculty")
            {
                $query4 = $this->con->prepare("SELECT * FROM courseinstances WHERE userID=:userID");
                $query4->bindParam(":userID",$_SESSION["userID"]);
                $query4->execute();
                $sqldata4 = $query4->fetchAll(PDO::FETCH_ASSOC);
                $addedCoursesOptions="";
                foreach($sqldata4 as $row)
                {
                    $courseCode = $row["courseCode"];
                    $query5 = $this->con->prepare("SELECT COUNT(id) FROM institutecourses WHERE code=:code");
                    $query5->bindParam(":code",$row["courseCode"]);
                    $query5->execute();
                    $sqldata5 = $query5->fetch(PDO::FETCH_ASSOC);
                    $query5x = $this->con->prepare("SELECT name FROM institutecourses WHERE code=:code");
                    $query5x->bindParam(":code",$row["courseCode"]);
                    $query5x->execute();
                    $sqldata5x = $query5x->fetch(PDO::FETCH_ASSOC);
                    if($sqldata5["COUNT(id)"]==0)
                    {
                        $query6 = $this->con->prepare("SELECT name FROM additionalcourses WHERE code=:code");
                        $query6->bindParam(":code",$row["courseCode"]);
                        $query6->execute();
                        $sqldata6 = $query6->fetch(PDO::FETCH_ASSOC);
                        $courseTitle = $sqldata6["name"];
                    }
                    else
                    {
                        $courseTitle = $sqldata5x["name"];
                    }
                    $result = "<option value='$courseCode'>$courseTitle</option>";
                    $addedCoursesOptions.=$result;
                }
            }
            else
            {
                $addedCoursesOptions="";
            }
        }
        else
        {
            $addedCoursesOptions="";
        }
        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <title>$title</title>
            <meta charset='UTF-8'>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css' rel='stylesheet'
            integrity='sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1' crossorigin='anonymous'>
            <link type='text/css' href='acadgenix.css' rel='stylesheet'>
            <link rel='stylesheet' href='player/skin/skin.css'>
            <link rel='stylesheet' href='watch.css'>
            <link type = 'image/icon type' href='assets/images/PlainLogo.png' rel='icon'>
            <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js'
            integrity='sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW' crossorigin='anonymous'></script>
            <script type='text/javascript' src='acadgenix.js'></script>
            <script src='player/flowplayer.min.js'></script>
            <script type='text/javascript' src='watch.js'></script>
        </head>
        <body>
            <div id='pageContainer'>
                <div id='head'>
                    <a href='https://acadgenix.ga/' class='logo'>
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
                            <li class='active' id='create-content-option'>
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
                <div id='uploadIcon' title='Upload Video'>
                    <button>
                        <svg xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' width='65' height='65' viewBox='0 0 100 100' style=' fill:#000000;'>
                            <path fill='#c7ede6' d='M88.704,55.677c0.3-0.616,0.566-1.264,0.796-1.943c2.633-7.77-1.349-17.078-9.733-19.325 C78.86,23.026,70.86,15.216,61.826,13.884c-10.341-1.525-19.814,5.044-22.966,15.485c-3.799-1.346-7.501-1.182-10.99,0.857 c-1.583,0.732-3.031,1.812-4.33,3.233c-1.907,2.086-3.147,4.719-3.652,7.495c-0.748,0.118-1.483,0.236-2.176,0.484 c-4.04,1.449-6.589,4.431-7.288,8.923c-0.435,2.797,0.443,5.587,0.933,6.714c1.935,4.455,6.422,6.98,10.981,6.312 c0.227-0.033,0.557,0.069,0.752,0.233c0.241,7.12,3.698,13.417,8.884,17.014c8.321,5.772,19.027,3.994,25.781-3.921 c2.894,2.96,6.338,4.398,10.384,3.876c4.023-0.519,7.147-2.739,9.426-6.349c1.053,0.283,2.051,0.691,3.083,0.804 c4.042,0.442,7.324-1.165,9.732-4.8c0.922-1.391,1.793-3.194,1.793-6.354C92.174,60.634,90.88,57.667,88.704,55.677z'></path>
                            <path fill='#f29373' d='M49.764 73.3L49.764 48.048 43.967 53.846 41.256 51.135 51.668 40.72 61.805 51.128 59.087 53.846 53.3 48.059 53.3 73.3z'></path>
                            <path fill='#472b29' d='M51.711 41.765l9.111 9.357-1.734 1.734-4.097-4.097-2.39-2.39v3.38V72.6h-2.136V49.739v-3.38l-2.39 2.39-4.107 4.107-1.721-1.721 9.333-9.335.048.058L51.711 41.765M51.677 39.723l-.001.001-.001-.001L40.266 51.135l3.701 3.701 5.097-5.097V74H54V49.749l5.087 5.087 3.701-3.701L51.677 39.723 51.677 39.723zM63.5 38h-16c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h16c.276 0 .5.224.5.5S63.776 38 63.5 38zM45.5 38h-1c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h1c.276 0 .5.224.5.5S45.776 38 45.5 38zM42.5 38h-3c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h3c.276 0 .5.224.5.5S42.776 38 42.5 38z'></path>
                            <g>
                                <path fill='#f29373' d='M65.7 40.3L65.7 35.3 37.3 35.3 37.3 40.3 33.7 40.3 33.7 31.7 69.3 31.7 69.3 40.3z'></path>
                                <path fill='#472b29' d='M68.6,32.4V36v3.6h-2.2V36v-1.4H65H38h-1.4V36v3.6h-2.2V36v-3.6H38h27H68.6 M70,31h-5H38h-5v5v5h5 v-5h27v5h5v-5V31L70,31z'></path>
                            </g>
                            <g>
                                <path fill='#fdfcef' d='M76.164,79.43c0,0,10.616,0,10.681,0c2.452,0,4.439-1.987,4.439-4.439 c0-2.139-1.513-3.924-3.527-4.344c0.023-0.187,0.039-0.377,0.039-0.57c0-2.539-2.058-4.598-4.597-4.598 c-1.499,0-2.827,0.721-3.666,1.831c-0.215-2.826-2.739-5.007-5.693-4.646c-2.16,0.264-3.947,1.934-4.344,4.073 c-0.127,0.686-0.114,1.352,0.013,1.977c-0.579-0.624-1.403-1.016-2.322-1.016c-1.68,0-3.052,1.308-3.16,2.961 c-0.763-0.169-1.593-0.158-2.467,0.17c-1.671,0.627-2.861,2.2-2.93,3.983c-0.099,2.533,1.925,4.617,4.435,4.617 c0.191,0,0.861,0,1.015,0h9.218'></path>
                                <path fill='#472b29' d='M86.845,79.93H76.164c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h10.681 c2.172,0,3.939-1.767,3.939-3.939c0-1.854-1.316-3.476-3.129-3.855c-0.254-0.053-0.425-0.292-0.394-0.55 c0.021-0.168,0.035-0.337,0.035-0.51c0-2.259-1.838-4.098-4.098-4.098c-1.292,0-2.483,0.595-3.267,1.632 c-0.126,0.166-0.34,0.237-0.54,0.179c-0.2-0.059-0.342-0.235-0.358-0.442c-0.094-1.238-0.701-2.401-1.664-3.19 c-0.973-0.798-2.207-1.149-3.471-0.997c-1.947,0.238-3.556,1.747-3.913,3.668c-0.112,0.601-0.108,1.201,0.011,1.786 c0.045,0.22-0.062,0.442-0.261,0.545c-0.199,0.102-0.443,0.06-0.595-0.104c-0.513-0.552-1.208-0.856-1.956-0.856 c-1.4,0-2.569,1.095-2.661,2.494c-0.01,0.146-0.082,0.28-0.199,0.367c-0.117,0.087-0.268,0.118-0.408,0.088 c-0.755-0.167-1.468-0.118-2.183,0.15c-1.498,0.562-2.545,1.982-2.606,3.535c-0.042,1.083,0.347,2.109,1.096,2.889 c0.75,0.78,1.758,1.209,2.84,1.209h10.233c0.276,0,0.5,0.224,0.5,0.5s-0.224,0.5-0.5,0.5H63.065 c-1.356,0-2.621-0.539-3.561-1.516c-0.939-0.977-1.428-2.263-1.375-3.621c0.076-1.949,1.384-3.73,3.254-4.432 c0.72-0.27,1.464-0.363,2.221-0.279c0.362-1.655,1.842-2.884,3.582-2.884c0.603,0,1.194,0.151,1.72,0.431 c0.004-0.327,0.036-0.655,0.097-0.983c0.436-2.346,2.399-4.188,4.775-4.478c1.54-0.191,3.039,0.244,4.226,1.216 c0.899,0.737,1.543,1.742,1.847,2.851c0.919-0.807,2.094-1.256,3.347-1.256c2.811,0,5.098,2.287,5.098,5.098 c0,0.064-0.001,0.128-0.004,0.192c2.048,0.628,3.492,2.547,3.492,4.723C91.784,77.714,89.568,79.93,86.845,79.93z'></path>
                                <path fill='#fdfcef' d='M73.591,69.651c-1.642-0.108-3.055,1.026-3.157,2.533c-0.013,0.187-0.004,0.371,0.023,0.55 c-0.317-0.358-0.786-0.6-1.324-0.636c-0.985-0.065-1.836,0.586-1.959,1.471c-0.179-0.049-0.366-0.082-0.56-0.095 c-1.437-0.094-2.674,0.898-2.762,2.216'></path>
                                <path fill='#472b29' d='M63.853,75.941c-0.006,0-0.012,0-0.017,0c-0.138-0.009-0.242-0.128-0.233-0.266 c0.098-1.454,1.453-2.556,3.028-2.449c0.116,0.008,0.234,0.022,0.353,0.045c0.26-0.878,1.158-1.485,2.166-1.421 c0.377,0.025,0.73,0.139,1.035,0.33c0-0.004,0-0.008,0-0.012c0.111-1.641,1.652-2.872,3.423-2.765 c0.138,0.009,0.242,0.128,0.233,0.266c-0.009,0.138-0.131,0.243-0.266,0.233c-1.514-0.102-2.799,0.933-2.891,2.3 c-0.011,0.165-0.004,0.332,0.021,0.496c0.017,0.109-0.041,0.217-0.141,0.264c-0.098,0.047-0.219,0.023-0.293-0.061 c-0.285-0.321-0.705-0.522-1.154-0.552c-0.846-0.056-1.589,0.496-1.695,1.256c-0.01,0.071-0.05,0.134-0.109,0.174 c-0.06,0.04-0.135,0.051-0.203,0.033c-0.173-0.046-0.345-0.076-0.511-0.086c-1.303-0.085-2.417,0.805-2.497,1.983 C64.093,75.84,63.983,75.941,63.853,75.941z'></path>
                                <g>
                                    <path fill='#fdfcef' d='M88.642,71.167c-1.543-0.727-3.327-0.213-3.985,1.15c-0.082,0.169-0.142,0.344-0.182,0.521'></path>
                                    <path fill='#472b29' d='M84.474,73.088c-0.018,0-0.037-0.002-0.056-0.006c-0.135-0.031-0.219-0.165-0.188-0.299 c0.045-0.199,0.113-0.393,0.201-0.574c0.716-1.484,2.651-2.054,4.317-1.268c0.125,0.059,0.179,0.208,0.12,0.333 c-0.059,0.126-0.209,0.177-0.333,0.12c-1.417-0.667-3.056-0.204-3.654,1.033c-0.072,0.148-0.127,0.305-0.164,0.468 C84.691,73.009,84.588,73.088,84.474,73.088z'></path>
                                </g>
                            </g>
                            <g>
                                <path fill='#fdfcef' d='M30.126,63.487c1.71,0,3.194,0,3.215,0c1.916,0,3.469-1.52,3.469-3.396 c0-1.636-1.182-3.001-2.756-3.323c0.018-0.143,0.031-0.288,0.031-0.436c0-1.942-1.609-3.517-3.593-3.517 c-1.172,0-2.209,0.551-2.865,1.401c-0.168-2.162-2.141-3.83-4.45-3.554c-1.688,0.202-3.084,1.479-3.395,3.116 c-0.1,0.525-0.089,1.034,0.01,1.512c-0.453-0.477-1.097-0.777-1.814-0.777c-1.313,0-2.385,1.001-2.47,2.265 c-0.596-0.129-1.245-0.121-1.928,0.13c-1.306,0.48-2.236,1.683-2.29,3.047c-0.077,1.937,1.504,3.532,3.467,3.532 c0.149,0,0.673,0,0.794,0h7.204 M24.245,63.487h0.327'></path>
                                <path fill='#472b29' d='M33.341,63.987h-3.215c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h3.215 c1.637,0,2.969-1.299,2.969-2.896c0-1.363-0.991-2.554-2.356-2.833c-0.256-0.052-0.429-0.293-0.396-0.552 c0.016-0.123,0.027-0.247,0.027-0.374c0-1.664-1.388-3.017-3.093-3.017c-0.977,0-1.877,0.44-2.47,1.207 c-0.126,0.164-0.341,0.233-0.539,0.173c-0.198-0.059-0.339-0.234-0.355-0.44c-0.071-0.913-0.507-1.741-1.227-2.332 c-0.742-0.609-1.687-0.883-2.665-0.764c-1.475,0.177-2.694,1.292-2.963,2.712c-0.084,0.443-0.081,0.887,0.008,1.317 c0.046,0.219-0.06,0.441-0.257,0.545c-0.198,0.104-0.44,0.063-0.595-0.099c-0.38-0.401-0.896-0.622-1.452-0.622 c-1.038,0-1.903,0.79-1.971,1.799c-0.01,0.145-0.082,0.278-0.198,0.366s-0.265,0.119-0.406,0.089 c-0.573-0.125-1.111-0.087-1.65,0.111c-1.129,0.415-1.917,1.459-1.963,2.598c-0.031,0.782,0.252,1.526,0.799,2.096 c0.568,0.591,1.337,0.916,2.167,0.916h7.998c0.276,0,0.5,0.224,0.5,0.5s-0.224,0.5-0.5,0.5h-7.998 c-1.089,0-2.142-0.446-2.888-1.224c-0.737-0.767-1.12-1.771-1.078-2.828c0.061-1.538,1.113-2.943,2.617-3.496 c0.548-0.201,1.115-0.276,1.688-0.228c0.332-1.268,1.507-2.198,2.882-2.198c0.431,0,0.854,0.094,1.24,0.269 c0.011-0.199,0.035-0.397,0.073-0.597c0.35-1.844,1.924-3.291,3.827-3.519c1.249-0.149,2.462,0.2,3.418,0.984 c0.668,0.548,1.147,1.254,1.399,2.047c0.717-0.568,1.61-0.882,2.557-0.882c2.257,0,4.093,1.802,4.093,4.017 c0,0.02,0,0.04,0,0.06c1.603,0.52,2.726,2.011,2.726,3.7C37.31,62.239,35.53,63.987,33.341,63.987z M24.571,63.987h-0.327 c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h0.327c0.276,0,0.5,0.224,0.5,0.5S24.847,63.987,24.571,63.987z'></path>
                                <g>
                                    <path fill='#472b29' d='M31.79,58.695c-0.018,0-0.036-0.002-0.055-0.006c-0.135-0.03-0.219-0.164-0.189-0.299 c0.035-0.153,0.087-0.301,0.157-0.441c0.555-1.123,2.084-1.537,3.409-0.924c0.125,0.058,0.18,0.207,0.122,0.332 s-0.206,0.179-0.332,0.122c-1.081-0.501-2.315-0.19-2.751,0.692c-0.052,0.104-0.091,0.214-0.116,0.328 C32.008,58.615,31.905,58.695,31.79,58.695z'></path>
                                </g>
                                <g>
                                    <path fill='#472b29' d='M27.106,63.987h-1.005c-0.276,0-0.5-0.224-0.5-0.5s0.224-0.5,0.5-0.5h1.005 c0.276,0,0.5,0.224,0.5,0.5S27.382,63.987,27.106,63.987z'></path>
                                </g>
                            </g>
                            <g>
                                <path fill='#fff' d='M47.405 23H37.5c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h9.905c.276 0 .5.224.5.5S47.682 23 47.405 23zM50.5 23h-1c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h1c.276 0 .5.224.5.5S50.777 23 50.5 23zM55.491 25H46.5c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h8.991c.276 0 .5.224.5.5S55.767 25 55.491 25zM44.5 25h-1c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h1c.276 0 .5.224.5.5S44.777 25 44.5 25zM41.5 25h-2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h2c.276 0 .5.224.5.5S41.777 25 41.5 25zM47.5 27h-2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h2c.276 0 .5.224.5.5S47.776 27 47.5 27zM50.5 18c-.177 0-.823 0-1 0-.276 0-.5.224-.5.5 0 .276.224.5.5.5.177 0 .823 0 1 0 .276 0 .5-.224.5-.5C51 18.224 50.776 18 50.5 18zM50.5 20c-.177 0-4.823 0-5 0-.276 0-.5.224-.5.5 0 .276.224.5.5.5.177 0 4.823 0 5 0 .276 0 .5-.224.5-.5C51 20.224 50.776 20 50.5 20zM55.5 22c-.177 0-2.823 0-3 0-.276 0-.5.224-.5.5 0 .276.224.5.5.5.177 0 2.823 0 3 0 .276 0 .5-.224.5-.5C56 22.224 55.776 22 55.5 22z'></path></g><g><path fill='#fff' d='M81.5 49h-10c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h10c.276 0 .5.224.5.5S81.776 49 81.5 49zM85.5 49h-2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h2c.276 0 .5.224.5.5S85.776 49 85.5 49zM90.5 51h-10c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h10c.276 0 .5.224.5.5S90.777 51 90.5 51zM78.5 51h-1c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h1c.276 0 .5.224.5.5S78.776 51 78.5 51zM75.375 51H73.5c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h1.875c.276 0 .5.224.5.5S75.651 51 75.375 51zM84.5 47h-5c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h5c.276 0 .5.224.5.5S84.777 47 84.5 47zM81.5 53h-2c-.276 0-.5-.224-.5-.5s.224-.5.5-.5h2c.276 0 .5.224.5.5S81.776 53 81.5 53z'></path>
                            </g>
                        </svg>
                    </button>
                </div>
                <div id='sideNavDisabling' data-backdrop='static' data-keyboard='false'>
                </div>
                <div id='uploadForm' style='display:none;'>
                    <form action='utilities/upload.php' target='dummyFrame' method='POST' enctype='multipart/form-data' id='uploadFormActual'>
                        <input type='text' class='VideoTitle' name='VideoTitle' placeholder='Title' maxlength='50' required>
                        <textarea rows='3' class='VideoDescription' name='VideoDescription' placeholder='Description' maxlength='300'></textarea>
                        <div class='form-floating'>
                            <select class='form-select' id='floatingSelect1' aria-label='Floating label select example' name='addToCourse' required>
                                $addedCoursesOptions
                            </select>
                            <label for='floatingSelect1'>Add Video to:</label>
                        </div>
                        <div class='chooseVideo'>
                            <input type='file' class='ChooseVideoFile' id='file' name='file'>
                            <label for='file' id='label'>Choose a Video file</label>
                        </div>
                        <input type='submit' class='upload' name='uploadButton' value='Upload'>
                        <p id='uploadWarning'></p>
                    </form>
                    <div class='NotSignedIn' style='display:none;'>
                        <img src='assets/images/SignIn.jpg' title='Please Sign In' alt='Sign In Picture'/>
                        <p>
                            <a href='loginPage/loginPage.php'>Sign In</a> to AcadGenix
                        </p>
                    </div>
                </div>
                <div id='main'>
                    <div id='content'>
                        <div id='watchPageContent'>
                        <div id='WatchContainer'>
                            <div class='playerAndCreator'>
                                <div class='flowplayer' data-swf='flowplayer.swf' data-ratio='0.4167'>
                                    <video preload='metadata'>
                                        <source type='video/mp4' src='$filePath'>
                                        <p>Your Browser doesn't support MP4 files</p>
                                    </video>
                                </div>
                                <div class='defaultplayer'>
                                    <video preload='metadata' controls>
                                        <source type='video/mp4' src='$filePath'>
                                        <p>Your Browser doesn't support MP4 files</p>
                                    </video>
                                </div>
                                <div class='creator'>
                                    <div class='creatorPicture'>
                                        <img src='$creatorPic' alt='User DP' title='$creatorName'/>
                                    </div>
                                    <a href='$creatorProfile' target='_blank'><p class='creatorName'>$creatorName</p></a>
                                </div>
                            </div>";
    }
}
class VideoInfo
{
    private $video;
    public function __construct($video)
    {
        $this->video=$video;
    }
    public function create()
    {
        $title = $this->video->getTitle();
        $description = $this->video->getDescription();
        $uploadDate = explode(" ",$this->video->getUploadDate())[0];
        $views = $this->video->getViews();
        $views = $this->numeric($views);
        $likes = $this->video->getLikes();
        $likes = $this->numeric($likes);
        $liked = $this->video->liked();
        return "
        <div id='infoNotes'>
            <div class='VideoInfo'>
                <svg xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' width='65' height='65' viewBox='0 0 172 172' style='fill:#000000;'>
                    <g fill='none' fill-rule='nonzero' stroke='none' stroke-width='1' stroke-linecap='butt' stroke-linejoin='miter' stroke-miterlimit='10' stroke-dasharray='' stroke-dashoffset='0' font-family='none' font-weight='none' font-size='none' text-anchor='none' style='mix-blend-mode: normal'>
                        <path d='M0,172v-172h172v172z' fill='none'></path>
                        <g>
                            <path d='M149.984,97.524c1.892,-3.784 3.096,-7.912 3.096,-12.384c0,-11.352 -7.224,-21.156 -17.2,-24.94v0c0,-19.952 -16.168,-36.12 -36.12,-36.12c-16.856,0 -30.96,11.524 -34.916,27.176c-2.58,-0.86 -5.16,-1.376 -8.084,-1.376c-13.244,0 -24.08,9.976 -25.628,22.876c-8.944,1.892 -15.652,9.804 -15.652,19.264c0,11.008 8.772,19.78 19.78,19.78c0.344,0 0.688,0 0.86,0c0,0.344 0,0.516 0,0.86c0,19.436 15.824,35.26 35.26,35.26c11.008,0 20.984,-5.16 27.348,-13.072c3.784,3.784 8.944,6.192 14.792,6.192c8.084,0 14.964,-4.644 18.404,-11.18c1.892,0.516 3.784,0.86 5.676,0.86c10.492,0 18.92,-8.428 18.92,-18.92c0,-5.676 -2.58,-10.836 -6.536,-14.276z' fill='#c7ede6'></path>
                            <path d='M137.428,104.06c0,0 6.192,0 10.492,0c4.3,0 7.74,-3.44 7.74,-7.74c0,-3.956 -3.096,-7.224 -7.052,-7.74c0,-0.344 0.172,-0.688 0.172,-0.86c0,-4.3 -3.44,-7.74 -7.74,-7.74c-2.408,0 -4.644,1.204 -6.02,2.924c-0.172,-4.472 -3.956,-8.084 -8.6,-8.084c-4.816,0 -8.6,3.784 -8.6,8.6c0,0.688 0.172,1.548 0.344,2.236c-1.204,-1.376 -2.752,-2.236 -4.644,-2.236c-3.096,0 -5.504,2.236 -6.02,5.332c-0.344,0 -0.688,-0.172 -0.86,-0.172c-4.3,0 -7.74,3.44 -7.74,7.74c0,4.3 3.44,7.74 7.74,7.74c4.3,0 16.34,0 16.34,0h9.288v0.86h5.16z' fill='#fdfcef'></path>
                            <path d='M126.42,73.96c-5.16,0 -9.46,4.3 -9.46,9.46v0c-1.032,-0.688 -2.236,-0.86 -3.44,-0.86c-3.096,0 -5.848,2.236 -6.708,5.16h-0.172c-4.816,0 -8.6,3.784 -8.6,8.6c0,4.816 3.784,8.6 8.6,8.6h25.628c0.516,0 0.86,-0.344 0.86,-0.86c0,-0.516 -0.344,-0.86 -0.86,-0.86h-25.628c-3.784,0 -6.88,-3.096 -6.88,-6.88c0,-3.784 3.096,-6.88 6.88,-6.88c0.172,0 0.344,0 0.516,0h0.172c0,0 0,0 0.172,0c0.344,0 0.86,-0.344 0.86,-0.688c0.344,-2.58 2.58,-4.472 5.16,-4.472c1.548,0 2.924,0.688 3.956,1.892c0.172,0.172 0.344,0.344 0.688,0.344c0.172,0 0.344,0 0.344,-0.172c0.344,-0.172 0.516,-0.516 0.516,-1.032c-0.172,-0.688 -0.344,-1.376 -0.344,-2.064c0,-4.3 3.44,-7.74 7.74,-7.74c4.128,0 7.396,3.268 7.74,7.396c0,0.344 0.344,0.688 0.516,0.86c0.172,0 0.172,0 0.344,0c0.344,0 0.516,-0.172 0.688,-0.344c1.376,-1.72 3.268,-2.58 5.332,-2.58c3.784,0 6.88,3.096 6.88,6.88c0,0.172 0,0.344 0,0.516v0.172c0,0.172 0,0.516 0.172,0.688c0.172,0.172 0.344,0.344 0.516,0.344c3.612,0.344 6.192,3.268 6.192,6.88c0,3.784 -3.096,6.88 -6.88,6.88h-10.492c-0.516,0 -0.86,0.344 -0.86,0.86c0,0.516 0.344,0.86 0.86,0.86h10.492c4.816,0 8.6,-3.784 8.6,-8.6c0,-4.128 -2.924,-7.568 -6.88,-8.428v-0.172c0,-4.816 -3.784,-8.6 -8.6,-8.6c-2.064,0 -3.956,0.688 -5.504,2.064c-1.032,-4.128 -4.644,-7.224 -9.116,-7.224z' fill='#472b29'></path>
                            <path d='M123.84,86c-2.408,0 -4.472,1.72 -4.988,3.956c-0.688,-0.344 -1.376,-0.516 -2.064,-0.516c-2.064,0 -3.784,1.548 -4.128,3.612c-0.344,0 -0.688,-0.172 -1.032,-0.172c-2.58,0 -4.816,2.064 -4.988,4.644c0,0.172 0.172,0.516 0.344,0.516v0c0.172,0 0.344,-0.172 0.344,-0.344c0.172,-2.236 2.064,-3.956 4.128,-3.956c0.344,0 0.86,0 1.204,0.172c0,0 0,0 0.172,0c0.172,0 0.172,0 0.344,-0.172c0.172,0 0.172,-0.172 0.172,-0.344c0,-1.72 1.376,-3.268 3.268,-3.268c0.688,0 1.548,0.344 2.064,0.86c0,0 0.172,0.172 0.344,0.172h0.172c0.172,0 0.172,-0.172 0.344,-0.344c0.172,-2.236 2.064,-3.956 4.3,-3.956c0.344,0 0.688,0 1.204,0.172c0,0 0,0 0.172,0c0.172,0 0.344,-0.172 0.344,-0.344c0,-0.172 0,-0.516 -0.344,-0.516c-0.516,-0.172 -0.86,-0.172 -1.376,-0.172zM147.748,88.58c-2.236,0 -4.3,1.548 -4.988,3.784c0,0.172 0,0.516 0.344,0.516c0,0 0,0 0.172,0c0.172,0 0.344,-0.172 0.344,-0.344c0.516,-1.892 2.236,-3.096 4.128,-3.096c0.172,0 0.344,0 0.516,0v0c0.172,0 0.344,-0.172 0.344,-0.344c0,-0.172 -0.172,-0.516 -0.344,-0.516c0,0 -0.344,0 -0.516,0z' fill='#472b29'></path>
                            <path d='M26.488,87.72h-17.028c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h17.028c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM31.82,87.72h-1.72c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h1.72c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM40.42,91.16h-15.48c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h15.48c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM21.5,91.16h-1.72c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h1.72c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM16.34,91.16h-3.44c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h3.44c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM26.66,94.6h-3.44c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h3.44c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM31.82,79.12c-0.344,0 -1.376,0 -1.72,0c-0.516,0 -0.86,0.344 -0.86,0.86c0,0.516 0.344,0.86 0.86,0.86c0.344,0 1.376,0 1.72,0c0.516,0 0.86,-0.344 0.86,-0.86c0,-0.516 -0.344,-0.86 -0.86,-0.86zM31.82,82.56c-0.344,0 -8.256,0 -8.6,0c-0.516,0 -0.86,0.344 -0.86,0.86c0,0.516 0.344,0.86 0.86,0.86c0.344,0 8.256,0 8.6,0c0.516,0 0.86,-0.344 0.86,-0.86c0,-0.516 -0.344,-0.86 -0.86,-0.86zM40.42,86c-0.344,0 -4.816,0 -5.16,0c-0.516,0 -0.86,0.344 -0.86,0.86c0,0.516 0.344,0.86 0.86,0.86c0.344,0 4.816,0 5.16,0c0.516,0 0.86,-0.344 0.86,-0.86c0,-0.516 -0.344,-0.86 -0.86,-0.86z' fill='#ffffff'></path>
                            <g fill='#ffffff'>
                                <path d='M124.7,41.28h-17.2c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h17.2c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM131.58,41.28h-3.44c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h3.44c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM140.18,44.72h-17.2c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h17.2c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM119.54,44.72h-1.72c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h1.72c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM114.208,44.72h-3.268c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h3.268c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM129.86,37.84h-8.6c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h8.6c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86zM124.7,48.16h-3.44c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h3.44c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.344,0.86 -0.86,0.86z'></path>
                            </g>
                            <g>
                                <path d='M63.64,122.636c-6.88,0 -12.556,-5.676 -12.556,-12.556v-48.16c0,-6.88 5.676,-12.556 12.556,-12.556h48.16c6.88,0 12.556,5.676 12.556,12.556v48.16c0,6.88 -5.676,12.556 -12.556,12.556z' fill='#0a30ff'></path>
                                <path d='M111.8,50.568c6.192,0 11.352,5.16 11.352,11.352v48.16c0,6.192 -5.16,11.352 -11.352,11.352h-48.16c-6.192,0 -11.352,-5.16 -11.352,-11.352v-48.16c0,-6.192 5.16,-11.352 11.352,-11.352h48.16M111.8,48.16h-48.16c-7.568,0 -13.76,6.192 -13.76,13.76v48.16c0,7.568 6.192,13.76 13.76,13.76h48.16c7.568,0 13.76,-6.192 13.76,-13.76v-48.16c0,-7.568 -6.192,-13.76 -13.76,-13.76z' fill='#472b29'></path>
                            </g>
                            <g fill='#472b29'>
                                <path d='M117.648,81.7c-0.516,0 -0.86,-0.344 -0.86,-0.86v-6.364c0,-0.516 0.344,-0.86 0.86,-0.86c0.516,0 0.86,0.344 0.86,0.86v6.364c0,0.516 -0.344,0.86 -0.86,0.86z'></path>
                            </g>
                            <g fill='#472b29'>
                                <path d='M117.82,70.692c-0.516,0 -0.86,-0.344 -0.86,-0.86v-3.268c0,-0.516 0.344,-0.86 0.86,-0.86c0.516,0 0.86,0.344 0.86,0.86v3.268c0,0.344 -0.344,0.86 -0.86,0.86z'></path>
                            </g>
                            <g fill='#472b29'>
                                <path d='M110.596,116.788h-45.58c-4.472,0 -8.084,-3.612 -8.084,-8.084v-45.58c0,-4.472 3.612,-8.084 8.084,-8.084h41.28c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.516,0.86 -0.86,0.86h-41.28c-3.612,0 -6.364,2.924 -6.364,6.364v45.408c0,3.612 2.924,6.364 6.364,6.364h45.408c3.612,0 6.364,-2.924 6.364,-6.364v-23.22c0,-0.516 0.344,-0.86 0.86,-0.86c0.516,0 0.86,0.344 0.86,0.86v23.392c0.172,4.472 -3.44,8.084 -7.912,8.084z'></path>
                            </g>
                            <g>
                                <path d='M91.504,100.276c0,1.548 1.548,2.408 4.988,2.408v3.096h-17.544v-3.096c3.268,0 4.816,-0.86 4.816,-2.408v-16.168c0,-1.548 -1.548,-2.408 -4.816,-2.408v-3.096h12.556c0,0.172 0,21.672 0,21.672zM92.192,69.832c0,1.204 -0.516,2.236 -1.376,3.268c-0.86,0.86 -2.064,1.376 -3.44,1.376c-0.688,0 -1.376,-0.172 -1.892,-0.344c-0.516,-0.172 -1.032,-0.516 -1.548,-1.032c-0.516,-0.344 -0.86,-0.86 -1.032,-1.376c-0.344,-0.516 -0.344,-1.204 -0.344,-1.72c0,-1.204 0.516,-2.408 1.376,-3.268c1.032,-0.86 2.064,-1.376 3.44,-1.376c1.376,0 2.408,0.516 3.44,1.376c1.032,0.688 1.376,1.72 1.376,3.096z' fill='#fdfcee'></path>
                                <path d='M97.352,106.64h-19.264v-4.816h0.86c1.892,0 3.956,-0.344 3.956,-1.548v-16.168c0,-1.032 -1.376,-1.548 -3.956,-1.548h-0.86v-4.816h14.276v22.532c0,0.344 0,0.516 0.172,0.688c0.344,0.344 1.204,0.86 3.784,0.86h0.86v4.816zM79.808,104.92h15.824v-1.376c-2.064,-0.172 -3.44,-0.516 -4.3,-1.376c-0.516,-0.516 -0.688,-1.204 -0.688,-1.892v-20.64h-10.836v1.204c3.268,0.172 4.816,1.204 4.816,3.268v16.168c0,2.064 -1.72,3.096 -4.816,3.268zM87.376,75.164c-0.86,0 -1.548,-0.172 -2.236,-0.516c-0.688,-0.344 -1.204,-0.688 -1.72,-1.204c-0.516,-0.516 -0.86,-1.032 -1.204,-1.72c-0.344,-0.688 -0.516,-1.376 -0.516,-2.236c0,-1.548 0.516,-2.752 1.72,-3.784c1.204,-1.032 2.408,-1.548 3.956,-1.548c1.548,0 2.924,0.516 3.956,1.548c1.204,1.032 1.72,2.408 1.72,3.784c0,1.548 -0.516,2.752 -1.72,3.784c-1.032,1.376 -2.236,1.892 -3.956,1.892zM87.376,66.048c-1.032,0 -2.064,0.344 -2.924,1.032c-0.86,0.688 -1.204,1.548 -1.204,2.58c0,0.516 0.172,1.032 0.344,1.376c0.172,0.516 0.516,0.86 0.86,1.204c0.344,0.344 0.86,0.688 1.204,0.86c0.516,0.172 1.032,0.344 1.548,0.344c1.204,0 2.064,-0.344 2.752,-1.032c0.688,-0.688 1.204,-1.548 1.204,-2.58c0,-1.032 -0.344,-1.892 -1.204,-2.58c-0.516,-0.86 -1.376,-1.204 -2.58,-1.204z' fill='#472b29'></path>
                            </g>
                            <g>
                                <path d='M62.78,126.42c0,0 2.752,0 6.02,0c3.268,0 6.02,-2.752 6.02,-6.02c0,-3.096 -2.236,-5.504 -5.332,-6.02c0,-0.344 0.172,-0.688 0.172,-0.86c0,-3.268 -2.752,-6.02 -6.02,-6.02c-1.72,0 -3.44,0.86 -4.472,2.064c-0.688,-3.096 -3.44,-5.504 -6.708,-5.504c-3.784,0 -6.88,3.096 -6.88,6.88c0,0.344 0,0.688 0.172,1.032c-0.688,-0.516 -1.72,-1.032 -2.752,-1.032c-2.064,0 -3.784,1.548 -4.3,3.612c-0.344,0 -0.688,-0.172 -0.86,-0.172c-3.268,0 -6.02,2.752 -6.02,6.02c0,3.268 2.752,6.02 6.02,6.02c3.268,0 12.9,0 12.9,0v0.86h12.04z' fill='#fdfcef'></path>
                                <path d='M65.876,118.68c-0.172,0 -0.516,-0.172 -0.516,-0.516c0,-2.064 1.72,-3.784 3.784,-3.784c0,0 1.204,0 2.064,0.172c0.172,0 0.344,0.344 0.344,0.516c0,0.172 -0.344,0.344 -0.516,0.344c-0.688,-0.172 -1.892,-0.172 -1.892,-0.172c-1.548,0 -2.924,1.376 -2.924,2.924c0,0.344 -0.172,0.516 -0.344,0.516zM54.18,125.56c-0.47496,0 -0.86,0.38504 -0.86,0.86c0,0.47496 0.38504,0.86 0.86,0.86c0.47496,0 0.86,-0.38504 0.86,-0.86c0,-0.47496 -0.38504,-0.86 -0.86,-0.86z' fill='#472b29'></path>
                                <path d='M68.8,127.28h-6.02c-0.516,0 -0.86,-0.344 -0.86,-0.86c0,-0.516 0.344,-0.86 0.86,-0.86h6.02c2.924,0 5.16,-2.236 5.16,-5.16c0,-2.58 -1.892,-4.816 -4.472,-5.16c-0.172,0 -0.516,-0.172 -0.516,-0.344c0,-0.172 -0.172,-0.344 -0.172,-0.688c0,-0.344 0,-0.516 0,-0.86c0,-2.924 -2.236,-5.16 -5.16,-5.16c-1.376,0 -2.752,0.688 -3.784,1.72c-0.172,0.172 -0.516,0.344 -0.86,0.172c-0.344,0 -0.516,-0.344 -0.688,-0.688c-0.516,-2.752 -3.096,-4.816 -5.848,-4.816c-3.268,0 -6.02,2.752 -6.02,6.02c0,0.172 0,0.516 0,0.688c0,0.344 -0.172,0.688 -0.344,0.86c-0.172,0.172 -0.688,0.172 -1.032,0c-0.516,0 -1.376,-0.344 -2.064,-0.344c-1.72,0 -3.096,1.204 -3.44,2.752c0,0.516 -0.516,0.688 -1.032,0.688c-0.344,0 -0.516,0 -0.86,0c-2.924,0 -5.16,2.236 -5.16,5.16c0,2.924 2.236,5.16 5.16,5.16h12.9c0.516,0 0.86,0.344 0.86,0.86c0,0.516 -0.172,0.86 -0.688,0.86h-12.9c-3.784,0 -6.88,-3.096 -6.88,-6.88c0,-3.784 3.096,-6.88 6.88,-6.88c0.172,0 0.172,0 0.344,0c0.688,-2.064 2.58,-3.44 4.816,-3.44c0.516,0 1.204,0.172 1.72,0.344c0.344,-3.956 3.612,-7.224 7.74,-7.224c3.096,0 6.02,1.892 7.224,4.816c1.204,-0.86 2.58,-1.376 3.956,-1.376c3.784,0 6.88,3.096 6.88,6.88v0.172c2.924,0.688 5.16,3.44 5.16,6.708c0,3.784 -3.096,6.88 -6.88,6.88z' fill='#472b29'></path>
                                <path d='M59.34,125.56c-0.344,0 -1.376,0 -1.72,0c-0.516,0 -0.86,0.344 -0.86,0.86c0,0.516 0.344,0.86 0.86,0.86c0.344,0 1.376,0 1.72,0c0.516,0 0.86,-0.344 0.86,-0.86c0,-0.516 -0.344,-0.86 -0.86,-0.86z' fill='#472b29'></path>
                            </g>
                        </g>
                    </g>
                </svg>
                <p id='VideoTitle'>$title</p>
                <div id='VideoDescription'>
                    <div class='icon'>
                        <img src='assets/images/descriptionIcon.png'/>
                    </div>
                    <p class='description'>$description</p>
                </div>
                <div id='Views'>
                    <div class='icon'>
                        <img src='assets/images/viewsIcon.png'/>
                    </div>
                    <p class='count'>$views</p>
                </div>
                <div id='Likes'>
                    <div class='icon'>
                        <img src='assets/images/".($liked?"likeIcon.png":"notLikedIcon.png")."'/>
                    </div>
                    <p class='count'>$likes</p>
                </div>
                <p id='UploadDate'>Uploaded on $uploadDate</p>
            </div>";
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
class Notepad
{
    private $con;
    public function __construct($con)
    {
        $this->con = $con;
    }
    public function create()
    {
        $title = $this->getTitle();
        $content = $this->getContent();
        return "<div id='notepad'>
                    <div class='letter'>
                        <div id='notepadHead'>
                            <div class='icon'>
                                <img src='https://img.icons8.com/clouds/100/000000/book-and-pencil.png'/>
                            </div>
                            <div class='title' contenteditable spellcheck='false'>
                                $title
                            </div>
                            <div class='save'>
                                <img src='https://img.icons8.com/clouds/100/000000/save-as.png'/>
                            </div>
                        </div>
                        <div class='text' contenteditable spellcheck='false'>
                            $content
                        </div>
                    </div>
                </div>
            </div>
            </div>";
    }
    public function getTitle()
    {
        $query = $this->con->prepare("SELECT COUNT(id) FROM notes WHERE videoID=:videoID AND userID=:userID");
        $query->bindParam(":videoID", $_GET['videoID']);
        $query->bindParam(":userID", $_SESSION['userID']);
        $query->execute();
        $sqldata = $query->fetch(PDO::FETCH_ASSOC);
        $queryx = $this->con->prepare("SELECT title FROM notes WHERE videoID=:videoID AND userID=:userID");
        $queryx->bindParam(":videoID", $_GET['videoID']);
        $queryx->bindParam(":userID", $_SESSION['userID']);
        $queryx->execute();
        $sqldatax = $queryx->fetch(PDO::FETCH_ASSOC);
        if($sqldata["COUNT(id)"]==1)
        {
            return $sqldatax["title"];
        }
        else
        {
            return "Title";
        }
    }
    public function getContent()
    {
        $query = $this->con->prepare("SELECT COUNT(id) FROM notes WHERE videoID=:videoID AND userID=:userID");
        $query->bindParam(":videoID", $_GET['videoID']);
        $query->bindParam(":userID", $_SESSION['userID']);
        $query->execute();
        $sqldata = $query->fetch(PDO::FETCH_ASSOC);
        $queryx = $this->con->prepare("SELECT content FROM notes WHERE videoID=:videoID AND userID=:userID");
        $queryx->bindParam(":videoID", $_GET['videoID']);
        $queryx->bindParam(":userID", $_SESSION['userID']);
        $queryx->execute();
        $sqldatax = $queryx->fetch(PDO::FETCH_ASSOC);
        if($sqldata["COUNT(id)"]==1)
        {
            return $sqldatax["content"];
        }
        else
        {
            return "You can edit this text!<br/><br/>
            E = mc^2<br/>
            E: Energy;
            m: mass;
            c: speed of light";
        }
    }
}
class CommentPanel
{
    private $con, $comments;
    public function __construct($con, $id)
    {
        $this->con = $con;
        $this->comments = $this->getComments($id);
    }
    public function create()
    {
        return $this->comments;
    }
    public function getComments($id)
    {
        $result="";
        $query = $this->con->prepare("SELECT COUNT(id) FROM comments WHERE videoID=:videoID");
        $query->bindParam(":videoID",$id);
        $query->execute();
        $sqldata = $query->fetch(PDO::FETCH_ASSOC);
        $count = $sqldata["COUNT(id)"];
        $query1 = $this->con->prepare("SELECT * FROM comments WHERE videoID=:videoID ORDER BY commentDateTime DESC");
        $query1->bindParam(":videoID",$id);
        $query1->execute();
        $sqldata1 = $query1->fetchAll(PDO::FETCH_ASSOC);
        $result.="<div class='comments_section'>
                    <div class='panel-header'>
                        <span id='panel-title'> Comments </span>
                        <span class='counter'> $count </span>
                    </div>
                    <div class='panel-collapse'>
                        <form action=''>
                            <textarea placeholder='Comment' maxlength='1000' required></textarea>
                            <input class='commentButton' type='submit' value='Leave Comment'/>
                        </form>
                    </div>
                    <ul class='comment_list'>";
        foreach($sqldata1 as $row)
        {
            $query2 = $this->con->prepare("SELECT * FROM users WHERE id=:userID");
            $query2->bindParam(":userID", $row["userID"]);
            $query2->execute();
            $sqldata2 = $query2->fetch(PDO::FETCH_ASSOC);
            $userPic = $sqldata2["imageURL"];
            $name = $sqldata2["name"];
            $role = $sqldata2["role"];
            $commentDateTime = $row["commentDateTime"];
            $content = $row["comment"];
            $comment = "
            <li>
                <div class='comment_card' data-depth='0'>
                    <div class='figure'>
                        <img class='image' src='$userPic' alt='commentor DP' title='$name'/>
                        <div class='fig_caption'>
                            <h5 class='name'>$name</h5>
                            <h6 class='occupation'>$role</h6>
                            <p class='date'>$commentDateTime</p>
                        </div>
                    </div>
                    <p class='comment_text'>
                        $content
                    </p>
                </div>
            </li>";
            $result.=$comment;
        }
        $result.="</ul>
            </div>
        </div>
        </div>
        </div>
        <iframe name='dummyFrame' id='dummyFrame' style='display: none;'></iframe>
        <div id='loadingModal' data-backdrop='static' data-keyboard='false' style='display:none;'>
            <img src='assets/images/uploading.gif' title='Please Wait for a while!' alt='Loading Spinner GIF'/>
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
            <li class='active' id='create-content-option2'>
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
        return $result;
    }
}
?>