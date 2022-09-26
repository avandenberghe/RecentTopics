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
	'ACL_U_RT_VIEW'            => 'Ostatnie Tematy: może wyświetlać ostatnie tematy.',
	'ACL_U_RT_ENABLE'          => 'Ostatnie Tematy: może włączać/wyłączać wyświetlanie ostatnich tematów.',
	'ACL_U_RT_LOCATION'        => 'Ostatnie Tematy: może wybierać pozycje wyświetlania bloku ostatnich tematów.',
	'ACL_U_RT_SORT_START_TIME' => 'Ostatnie Tematy: może zmieniać kolejność sortowania tematów.',
	'ACL_U_RT_UNREAD_ONLY'     => 'Ostatnie Tematy: może zmieniać ustawienie "wyświetlaj tylko nieprzeczytane tematy".',
	'ACL_U_RT_NUMBER'          => 'Ostatnie Tematy: może zmieniać ilość ostatnich tematów wyświetlanych na stronie.',
	)
);
