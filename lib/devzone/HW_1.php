<style>
	td{
		width: 30px;
		height: 30px;
		text-align: center;
		color: #888;
		border: 1px solid #888;
		cursor: pointer;
		font: 14px sans-serif;
	}
</style>

<div id="container"></div>

<script>

let container = document.getElementById('container');

let input1 = document.createElement('input');
input1.type = 'number';
input1.value = 5;
container.appendChild(input1);

let input2 = document.createElement('input');
input2.type = 'number';
input2.value = 3;
container.appendChild(input2);

let button = document.createElement('input');
button.type = 'button';
button.value = 'create table';
button.onclick = () => {
	table.innerHTML = '';
	for (let i = 0; i < input1.value; i++) {
		let tr = document.createElement('tr');
		for (let j = 0; j < input2.value; j++) {
			let td = document.createElement('td');
			td._index = i+'-'+j;
			td.onclick = (e) => { e.target.innerText = e.target._index }
			tr.appendChild(td);
		}
		table.appendChild(tr);
	}
}
container.appendChild(button);

let table = document.createElement('table');
container.appendChild(table);


</script>