<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 * Slovak translation, originally by Dark77
 */

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge(
	$lang, array(
	'RT_ENABLE'              => 'Zobraziť najnovšie témy',
	'RT_TOP'                 => 'Zobraziť hore',
	'RT_BOTTOM'              => 'Zobraziť dole',
	'RT_SIDE'                => 'Zobraziť na strane',
	'RT_LOCATION'            => 'Vyberte umiestnenie',
	'RT_LOCATION_EXP'        => 'Vyberte umiestnenie pre zobrazenie najnovších tém.',
	'RT_VIEWFORUM_LOCATION'  => 'Umiestnenie na stránke fóra',
	'RT_VIEWFORUM_LOCATION_EXP' => 'Vyberte, kde sa majú zobrazovať najnovšie témy na stránke fóra.',
	'RT_NUMBER'              => 'Počet najnovších tém na zobrazenie',
	'RT_NUMBER_EXP'          => 'Maximálny počet tém na zobrazenie na stránku.',
	'RT_SORT_START_TIME'     => 'Zoradiť najnovšie témy podľa času vytvorenia',
	'RT_SORT_START_TIME_EXP' => 'Namiesto zoradenia podľa času posledného príspevku.',
	'RT_UNREAD_ONLY'         => 'Zobraziť iba neprečítané témy v najnovších témach',
	)
);
