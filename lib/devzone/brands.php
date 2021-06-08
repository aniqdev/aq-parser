<div class="brands-alphabet" id="brands_alphabet"></div>
<?php include 'brands-list.php'; ?>
<script>
(function(d, O, letter, current_letter, brand_name){
var cl = console.log
var brands_obj = {}
d.querySelectorAll('.page-brands-list-link').forEach(function(el){
	brand_name = el.innerText
	current_letter = brand_name[0]
    if(!is_bottom_link(brand_name)){
    	if(!brands_obj[current_letter]) brands_obj[current_letter] = []
    	brands_obj[current_letter].push({h: el.href, t: brand_name})
    }
})
brands_html = O.values(brands_obj).map(make_ul).join('')
d.getElementById('page-brands').innerHTML = brands_html

function is_bottom_link(word){return ['Бесплатная доставка','Новинки','Sale'].includes(word)}
function make_ul(brand_list){
	letter = brand_list[0].t[0]
	var ul = '<ul class="letter-list" id="letter_list_'+letter+'"><li class="first-char">'+letter+'</li>'
    ul += brand_list.map((el)=>'<li><a href="'+el.h+'" class="bttn-underline">'+el.t+'</a></li>').join('')
    ul += '</ul>'
	return ul
}

var brands_alphabet = d.querySelector('#brands_alphabet')
brands_alphabet.innerHTML = '<button value="all" class="bttn-underline active">Все</button>'+O.keys(brands_obj).map((letter)=>{
	return '<button class="bttn-underline" value="'+letter+'">'+letter+'</button>'
}).join('')
brands_alphabet.addEventListener('click', function(e){
	if(!(letter = e.target.value)) return false
    d.querySelector('button.active').classList.remove('active')
    d.querySelector('button[value="'+letter+'"]').classList.add('active')
	if (letter === 'all') d.getElementById('page-brands').innerHTML = brands_html
	else d.getElementById('page-brands').innerHTML = make_ul(brands_obj[letter])
})
}(document, Object))
</script>

<script>
(function(d, O){
	return; // old version
var brands_obj = {}
var brands = '<ul class="letter-list" id="letter_list_A"><li class="first-char">A</li>'
var last_letter = 'A'
var current_letter
document.querySelectorAll('.page-brands-list-link').forEach(function(el){
	current_letter = el.innerText[0]
    if(!is_bottom_link(el.innerText)){
	    if(last_letter !== current_letter) { // первая буква в списке
			brands += '</ul>'
			brands_obj[last_letter] = brands;
			brands = ''
			brands += '<ul class="letter-list" id="letter_list_'+current_letter+'"><li class="first-char">'
	        brands += current_letter
	        brands += '</li>'
	        last_letter = current_letter
	    }
	    brands += '<li><a href="'+el.href+'" class="bttn-underline">'+el.innerText+'</a></li>'
    } 
})
brands += '</ul>'
brands_obj[last_letter] = brands;
brands_html = Object.values(brands_obj).join('')
document.getElementById('page-brands').innerHTML = brands_html
  
function is_bottom_link(word){
	return ['Бесплатная доставка','Новинки','Sale'].includes(word)
}

var brands_alphabet = document.querySelector('#brands_alphabet')
brands_alphabet.innerHTML = '<button value="all" class="bttn-underline active">Все</button>'+Object.keys(brands_obj).map((letter)=>{
	return '<button class="bttn-underline" value="'+letter+'">'+letter+'</button>'
}).join('')
brands_alphabet.addEventListener('click', function(e){
	var letter = e.target.value
	if(!letter) return false
    document.querySelector('button.active').classList.remove('active')
    document.querySelector('button[value="'+letter+'"]').classList.add('active')
	if (letter === 'all') {
		document.getElementById('page-brands').innerHTML = brands_html
	}else{
		document.getElementById('page-brands').innerHTML = brands_obj[letter]
	}
})
}())
</script>

<style>
#page-brands{
  display: flex;
  flex-wrap: wrap;
}
.letter-list{
  width:20%;
  list-style: none;
}
.first-char{
	font-size: 2em;  
}
.bottom-links{
display: flex;
list-style: none;
padding-left: 0;
}
.bottom-links li{
width: 20%;
padding-left: 40px;
}
</style>