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
	'RECENT_TOPICS'     => 'Najnovšie témy',
	'RT_NO_TOPICS'		=> 'Nie sú žiadne nové témy na zobrazenie.',
	'LIKES'				=> 'Likes',
	'VIEWING_RECENT_TOPICS'	=> 'Prezerá <a href="%s">Najnovšie témy</a>',
	'EXTENSION_REQUIRES_330'	=> 'Toto rozšírenie vyžaduje phpBB 3.3.0 alebo vyššie.',
	)
);
