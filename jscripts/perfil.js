$(window).ready(function(){
	
	//Fotos del album
	$("div.album").each(function(){
		var top = 0;
		var i=0;
		$(this).find("div.foto").each(function(){
			top_tmp = "-" + top + "px";
			$(this).css("top", top_tmp);
			
			if(i==0){
				$(this).css("text-align","left");
			}else if(i==1){
				$(this).css("text-align","center");
			}else if(i == 2){
				$(this).css("text-align","right");
			}
			
			top = parseInt(top) + (parseInt($(this).find("img").css("height").match(/[0-9]*/gim)[0]) * 0.9);
			i++;
		});
	});
});
