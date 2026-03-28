<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 * Arabic translation by Bassel Taha Alhitary (www.alhitary.net)
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
	'RECENT_TOPICS'    => 'أحدث المواضيع',
	'RT_NO_TOPICS'		=> 'لا توجد مواضيع جديدة لعرضها.',
	'VIEWING_RECENT_TOPICS'	=> 'يتصفح <a href="%s">أحدث المواضيع</a>',
	'EXTENSION_REQUIRES_330'	=> 'هذا الامتداد يتطلب phpBB 3.3.0 أو أعلى.',
	)
);
