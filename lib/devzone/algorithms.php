<link rel="stylesheet" href="css/algorithms.css">

<script>

var { log, error } = console

var arr1 = [2,5,78,8,5,9,6,34,5,123,434,6,87,9,980]

// log(linearSearch(arr, 78))
// log(linearSearch(arr, 6))
// log(linearSearch(arr, 321))


function linearSearch(array, value) {
	var count = 0
	try{
		for (let i = arr.length - 1; i >= 0; i--) {
			count++
			if(arr[i] === value){
				return i
			}
		}
		return null
	}catch(e){
		error(e.message)
	}finally{
		log('count =' , count)
	}
}




var arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 80, 544, 945, 1233]
// log(binarySearch(arr, 5));
// log(binarySearch(arr, 7));
// log('arr.length ', arr.length)
for (var i = arr.length - 1; i >= 0; i--) {
	// log(binarySearch(arr, arr[i]));
}


function binarySearch(array, item) {
	var count = 0
	try{
		let start = 0
		let end = array.length
		let middle
		while (start < end) {
			count++
			middle = Math.floor((start + end) / 2)
			log('middle ',middle)
			if (array[middle] === item) {
				return middle
			}
			if (item < array[middle]) {
				end = middle // ? middle - 1
			}else{
				start = middle + 1
			}
		}
		return -1
	}catch(e){
		error(e.message)
	}finally{
		// log('count =' , count)
	}
}


function selectionSorting(array) {
	let count = 0
	try{
		for (var i = 0; i < array.length; i++) {
			let indexMin = i
			for (var j = i+1; j < array.length; j++) {
				count++
				if (array[j] < array[indexMin]) {
					indexMin = j
				}
			}
			let tmp = array[i]
			array[i] = array[indexMin]
			array[indexMin] = tmp
		}
		return array
	}catch(e){
		error(e.message)
	}finally{
		log('count =' , count)
	}
}

var arr3 = [2,5,78,8,5,9,6,34,5,123,434,6,87,9,980,43,45,654,26783,456,678,234,645]
// log(selectionSorting(arr3))

log(bubbleSort(arr3))
log(arr3[arr3.length + 1])

function bubbleSort(array) {	
	let count = 0
	try{
		for (var i = 0; i < array.length; i++) {
			for (var j = 0; j < array.length; j++) {
				count++
				if (array[j+1] < array[j]) {
					let tmp = array[j+1]
					array[j+1] = array[j]
					array[j] = tmp
				}
			}
		}
		return array
	}catch(e){
		error(e.message)
	}finally{
		log('count =' , count)
	}
}


</script>