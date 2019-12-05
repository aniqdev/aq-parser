/*
* Made by Jon (Coyninja.com), May 2017
* Sorry for the mess - just threw it together quickly
* and tried to make it work properly on modern browsers.
*/

(function($){

	// $(document).foundation()
	// moment().format();

	var userTime;
	var utcTime;

	var utcTimeFormat = "MMM Do, YYYY HH:mm";
	var userTimeFormat = "LLL";

	var utcTimeInput;

	var urlHash = 0;
	var hashTimeMoment;
	var timerCountdown;
	var informedLate = false;

	function GetUTCTime() {
		//It's weird, I know - but I don't trust user's clocks to know the right UTC time.
		$.get("/utctime.php", function (data) {
			if (!isNaN(data)) {
				if (+data >= 10000) {
					utcTime = moment.utc(+data * 1000);
				}
			}
		});
		//Refresh the user time too because why not
		userTime = moment();
	}
	GetUTCTime();
	//Run GetUTCTime every 15 minutes just to make sure there's no serious drift
	var timeUpdater = setInterval(GetUTCTime, 900000);

	//Make clones in a function in case we ever want to update the table live.
	//Remember - you have to clone since moment.js objects are mutable.
	var utcTimeClone;
	var userTimeClone;
	function MakeClones() {
		utcTimeClone = moment.utc(utcTime);
		userTimeClone = moment(userTime);
	}

	//Check for URL hash, if it's set - don't bother showing the table
	GetURLHash();
	if (urlHash == 0) {
		MakeClones();
		$("#timeTableDiv").show();
		$(".instructions").show();

		//Add the date time comparison table entries dateTimeComparisonBody
		var timeMod = 0;
		for (i = 0; i < 1; i++) {
			$("#dateTimeComparisonBody").append("<tr id='dtc" + i + "'><td class='timeModifier'>Current</td><td class='eveTime'>" + utcTimeClone.add(0, "h").format(utcTimeFormat) + "</td><td class='userTime'>" + userTimeClone.add(0, "h").format(userTimeFormat) + "</td></tr>");
			timeMod++;
		}

		for (i = 1; i < 25; i++) {
			$("#dateTimeComparisonBody").append("<tr id='dtc" + i + "'><td class='timeModifier'>+" + timeMod + "hr</td><td class='eveTime'>" + utcTimeClone.add(1, "h").format(utcTimeFormat) + "</td><td class='userTime'>" + userTimeClone.add(1, "h").format(userTimeFormat) + "</td></tr>");
			timeMod++;
		}
	} else {
		hashTimeMoment = moment.unix(urlHash).utc();
		timerCountdown = hashTimeMoment;

		$(".scheduled").show();
		$("#countdownDiv").show();
		$("#inputEveTime").val(hashTimeMoment.format(utcTimeFormat));
		$("#inputEveTime").prop('disabled', true); //Disable input so people don't get confused.

		ProcessInput(false);
	}

	var updateClocks = setInterval(function() {
		utcTime.add(1, "s");
		userTime.add(1, "s");
		$('#userTime').text(userTime.format(userTimeFormat));
		$('#utcTime').text(utcTime.format(utcTimeFormat));
		if (typeof hashTimeMoment == "object") {
			if (utcTime.isBefore(hashTimeMoment)) {
				$('#timerCountdown').text(hashTimeMoment.local().countdown().toString());
			} else {
				if (!informedLate) {
					$('#timerCountdown').text("You're late");
					informedLate = true;
				}
			}
		}
	}, 1000);

	//Sadly have to require the user to set the year, otherwise chrome does weird things.
	$('#inputEveTimeForm').on('submit', function() {
		ProcessInput(true);
	});

	function ProcessInput(makeLink) {
		var userInputValue = $("#inputEveTime").val().replace(/(\d{1,2})\s?(st|nd|rd|th)/ig, "$1");
		utcTimeInput = moment.utc(userInputValue);
		$("#inputLocalTime").text(utcTimeInput.local().format(userTimeFormat));
		$("#localTimeText").show();
		if (utcTimeInput.isValid() && makeLink)
			GenerateLink(utcTimeInput);
	}

	function GetURLHash() {
		urlHash = (window.location.hash).replace("#", "");
	}

	function GenerateLink(utcTimeInput) {
		$("#shareLinkDiv").show();
		$("#generatedLink").val("https://gig-games.de/#"+utcTimeInput.unix());
		$("#generatedLink").focus();
	}
})(jQuery);