<style>
.brick{
	height: 30px;
	margin: 1px 5px;
	outline: 1px solid #fbb7;
	display: inline-block;
}
</style>
<div id="wall"></div>
<script>
var { log } = console
let wall = [
	[1,2,2,1],
	[3,1,2],
	[1,3,2],
	[2,4],
	[3,1,2],
	[1,3,1,1]
]
let wallDiv = document.getElementById('wall')
let result = []
let borders
for(let i in wall){
	borders = 0
	for(let j in wall[i]){
		// log(wall[i][j])
		borders += wall[i][j]
		wallDiv.appendChild(create_brick(wall[i][j]))
		if(result[borders]) result[borders] += 1
		else result[borders] = 1
	}

	log(wall[i])
	wallDiv.appendChild(document.createElement('br'))
}
log(result)
function create_brick(length) {
	let span = document.createElement('span')
	span.className = 'brick'
	span.style.width = `${(30 * length) + ((length - 1) * 10)}px`
	return span
}


</script>