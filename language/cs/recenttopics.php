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
	'RECENT_TOPICS'    => 'Nedávná témata',
	'RT_NO_TOPICS'		=> 'Žádná nedávná témata.',
	'LIKES'				=> 'Lajky',
	'VIEWING_RECENT_TOPICS'	=> 'Prohlíží <a href="%s">Nedávná témata</a>',
	'EXTENSION_REQUIRES_330'	=> 'Tato extenze vyžaduje phpBB 3.3.0 nebo vyšší.',
	)
);
