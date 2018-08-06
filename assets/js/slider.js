var myVar;
var tempCount = 0;
function reset(id, count){
  for(let i=0; i<count; i++){
    document.getElementById("slide-number"+id+i).style.display = 'none';
  }
}

function startSlide(id, count){
  reset(id, count);
  document.getElementById("slide-number"+id+"0").style.display = 'block';
}

function myTimer(id, count) {
  reset(id, count);

  if(tempCount === count){
    tempCount = 0;
    document.getElementById("slide-number"+id+"0").style.display = 'block';

  }
  else {
    document.getElementById("slide-number"+id+tempCount).style.display = 'block';
    tempCount++;
  }

}

function openNav(id, count) {
  document.getElementById("myNav"+id).style.width = "100%";
  startSlide(id, count);
  myVar = setInterval(function(){ myTimer(id, count, 0); }, 3000);
}

function closeNav(id) {
    document.getElementById("myNav"+id).style.width = "0%";
    clearInterval(myVar);
}
