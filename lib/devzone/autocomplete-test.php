<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- 1. Load the Google Places library -->
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&language=en&key=AIzaSyDaxy5czoRVMq96SO0Yo7xCQLg-GT1i1ws"></script>

  </head>
  <body>

    <!-- 2. Insert and input tag with a useful id -->
    <input type="text" id="autocomplete"/>

    <!-- 3. Use this script to call the Google Places API -->
    <script>
      var input = document.getElementById('autocomplete')

      // Limit the results to just Cities in the US
      var options = {
        types: ['(cities)'],
        componentRestrictions: {country: "us"}
       }
      var autocomplete = new google.maps.places.Autocomplete(input, options)

      google.maps.event.addListener(autocomplete, 'place_changed', function(){
         var place = autocomplete.getPlace()
      })
    </script>

  </body>
</html>