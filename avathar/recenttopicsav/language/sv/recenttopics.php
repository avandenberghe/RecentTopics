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
	'RECENT_TOPICS'     => 'Senaste trådar',
	'RT_NO_TOPICS'		=> 'Det finns inga nya trådar att visa.',
	'LIKES'				=> 'Likes',
	'VIEWING_RECENT_TOPICS'	=> 'Visar <a href="%s">Senaste trådar</a>',
	'EXTENSION_REQUIRES_330'	=> 'Detta tillägg kräver phpBB 3.3.0 eller högre.',
	)
);
