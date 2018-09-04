$( document ).ready(function() {
    $('#error').hide();
});

function converToZip(indexValue, countValue, index) {
    $("#closeBtn"+indexValue).hide();
    $("#download-btn"+indexValue).hide();
    $('#upload'+indexValue).hide();

    var progress = 0;
    var setTime = 0;

    // set the progress bar timer value.
    if(countValue < 100){
        progress = Math.floor( 100/countValue);
        setTime = 2000;
    }
    else{
        progress = 2;
        setTime = ((Math.floor( countValue/100)+1)*1000);
    }
    var current_progress = 0;

    // Starting the timer.
    $('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);
        var interval = setInterval(function() {
            current_progress += progress;
            $("#dynamic"+indexValue)
                .css("width", current_progress + "%")
                .attr("aria-valuenow", current_progress);

            if(current_progress >= 100){
                clearInterval(interval);
                $("#closeBtn"+indexValue).show();
                setTimeout(function(){
                    $("#download-btn"+indexValue).show();
                }, 2000);

            }
        }, setTime);

    document.getElementById("zip"+indexValue).innerHTML = "";

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("zip"+indexValue).innerHTML = this.responseText;
        }
    }
    xmlhttp.open("GET", "download-zip.php?id="+(indexValue+index), true);
    xmlhttp.send(); 
}

var selectedAlbums = function(){
    var temp = -1;
    var index;
    var count = 0;
    var progress = 0;
    var setTime = 0;
    var current_progress = 0;
    var ajaxCallTemp = true;

    var checks = $('input[type="checkbox"]:checked').map(function(){
        temp = $(this).val();
    }).get();
    
    if(temp != -1){
        document.getElementById("zip").innerHTML = "";

        var checks = $('input[type="checkbox"]:checked').map(function(){
            var splitData = $(this).val().split("_");
            count = parseInt(splitData[1])+count;
            index = parseInt(splitData[0]);


            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("zip").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "download-zip.php?selectedID1="+index, true);
            xmlhttp.send();
        }).get();


        $('#error').hide();
        $('#progress').show();
        $("#closeBtn").hide();
        $("#download-btn").hide();
        $('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);



        if(count < 100){
            progress = Math.floor( 100/count);
            setTime = 2000;
        }
        else{
            progress = 2;
            setTime = ((Math.floor( count/100)+1)*1000);
        }


        $('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);
        var interval = setInterval(function() {
            current_progress += progress;
            $("#dynamic")
                .css("width", current_progress + "%")
                .attr("aria-valuenow", current_progress);
            if(current_progress>=60){

                if(ajaxCallTemp == true){
                    document.getElementById("zip").innerHTML = "";

                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("zip").innerHTML = this.responseText;
                        }
                    }
                    xmlhttp.open("GET", "download-zip.php?selectedID2=", true);
                    xmlhttp.send();
                    ajaxCallTemp = false;
                }


            }
            if(current_progress >= 100){
                clearInterval(interval);
                $("#closeBtn").show();


                setTimeout(function(){
                    $("#download-btn").show();
                }, 10000);

            }
        }, setTime);
    }
    else{
        $('#progress').hide();
        $('#error').show();
        $("#download-btn").hide();
    }
}

function allAlbums(albumLenght, totalPhotos){
    var ajaxCallTemp = true;

    $('#error').hide();
    $("#closeBtn").hide();
    $("#download-btn").hide();
    $('#progress').show();
    $('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);
    var current_progress = 0;

    document.getElementById("zip").innerHTML = ""
    for(i=0; i<=albumLenght; i++){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("zip").innerHTML = this.responseText;
            }
        }

        xmlhttp.open("GET", "download-zip.php?selectedID1="+i, true);
        xmlhttp.send();
    }

    if(totalPhotos < 100){
        progress = Math.floor( 100/totalPhotos);
        setTime = 2000;
    }
    else{
        progress = 2;
        setTime = ((Math.floor( totalPhotos/100)+1)*1000);
    }

    $('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);
    var interval = setInterval(function() {
        current_progress += progress;
        $("#dynamic")
            .css("width", current_progress + "%")
            .attr("aria-valuenow", current_progress);

        if(current_progress>=60){

            if(ajaxCallTemp == true){
                document.getElementById("zip").innerHTML = "";

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("zip").innerHTML = this.responseText;
                    }
                }
                xmlhttp.open("GET", "download-zip.php?selectedID2=", true);
                xmlhttp.send();
                ajaxCallTemp = false;
            }
        }
        if(current_progress >= 100){
            clearInterval(interval);
            $("#closeBtn").show();
            setTimeout(function(){
                $("#download-btn").show();
            }, 10000);

        }
    }, setTime);
}
