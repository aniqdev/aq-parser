<?php 



$json = file_get_contents('https://esi.evetech.net/latest/status/?datasource=tranquility');

$arr_tr = json_decode($json, true);

sa($arr_tr);


$json = file_get_contents('https://esi.evetech.net/latest/status/?datasource=singularity');

$arr_si = json_decode($json, true);

sa($arr_si);



arrayDB("INSERT INTO eve_server_status SET
		tr_players = '"._esc($arr_tr['players'])."',
		tr_server_version = '"._esc($arr_tr['server_version'])."',
		tr_start_time = '"._esc($arr_tr['start_time'])."',
		si_players = '"._esc($arr_si['players'])."',
		si_server_version = '"._esc($arr_si['server_version'])."',
		si_start_time = '"._esc($arr_si['start_time'])."'
	");


// CREATE TABLE `eve_server_status` (
// 	`id` INT(11) NOT NULL AUTO_INCREMENT,
// 	`tr_players` INT(11) NOT NULL,
// 	`tr_server_version` VARCHAR(50) NOT NULL,
// 	`tr_start_time` VARCHAR(50) NOT NULL,
// 	`si_players` INT(11) NOT NULL,
// 	`si_server_version` VARCHAR(50) NOT NULL,
// 	`si_start_time` VARCHAR(50) NOT NULL,
// 	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
// 	PRIMARY KEY (`id`)
// )
// ENGINE=InnoDB
// ;
