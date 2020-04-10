<link rel="stylesheet" href="css/jquery-ui.min.css">
<script src="js/jquery-ui.min.js"></script>
<script>
$( function() {
  var cache = {};
  $( "#games" ).autocomplete({
    minLength: 2,
    source: function( request, response ) {
      var term = request.term;
      if ( term in cache ) {
        response( cache[ term ].suggestions );
        return;
      }

      $.getJSON( "q.php", request, function( data, status, xhr ) {
        cache[ term ] = data;
        response( data.suggestions);
      });
    },
    select: function( event, ui ) {console.dir(event.target.value)}
  });

  $('body').on('click', '.ui-menu-item-wrapper', function(e) {
    console.dir($(this).text());
  });

} );
</script>
</head>
<body>
 
<div class="ui-widget">
  <label for="games">Games: </label>
  <input id="games" name="q">
</div>
