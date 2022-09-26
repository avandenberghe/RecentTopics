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
	'RT_ENABLE'              => 'Wyświetl ostatnie tematy',
	'RT_BOTTOM'              => 'Wyświetlaj na dole',
	'RT_SIDE'                => 'Wyświetlaj na boku',
	'RT_TOP'                 => 'Wyświetlaj na górze',
	'RT_LOCATION'            => 'Wybierz pozycję',
	'RT_LOCATION_EXP'        => 'Wyświetl pozycje do wyświetlania ostatnich tematów.',
	'RT_NUMBER'              => 'Liczba ostatnich tematów do wyświetlenia',
	'RT_NUMBER_EXP'          => 'Maksymalna liczba tematów do wyświetlenia na stronie.',
	'RT_SORT_START_TIME'     => 'Sortuj tematy po dacie rozpoczęcia tematu',
	'RT_SORT_START_TIME_EXP' => 'Zamist sortowania po dacie ostatniego posta.',
	'RT_UNREAD_ONLY'         => 'Wyświetlaj tylko nieprzeczytane tematy w ostatnich wątkach',
	)
);
