function replaceMenuLinks() {
	var menuItems = document.querySelectorAll('.navigation-main a[href*="www.cdvet"]');
	
	menuItems.forEach(function(item) {
		item.href = item.href.replace('/www.cdvet.de/','/b2b.cdvet.de/')
	});
}

if(location.host === "b2b.cdvet.de"){
	if( document.readyState !== 'loading' ) {
	    replaceMenuLinks();
	} else {
		document.addEventListener("DOMContentLoaded", replaceMenuLinks);	
	}
}