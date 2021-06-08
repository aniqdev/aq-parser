function themenwelt_romove_link(){
	if(location.pathname === "/shop/themenwelt/"){
	    document.querySelector('.link--show-listing').remove();
	}
}
themenwelt_romove_link()
document.addEventListener("DOMContentLoaded", themenwelt_romove_link);