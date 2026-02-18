<?php
/**
 *
 * @package Recent Topics Extension
 * Swedish translation
 *
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
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
	'RT_ENABLE'              => 'Visa senaste trådar',
	'RT_BOTTOM'              => 'Visa nederst',
	'RT_SIDE'                => 'Visa vid sidan',
	'RT_TOP'                 => 'Visa överst',
	'RT_LOCATION'            => 'Välj plats',
	'RT_LOCATION_EXP'        => 'Välj plats för att visa senaste trådar.',
	'RT_NUMBER'              => 'Antal senaste trådar att visa',
	'RT_NUMBER_EXP'          => 'Maximalt antal trådar att visa per sida.',
	'RT_SORT_START_TIME'     => 'Sortera senaste trådar efter trådens starttid',
	'RT_SORT_START_TIME_EXP' => 'Istället för att sortera efter senaste inläggets tid.',
	'RT_UNREAD_ONLY'         => 'Visa bara olästa trådar i senaste trådar',
	)
);
