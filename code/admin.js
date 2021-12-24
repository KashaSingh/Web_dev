$(document).ready(function()
{
	function find(el, selector)
	{
		let finded;
		return (finded = el.querySelector(selector)) ? finded : null;
	}
	function siblings(el)
	{
		const siblings = [];
		for (let sibling of el.parentNode.children)
		{
			if (sibling !== el)
			{
				siblings.push(sibling);
			}
		}
		return siblings;
	}
	$('.sidebar .categories').click(function (event)
	{
		event.preventDefault();
		const item = event.target.closest('.has-dropdown');
		if(item)
		{
			item.classList.toggle('opened');
			siblings(item).forEach(sibling => {
				sibling.classList.remove('opened');
			});
			if (item.classList.contains('opened'))
			{
				const toOpen = find(item, '.sidebar-dropdown');
				if (toOpen)
				{
					toOpen.classList.add('active');
				}
				siblings(item).forEach(sibling => {
					const toClose = find(sibling, '.sidebar-dropdown');
					if (toClose)
					{
						toClose.classList.remove('active');
					}
				});
			}
			else
			{
				find(item, '.sidebar-dropdown').classList.toggle('active');
			}
		}
	});
	$(".uploadFormActual").submit(function(){
        $("#uploadWarningCal").text("");
		$("#uploadWarningSchedule").text("");
		$("#uploadWarningCourse").text("");
		$("#uploadWarningAddCourse").text("");
		$("#uploadWarningNews").text("");
		$("#uploadWarningCV").text("");
		$("#deleteVideoWarning").text("");
        $("#loadingModal").show();
        const interval = setInterval(function() {
            if($("#uploadWarningCal").text()!=""&&$("#wrapper-academicCalendar-current").attr("style")!="display:none;")
            {
                $("#loadingModal").hide();
                clearInterval(interval);
				if($("#uploadWarningCal").text()=="Upload Successful!")
				{
					window.location.reload();
				}
            }
			else if($("#uploadWarningSchedule").text()!=""&&$("#wrapper-timeTables").attr("style")!="display:none;")
            {
                $("#loadingModal").hide();
                clearInterval(interval);
				if($("#uploadWarningSchedule").text()=="Upload Successful!")
				{
					window.location.reload();
				}
            }
			else if($("#uploadWarningCV").text()!=""&&$("#wrapper-faculty").attr("style")!="display:none;")
            {
                $("#loadingModal").hide();
                clearInterval(interval);
				if($("#uploadWarningCV").text()=="Upload Successful!")
				{
					window.location.reload();
				}
            }
			else if($("#uploadWarningNews").text()!=""&&$("#wrapper-news").attr("style")!="display:none;")
            {
                $("#loadingModal").hide();
                clearInterval(interval);
				if($("#uploadWarningNews").text()=="Upload Successful!")
				{
					window.location.reload();
				}
            }
			else if($("#uploadWarningCourse").text()!=""&&$("#wrapper-instituteCourses").attr("style")!="display:none;")
            {
                $("#loadingModal").hide();
                clearInterval(interval);
				if($("#uploadWarningCourse").text()=="Upload Successful!")
				{
					window.location.reload();
				}
            }
			else if($("#uploadWarningAddCourse").text()!=""&&$("#wrapper-additionalCourses").attr("style")!="display:none;")
            {
                $("#loadingModal").hide();
                clearInterval(interval);
				if($("#uploadWarningAddCourse").text()=="Upload Successful!")
				{
					window.location.reload();
				}
            }
			else if($("#deleteVideoWarning").text()!=""&&$("#wrapper-delete-content").attr("style")!="display:none;")
            {
                $("#loadingModal").hide();
                clearInterval(interval);
				if($("#deleteVideoWarning").text()=="Upload Successful!")
				{
					window.location.reload();
				}
            }
        }, 500);
      });
	  $("#fileCal").change(function() {
        var filename = $("#fileCal").val().split('\\').pop();
        $("#fileCalLabel").text(filename);
        if(filename=="")
        {
            $("#fileCalLabel").text("Choose a file (PDF only)");
        }
    });
	$("#scheduleFile").change(function() {
        var filename = $("#scheduleFile").val().split('\\').pop();
        $("#scheduleFileLabel").text(filename);
        if(filename=="")
        {
            $("#scheduleFileLabel").text("Choose a file (PDF only)");
        }
    });
	$("#CVFile").change(function() {
        var filename = $("#CVFile").val().split('\\').pop();
        $("#CVFileLabel").text(filename);
        if(filename=="")
        {
            $("#CVFileLabel").text("Choose a file (PDF only)");
        }
    });
	$("#newsFile").change(function() {
        var filename = $("#newsFile").val().split('\\').pop();
        $("#newsFileLabel").text(filename);
        if(filename=="")
        {
            $("#newsFileLabel").text("Choose a file (PDF/PHP/HTML)");
        }
    });
	$("#courseFile").change(function() {
        var filename = $("#courseFile").val().split('\\').pop();
        $("#courseFileLabel").text(filename);
        if(filename=="")
        {
            $("#courseFileLabel").text("Choose a file (PDF only)");
        }
    });
	$("#addCourseFile").change(function() {
        var filename = $("#addCourseFile").val().split('\\').pop();
        $("#addCourseFileLabel").text(filename);
        if(filename=="")
        {
            $("#addCourseFileLabel").text("Choose a file (PDF only)");
        }
    });
	$("#floatingSelect1").change(function(){
        var selected = $(this).children("option:selected").val();
        if(selected=="Add")
		{
			$("#uploadCourseForm .courseName").removeAttr('disabled');
			$("#uploadCourseForm .chooseCourse input").removeAttr('disabled');
			$("#uploadCourseForm #typeDropdown select").removeAttr('disabled');
		}
		else if(selected=="Modify")
		{
			$("#uploadCourseForm .courseName").attr('disabled','disabled');
			$("#uploadCourseForm #typeDropdown select").attr('disabled','disabled');
			$("#uploadCourseForm .chooseCourse input").removeAttr('disabled');
		}
		else if(selected=="Remove")
		{
			$("#uploadCourseForm .courseName").attr('disabled','disabled');
			$("#uploadCourseForm #typeDropdown select").attr('disabled','disabled');
			$("#uploadCourseForm .chooseCourse input").attr('disabled','disabled');
		}
    });
	$("#floatingSelect5").change(function(){
        var selected = $(this).children("option:selected").val();
        if(selected=="Add")
		{
			$("#uploadAddCourseForm .courseName").removeAttr('disabled');
			$("#uploadAddCourseForm .chooseCourse input").removeAttr('disabled');
		}
		else if(selected=="Modify")
		{
			$("#uploadAddCourseForm .courseName").attr('disabled','disabled');
			$("#uploadAddCourseForm .chooseCourse input").removeAttr('disabled');
		}
		else if(selected=="Remove")
		{
			$("#uploadAddCourseForm .courseName").attr('disabled','disabled');
			$("#uploadAddCourseForm .chooseCourse input").attr('disabled','disabled');
		}
    });
});