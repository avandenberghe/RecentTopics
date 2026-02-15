<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 * German (Sie) translation by Andreas Vandenberghe
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
	'RECENT_TOPICS'    => 'Aktuelle Themen',
	'RT_NO_TOPICS'	   =>  'Es sind keine neuen Themen vorhanden.',
	'VIEWING_RECENT_TOPICS'	=> 'Schaut sich <a href="%s">Aktuelle Themen</a> an',
	'EXTENSION_REQUIRES_330'	=> 'Diese Erweiterung benötigt phpBB 3.3.0 oder höher.',
	)
);
