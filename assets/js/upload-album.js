$( document ).ready(function() {
    $('#error').hide();
});

function singleAlbum(indexValue, countValue, index) {
    $("#download-btn").hide();
    $('#error').hide();
    $('#img-1').show();
    $("#closeBtn").hide();


    $.ajax({
        url: 'upload-data.php',
        type: 'GET',
        data: 'indexValue='+(indexValue+index),
        success: function(data) {
            $('#zip').html(data);
        },
        error: function(e) {
            alert("please logout");
        }
    });
}

var selectedAlbums = function(){
    var temp = -1;
    var count = 0;
    var index = 0;
    var i = 0;
    var indexValue = [];


    var checks = $('input[type="checkbox"]:checked').map(function(){
        temp = $(this).val();
    }).get();

    if(temp != -1){
        $("#download-btn").hide();
        $('#error').hide();
        $('#img-1').show();
        $("#closeBtn").hide();
        
        var checks = $('input[type="checkbox"]:checked').map(function(){
            var splitData = $(this).val().split("_");

            count = parseInt(splitData[1])+count;
            index = parseInt(splitData[0]);

            indexValue.push(index);
        }).get();

        $.ajax({
            url: 'upload-data.php',
            type: 'GET',
            data: 'indexValue='+indexValue,
            success: function(data) {
                $('#zip').html(data);
            },
            error: function(e) {
                alert("please logout");
            }
        });

    }else{
        $('#img-1').hide();
        $('#error').show();
        $("#download-btn").hide();
        $("#closeBtn").show();
    }	
} 

function allAlbums(albumLenght, totalPhotos){
    var indexValue = [];
    for(i = 0; i <= albumLenght; i++){
        indexValue.push(i);
    }

    $("#download-btn").hide();
    $('#error').hide();
    $('#img-1').show();
    $("#closeBtn").hide();

    $.ajax({
        url: 'upload-data.php',
        type: 'GET',
        data: 'indexValue='+indexValue,
        success: function(data) {
            $('#zip').html(data);
        },
        error: function(e) {
            alert("please logout");
        }
    });

}
