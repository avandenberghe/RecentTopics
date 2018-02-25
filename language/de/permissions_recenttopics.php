<?php
/**
 *
 * @package Recent Topics Extension
 *
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

/**
 * DO NOT CHANGE
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
		'ACL_U_RT_VIEW'            => 'Aktuelle Themen: Kann die Aktuelle Themen sehen.',
		'ACL_U_RT_ENABLE'          => 'Aktuelle Themen: Kann Anzeige der Aktuelle Themen aktivieren / deaktivieren.',
		'ACL_U_RT_LOCATION'        => 'Aktuelle Themen: Kann das Anzeigeort des Blocks «Aktuelle Themen» ändern .',
		'ACL_U_RT_SORT_START_TIME' => 'Aktuelle Themen: Kann sortierung des Blocks «Aktuelle Themen» ändern.',
		'ACL_U_RT_UNREAD_ONLY'     => 'Aktuelle Themen: Kann wahl zur ungelesenen Aktuelle Themen ändern.',
		'ACL_U_RT_NUMBER'          => 'Aktuelle Themen: Kann Anzahl der Aktuelle Themen pro Seite ändern',
	)
);
