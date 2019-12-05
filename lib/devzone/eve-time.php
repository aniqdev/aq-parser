
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVE Online Time Converter</title>
	<meta name="description=" content="Convert EVE Online Time. Create and share op times. Simple and easy because Time is hard.">
    <!-- <link rel="stylesheet" href="eve-css/foundation.css"> -->
    <link type="text/css" media="all" href="https://gig-games.de/wp-content/cache/autoptimize/css/autoptimize_b8d2acb9758850402245d39c7b5ed72b.css" rel="stylesheet" />
    <link rel="stylesheet" href="eve-css/app.css?t=<?= filemtime('eve-css/app.css') ?>">
  </head>
  <body>

<div class="container eve-time-wrapper">
	
<!--     <div class="row">
      <div class="large-12 columns">
        <h2 class="text-center" id="userTime"></h2>
	  </div>
		<div class="large-12 columns">
			<h2 class="text-center" id="utcTime"></h2>
		</div>
	</div> -->
	<div class="row">
		<div class="medium-6 large-centered columns">
			<h2 class="text-center">EVE Online time</h2><br>
			<form action="javascript:void(0);" id="inputEveTimeForm" >
				<label class="instructions" style="display: none;">Input EVE time for op (Dec 17,2017 16:00) & hit enter</label>
                <label class="scheduled" id="sharedlink" style="display: none;">Your scheduled op time is:</label>
				<input type="text" placeholder="Dec 17, 2017 16:00" id="inputEveTime" />
			</form>
		</div>
	</div>
	<div class="row">
		<div class="medium-6 large-centered columns">
			<div class="primary callout">
				<h3 id="inputLocalTime" class="text-center"> &nbsp;</h3>
				<p id="localTimeText" class="text-right help-text" style="display: none;">Local time</p>
			</div>
			<div class="secondary callout" id="countdownDiv" style="display: none;">
				<h3 class="text-center" id="timerCountdown"></h3>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="medium-6 large-centered columns" id="shareLinkDiv" style="display:none;">
			<div class="secondary callout">
				<input type="text" id="generatedLink" placeholder="Enter time above to share link with all your weebs." onFocus="this.select()" />
			</div>
			<br><img src="/eve-images/break.png">
		</div>
	</div>

    <div class="row" id="timeTableDiv" style="display:none;">
      <div class="large-centered columns">
		  <table role="grid" summary="A table listing the time in Eve-Online, and your local time." id="dateTimeComparisonTable">
			  <thead>The table below is based on the time when this page was loaded.
			  <tr>
				  <th width="100">+</th>
				  <th width="200">EVE</th>
				  <th width="200">Local</th>
			  </tr>
			  </thead>
			  <tbody id="dateTimeComparisonBody">
			  </tbody>
		  </table>
      </div>
    </div>

    <div class="row">
        <div class="medium-6 large-centered columns">
            <form action="javascript:void(0);" id="inputEveTimeForm" >
                <label class="scheduled" id="sharedlink" style="display: none;">Make your own op-time link: <a href="index.html" color="#FFFFFF">here</a></label>
            </form>
        </div>
    </div>
    <br><br>

    <div class="row">
        <div class="medium-6 large-centered columns">
            Does this site help keep all your weebs in order?<br>Feel free to send isk to <p style="color: yellow; display: inline;">Shade Orblighter</p>! Thank you!
            <br><br><img src="/eve-images/break.png">
        </div>
    </div>


    <div class="row">
        <div class="medium-6 large-centered columns">
            <i>All EVE related materials are property of CCP Games</i><br>
            <i>This site is maintained by Shade Orblighter</i><br>
            <i>©2018 Shade Orblighter</i>
        </div>

    </div>

</div>  




    <script src="eve-js/vendor/jquery.js"></script>
    <script src="eve-js/vendor/what-input.js"></script>
    <!-- <script src="eve-js/vendor/foundation.js"></script> -->
	<script src="eve-js/vendor/moment.min.js"></script>
	<script src="eve-js/vendor/countdown.min.js"></script>
	<script src="eve-js/vendor/moment-countdown.min.js"></script>
    <script src="eve-js/app.js?v=1.1"></script>
	<script>
		// moment().format();
	</script>
  </body>
</html>