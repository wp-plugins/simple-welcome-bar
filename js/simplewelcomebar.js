function setCookie (cookieName, cookieValue, cookieExpire) {
	wbarDate = new Date();
	wbarDate.setTime(wbarDate.getTime() + cookieExpire);
	wbarExpireDate = wbarDate.toUTCString();
	document.cookie=cookieName + '=' + cookieValue + '; expires='+ wbarDate.toUTCString() +'; path=/';
}

function readCookie(cookieName){
	var results = document.cookie.match(cookieName + '=(.*?)(;|$)')
	if(results){
		return(results[1])
	    }
	else {return null}
}

jQuery(function () {
	
	//click to hide the bar
	jQuery("#wb-close").click(function(){
		jQuery("#welcomebar").slideUp("normal");
		jQuery(".wb-spacer").slideUp("normal");
		setCookie("welcomebar","hidden", 604800000);
	});

	var wpbarCookieRead = readCookie("welcomebar");
	
	//hides the bar if it has already been closed
	if(wpbarCookieRead == 'hidden') {
		jQuery("#welcomebar").css("display","none");
		jQuery(".wb-spacer").css("display","none");
	};

});
