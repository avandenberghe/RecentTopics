<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 * Ukrainian translation
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
	'RECENT_TOPICS'     => 'Останні Теми',
	'RT_NO_TOPICS'		=> 'Немає нових тем.',
	'LIKES'				=> 'Вподобання',
	'VIEWING_RECENT_TOPICS'	=> 'Переглядає <a href="%s">Останні Теми</a>',
	'EXTENSION_REQUIRES_330'	=> 'Це розширення потребує phpBB 3.3.0 або вище.',
	)
);
