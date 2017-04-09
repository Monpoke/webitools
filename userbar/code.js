

function ReplaceYoutube() {
	
	var analyse = $("body").html();
	var reg=new RegExp("{youtube}(.+){/youtube}", "g");
	analyse=analyse.replace(reg, "");
	$("body").html(analyse);
	
	 
		
}

$(function(){
	
	var currentLocation =  document.location.href;
	currentLocation = currentLocation.substring( currentLocation.lastIndexOf('/')+1, currentLocation.length);
	
	ReplaceYoutube();
});