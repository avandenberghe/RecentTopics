<?php
/**
 *
 * @package Recent Topics Extension
 * Polish translation by dgrochowski
 *
 * @copyright (c) 2022 dgrochowski
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
	'RECENT_TOPICS'     => 'Ostatnie Tematy',
	'RT_NO_TOPICS'      => 'Nie ma nowych tematów do wyświetlenia.',
	)
);
