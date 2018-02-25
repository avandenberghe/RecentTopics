<?php
/**
 *
 * @package Recent Topics Extension
 * Dutch translation by PayBas, Sajaki
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
		'RECENT_TOPICS_LIST'            => 'Weergeven in Recente Onderwerpen',
		'RECENT_TOPICS_LIST_EXPLAIN'    => 'Vink dit aan om onderwerpen van dit forum weer te geven in de ”Recente Onderwerpen” extensie.',

		//acp title
		'RECENT_TOPICS'                 => 'Recente Onderwerpen',
		'RT_CONFIG'                     => 'Instellingen',
		'RECENT_TOPICS_EXPLAIN'         => 'Hier kan je de instellingen aanpassen van de Recente Onderwerpen Extensie.<br /><br />forumactivering kan ingesteld worden in het Forum beheerderspaneel voor dat forum.<br />Ga ook uw gebruikerspaneel na dat voorrang heeft op beheerderspaneelinstellingen.',

		//algemene instellingen
		'RT_GLOBAL_SETTINGS'            => 'Algemene instellingen',
		'RT_DISPLAY_INDEX'              => 'Toon op de index pagina',
		'RT_NUMBER'                     => 'Aantal recente onderwerpen',
		'RT_NUMBER_EXP'                 => 'Maximum aantal onderwerpen per pagina.',
		'RT_PAGE_NUMBER'                => 'Toon alle pagina’s',
		'RT_PAGE_NUMBER_EXP'            => 'Aanvinken om alle pagina’s weer te geven.',
		'RT_PAGE_NUMBERMAX'             => 'Maximum aantal pagina’s',
		'RT_PAGE_NUMBERMAX_EXP'         => 'Stel het maximum aantal pagina’s in (1-999).',
		'RT_MIN_TOPIC_LEVEL'            => 'Onderwerptypes',
		'RT_MIN_TOPIC_LEVEL_EXP'        => 'Stel het minimum weer te geven onderwerptype in.',
		'RT_ANTI_TOPICS'                => 'Uitgesloten onderwerpen',
		'RT_ANTI_TOPICS_EXP'            => 'Vul de onderwerp id’s in (bijvoorbeeld 7,9), anders 0. (deze nummers vind je in de url viewtopic.php?t=12345)',
		'RT_PARENTS'                    => 'Weergeven van hoofdforums',
		'RT_PARENTS_EXP'                => 'Toon de hoofdforums in de onderwerpregel van de recente onderwerpen.',

		//user instellingen
		'RT_OVERRIDABLE'                => 'Instellingen waarvoor gebruikerspaneel voorrang heeft',
		'RT_LOCATION'                   => 'Blok instellingen',
		'RT_LOCATION_EXP'               => 'Kies plaats van ’Recente onderwerpen’ blok.',
		'RT_TOP'                        => 'Toon boven',
		'RT_BOTTOM'                     => 'Toon beneden',
		'RT_SIDE'                       => 'Toon rechts',
		'RT_SORT_START_TIME'            => 'Sorteer op onderwerptijdstip',
		'RT_SORT_START_TIME_EXP'        => 'Sorteer op onderwerptijdstip, niet op tijdstip laatste reactie',
		'RT_UNREAD_ONLY'                => 'Enkel ongelezen onderwerpen weergeven',
		'RT_UNREAD_ONLY_EXP'            => 'Activeer deze optie om enkel ongelezen recente onderwerpen weer te geven.',
		'RT_RESET_DEFAULT'              => 'Stel gebruikersinstellingen opnieuw in',
		'RT_RESET_DEFAULT_EXP'          => 'Stel instellingen van alle gebruikers opnieuw in tot de standaard',

		//ondersteuning andere extensies
		'RT_NICKVERGESSEN_NEWSPAGE'     => 'Ondersteuning van nieuwspagina extensie Nickvergessen',
		'RT_VIEW_ON'                    => 'Toon recente onderwerpen op:',

		//Donatiies
		'RT_DONATE_URL'             => 'http://www.avathar.be/bbdkp/app.php/page/donate',
		'PAYPAL_IMAGE_URL'          => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
		'PAYPAL_ALT'                => 'Doe een donatie via PayPal',
		'RT_DONATE'					=> 'donatie aan RecentTopics',
		'RT_DONATE_SHORT'			=> 'Doe een donatie aan RecentTopics',
		'RT_DONATE_EXPLAIN'			=> 'RecentTopics is 100% gratis. Als je dit een nuttige extensie vindt en je de auteurs wil ondersteunen, kan je overwegen om een vrijblijvende donatie te doen.',
	)
);
