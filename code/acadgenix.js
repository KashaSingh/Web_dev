$(document).ready(function()
{
    var loc = window.location.toString();
    if(loc.split(":")[0]=="http")
    {
        window.location = [loc.slice(0, 4), 's', loc.slice(4)].join('');
    }
    if(window.history.replaceState )
    {
        window.history.replaceState( null, null, window.location.href );
    }
    if(!sessionStorage.length) {
        localStorage.setItem('getSessionStorage', Date.now());
    };
    window.addEventListener('storage', function(event){
        if (event.key == 'getSessionStorage')
        {
            localStorage.setItem('sessionStorage', JSON.stringify(sessionStorage));
            localStorage.removeItem('sessionStorage');
        }
        else if (event.key=='sessionStorage'&&!sessionStorage.length)
        {
            var data = JSON.parse(event.newValue);
            for(key in data)
            {
                sessionStorage.setItem(key, data[key]);
            }
            if(window.sessionStorage.ID!=null)
            {
                window.location.reload();
            }
            else
            {
                $.post("utilities/destroySession.php");
            }
        }
    });
    var userName = window.sessionStorage.Name;
    var userMail = window.sessionStorage.Email;
    var picURL = window.sessionStorage.ImageURL;
    if(window.sessionStorage.Role=='Student')
    {
        $("#search-results .faq-container .faq .includeContent").show();
    }
    else if(window.sessionStorage.Role=='Faculty')
    {
        $("#learn-option").hide();
        $("#exam-writing-option").hide();
        $("#exam-dashboard-option").show();
        $("#learn-option2").hide();
        $("#exam-writing-option2").hide();
        $("#exam-dashboard-option2").show();
    }
    if(!userName)
    {
        userName="Guest User";
        userMail="Please Sign In";
        picURL="https://img.icons8.com/fluent-systems-filled/48/000000/user.png";
        $("#content .SignedIn").hide();
        $(".NotSignedIn").show();
        $("#uploadFormActual").attr("style", "display:none;");
        $(".academic-calendar").hide();
        $(".time-table").hide();
        $(".latest-news").hide();
    }
    else if(window.sessionStorage.Role=='Student')
    {
        const currentDate = new Date();
        correction = (currentDate.getMonth()<6)?0:1;
        year = Math.abs(((currentDate.getFullYear())%100)-parseInt(userMail.substring(0,2)))+correction;
        if(year==1)
        {
            $("#schedule a").attr("href",$("#schedule a").attr("href").split("?")[0]);
        }
        else if(year==2)
        {
            $("#schedule a").attr("href",$("#schedule a").attr("href").split("?")[1]);
        }
        else if(year==3)
        {
            $("#schedule a").attr("href",$("#schedule a").attr("href").split("?")[2]);
        }
        else if(year==4)
        {
            $("#schedule a").attr("href",$("#schedule a").attr("href").split("?")[3]);
        }
        else
        {
            $(".time-table").hide();
        }
    }
    else
    {
        $(".time-table").hide();
    }
    $("#userName").text(userName);
    $("#userMail").text(userMail);
    $("#profilePicture").attr("src",picURL);
    $("#profilePicture").attr("title",userName);
    $('#navIcon').click(function(){
        $(this).toggleClass('open');
        var nav = $("#sideNav");
        var navDisabling = $("#sideNavDisabling");
        if(nav.is(":visible"))
        {
            nav.hide();
            navDisabling.hide();
        }
        else
        {
            nav.show();
            navDisabling.show();
        }
    });
    $('#dateButton').click(function(){
        $.post("utilities/setDateButton.php");
        window.location.reload();
    });
    $('#popularButton').click(function(){
        $.post("utilities/setPopularButton.php");
        window.location.reload();
    });
    let menuItems = document.querySelectorAll('#sideNav .nav-bar ul li');
    const navItemClick = function() {
        let element = this;
        menuItems.forEach(item => {
            item.classList.remove('active');
        });
        if($(element).attr('id')=="home-option")
        {
            window.location = 'https://acadgenix.ga/index.php';
        }
        else if($(element).attr('id')=="learn-option")
        {
            window.location = "https://acadgenix.ga/learn.php";
        }
        else if($(element).attr('id')=="create-content-option")
        {
            window.location = "https://acadgenix.ga/createCourse.php";
        }
        else if($(element).attr('id')=="exam-dashboard-option")
        {
            window.location = "https://acadgenix.ga/facultyAssignmentDashboard.php";
        }
        else if($(element).attr('id')=="exam-writing-option")
        {
            window.location = "https://acadgenix.ga/assignment.php";
        }
        element.classList.add('active');
    }
    menuItems.forEach(item => {
        item.addEventListener('click', navItemClick);
    });
    let menuItems2 = document.querySelectorAll('body > .nav-bar ul li');
    const navItemClick2 = function() {
        let element = this;
        menuItems2.forEach(item => {
            item.classList.remove('active');
        });
        if($(element).attr('id')=="home-option2")
        {
            window.location = 'https://acadgenix.ga/index.php';
        }
        else if($(element).attr('id')=="learn-option2")
        {
            window.location = "https://acadgenix.ga/learn.php";
        }
        else if($(element).attr('id')=="create-content-option2")
        {
            window.location = "https://acadgenix.ga/createCourse.php";
        }
        else if($(element).attr('id')=="exam-dashboard-option2")
        {
            window.location = "https://acadgenix.ga/facultyAssignmentDashboard.php";
        }
        else if($(element).attr('id')=="exam-writing-option2")
        {
            window.location = "https://acadgenix.ga/assignment.php";
        }
        element.classList.add('active');
    }
    menuItems2.forEach(item => {
        item.addEventListener('click', navItemClick2);
    });
    $('#uploadIcon').click(function(){
        var uploadForm = $("#uploadForm");
        $("#uploadWarning").text("");
        $("#uploadForm form").trigger("reset");
        if(uploadForm.css("display")=="none")
        {
            uploadForm.show();
        }
        else
        {
            uploadForm.hide();
        }
    });
    $("#file").change(function() {
        var filename = $('input[type=file]').val().split('\\').pop();
        $("#label").text(filename);
        if(filename=="")
        {
            $("#label").text("Choose a Video File");
        }
    });
    $("#uploadFormActual").submit(function(){
        $("#uploadWarning").text("");
        $("#loadingModal").show();
        const interval = setInterval(function() {
            if($("#uploadWarning").text()!="")
            {
                $("#loadingModal").hide();
                clearInterval(interval);
                if($("#uploadWarning").text()=="Upload Successful!")
                {
                    $("#uploadForm").hide();
                    window.location.reload();
                }
            }
        }, 500);
    });
    var playPromise;
    const allVideos = document.querySelectorAll('.video');
    allVideos.forEach((v) => {v.addEventListener('mouseover', () => {
          const video = v.querySelector('video');
          playPromise = video.play();
        });
        v.addEventListener('mouseleave', () => {
            const video = v.querySelector('video');
            if (playPromise !== undefined){
                video.pause();
            }
        });
    });
    const toggles = document.querySelectorAll(".faq-toggle");
    toggles.forEach((toggle) => {
        toggle.addEventListener("click", () => {
            toggle.parentNode.classList.toggle("active");
        });
    });
    $("#search-results .faq-container .faq").click(function(event){
        if(!(event.target.classList.contains("includeContent")||event.target.classList.contains("faq-toggle")||event.target.classList.contains("fas")))
        {
            $loc = ("https://acadgenix.ga/content.php".concat("?instanceID=")).concat($(this).find("#instanceID").text());
            window.setTimeout(function(){window.location =  $loc;}, 500);
        }
    });
    $("#search-results .faq-container .faq .includeContent").click(function(){
        $.post("utilities/includeContent.php",{instanceID: $(this).parent().find("#instanceID").text()});
        var k = $(this).parent().find(".contentAdded");
        k.show();
        setTimeout(function(){k.hide();}, 2000);
    });
    $("#create-course #floatingSelect2").change(function(){
        var selected = $(this).children("option:selected").val();
        if(selected=="Add")
		{
            $("#create-course form").attr("action", "utilities/addCourseInstance.php");
            $("#create-course .upload").val("Add");
		}
		else if(selected=="Delete")
		{
            $("#create-course form").attr("action", "utilities/removeCourseInstance.php");
            $("#create-course .upload").val("Delete (permanent action)");
		}
    });
    $("#create-course form").submit(function(){
        $("#uploadWarningAddCourse").text("");
        const interval = setInterval(function() {
            if($("#uploadWarningAddCourse").text()!="This course was never created!"&&$("#uploadWarningAddCourse").text()!="Course Already Created!"&&$("#uploadWarningAddCourse").text()!="")
            {
                location.reload();
                clearInterval(interval);
            }
        }, 250);
    });
    $("#create-course .faq-container .faq .faq-title").click(function(){
        window.location = ("https://acadgenix.ga/content.php".concat("?instanceID=")).concat($(this).parent().find("#instanceID").text());
    });
    $("#deleteVideoForm form").submit(function(event){
        event.preventDefault();
        var intension = confirm("Are you sure you want to delete this video? This is a permanent action!");
        if(!intension)
        {
            return;
        }
        else
        {
            $.post("utilities/deleteVideo.php", {videoID: $("#floatingSelect2").val()});
            window.setTimeout(function(){window.location.reload();}, 2000);
        }
    });
    $("#learn .faq-container .faq").click(function(event){
        if(!(event.target.classList.contains("faq-toggle")||event.target.classList.contains("fas")))
        {
            $loc = ("https://acadgenix.ga/content.php".concat("?instanceID=")).concat($(this).find("#instanceID").text());
            window.setTimeout(function(){window.location =  $loc;}, 500);
        }
    });
    if(window.location=="https://acadgenix.ga/facultyAssignmentDashboard.php")
    {
        $currentDate = new Date();
        $timestamp1 = ($currentDate.getFullYear()<10?"0"+$currentDate.getFullYear().toString():$currentDate.getFullYear().toString())+"-"+($currentDate.getMonth()+1<10?"0"+($currentDate.getMonth()+1).toString():($currentDate.getMonth()+1).toString())+"-"+($currentDate.getDate()<10?"0"+$currentDate.getDate().toString():$currentDate.getDate().toString())+"T"+($currentDate.getHours()<10?"0"+$currentDate.getHours().toString():$currentDate.getHours().toString())+":"+($currentDate.getMinutes()<10?"0"+$currentDate.getMinutes().toString():$currentDate.getMinutes().toString());
        $timestamp2 = ($currentDate.getFullYear()<10?"0"+$currentDate.getFullYear().toString():$currentDate.getFullYear().toString())+"-"+($currentDate.getMonth()+1<10?"0"+($currentDate.getMonth()+1).toString():($currentDate.getMonth()+1).toString())+"-"+($currentDate.getDate()+1<10?"0"+($currentDate.getDate()+1).toString():($currentDate.getDate()+1).toString())+"T"+($currentDate.getHours()<10?"0"+$currentDate.getHours().toString():$currentDate.getHours().toString())+":"+($currentDate.getMinutes()<10?"0"+$currentDate.getMinutes().toString():$currentDate.getMinutes().toString());
        $("#examStartTime").val($timestamp1);
        $("#examEndTime").val($timestamp2);
        if($("#pickAssignmentName select").html().trim()!="")
        {
            var $modifyStartTime= $("#".concat($("#exam-dashboard #floatingSelect3").children("option:selected").val())).find(".examStart").text().split(" ")[3].concat("T".concat($("#".concat($("#exam-dashboard #floatingSelect3").children("option:selected").val())).find(".examStart").text().split(" ")[4]));
            var $modifyEndTime= $("#".concat($("#exam-dashboard #floatingSelect3").children("option:selected").val())).find(".examEnd").text().split(" ")[3].concat("T".concat($("#".concat($("#exam-dashboard #floatingSelect3").children("option:selected").val())).find(".examEnd").text().split(" ")[4]));
        }
    }
    $("#exam-dashboard #floatingSelect3").prop('disabled',true);
    $("#exam-dashboard #floatingSelect3").change(function(){
        var selected = $(this).children("option:selected").val();
        $modifyStartTime= $("#".concat(selected)).find(".examStart").text().split(" ")[3].concat("T".concat($("#".concat(selected)).find(".examStart").text().split(" ")[4]));
        $modifyEndTime= $("#".concat(selected)).find(".examEnd").text().split(" ")[3].concat("T".concat($("#".concat(selected)).find(".examEnd").text().split(" ")[4]));
        $("#examStartTime").val($modifyStartTime);
        $("#examEndTime").val($modifyEndTime);
    });
    $("#exam-dashboard #floatingSelect2").change(function(){
        var selected = $(this).children("option:selected").val();
        if(selected=="Add")
		{
            $("#assignmentDetails").attr('action','utilities/createExam.php');
            $("#exam-dashboard #floatingSelect3").prop('disabled',true);
            $("#exam-dashboard #pickAssignmentName").hide();
            $("#exam-dashboard #assignmentName").prop('disabled',false);
            $("#exam-dashboard #assignmentName").show();
            $("#exam-dashboard #startTimeContainer").prop('disabled',false);
            $("#exam-dashboard #startTimeContainer").show();
            $("#exam-dashboard #endTimeContainer").prop('disabled',false);
            $("#exam-dashboard #endTimeContainer").show();
            $("#examStartTime").val($timestamp1);
            $("#examEndTime").val($timestamp2);
            $("#exam-dashboard #pickCourseContainer").prop('disabled',false);
            $("#exam-dashboard #pickCourseContainer").show();
            $("#qTotal").prop('disabled',false);
            $("#qTotal").show();
            $("#generateBtn").val("Generate Form");
		}
		else if(selected=="Modify")
		{
            $("#assignmentDetails").attr('action','utilities/modifyExam.php');
			$("#exam-dashboard #assignmentName").prop('disabled',true);
            $("#exam-dashboard #assignmentName").hide();
            $("#qTotal").prop('disabled',true);
            $("#qTotal").hide();
            $("#exam-dashboard #floatingSelect3").prop('disabled',false);
            $("#exam-dashboard #pickAssignmentName").show();
            $("#exam-dashboard #startTimeContainer").prop('disabled',false);
            $("#exam-dashboard #startTimeContainer").show();
            $("#exam-dashboard #endTimeContainer").prop('disabled',false);
            $("#exam-dashboard #endTimeContainer").show();
            $("#examStartTime").val($modifyStartTime);
            $("#examEndTime").val($modifyEndTime);
            $("#exam-dashboard #pickCourseContainer").prop('disabled',false);
            $("#exam-dashboard #pickCourseContainer").show();
            $("#generateBtn").val("Modify");
		}
		else if(selected=="Cancel")
		{
            $("#assignmentDetails").attr('action','utilities/cancelExam.php');
			$("#exam-dashboard #assignmentName").prop('disabled',true);
            $("#exam-dashboard #assignmentName").hide();
            $("#exam-dashboard #startTimeContainer").prop('disabled',true);
            $("#exam-dashboard #startTimeContainer").hide();
            $("#exam-dashboard #endTimeContainer").prop('disabled',true);
            $("#exam-dashboard #endTimeContainer").hide();
            $("#exam-dashboard #pickCourseContainer").prop('disabled',true);
            $("#exam-dashboard #pickCourseContainer").hide();
            $("#qTotal").prop('disabled',true);
            $("#qTotal").hide();
            $("#exam-dashboard #floatingSelect3").prop('disabled',false);
            $("#exam-dashboard #pickAssignmentName").show();
            $("#generateBtn").val("Cancel");
		}
    });
    const qTotal = document.getElementById('qTotal');
    let pos;
    $("#assignmentDetails").submit(function(){
        $("#uploadWarningAssignment").text("");
        $("#loadingModal").show();
        const interval = setInterval(function() {
            if($("#uploadWarningAssignment").text()!="")
            {
                $("#loadingModal").hide();
                clearInterval(interval);
                if($("#uploadWarningAssignment").text()=="Exam Created Successfully!")
                {
                    pos = 1;
                    $("#quizConfig").hide();
                    $("#formContainer").show();
                    $("#uploadWarningAssignment").text("");
                }
                else if($("#uploadWarningAssignment").text()=="Exam Modified Successfully!")
                {
                    $("#uploadWarningAssignment").text("");
                    window.location.reload();
                }
                else if($("#uploadWarningAssignment").text()=="Exam Cancelled Successfully!")
                {
                    $("#uploadWarningAssignment").text("");
                    window.location.reload();
                }
            }
        }, 250);
    });
    $("#quizForm").submit(function(event){
        event.preventDefault();
        if(pos<qTotal.value)
        {
            if($("#correctValue").val().toUpperCase()!='A'&&$("#correctValue").val().toUpperCase()!='B'&&$("#correctValue").val().toUpperCase()!='C'&&$("#correctValue").val().toUpperCase()!='D')
            {
                $("#answerError").show();
                setTimeout(function(){$("#answerError").hide()},1000);
                return;
            }
            storeData();
            $("#questionAdded").show();
            setTimeout(function(){$("#questionAdded").hide()},1000);
        }
        else if (pos==qTotal.value)
        {
            if($("#correctValue").val().toUpperCase()!='A'&&$("#correctValue").val().toUpperCase()!='B'&&$("#correctValue").val().toUpperCase()!='C'&&$("#correctValue").val().toUpperCase()!='D')
            {
                $("#answerError").show();
                setTimeout(function(){$("#answerError").hide()},1000);
                return;
            }
            storeData();
            $("#questionAdded").show();
            setTimeout(function(){window.location.reload();},500);
        }
        else
        {
            alert('Display error');
        }
    });
    function storeData()
    {
        const title = document.getElementById('title').value;
        const input1 = document.getElementById('input1').value;
        const input2 = document.getElementById('input2').value;
        const input3 = document.getElementById('input3').value;
        const input4 = document.getElementById('input4').value;
        const value = document.getElementById('correctValue').value.toUpperCase();
        $.post("utilities/insertQuestion.php",{assignmentName:$("#assignmentName").val(), instanceID:$("#floatingSelect4").val(), question:title, optionA:input1, optionB:input2, optionC:input3, optionD:input4, answer:value});
        pos++;
        $("#quizForm").trigger("reset");
    }
    $("#exam-dashboard .faq-container .faq").click(function(event){
        if(!event.target.classList.contains("faq-toggle"))
        {
            if(event.target.classList.contains("delete"))
            {
                $.post("utilities/deleteQuestion.php", {questionID: event.target.id});
                setTimeout(function(){window.location.reload();}, 1000);
            }
            else if(event.target.classList.contains("addQuestion"))
            {
                $(this).find("form").show();
            }
            else if(event.target.classList.contains("releaseMarks"))
            {
                $.post("utilities/releaseMarks.php", {examID:$(this).find(".examID").val()});
            }
        }
    });
    $("#exam-dashboard .faq-container .faq form").submit(function(event){
        event.preventDefault();
        $.post("utilities/addQuestion.php", {examID: $(this).find(".examID").val(), question: $(this).find(".title").val(), optionA: $(this).find(".input1").val(), optionB: $(this).find(".input2").val(), optionC: $(this).find(".input3").val(), optionD: $(this).find(".input4").val(), answer:$(this).find(".correctValue").val()});
        setTimeout(function(){window.location.reload();}, 1500);
    });
    const examinations = document.querySelectorAll("#exam-dashboard .faq");
    examinations.forEach((exam) => {
        var examArr = $("#".concat(exam.id)).children();
        var $examStartTime = examArr[2].innerText.split(" ")[3].concat("T".concat(examArr[2].innerText.split(" ")[4]));
        var $examEndTime = examArr[3].innerText.split(" ")[3].concat("T".concat(examArr[3].innerText.split(" ")[4]));
        var currentDate = (new Date()).getTime();
        var startDate = Date.parse($examStartTime);
        var endDate = Date.parse($examEndTime);
        if(currentDate<startDate)
        {
            document.getElementById(exam.id).querySelector(".releaseMarks").style.display = "none";
            document.getElementById(exam.id).querySelector(".status").innerText = "Status: Yet to be Conducted";
        }
        else if(currentDate>=startDate && currentDate<=endDate)
        {
            document.getElementById(exam.id).querySelector(".releaseMarks").style.display = "none";
            deleteButtons = document.getElementById(exam.id).querySelectorAll(".delete");
            deleteButtons.forEach((deleteButton) => {
                deleteButton.style.display = "none";
            });
            document.getElementById(exam.id).querySelector(".addQuestion").style.display = "none";
            document.getElementById(exam.id).querySelector(".status").innerText = "Status: Being conducted";
        }
        else if(currentDate>endDate)
        {
            document.getElementById(exam.id).querySelector(".addQuestion").style.display = "none";
            document.getElementById(exam.id).querySelector(".status").innerText = "Status: Conducted";
        }
    });
    $("#exam-writing .faq-container .faq").click(function(event){
        if(!(event.target.classList.contains("faq-toggle")||event.target.classList.contains("fas")))
        {
            window.location = ("https://acadgenix.ga/exam.php".concat("?examID=")).concat($(this).find(".examID").text());
        }
    });
});