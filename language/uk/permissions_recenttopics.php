<?php
/**
 *
 * @package Recent Topics Extension
 * English translation by PayBas
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
	'ACL_U_RT_VIEW'            => 'Останні Теми: може переглядати Останні Теми.',
	'ACL_U_RT_ENABLE'          => 'Останні Теми: може вмикати чи вимикати відображення Останніх Тем.',
	'ACL_U_RT_LOCATION'        => 'Останні Теми: може обирати розташування Останніх Тем.',
	'ACL_U_RT_SORT_START_TIME' => 'Останні Теми: може обирати порядок сортування Останніх Тем.',
	'ACL_U_RT_UNREAD_ONLY'     => 'Останні Теми: може змінювати налаштування, щоб відображати тільки непрочитані теми.',
	'ACL_U_RT_NUMBER'          => 'Останні Теми: може змінювати кількість Останніх Тем на сторінку.',
	)
);
