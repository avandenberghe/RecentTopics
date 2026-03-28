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
	'ACL_U_RT_VIEW'            => 'Najnovšie témy: Môže zobraziť najnovšie témy.',
	'ACL_U_RT_ENABLE'          => 'Najnovšie témy: Môže zapnúť alebo vypnúť zobrazenie najnovších tém.',
	'ACL_U_RT_LOCATION'        => 'Najnovšie témy: Môže meniť umiestnenie bloku najnovších tém.',
	'ACL_U_RT_SORT_START_TIME' => 'Najnovšie témy: Môže meniť spôsob zoradenia najnovších tém.',
	'ACL_U_RT_UNREAD_ONLY'     => 'Najnovšie témy: Môže meniť nastavenie neprečítaných tém.',
	'ACL_U_RT_NUMBER'          => 'Najnovšie témy: Môže nastaviť počet tém na stránku.',
	)
);
