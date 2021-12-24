<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - AcadGenix</title>
    <meta charset="UTF-8">
    <meta name="google-signin-client_id" content="323147213234-bhtfbl7215l1h71lpgsapd9uju2jhgr5.apps.googleusercontent.com">
    <link type="text/css" href="loginPage.css" rel="stylesheet">
    <link type="image/icon type" href="../assets/images/PlainLogo.png" rel="icon">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body id="login-bg">
    <div id="back-panel">
        <div class="square" style="--i:0;"></div>
        <div class="square" style="--i:1;"></div>
        <div class="square" style="--i:2;"></div>
        <div class="square" style="--i:3;"></div>
        <div class="square" style="--i:4;"></div>
    </div>
    <div id="front-panel">
        <img id="LoginPage-logo" src="../assets/images/PlainLogoFull.png" alt="AcadGenix Logo" title="AcadGenix - an e-learning Platform" width="80%">
        <p id="text">
            Welcome to AcadGenix!<br/>This e-learning platform strives to provide content towards Employability knowledge comprising Communication, ICT, Self-Management, Entrepreneurial and Green Skills.
        </p>
        <div id="my-signin2" onclick='signInClicked();'></div>
        <script>
            window.sessionStorage.setItem('Clicked','false');
            function signInClicked()
            {
                window.sessionStorage.setItem('Clicked','true');
            }
            function onSuccess(googleUser) {
                if(window.sessionStorage.Clicked=='false')
                {
                    return;
                }
                var profile = googleUser.getBasicProfile();
                if(profile.getEmail()=="acadgenix@gmail.com")
                {
                    $.post("account.php", {ID:profile.getId(), name: profile.getName(), ImageURL: profile.getImageUrl(), email: profile.getEmail(), role:'Admin'});
                    setTimeout(function(){}, 2000);
                    window.location = "https://acadgenix.ga/admin.php";
                    return;
                }
                /*else if(profile.getEmail().split("@")[1]!="iiitdwd.ac.in")
                {
                    alert("Join using IIIT Dharwad Institute gmail account!");
                    signOut();
                    return;
                }*/
                window.sessionStorage.setItem('ID',profile.getId());
                window.sessionStorage.setItem('Name',profile.getName());
                window.sessionStorage.setItem('ImageURL',profile.getImageUrl());
                window.sessionStorage.setItem('Email',profile.getEmail());
                if(profile.getEmail().split("@")[0].match(/\d\d\S\S\S\d\d\d/))
                {
                    window.sessionStorage.setItem('Role', 'Student');
                }
                else
                {
                    window.sessionStorage.setItem('Role', 'Faculty');
                }
                $.post("account.php", {ID:profile.getId(), name: profile.getName(), ImageURL: profile.getImageUrl(), email: profile.getEmail(), role:window.sessionStorage.Role});
                window.location = "https://acadgenix.ga/index.php";
            }
            function onFailure(error) {
                console.log(error);
            }
            function renderButton() {
                gapi.signin2.render('my-signin2', {
                    'scope': 'profile email',
                    'width': 240,
                    'height': 50,
                    'longtitle': true,
                    'theme': 'dark',
                    'onsuccess': onSuccess,
                    'onfailure': onFailure
                });
            }
        </script>
        <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
        <div id="signOut" onclick='signOut();' style='display:none;'>
            <img src="https://img.icons8.com/clouds/50/000000/logout-rounded.png"/>
            <div>Sign Out of AcadGenix</div>
        </div>
        <script>
            if(window.sessionStorage.ID!=null)
            {
                $("#signOut").show();
            }
            function signOut() {
                var auth2 = gapi.auth2.getAuthInstance();
                auth2.signOut().then(function () {
                    $("#signOut").hide();
                    window.sessionStorage.clear();
                    $.post("../utilities/destroySession.php");
                    window.location="https://acadgenix.ga/index.php";
                });
            }
        </script>
    </div>
</body>
</html>