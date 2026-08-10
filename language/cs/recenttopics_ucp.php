<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 * Czech translation by R3gi
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
	'RT_ENABLE'              => 'Zobrazit nedávná témata',
	'RT_TOP'                 => 'Zobrazit nahoře',
	'RT_BOTTOM'              => 'Zobrazit dole',
	'RT_SIDE'                => 'Zobrazit na straně',
	'RT_LOCATION'            => 'Vyberte umístění',
	'RT_LOCATION_EXP'        => 'Vyberte umístění pro zobrazení nedávné témata.',
	'RT_VIEWFORUM_LOCATION'  => 'Umístění na stránce fóra',
	'RT_VIEWFORUM_LOCATION_EXP' => 'Vyberte, kde se mají zobrazovat nedávná témata na stránce fóra.',
	'RT_NUMBER'                     => 'Nedávná témata',
	'RT_NUMBER_EXP'                 => 'Počet nedávných témat k zobrazení.',
	'RT_SORT_START_TIME'     => 'Řadit nedávná témata podle času založení',
	'RT_SORT_START_TIME_EXP' => 'Namísto jejich řazení podle času posledního příspěvku.',
	'RT_UNREAD_ONLY'         => 'V nedávných tématech zobrazovat pouze nepřečtená témata',
	)
);
