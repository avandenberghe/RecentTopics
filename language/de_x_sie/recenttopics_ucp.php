<?php
/**
 *
 * @package Recent Topics Extension
 * English translation by PayBas
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
		'RT_ENABLE'              => '«Aktuelle Themen» anzeigen',
		'RT_TOP'                 => 'Ansicht oben',
		'RT_BOTTOM'              => 'Ansicht unten',
		'RT_SIDE'                => 'Ansicht auf die Seite',
		'RT_LOCATION'            => 'Anzeigelage',
		'RT_LOCATION_EXP'        => 'Anzeigelage des Blocks «aktuellen Themen»',
		'RT_NUMBER'              => 'Anzahl Aktuelle Themen',
		'RT_NUMBER_EXP'          => 'Maximale Anzahl Themen pro Seite',
		'RT_SORT_START_TIME'     => 'Nach Themen Startzeit sortieren',
		'RT_SORT_START_TIME_EXP' => 'Wenn diese Option aktiviert ist, werden die Themen nach dem Datum des ersten Beitrags anstelle des letzten Beitrags sortiert.',
		'RT_UNREAD_ONLY'         => 'Nur ungelesene Themen anzeigen',
	)
);
