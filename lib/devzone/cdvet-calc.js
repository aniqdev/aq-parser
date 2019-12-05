;(function() {

   var cork = document.createElement('div')
   function get_element(id) {
      return document.getElementById(id) || cork;
   }

   function set(elem, html) {
      get_element(elem).innerHTML = Math.round(html)
   }

   var elem_perc_of_weight = get_element('perc_of_weight_inp')
   var elem_dog_weight = get_element('dog_weight_inp')
   var elem_meat_plant = get_element('meat_plant_inp')

   console.log(elem_perc_of_weight)

   function calculate() {
      var perc_of_weight = +elem_perc_of_weight.value
      var dog_weight = +elem_dog_weight.value
      var perc_of_meat = +elem_meat_plant.value

      var total_food = dog_weight * perc_of_weight * 10 // 1000g / 100%

      set('js_total_food', total_food)
      set('js_total_food_m7', total_food * 7)

      var total_meat = total_food * perc_of_meat / 100

      set('js_meet', total_meat)
      set('js_meet_1', total_meat * 0.5)
      set('js_meet_2', total_meat * 0.16)
      set('js_meet_3', total_meat * 0.12)
      set('js_meet_4', total_meat * 0.12)

      set('js_meet_m7', total_meat * 7)
      set('js_meet_1_m7', total_meat * 0.5 * 7)
      set('js_meet_2_m7', total_meat * 0.16 * 7)
      set('js_meet_3_m7', total_meat * 0.12 * 7)
      set('js_meet_4_m7', total_meat * 0.12 * 7)

      var total_plant = total_food * (100 - perc_of_meat) / 100

      set('js_plant', total_plant)
      set('js_plant_1', total_plant * 0.75)
      set('js_plant_2', total_plant * 0.25)

      set('js_plant_m7', total_plant * 7)
      set('js_plant_1_m7', total_plant * 0.75 * 7)
      set('js_plant_2_m7', total_plant * 0.25 * 7)

      set('js_fat', total_food * 0.016)
      set('js_fat_m7', total_food * 0.016 * 7)
   }

   calculate()

   elem_perc_of_weight.addEventListener('change', calculate);
   elem_dog_weight.addEventListener('change', calculate);
   elem_dog_weight.addEventListener('keyup', calculate);
   elem_meat_plant.addEventListener('change', calculate);

}());