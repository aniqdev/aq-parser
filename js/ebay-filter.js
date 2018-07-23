$.post('http://parser.gig-games.de/ajax.php?action=update-filter-values',
	{action:'get_filter_data', steam_table:'steam_fr'},
	function(data) {
        console.dir(data);
	}, 'json');


$('.mbg-nw').text('GiG-Games');