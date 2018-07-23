<?php



if (isset($_POST['action']) && $_POST['action'] === 'save') {

	$steam_link = $_POST['link'];

	foreach (['steam_de',
			  'steam_en',
			  'steam_fr',
			  'steam_es',
			  'steam_it'] as $steam_table) {
		$steam_game = new SteamGame($steam_link, $steam_table);

		$sids[$steam_table] = $steam_game
				->getDOM()
				->getTitle()
				->getDescription()
				->getType()
				->getAppId()
				->getPrices()
				->getReleaseDate()
				->getNotice()
				->getSpecs()
				->getLanguages()
				->getGenres()
				->getDeveloper()
				->getPublisher()
				->getOs()
				->getSysReq()
				->getRatingReviews()
				->getTags()
				->getUsk()
				->getIncludes()
				->savePictures()
				->save();
	}



	echo json_encode([
		'status' => 'success',
		'sids' => $sids,
		'ERRORS' => $_ERRORS,
	]);

}


