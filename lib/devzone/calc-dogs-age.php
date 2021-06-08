<div class="container">
	Years: 
	<select oninput="calculate_age()" id="dogs_age_years">
		<option value="0">0</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
		<option value="21">21</option>
		<option value="22">22</option>
		<option value="23">23</option>
		<option value="24">24</option>
		<option value="25">25</option>
	</select>
	Month: 
	<select oninput="calculate_age()" id="dogs_age_month">
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
		<option value="11">11</option>
	</select>

	<h4>Dog age: <b id="dogs_age_output"></b> human years</h4>
</div>
<script>
function calculate_age() {
	var years = document.getElementById('dogs_age_years')
	var month = document.getElementById('dogs_age_month')
	var output = document.getElementById('dogs_age_output')

	years = +years.value
	month = +month.value
	var dog_age = Math.log(years + (month / 12))*16+31
	dog_age = dog_age.toFixed(2)
	// console.log((dog_age % 1).toFixed(2))
	// console.log(dog_age_month = Math.round((dog_age % 1).toFixed(2) * 12))
	if(dog_age < 0) dog_age = '0.25'
	output.innerText = dog_age
}
calculate_age()
</script>