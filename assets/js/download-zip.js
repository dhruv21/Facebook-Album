$( document ).ready(function() {
	$('#error').hide();
});

function converToZip(indexValue, countValue) {
	$("#closeBtn"+indexValue).hide();

	progress = Math.floor( 100/countValue);
	var current_progress = 0;

	$('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);
	var interval = setInterval(function() {
		current_progress += progress;
		$("#dynamic"+indexValue)
			.css("width", current_progress + "%")
			.attr("aria-valuenow", current_progress);

			if(current_progress >= 100){
				clearInterval(interval);
				$("#closeBtn"+indexValue).show();
			}
		}, 2000);


		document.getElementById("zip"+indexValue).innerHTML = "";

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("zip"+indexValue).innerHTML = this.responseText;
			}
		}
	xmlhttp.open("GET", "download-zip.php?id="+indexValue, true);
	xmlhttp.send();
}

var selectedAlbums = function(){
	var temp = -1;

	var checks = $('input[type="checkbox"]:checked').map(function(){
		temp = $(this).val();
	}).get()

	if(temp != -1){
		$('#error').hide();
		$('#progress').show();
		$("#closeBtn").hide();
		$("#download-btn").hide();
		$('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);

		var current_progress = 0;

		var interval = setInterval(function() {
			current_progress += 10;
			$("#dynamic")
			.css("width", current_progress + "%")
			.attr("aria-valuenow", current_progress);

			if(current_progress >= 100){
				clearInterval(interval);
				$("#closeBtn").show();
				$("#download-btn").show(1000);
			}
		}, 2000);


		document.getElementById("zip").innerHTML = "";

		var checks = $('input[type="checkbox"]:checked').map(function(){
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("zip").innerHTML = this.responseText;
				}
			}
			xmlhttp.open("GET", "download-zip.php?selectedID1="+$(this).val(), true);
			xmlhttp.send();
		}).get()

		document.getElementById("zip").innerHTML = "";

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("zip").innerHTML = this.responseText;
			}
		}
		xmlhttp.open("GET", "download-zip.php?selectedID2=", true);
		xmlhttp.send();
	}
	else{
		$('#progress').hide();
		$('#error').show();
		$("#download-btn").hide();
	}
}

function allAlbums(albumLenght){
	$('#error').hide();
	$("#closeBtn").hide();
	$("#download-btn").hide();
	$('#progress').show();
	$('.progress-bar').css('width', '0%').attr('aria-valuenow', 0);
	var current_progress = 0;


	var interval = setInterval(function() {
		current_progress += 10;
		$("#dynamic")
			.css("width", current_progress + "%")
			.attr("aria-valuenow", current_progress);

			if(current_progress >= 100){
				clearInterval(interval);
				$("#closeBtn").show();
				$("#download-btn").show(1000);
			}
		}, 2000);

		document.getElementById("zip").innerHTML = "";

		for(i=0; i<albumLenght; i++){

			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("zip").innerHTML = this.responseText;
				}
			}

			xmlhttp.open("GET", "download-zip.php?selectedID1="+i, true);
			xmlhttp.send();
		}

		document.getElementById("zip").innerHTML = "";

		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("zip").innerHTML = this.responseText;
			}
		}

		xmlhttp.open("GET", "download-zip.php?selectedID2=", true);
		xmlhttp.send();
}
