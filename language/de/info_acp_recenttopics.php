<?php
/**
 *
 * @package Recent Topics Extension
 * German translation by Andreas Vandenberghe
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
	//forum acp
	'RECENT_TOPICS_LIST'            => 'In aktuellen Themen anzeigen',
	'RECENT_TOPICS_LIST_EXPLAIN'    => 'Aktiviere dieses Kontrollkästchen, um Themen in diesem Forum in der Erweiterung ”Aktuelle Themen” anzuzeigen.',

	//acp title
	'RECENT_TOPICS'                 => 'Aktuelle Themen',
	'RT_CONFIG'                     => 'Einstellungen',
	'RECENT_TOPICS_EXPLAIN'         => 'Auf dieser Seite kannst du die Einstellungen der aktuellen Themenerweiterung anpassen.<br /><br />Spezifische Foren können durch ändern der Einstellungen der jeweiligen Foren eingeschlossen oder ausgeschlossen werden.<br />Überprüfe doch auch die Benutzerberechtigungen, so dass sie sich einige Parameter ändern können. die Vorrang haben vor den Einstellungen des Admin-Panels.',

	//allgemeine Einstellungen
	'RT_GLOBAL_SETTINGS'            => 'Globale Einstellungen',
	'RT_DISPLAY_INDEX'              => 'Anzeigen auf der Index-Seite',
	'RT_NUMBER'                     => 'Anzahl Aktuelle Themen',
	'RT_NUMBER_EXP'                 => 'Maximale Anzahl Themen pro Seite',
	'RT_PAGE_NUMBER'                => 'Alle Seiten anzeigen',
	'RT_PAGE_NUMBER_EXP'            => 'Aktiviere diese Option, um alle Seiten anzuzeigen.',
	'RT_PAGE_NUMBERMAX'             => 'Maximale Seitenanzahl',
	'RT_PAGE_NUMBERMAX_EXP'         => 'Lege die maximale Anzahl der Seiten fest (1-999).',
	'RT_MIN_TOPIC_LEVEL'            => 'Minimaler Thementyp',
	'RT_MIN_TOPIC_LEVEL_EXP'        => 'Definiert das Minimum eines Thementyps, der angezeigt wird. Wenn du ein Thementyp angibst, werden nur Themen dieses oder eines höheren Typs angezeigt.',
	'RT_ANTI_TOPICS'                => 'Ausgeschlossene Themen',
	'RT_ANTI_TOPICS_EXP'            => 'Gebe die Subjekt-IDs ein, kommagetrennt (z. B. 7,9), andernfalls 0 um alle Themen an zu zeigen. (wie im url viewtopic.php?t=12345).',
	'RT_PARENTS'                    => 'Übergeordnete Foren anzeigen',
	'RT_PARENTS_EXP'                => 'Übergeordnete Foren in der Liste der aktuellen Themen anzeigen.',

	//Benutzereinstellungen
	'RT_OVERRIDABLE'                => 'Einstellungen, für die das Benutzerfeld Priorität hat',
	'RT_LOCATION'                   => 'Anzeigelage',
	'RT_LOCATION_EXP'               => 'Wähle den Anzeigeort der aktuellen Themen.',
	'RT_TOP'                        => 'Ansicht oben',
	'RT_BOTTOM'                     => 'Ansicht unten',
	'RT_SIDE'                       => 'Ansicht auf die Seite',
	'RT_SORT_START_TIME'            => 'Nach Themen Startzeit sortieren',
	'RT_SORT_START_TIME_EXP'        => 'Wenn diese Option aktiviert ist, werden die Themen nach dem Datum des ersten Beitrags anstelle des letzten Beitrags sortiert.',
	'RT_UNREAD_ONLY'                => 'Nur ungelesene Themen anzeigen',
	'RT_UNREAD_ONLY_EXP'            => 'Diese Option zeigt nur ungelesene Themen an (egal ob diese aktuell sind oder nicht). Diese Funktion nutzt die gleichen Einstellungen (Ausgeschlossene Foren / Themen, etc.) wie die normale Version. Hinweis: diese Funktion steht nur eingeloggten Benutzern zur Verfügung; Gäste sehen die normale „Aktuelle Themen“ Liste.',
	'RT_RESET_DEFAULT'              => 'User Einstellungen zurücksetzen',
	'RT_RESET_DEFAULT_EXP'          => 'Setzt die User Einstellungen zurück auf die Standard Einstellungen',

	//Enable for extensions
	'RT_NICKVERGESSEN_NEWSPAGE'     => 'Unterstützung für erweiterung ’Newspage’ von Nickvergessen',
	'RT_VIEW_ON'                    => 'Aktuelle Themen anzeigen auf:',

	//Donatiies
	'RT_DONATE_URL'             => 'http://www.avathar.be/bbdkp/app.php/page/donate',
	'PAYPAL_IMAGE_URL'          => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
	'PAYPAL_ALT'                => 'Machen eine Spende über PayPal',
	'RT_DONATE'					=> 'Spende an RecentTopics',
	'RT_DONATE_SHORT'			=> 'Spende an RecentTopics',
	'RT_DONATE_EXPLAIN'			=> 'RecentTopics ist zu 100% kostenlos. Wenn du dies eine nützliche Erweiterung findest und du die Autoren unterstützen möchtest, könntest du eine unverbindliche Spende in Erwägung ziehen.',
	)
);
