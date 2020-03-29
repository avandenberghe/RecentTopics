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
	'RT_ENABLE'              => 'Відображати останні теми',
	'RT_BOTTOM'              => 'Відображати внизу',
	'RT_SIDE'                => 'Відображати збоку',
	'RT_TOP'                 => 'Відображати наверху',
	'RT_LOCATION'            => 'Оберіть місце розташування',
	'RT_LOCATION_EXP'        => 'Оберіть розташування блоку з останніми темами.',
	'RT_NUMBER'              => 'Кількість останніх тем на сторінку',
	'RT_NUMBER_EXP'          => 'Максимальна кількість останніх тем, які будуть відображатись на одній сторінці.',
	'RT_SORT_START_TIME'     => 'Сортувати список останніх тем за датою створення тем',
	'RT_SORT_START_TIME_EXP' => 'Замість сортування тем за датами останніх повідомлень.',
	'RT_UNREAD_ONLY'         => 'Відображати тільки непрочитані теми в списку останніх тем',
	)
);
