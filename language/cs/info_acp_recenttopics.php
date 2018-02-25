<?php
/**
 *
 * @package Recent Topics Extension
 * Czech translation by R3gi
 *
 * @copyright (c) 2017 PayBas
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
	//forum acp
	'RECENT_TOPICS_LIST'            => 'Zahrnout obsah do nedávných témat',
	'RECENT_TOPICS_LIST_EXPLAIN'    => 'Je-li povoleno, témata z tohoto fóra mohou být zobrazena v bloku nedávných témat (rozšíření).',
	//acp title
	'RECENT_TOPICS'                 => 'Nedávná témata',
	'RT_CONFIG'                     => 'Nastavení',
	'RECENT_TOPICS_EXPLAIN'         => 'Na této stránce můžete měnit nastavení rozšíření „Recent Topics“.<br /><br />Konkrétní fóra lze zahrnout či vyloučit změnou nastavení jednotlivých fór.<br />Ujistěte se také, že mají uživatelé správně nastavena uživatelská oprávnění dle vašich potřeb. Na základě oprávnění si mohou uživatelé některá níže uvedená nastavení měnit dle svých potřeb ve svém uživatelském panelu.',
	//global settings
	'RT_GLOBAL_SETTINGS'            => 'Globální nastavení',
	'RT_DISPLAY_INDEX'              => 'Zobrazovat na úvodní stránce?',
	'RT_NUMBER'                     => 'Nedávná témata',
	'RT_NUMBER_EXP'                 => 'Počet nedávných témat k zobrazení.',
	'RT_PAGE_NUMBER'                => 'Počet stránek nedávných témat',
	'RT_PAGE_NUMBER_EXP'            => 'Zaškrtnutím této volby vypnete stránkování pro blok nedávných témat.',
	'RT_PAGE_NUMBERMAX'             => 'Maximální počet stránek',
	'RT_PAGE_NUMBERMAX_EXP'         => 'Nastavte maximum stránek (1-999) pro zobrazení ve stránkování nedávných témat (není-li přepsáno jiným nastavením).',
	'RT_MIN_TOPIC_LEVEL'            => 'Minimální úroveň tématu',
	'RT_MIN_TOPIC_LEVEL_EXP'        => 'Určuje minimální úrověň typu tématu pro zobrazení. Zobrazena budou pouze témata zvolené úrovně a vyšší.',
	'RT_ANTI_TOPICS'                => 'Vyloučená témata',
	'RT_ANTI_TOPICS_EXP'            => 'Identifikátory témat k vyloučení, oddělené čárkou „,“ (příklad: 7,9)<br />',
	'RT_PARENTS'                    => 'Zobrazit nadřazená fóra',
	'RT_PARENTS_EXP'                => 'Zobrazit nadřazená fóra v řádku podrobností pod názvem nedávného tématu.',
	//User Overridable settings. these apply for anon users and can be overridden by UCP
	'RT_OVERRIDABLE'                => 'Výchozí nastavení (lze přepsat v uživatelském panelu)',
	'RT_LOCATION'                   => 'Místo zobrazení',
	'RT_LOCATION_EXP'               => 'Nastavení umístění bloku nedávných témat. (prosilver)',
	'RT_TOP'                        => 'Zobrazit nahoře',
	'RT_BOTTOM'                     => 'Zobrazit dole',
	'RT_SIDE'                       => 'Zobrazit na straně',
	'RT_SORT_START_TIME'            => 'Řadit témata dle času založení',
	'RT_SORT_START_TIME_EXP'        => 'Je-li povoleno, nedávná témata budou řazena podle času založení namísto času odeslání posledního příspěvku.',
	'RT_UNREAD_ONLY'                => 'Zobrazovat pouze nepřečtená témata',
	'RT_UNREAD_ONLY_EXP'            => 'Je-li povoleno, budou zobrazena pouze nepřečtená témata (nehledě na to, zda jsou „nedávná“ či ne). Tato funkce používá stejné nastavení (vyjma fór/témat apod.) jako běžný režim. Poznámka: Nastavení funguje jen pro přihlášené uživatele, návštěvníci uvidí stále jen běžný seznam.',
	'RT_RESET_DEFAULT'              => 'Resetovat uživatelské nastavení',
	'RT_RESET_DEFAULT_EXP'          => 'Obnovit uživatelské nastavení na výchozí hodnoty.',
	//Enable for extensions
	'RT_NICKVERGESSEN_NEWSPAGE'     => 'Podpora pro rozšíření NewsPage',
	'RT_VIEW_ON'                     => 'Zobrazit nedávná témata na:',
	//Donation
	'RT_DONATE_URL'             => 'http://www.avathar.be/bbdkp/app.php/page/donate',
	'PAYPAL_IMAGE_URL'          => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
	'PAYPAL_ALT'                => 'Přispět pomocí PayPal',
	'RT_DONATE'					=> 'Přispějte na vývoj RecentTopics',
	'RT_DONATE_SHORT'			=> 'Podpořte vývoj rozšíření RecentTopics',
	'RT_DONATE_EXPLAIN'			=> 'Rozšíření RecentTopics je zcela zdarma. Jedná se o hobby projekt, kterému věnujeme spoustu svého času a financí. Děláme to rádi, ale čas je drahý. Pokud vám rozšíření RecentTopics přijde užitečné, budeme velmi rádi, když nás podpoříte.',
	)
);
