<?php
/**
 *
 * @package Recent Topics Extension
 * Russian translation by HD321kbps
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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge(
	$lang, array(
	'RT_ENABLE'				=> 'Показывать последние темы',
	'RT_TOP' 				=> 'Показывать вверху',
	'RT_BOTTOM' 			=> 'Показывать внизу',
	'RT_SIDE' 				=> 'Показывать сбоку',
	'RT_LOCATION' 			=> 'Расположение последних тем',
	'RT_LOCATION_EXP' 		=> 'Выберите расположение для отображения блока последних тем.',
	'RT_NUMBER'				=> 'Число тем в списке',
	'RT_NUMBER_EXP'			=> 'Количество тем, отображаемых на главной странице.',
	'RT_SORT_START_TIME'	=> 'Сортировать по дате создания',
	'RT_SORT_START_TIME_EXP'=> 'Если включено, темы будут отсортированы по дате их создания, а не по дате последнего сообщения.',
	'RT_UNREAD_ONLY'		=> 'Только непрочтённые'
));
