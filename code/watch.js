$(document).ready(function()
{
    $searchParams = new URLSearchParams(window.location.search);
    window.sessionStorage.setItem('videoID',$searchParams.get('videoID'));
    $('#Likes .icon').click(function()
    {
        if(window.sessionStorage.ID==null)
        {
            alert("Please Sign In to appreciate this content!");
        }
        else if($("#Likes .icon img").attr("src")=="assets/images/notLikedIcon.png")
        {
            $.post("utilities/likeVideo.php",{videoID:window.sessionStorage.videoID,userID:window.sessionStorage.ID});
            $("#Likes .icon img").attr("src","assets/images/likeIcon.png");
            $("#Likes .count").text(parseInt($("#Likes .count").text())+1);
        }
        else if($("#Likes .icon img").attr("src")=="assets/images/likeIcon.png")
        {
            $.post("utilities/likeVideo.php", {videoID:window.sessionStorage.videoID,userID:window.sessionStorage.ID});
            $("#Likes .icon img").attr("src","assets/images/notLikedIcon.png");
            $("#Likes .count").text(parseInt($("#Likes .count").text())-1);
        }
    });
    $('#notepadHead .save').click(function()
    {
        if(window.sessionStorage.ID==null)
        {
            alert("Please Sign In to avail the save option!");
            return;
        }
        $.post("utilities/saveFile.php", {userID:window.sessionStorage.ID, videoID:window.sessionStorage.videoID, textTitle:$('.letter .title').text().trim(), textContent:$('.letter .text').html().trim()});
        download($.trim(($("#notepadHead .title").text()).concat(".txt")),$.trim($("#notepad .text").text()));
    });
    function download(filename, text)
    {
        var element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
        element.setAttribute('download', filename);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
    $(".panel-collapse form").submit(function(event)
    {
        event.preventDefault();
        if(window.sessionStorage.ID==null)
        {
            alert("Please Sign In to comment!");
            return;
        }
        $currentDate = new Date();
        $timestamp = ($currentDate.getFullYear()<10?"0"+$currentDate.getFullYear().toString():$currentDate.getFullYear().toString())+"-"+($currentDate.getMonth()<10?"0"+$currentDate.getMonth().toString():$currentDate.getMonth().toString())+"-"+($currentDate.getDate()<10?"0"+$currentDate.getDate().toString():$currentDate.getDate().toString())+" "+($currentDate.getHours()<10?"0"+$currentDate.getHours().toString():$currentDate.getHours().toString())+":"+($currentDate.getMinutes()<10?"0"+$currentDate.getMinutes().toString():$currentDate.getMinutes().toString())+":"+($currentDate.getSeconds()<10?"0"+$currentDate.getSeconds().toString():$currentDate.getSeconds().toString());
        $.post("utilities/comment.php", {userID:window.sessionStorage.ID, videoID:window.sessionStorage.videoID, comment:$('.panel-collapse textarea').val()});
        $(".comment_list").html([`
            <li>
                <div class='comment_card' data-depth='0'>
                    <div class='figure'>
                        <img class='image' src='`+window.sessionStorage.ImageURL+`' alt='`+window.sessionStorage.name+`'/>
                        <div class='fig_caption'>
                            <h5 class='name'>`+window.sessionStorage.Name+`</h5>
                            <h6 class='occupation'>`+window.sessionStorage.Role+`</h6>
                            <p class='date'>`+$timestamp+`</p>
                        </div>
                    </div>
                    <p class='comment_text'>
                        `+$('.panel-collapse textarea').val()+`
                    </p>
                </div>
            </li>`, $(".comment_list").html().slice(0)].join(''));
        $(".panel-header .counter").text(parseInt($(".panel-header .counter").text())+1);
        $('.panel-collapse textarea').val("");
    });
});