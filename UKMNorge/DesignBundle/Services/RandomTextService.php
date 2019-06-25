<?php

namespace UKMNorge\DesignBundle\Services;

/**
 * Leverer en random streng til nyhetsbrevet.
 *
 */
class RandomTextService {	
	
 	var $navn = [
		"Magnus",
		"Jonas",
		"Thomas",
		"Emma",
		"Julie",
		"Marte",
		"Martin",
		"Anna",
		"Mari",
		"Stine",
		"Sara"
	];

	var $action = [
		"juletreklatring (førstemann til stjerna)",
		"pepperkakebaking",
		"kurs i brillepussing",
		"snømåke-workshop",
		"kaffetrakte-kurs",
		"birøkter-kurs",
		"ballongblåse-workshop"
	];

	var $where = [
		"i bakgården",
		"på Kimen",
		"i 1001 natt",
		"i bestemorkroken",
		"på soverommet"
	];

	public static function getText() {
		$navnRand = rand(0,sizeof($navn)-1);
		$actionRand = rand(0, sizeof($action)-1);
		$where = rand(0, sizeof($where)-1);
		return $navn[$navnRand] + ' ' + $action[$actionRand] + ' ' + $where[$whereRand];
	}
	
	
}
