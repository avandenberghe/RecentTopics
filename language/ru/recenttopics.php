<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 * Russian translation by HD321kbps
 */

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'RECENT_TOPICS'	=> 'Последние темы',
	'RT_NO_TOPICS'	=> 'Нет последних тем для отображения.',
	'LIKES'				=> 'Лайки',
	'VIEWING_RECENT_TOPICS'	=> 'Просматривает <a href="%s">Последние темы</a>',
	'EXTENSION_REQUIRES_330'	=> 'Это расширение требует phpBB 3.3.0 или выше.',
));
