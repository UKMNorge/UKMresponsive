<?php

namespace UKMNorge\DesignBundle\Services;

/**
 * Leverer en random streng til nyhetsbrevet.
 *
 */
class RandomTextService {	
	
 	public static $navn = array(
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
	);

	public static $action = array(
		"juletreklatring (førstemann til stjerna)",
		"pepperkakebaking",
		"kurs i brillepussing",
		"snømåke-workshop",
		"kaffetrakte-kurs",
		"birøkter-kurs",
		"ballongblåse-workshop"
	);

	public static $where = array(
		"i bakgården",
		"på Kimen",
		"i 1001 natt",
		"i bestemorkroken",
		"på soverommet"
	);

	public static function getText() {
		$navnRand = rand(0,sizeof(self::$navn)-1);
		$actionRand = rand(0, sizeof(self::$action)-1);
		$where = rand(0, sizeof(self::$where)-1);
		return self::$navn[$navnRand] + ' ' + self::$action[$actionRand] + ' ' + self::$where[$whereRand];
	}
	
	
}
