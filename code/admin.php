<?php
session_start();
require_once("utilities/config.php");
if(!isset($_SESSION["userMail"]))
{
    exit();
}
else if($_SESSION["userMail"]!="acadgenix@gmail.com")
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
    function renderPage()
    {
        $query1 = $this->con->prepare("SELECT * FROM calendars WHERE calendarType='current'");
        $query1->execute();
        $sqldata1 = $query1->fetch(PDO::FETCH_ASSOC);
        $currentAcademicCalendar = $sqldata1["filePath"];
        $query2 = $this->con->prepare("SELECT * FROM calendars WHERE calendarType='archive'");
        $query2->execute();
        $sqldata2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $archive="";
        foreach($sqldata2 as $row)
        {
            $title = $row["title"];
            $filePath = $row["filePath"];
            $result = "
            <div class='Heading'>
	            <div class='content rounded-3 p-3'>
		            <h1 class='fs-3'>$title</h1>
		            <p class='mb-0'><a target='_blank' href='$filePath'>Download here</a></p>
	            </div>
            </div>";
            $archive.=$result;
        }
        $query3 = $this->con->prepare("SELECT * FROM schedules");
        $query3->execute();
        $sqldata3 = $query3->fetchAll(PDO::FETCH_ASSOC);
        $schedule1 = $schedule2 = $schedule3 = $schedule4 = "";
        foreach($sqldata3 as $row)
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
        $query4 = $this->con->prepare("SELECT * FROM users WHERE role='Faculty'");
        $query4->execute();
        $sqldata4 = $query4->fetchAll(PDO::FETCH_ASSOC);
        $faculty="";
        foreach($sqldata4 as $row)
        {
            $name = $row["name"];
            $pic = $row["imageURL"];
            $filePath = $row["cvFilePath"];
            $result = "<div class='mt-4'>
                            <div class='Heading'>
                                <div class='content rounded-3 p-3'>
                                    <div class='box d-flex rounded-2 align-items-center mb-4 mb-lg-0'>
                                        <img src='$pic' title='$name' alt='Faculty DP' style='height:60px; width:60px; border-radius:50%;'/>
                                        <div class='ms-3' style='width:100%;'>
                                            <div class='d-flex align-items-center'>
                                                <h3 class='mb-0' style='font-size:20px;'>$name</h3> <span class='d-block' style='margin-left:auto;'><a target='_blank' href='$filePath'>Download CV here</a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>";
            $faculty.=$result;
        }
        $query5 = $this->con->prepare("SELECT COUNT(id) FROM users WHERE role='Faculty'");
        $query5->execute();
        $sqldata5 = $query5->fetch(PDO::FETCH_ASSOC);
        $facultyCount = $sqldata5["COUNT(id)"];
        $query6 = $this->con->prepare("SELECT COUNT(id) FROM users WHERE role='Student'");
        $query6->execute();
        $sqldata6 = $query6->fetch(PDO::FETCH_ASSOC);
        $studentCount = $sqldata6["COUNT(id)"];
        $studentCountPhrase = ($studentCount!=1)?"Students":"Student";
        $query7 = $this->con->prepare("SELECT * FROM news");
        $query7->execute();
        $sqldata7 = $query7->fetchAll(PDO::FETCH_ASSOC);
        $title1 = $title2 = $title3 = $title4 = $title5 = "";
        $link1 = $link2 = $link3 = $link4 = $link5 = "";
        foreach($sqldata7 as $row)
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
        $coursesCount=0;
        $query8 = $this->con->prepare("SELECT * FROM institutecourses");
        $query8->execute();
        $sqldata8 = $query8->fetchAll(PDO::FETCH_ASSOC);
        $coursesTable="";
        foreach($sqldata8 as $row)
        {
            $coursesCount++;
            $name = $row["name"];
            $code = $row["code"];
            $type = $row["type"];
            $filePath = $row["filePath"];
            $result = "<tr>
                            <td>$name</td>
                            <td>$code</td>
                            <td>$type</td>
                            <td><a href='$filePath' target='_blank' class='text-decoration-none' style='cursor:pointer;'>Click Here</a></td>
                        </tr>";
            $coursesTable.=$result;
        }
        $query9 = $this->con->prepare("SELECT * FROM additionalcourses");
        $query9->execute();
        $sqldata9 = $query9->fetchAll(PDO::FETCH_ASSOC);
        $addCoursesTable="";
        foreach($sqldata9 as $row)
        {
            $coursesCount++;
            $name = $row["name"];
            $code = $row["code"];
            $filePath = $row["filePath"];
            $result = "<tr>
                            <td>$name</td>
                            <td>$code</td>
                            <td><a href='$filePath' target='_blank' class='text-decoration-none' style='cursor:pointer;'>Click Here</a></td>
                        </tr>";
            $addCoursesTable.=$result;
        }
        return "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <title>Admin - AcadGenix</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css' rel='stylesheet'
            integrity='sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1' crossorigin='anonymous'>
            <link type = 'image/icon type' href='assets/images/PlainLogo.png' rel='icon'>
            <link rel='stylesheet' href='admin.css'>
            <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js'
            integrity='sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW' crossorigin='anonymous'></script>
            <script type='text/javascript' src='admin.js'></script>
            <script type='text/javascript'>
                function dashboardClicked()
                {
                    $('#wrapper-academicCalendar-current').hide();
                    $('#wrapper-academicCalendar-archives').hide();
                    $('#wrapper-timeTables').hide();
                    $('#wrapper-instituteCourses').hide();
                    $('#wrapper-additionalCourses').hide();
                    $('#wrapper-faculty').hide();
                    $('#wrapper-delete-content').hide();
                    $('#wrapper-news').hide();
                    $('#wrapper-documentation').hide();
                    $('#wrapper-dashboard').show();
                }
                function CurrentAcademicCalendarClicked()
                {
                    $('#wrapper-dashboard').hide();
                    $('#wrapper-academicCalendar-archives').hide();
                    $('#wrapper-timeTables').hide();
                    $('#wrapper-instituteCourses').hide();
                    $('#wrapper-additionalCourses').hide();
                    $('#wrapper-faculty').hide();
                    $('#wrapper-delete-content').hide();
                    $('#wrapper-news').hide();
                    $('#wrapper-documentation').hide();
                    $('#wrapper-academicCalendar-current').show();
                }
                function ArchivesAcademicCalendarClicked()
                {
                    $('#wrapper-dashboard').hide();
                    $('#wrapper-academicCalendar-current').hide();
                    $('#wrapper-timeTables').hide();
                    $('#wrapper-instituteCourses').hide();
                    $('#wrapper-additionalCourses').hide();
                    $('#wrapper-faculty').hide();
                    $('#wrapper-delete-content').hide();
                    $('#wrapper-news').hide();
                    $('#wrapper-documentation').hide();
                    $('#wrapper-academicCalendar-archives').show();
                }
                function timeTablesClicked()
                {
                    $('#wrapper-dashboard').hide();
                    $('#wrapper-academicCalendar-current').hide();
                    $('#wrapper-academicCalendar-archives').hide();
                    $('#wrapper-instituteCourses').hide();
                    $('#wrapper-additionalCourses').hide();
                    $('#wrapper-faculty').hide();
                    $('#wrapper-delete-content').hide();
                    $('#wrapper-news').hide();
                    $('#wrapper-documentation').hide();
                    $('#wrapper-timeTables').show();
                }
                function instituteCoursesClicked()
                {
                    $('#wrapper-dashboard').hide();
                    $('#wrapper-academicCalendar-current').hide();
                    $('#wrapper-academicCalendar-archives').hide();
                    $('#wrapper-timeTables').hide();
                    $('#wrapper-additionalCourses').hide();
                    $('#wrapper-faculty').hide();
                    $('#wrapper-delete-content').hide();
                    $('#wrapper-news').hide();
                    $('#wrapper-documentation').hide();
                    $('#wrapper-instituteCourses').show();
                }
                function additionalCoursesClicked()
                {
                    $('#wrapper-dashboard').hide();
                    $('#wrapper-academicCalendar-current').hide();
                    $('#wrapper-academicCalendar-archives').hide();
                    $('#wrapper-timeTables').hide();
                    $('#wrapper-instituteCourses').hide();
                    $('#wrapper-faculty').hide();
                    $('#wrapper-delete-content').hide();
                    $('#wrapper-news').hide();
                    $('#wrapper-documentation').hide();
                    $('#wrapper-additionalCourses').show();
                }
                function facultyClicked()
                {
                    $('#wrapper-dashboard').hide();
                    $('#wrapper-academicCalendar-current').hide();
                    $('#wrapper-academicCalendar-archives').hide();
                    $('#wrapper-timeTables').hide();
                    $('#wrapper-instituteCourses').hide();
                    $('#wrapper-additionalCourses').hide();
                    $('#wrapper-delete-content').hide();
                    $('#wrapper-news').hide();
                    $('#wrapper-documentation').hide();
                    $('#wrapper-faculty').show();
                }
                function deleteVideoClicked()
                {
                    $('#wrapper-dashboard').hide();
                    $('#wrapper-academicCalendar-current').hide();
                    $('#wrapper-academicCalendar-archives').hide();
                    $('#wrapper-timeTables').hide();
                    $('#wrapper-instituteCourses').hide();
                    $('#wrapper-additionalCourses').hide();
                    $('#wrapper-faculty').hide();
                    $('#wrapper-news').hide();
                    $('#wrapper-documentation').hide();
                    $('#wrapper-delete-content').show();
                }
                function newsClicked()
                {
                    $('#wrapper-dashboard').hide();
                    $('#wrapper-academicCalendar-current').hide();
                    $('#wrapper-academicCalendar-archives').hide();
                    $('#wrapper-timeTables').hide();
                    $('#wrapper-instituteCourses').hide();
                    $('#wrapper-additionalCourses').hide();
                    $('#wrapper-faculty').hide();
                    $('#wrapper-delete-content').hide();
                    $('#wrapper-documentation').hide();
                    $('#wrapper-news').show();
                }
                function documentationClicked()
                {
                    $('#wrapper-dashboard').hide();
                    $('#wrapper-academicCalendar-current').hide();
                    $('#wrapper-academicCalendar-archives').hide();
                    $('#wrapper-timeTables').hide();
                    $('#wrapper-instituteCourses').hide();
                    $('#wrapper-additionalCourses').hide();
                    $('#wrapper-faculty').hide();
                    $('#wrapper-delete-content').hide();
                    $('#wrapper-news').hide();
                    $('#wrapper-documentation').show();
                }
                function signOut()
                {
                    $.post('utilities/destroySession.php');
                    window.sessionStorage.clear();
                    window.location='https://acadgenix.ga/';
                }
            </script>
        </head>
        <body>
            <aside class='sidebar position-fixed top-0 left-0 overflow-auto h-100 float-left'>
                <div class='sidebar-header d-flex justify-content-center align-items-center px-3 py-4'>
                    <img class='rounded-pill img-fluid' width='65' src='assets/images/iiitdwdLogo.png' alt='IIIT Logo' title='IIIT Dharwad'/>
                    <div class='ms-2'>
                        <h5 class='fs-6 mb-0'>
                            <a class='text-decoration-none' target='_blank' href='https://iiitdwd.ac.in'>Indian Institute of Information Technology, Dharwad</a>
                        </h5>
                    </div>
                </div>
                <ul class='categories list-unstyled'>
                    <li onclick='dashboardClicked();'>
                        <img src='https://img.icons8.com/metro/26/ffffff/home.png'/><a href='#'> Dashboard</a>
                    </li>
                    <li class='has-dropdown'>
                        <img src='https://img.icons8.com/metro/26/ffffff/calendar-1.png'/><a href='#'> Academic Calender</a>
                        <ul class='sidebar-dropdown list-unstyled'>
                            <li onclick='CurrentAcademicCalendarClicked();'><a href='#'>Current</a></li>
                            <li onclick='ArchivesAcademicCalendarClicked();'><a href='#'>Archives</a></li>
                        </ul>
                    </li>
                    <li onclick='timeTablesClicked();'>
                        <img src='https://img.icons8.com/metro/26/ffffff/overtime.png'/><a href='#'> Time Tables</a>
                    </li>
                    <li class='has-dropdown'>
                        <svg xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' width='20' height='20' viewBox='0 0 172 172' style=' fill:#000000;margin-bottom:6px;margin-right:6px;'><g fill='none' fill-rule='nonzero' stroke='none' stroke-width='1' stroke-linecap='butt' stroke-linejoin='miter' stroke-miterlimit='10' stroke-dasharray='' stroke-dashoffset='0' font-family='none' font-weight='none' font-size='none' text-anchor='none' style='mix-blend-mode: normal'><path d='M0,172v-172h172v172z' fill='none'></path><g fill='#ffffff'><path d='M3.92788,0c-1.98978,0 -3.92788,1.9381 -3.92788,3.92788v29.14904h52.92308v-29.14904c0,-1.98978 -1.9381,-3.92788 -3.92788,-3.92788zM123.625,0c-2.63581,0 -4.54808,1.9381 -4.54808,3.92788v29.14904h52.92308v-29.14904c0,-1.98978 -1.9381,-3.92788 -3.92788,-3.92788zM70.70192,26.46154c-2.63581,0 -4.54808,1.9381 -4.54808,3.92788v15.91827h39.69231v-15.91827c0,-1.98978 -1.9381,-3.92788 -3.92788,-3.92788zM0,39.69231v99.23077h52.92308v-99.23077zM119.07692,39.69231v105.84615h52.92308v-105.84615zM19.84615,52.92308h13.23077v72.76923h-13.23077zM66.15385,52.92308v99.23077h39.69231v-99.23077zM138.92308,52.92308h13.23077v79.38462h-13.23077zM79.38462,72.76923h13.23077v59.53846h-13.23077zM0,145.53846v22.53365c0,1.98978 1.9381,3.92788 3.92788,3.92788h44.44712c2.63582,0 4.54808,-1.9381 4.54808,-3.92788v-22.53365zM119.07692,152.15385v15.91827c0,2.63582 1.9381,3.92788 3.92788,3.92788h44.44712c2.63582,0 3.92788,-1.9381 3.92788,-3.92788v-15.91827zM66.15385,158.76923v9.30288c0,2.63582 1.9381,3.92788 3.92788,3.92788h31.21635c2.63582,0 3.92788,-1.9381 3.92788,-3.92788v-9.30288z'></path></g></g></svg>
                        <a href='#'> Courses Offered</a>
                        <ul class='sidebar-dropdown list-unstyled'>
                            <li onclick='instituteCoursesClicked();'><a href='#'>Institute Courses</a></li>
                            <li onclick='additionalCoursesClicked();'><a href='#'>Additonal Courses</a></li>
                        </ul>
                    </li>
                    <li onclick='facultyClicked();'>
                        <img src='https://img.icons8.com/metro/26/ffffff/podium-with-speaker.png'/><a href='#'> Creators</a>
                    </li>
                    <li onclick='deleteVideoClicked();'>
                        <img src='https://img.icons8.com/metro/26/ffffff/delete-property.png'/><a href='#'> Delete Video</a>
                    </li>
                    <li onclick='newsClicked();'>
                        <img src='https://img.icons8.com/metro/26/ffffff/google-news.png'/><a href='#'> Announcements</a>
                    </li>
                    <li onclick='documentationClicked();'>
                        <img src='https://img.icons8.com/metro/26/ffffff/document.png'/><a href='#'> Documentation</a>
                    </li>
                </ul>
            </aside>
            <nav class='navbar navbar-expand-md'>
                <div class='container-fluid mx-2'>
                    <div class='navbar-header'>
                        AcadGenix
                    </div>
                    <div class='collapse navbar-collapse' id='toggle-navbar'>
                        <ul class='navbar-nav ms-auto'>
                            <li class='nav-item'>
                                <button type='button' onclick='signOut();' class='btn btn-outline-light'>Logout</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <section id='wrapper-dashboard'>
                <div class='p-4'>
                    <div class='Heading'>
                        <div class='content rounded-3 p-3'>
                            <h1 class='fs-3'>Welcome to Dashboard</h1>
                            <p class='mb-0'>Hello Admin, welcome to AcadGenix platform's dashboard!</p>
                        </div>
                    </div>
                    <section class='statistics mt-4'>
                        <div class='row'>
                            <div class='col-lg-4'>
                                <div class='box d-flex rounded-2 align-items-center mb-4 mb-lg-0 p-3'>
                                    <div class='icon' style='background-color:#0d6efd;'>
                                        <img src='https://img.icons8.com/metro/26/ffffff/podium-with-speaker.png' alt='Faculty Icon' title='Faculty'/>
                                    </div>
                                    <div class='ms-3'>
                                        <div class='d-flex align-items-center'>
                                            <h3 class='mb-0'>$facultyCount</h3> <span class='d-block ms-2'>Faculty</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-lg-4'>
                                <div class='box d-flex rounded-2 align-items-center mb-4 mb-lg-0 p-3'>
                                    <div class='icon' style='background-color:#dc3545;'>
                                        <svg xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' width='26' height='26' viewBox='0 0 172 172' style=' fill:#000000;margin:auto;'><g fill='none' fill-rule='nonzero' stroke='none' stroke-width='1' stroke-linecap='butt' stroke-linejoin='miter' stroke-miterlimit='10' stroke-dasharray='' stroke-dashoffset='0' font-family='none' font-weight='none' font-size='none' text-anchor='none' style='mix-blend-mode: normal'><path d='M0,172v-172h172v172z' fill='none'></path><g fill='#ffffff'><path d='M3.92788,0c-1.98978,0 -3.92788,1.9381 -3.92788,3.92788v29.14904h52.92308v-29.14904c0,-1.98978 -1.9381,-3.92788 -3.92788,-3.92788zM123.625,0c-2.63581,0 -4.54808,1.9381 -4.54808,3.92788v29.14904h52.92308v-29.14904c0,-1.98978 -1.9381,-3.92788 -3.92788,-3.92788zM70.70192,26.46154c-2.63581,0 -4.54808,1.9381 -4.54808,3.92788v15.91827h39.69231v-15.91827c0,-1.98978 -1.9381,-3.92788 -3.92788,-3.92788zM0,39.69231v99.23077h52.92308v-99.23077zM119.07692,39.69231v105.84615h52.92308v-105.84615zM19.84615,52.92308h13.23077v72.76923h-13.23077zM66.15385,52.92308v99.23077h39.69231v-99.23077zM138.92308,52.92308h13.23077v79.38462h-13.23077zM79.38462,72.76923h13.23077v59.53846h-13.23077zM0,145.53846v22.53365c0,1.98978 1.9381,3.92788 3.92788,3.92788h44.44712c2.63582,0 4.54808,-1.9381 4.54808,-3.92788v-22.53365zM119.07692,152.15385v15.91827c0,2.63582 1.9381,3.92788 3.92788,3.92788h44.44712c2.63582,0 3.92788,-1.9381 3.92788,-3.92788v-15.91827zM66.15385,158.76923v9.30288c0,2.63582 1.9381,3.92788 3.92788,3.92788h31.21635c2.63582,0 3.92788,-1.9381 3.92788,-3.92788v-9.30288z'></path></g></g></svg>
                                    </div>
                                    <div class='ms-3'>
                                        <div class='d-flex align-items-center'>
                                            <h3 class='mb-0'>$coursesCount</h3> <span class='d-block ms-2'>Courses</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-lg-4'>
                                <div class='box d-flex rounded-2 align-items-center p-3'>
                                    <div class='icon' style='background-color:#198754;'>
                                        <img src='https://img.icons8.com/metro/26/ffffff/students.png' alt='Student Icon' title='Students'/>
                                    </div>
                                    <div class='ms-3'>
                                        <div class='d-flex align-items-center'>
                                            <h3 class='mb-0'>$studentCount</h3> <span class='d-block ms-2'>$studentCountPhrase</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class='admins mt-4'>
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class='box'>
                                    <h4>Developers:</h4>
                                    <div class='admin d-flex align-items-center rounded-2 p-3 mb-4'>
                                        <div class='img'>
                                            <img class='img-fluid rounded-pill' width='75' height='75' src='assets/images/Developers/KrishnaPaanchajanya.jpg' alt='admin'/>
                                        </div>
                                        <div class='ms-3'>
                                            <h3 class='fs-5 mb-1'>K V Krishna Paanchajanya</h3>
                                            <p class='mb-0'>Regn. No. 19BCS063</p>
                                        </div>
                                    </div>
                                    <div class='admin d-flex align-items-center rounded-2 p-3 mb-4'>
                                        <div class='img'>
                                            <img class='img-fluid rounded-pill' width='75' height='75' src='assets/images/Developers/KarthikSajjan.jpg' alt='admin'/>
                                        </div>
                                        <div class='ms-3'>
                                            <h3 class='fs-5 mb-1'>Karthik Sajjan</h3>
                                            <p class='mb-0'>Regn. No. 19BCS049</p>
                                        </div>
                                    </div>
                                    <div class='admin d-flex align-items-center rounded-2 p-3 mb-4'>
                                        <div class='img'>
                                            <img class='img-fluid rounded-pill' width='75' height='75' src='assets/images/Developers/DeepakChowdary.jpg' alt='admin'/>
                                        </div>
                                        <div class='ms-3'>
                                            <h3 class='fs-5 mb-1'>K Deepak Chowdary</h3>
                                            <p class='mb-0'>Regn. No. 19BCS050</p>
                                        </div>
                                    </div>
                                    <div class='admin d-flex align-items-center rounded-2 p-3'>
                                        <div class='img'>
                                            <img class='img-fluid rounded-pill' width='75' height='75' src='assets/images/Developers/Santeswar.jpg' alt='admin'/>
                                        </div>
                                        <div class='ms-3'>
                                            <h3 class='fs-5 mb-1'>K V J Santeswar</h3>
                                            <p class='mb-0'>Regn. No. 19BCS053</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class='box'>
                                    <h4><pre style='margin-bottom:12px;'> </pre></h4>
                                    <div class='admin d-flex align-items-center rounded-2 p-3 mb-4'>
                                        <div class='img'>
                                            <img class='img-fluid rounded-pill' width='75' height='75' src='assets/images/Developers/ManojSahithReddy.jpg' alt='admin'/>
                                        </div>
                                        <div class='ms-3'>
                                            <h3 class='fs-5 mb-1'>V Manoj Sahith Reddy</h3>
                                            <p class='mb-0'>Regn. No. 19BCS110</p>
                                        </div>
                                    </div>
                                    <div class='admin d-flex align-items-center rounded-2 p-3 mb-4'>
                                        <div class='img'>
                                            <img class='img-fluid rounded-pill' width='75' height='75' src='assets/images/Developers/Dhyan.jpg' alt='admin'/>
                                        </div>
                                        <div class='ms-3'>
                                            <h3 class='fs-5 mb-1'>Dhyan M G</h3>
                                            <p class='mb-0'>Regn. No. 19BCS038</p>
                                        </div>
                                    </div>
                                    <div class='admin d-flex align-items-center rounded-2 p-3 mb-4'>
                                        <div class='img'>
                                            <img class='img-fluid rounded-pill' width='75' height='75' src='assets/images/Developers/KashaSingh.jpg' alt='admin'/>
                                        </div>
                                        <div class='ms-3'>
                                            <h3 class='fs-5 mb-1'>Kasha Singh</h3>
                                            <p class='mb-0'>Regn. No. 19BCS051</p>
                                        </div>
                                    </div>
                                    <div class='admin d-flex align-items-center rounded-2 p-3'>
                                        <div class='img'>
                                            <img class='img-fluid rounded-pill' width='75' height='75' src='assets/images/Developers/Sabiha.jpg' alt='admin'/>
                                        </div>
                                        <div class='ms-3'>
                                            <h3 class='fs-5 mb-1'>Sabiha H B</h3>
                                            <p class='mb-0'>Regn. No. 19BCS094</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </section>
            <section id='wrapper-academicCalendar-current' style='display:none;'>
                <div class='p-4'>
                    <div class='Heading'>
                        <div class='content rounded-3 p-3'>
                            <h1 class='fs-3'>Current Academic Calendar</h1>
                            <p class='mb-0'><a target='_blank' href='$currentAcademicCalendar'>Download here</a></p>
                        </div>
                    </div>
                    <div class='mt-4'>
                        <div class='Heading'>
                            <div class='content rounded-3 p-3'>
                                <h1 class='fs-3'>Change Academic Calendar</h1>
                                <div id='uploadAcadCalForm'>
                                    <form action='utilities/uploadCal.php' target='dummyFrame' method='POST' enctype='multipart/form-data' class='uploadFormActual'>
                                        <input type='text' class='CalendarTitle' name='CalendarTitle' placeholder='Title' maxlength='50' required>
                                        <select class='form-select' aria-label='Default select example' name='uploadOption'>
                                            <option value='new' selected>Calendar for a new academic year</option>
                                            <option value='change'>Change Current Academic Calendar</option>
                                        </select>
                                        <div class='chooseCalendar'>
                                            <input type='file' class='ChooseCalendarFile' id='fileCal' name='file'>
                                            <label for='fileCal' id='fileCalLabel'>Choose a file (PDF only)</label>
                                        </div>
                                        <input type='submit' class='upload' name='uploadButton' value='Upload'>
                                        <p id='uploadWarningCal'></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id='wrapper-academicCalendar-archives' style='display:none;'>
                <div class='p-4'>
                    $archive
                </div>
            </section>
            <section id='wrapper-timeTables' style='display:none;'>
                <div class='p-4'>
                    <div class='Heading'>
                        <div class='content rounded-3 p-3'>
                            <h1 class='fs-3'>Time Tables (1st Year)</h1>
                            <p class='mb-0'><a target='_blank' href='$schedule1'>Download here</a></p>
                            <h1 class='fs-3'>Time Tables (2nd Year)</h1>
                            <p class='mb-0'><a target='_blank' href='$schedule2'>Download here</a></p>
                            <h1 class='fs-3'>Time Tables (3rd Year)</h1>
                            <p class='mb-0'><a target='_blank' href='$schedule3'>Download here</a></p>
                            <h1 class='fs-3'>Time Tables (4th Year)</h1>
                            <p class='mb-0'><a target='_blank' href='$schedule4'>Download here</a></p>
                            <h1 class='fs-3' style='margin-top:10px;'>Change Time Table</h1>
                            <div id='uploadTimeTableForm'>
                                <form action='utilities/uploadTimeTable.php' target='dummyFrame' method='POST' enctype='multipart/form-data' class='uploadFormActual'>
                                    <div class='chooseSchedule'>
                                        <input type='file' class='ChooseScheduleFile' id='scheduleFile' name='file'>
                                        <label for='scheduleFile' id='scheduleFileLabel'>Choose a file (PDF only)</label>
                                    </div>
                                    <div class='form-floating'>
                                        <select class='form-select' id='floatingSelect4' aria-label='Floating label select example' name='year' required>
                                            <option value='1' selected>1st Year</option>
                                            <option value='2'>2nd Year</option>
                                            <option value='3'>3rd Year</option>
                                            <option value='4'>4th Year</option>
                                        </select>
                                        <label for='floatingSelect4'>Change timetable of year:</label>
                                    </div>
                                    <input type='submit' class='upload' name='uploadButton' value='Upload'>
                                    <p id='uploadWarningSchedule'></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id='wrapper-instituteCourses' style='display:none;'>
                <div class='p-4'>
                    <div class='Heading'>
                        <div class='content rounded-3 p-3'>
                            <div id='uploadCourseForm'>
                                <form action='utilities/uploadCourse.php' target='dummyFrame' method='POST' enctype='multipart/form-data' class='uploadFormActual'>
                                    <div class='form-floating'>
                                        <select class='form-select' id='floatingSelect1' aria-label='Floating label select example' name='courseOperation' required>
                                            <option value='Add' selected>Add a new course</option>
                                            <option value='Modify'>Change syllabus of an existing course</option>
                                            <option value='Remove'>Remove an existing course</option>
                                        </select>
                                        <label for='floatingSelect1'>Operation:</label>
                                    </div>
                                    <input type='text' class='courseName' name='courseName' placeholder='Name' maxlength='50' required>
                                    <input type='text' class='courseCode' name='courseCode' placeholder='Course Code' maxlength='10' required>
                                    <div class='chooseCourse'>
                                        <input type='file' class='ChooseCourseFile' id='courseFile' name='file'>
                                        <label for='courseFile' id='courseFileLabel'>Choose a file (PDF only)</label>
                                    </div>
                                    <div class='form-floating' id='typeDropdown'>
                                        <select class='form-select' id='floatingSelect2' aria-label='Floating label select example' name='courseType' required>
                                            <option value='Regular' selected>Regular</option>
                                            <option value='Elective'>Elective</option>
                                        </select>
                                        <label for='floatingSelect2'>Course Type:</label>
                                    </div>
                                    <input type='submit' class='upload' name='uploadButton' value='Upload'>
                                    <p id='uploadWarningCourse'></p>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class='table-fill'>
                        <thead>
                            <tr>
                                <th>Course Name</th>
                                <th>Course Code</th>
                                <th>Course Type</th>
                                <th>Syllabus</th>
                            </tr>
                        </thead>
                        <tbody class='table-hover'>
                            $coursesTable
                        </tbody>
                    </table>
                </div>
            </section>
            <section id='wrapper-additionalCourses' style='display:none;'>
                <div class='p-4'>
                    <div class='Heading'>
                        <div class='content rounded-3 p-3'>
                            <div id='uploadAddCourseForm'>
                                <form action='utilities/uploadAddCourse.php' target='dummyFrame' method='POST' enctype='multipart/form-data' class='uploadFormActual'>
                                    <div class='form-floating'>
                                        <select class='form-select' id='floatingSelect5' aria-label='Floating label select example' name='courseOperation' required>
                                            <option value='Add' selected>Add a new course</option>
                                            <option value='Modify'>Change syllabus of an existing course</option>
                                            <option value='Remove'>Remove an existing course</option>
                                        </select>
                                        <label for='floatingSelect5'>Operation:</label>
                                    </div>
                                    <input type='text' class='courseName' name='courseName' placeholder='Name' maxlength='50' required>
                                    <input type='text' class='courseCode' name='courseCode' placeholder='Course Code' maxlength='10' required>
                                    <div class='chooseCourse'>
                                        <input type='file' class='ChooseCourseFile' id='addCourseFile' name='file'>
                                        <label for='addCourseFile' id='addCourseFileLabel'>Choose a file (PDF only)</label>
                                    </div>
                                    <input type='submit' class='upload' name='uploadButton' value='Upload'>
                                    <p id='uploadWarningAddCourse'></p>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class='table-fill'>
                        <thead>
                            <tr>
                                <th>Course Name</th>
                                <th>Course Code</th>
                                <th>Syllabus</th>
                            </tr>
                        </thead>
                        <tbody class='table-hover'>
                            $addCoursesTable
                        </tbody>
                    </table>
                </div>
            </section>
            <section id='wrapper-faculty' style='display:none;'>
                <div class='p-4'>
                    <div class='Heading'>
                        <div class='content rounded-3 p-3'>
                            <h1 class='fs-3'>Update Curriculum Vitae</h1>
                        </div>
                    </div>
                    <div class='Heading' style='margin-top:24px;'>
                        <div class='content rounded-3 p-3'>
                            <div id='addFacultyForm'>
                                <form action='utilities/addFaculty.php' target='dummyFrame' method='POST' enctype='multipart/form-data' class='uploadFormActual'>
                                    <input type='email' class='facultyMail' name='facultyMail' placeholder='Institute Mail ID' maxlength='60' required>
                                    <div class='chooseCV'>
                                        <input type='file' class='ChooseCVFile' id='CVFile' name='file' required>
                                        <label for='CVFile' id='CVFileLabel'>Choose CV (PDF only)</label>
                                    </div>
                                    <input type='submit' class='upload' name='uploadButton' value='Upload'>
                                    <p id='uploadWarningCV'></p>
                                </form>
                            </div>
                        </div>
                    </div>
                    $faculty
                </div>
            </section>
            <section id='wrapper-delete-content' style='display:none;'>
                <div class='p-4'>
                    <div class='Heading'>
                        <div class='content rounded-3 p-3'>
                            <h1 class='fs-3'>Delete Course Content</h1>
                        </div>
                    </div>
                    <div class='Heading' style='margin-top:24px;'>
                        <div class='content rounded-3 p-3'>
                            <div id='deleteVideoForm'>
                                <form action='utilities/deleteVideo.php' target='dummyFrame' method='POST' enctype='multipart/form-data' class='uploadFormActual'>
                                    <input type='text' class='videoID' name='videoID' placeholder='Enter Video URL' required>
                                    <input type='submit' class='upload' value='Delete (permanent action)'>
                                    <p id='deleteVideoWarning'></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id='wrapper-news' style='display:none;'>
                <div class='p-4'>
                    <div class='Heading'>
                        <div class='content rounded-3 p-3'>
                            <h1 class='fs-3'>$title1</h1>
                            <p class='mb-0'><a target='_blank' href='$link1'>Click here</a></p>
                            <h1 class='fs-3'>$title2</h1>
                            <p class='mb-0'><a target='_blank' href='$link2'>Click here</a></p>
                            <h1 class='fs-3'>$title3</h1>
                            <p class='mb-0'><a target='_blank' href='$link3'>Click here</a></p>
                            <h1 class='fs-3'>$title4</h1>
                            <p class='mb-0'><a target='_blank' href='$link4'>Click here</a></p>
                            <h1 class='fs-3'>$title5</h1>
                            <p class='mb-0'><a target='_blank' href='$link5'>Click here</a></p>
                            <h1 class='fs-3' style='margin-top:10px;'>Change News</h1>
                            <div id='uploadNewsForm'>
                                <form action='utilities/uploadNews.php' target='dummyFrame' method='POST' enctype='multipart/form-data' class='uploadFormActual'>
                                    <input type='text' class='newsTitle' name='newsTitle' placeholder='Title' maxlength='30' required>
                                    <div class='chooseNews'>
                                        <input type='file' class='ChooseNewsFile' id='newsFile' name='file'>
                                        <label for='newsFile' id='newsFileLabel'>Choose a file (PDF/PHP/HTML)</label>
                                    </div>
                                    <div class='form-floating'>
                                        <select class='form-select' id='floatingSelect3' aria-label='Floating label select example' name='newsNo' required>
                                            <option value='1' selected>News 1</option>
                                            <option value='2'>News 2</option>
                                            <option value='3'>News 3</option>
                                            <option value='4'>News 4</option>
                                            <option value='5'>News 5</option>
                                        </select>
                                        <label for='floatingSelect3'>Serial No.:</label>
                                    </div>
                                    <input type='submit' class='upload' name='uploadButton' value='Upload'>
                                    <p id='uploadWarningNews'></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section id='wrapper-documentation' style='display:none;'>
                Documentation
            </section>
            <iframe name='dummyFrame' id='dummyFrame' style='display: none;'></iframe>
            <div id='loadingModal' data-backdrop='static' data-keyboard='false' style='display:none;'>
                <img src='assets/images/uploading.gif' title='Please Wait for a while!' alt='Loading Spinner GIF'/>
            </div>
        </body>
        </html>";
    }
}
?>