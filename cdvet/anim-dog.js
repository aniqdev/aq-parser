(function() {
var phrases = [
	{
		text: 'Jacketz: Schützt, wärmt und ist bequem!',
		href: 'https://rebopharm24.de/shop/exklusiv/jacketz/165217/jacketz-medical-body-fuer-hunde?number=163927&c=1253&mtm_campaign=AnimationHund%20Variante%201&mtm_kwd=AnimationHund%20Variante%201',
		animation: '',
		
	},
	{
		text: 'Mit Jacketz fühle ich mich wohl – und Wunden sind perfekt geschützt.',
		href: 'https://rebopharm24.de/shop/exklusiv/jacketz/165217/jacketz-medical-body-fuer-hunde?number=163927&c=1253&mtm_campaign=AnimationHund%20Variante%203&mtm_kwd=AnimationHund%20Variante%203',
		animation: 'animation: 10s anime-banner-text2 5s linear infinite;',
	},
	{
		text: 'Jacketz - jetzt in neuen, frischen Farben.',
		href: 'https://rebopharm24.de/shop/exklusiv/jacketz/165217/jacketz-medical-body-fuer-hunde?number=163927&c=1253&mtm_campaign=AnimationHund%20Variante%202&mtm_kwd=AnimationHund%20Variante%202',
		animation: '',
	},
]
var random_phrase = phrases[getRandomInt(3)]
// var random_phrase = phrases[1]
// console.log('hi');
var anim_dog_element = document.createElement('div')
anim_dog_element.id = 'anim_dog'
anim_dog_element.innerHTML = '<a href="'+random_phrase.href+'" target="_blank"><img src="https://rebopharm24.de/shop/files/JacketzHund250x200.gif"></a><div id="cloud_xs"></div><div id="cloud_sm"></div>'

var anim_cloud_element = document.createElement('span')
anim_cloud_element.id = 'anim_dog_cloud'
anim_cloud_element.innerHTML = '<div class="-inner" id="anim_cloud_inner" style="'+random_phrase.animation+'"><div class="-b1">'+random_phrase.text+'</div>'+
	'<div class="-b2">'+random_phrase.text+'</div></div>'
anim_dog_element.appendChild(anim_cloud_element)



var anim_close_element = document.createElement('span')
anim_close_element.id = 'anim_close'
anim_close_element.innerHTML = '×'
anim_dog_element.appendChild(anim_close_element)

var anim_banner_element = document.createElement('div')
anim_banner_element.id = 'anim_banner'
anim_banner_element.innerHTML = '<a href="https://rebopharm24.de/shop/praxisbedarf/behandlung/bodys/hundebodys/165217/jacketz-medical-body-fuer-hunde?c=1253" target="_blank"><img src="https://rebopharm24.de/shop/files/Jacketz_banner.png"></a>'

function run_anim() {
	document.body.appendChild(anim_dog_element);
	// document.body.appendChild(anim_banner_element);
	// var anim_cloud_inner = document.getElementById('anim_cloud_inner')
	// setTimeout(()=>{
	// 	anim_cloud_inner.style.top = -(anim_cloud_inner.clientHeight / 2) + 'px'
	// },2000)
}


anim_dog_element.onclick = function(e){
	localStorage.setItem('do_not_show_anim', Date.now());
}
anim_banner_element.onclick = function(e){
	localStorage.setItem('do_not_show_anim', Date.now());
}

anim_close_element.onclick = function(e){
	e.stopPropagation()
	anim_dog_element.remove()
	anim_banner_element.remove()
	localStorage.setItem('do_not_show_anim', Date.now());
}

var do_not_show_anim = localStorage.getItem('do_not_show_anim');
var is_24h_passed = (Date.now() - do_not_show_anim) > 1000*60*60*24
if(!do_not_show_anim || is_24h_passed){
	setTimeout(run_anim, 1500)
}


function getRandomInt(max) {
  return Math.floor(Math.random() * max);
}
}())