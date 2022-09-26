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
	//forum acp
	'RECENT_TOPICS_LIST'            => 'Wyświetlaj w “Ostatnie Tematy”',
	'RECENT_TOPICS_LIST_EXPLAIN'    => 'Włącz, aby wyświetlać tematy z forum w rozszerzeniu RecentTopics.',

	//acp title
	'RECENT_TOPICS'                 => 'Ostatnie Tematy',
	'RT_CONFIG'                     => 'Konfiguracja',
	'RECENT_TOPICS_EXPLAIN'         => 'Na tej stronie możesz zmienić ustawienia rozszerzenia Ostatnie Tematy.<br /><br />Wybrane fora mogą być dodane lub usunięte poprzez edycję wybranego fora w ACP.<br />Sprawdź także, które ustawienia dotyczące tego rozszerzenia powinny być zarządzalne przez samych użytkowników.',

	//global settings
	'RT_GLOBAL_SETTINGS'            => 'Ustawienia Globalne',
	'RT_DISPLAY_INDEX'              => 'Wyświetj na Stronie Głównej',
	'RT_NUMBER'                     => 'Liczba ostatnich tematów do wyświetlenia',
	'RT_NUMBER_EXP'                 => 'Maksymalna liczba tematów do wyświetlenia na stronie.',
	'RT_PAGE_NUMBER'                => 'Wyświetlaj wszystkie strony',
	'RT_PAGE_NUMBER_EXP'            => 'Ta opcja nadpisuje maksymalną liczbę stron do wyświetlenia, wyświetla wszystkie strony bez względu na ustawione ograniczenie.',
	'RT_PAGE_NUMBERMAX'             => 'Maksymalna liczba stron',
	'RT_PAGE_NUMBERMAX_EXP'         => 'Ustaw maksymalną liczbę stron do wyświetlenia w rozszerzeniu (ta opcja może zostać nadpisana).',
	'RT_MIN_TOPIC_LEVEL'            => 'Minimalna ważność tematu',
	'RT_MIN_TOPIC_LEVEL_EXP'        => 'Określa minimalny poziom typów wiadomości, które mogą być wyświetlane w Ostatnich Tematach. Wyświetli tylko tematy o wskazanym poziomie lub wyższym.',
	'RT_ANTI_TOPICS'                => 'Pomijaj tematy o wskazach ID',
	'RT_ANTI_TOPICS_EXP'            => 'Numery ID tematów do pominięcia, rozdzielone znakiem przecinka “,” (Na przykład: 7,9)<br />Wartość 0 wyłącza funkcjonalność.',
	'RT_PARENTS'                    => 'Wyświetlaj nadrzędne fora',
	'RT_PARENTS_EXP'                => 'Wyświetlaj nazwę i odnośnik nadrzędnego forum w wyświetlanym temacie',

	//User Overridable settings. these apply for anon users and can be overridden by UCP
	'RT_OVERRIDABLE'                => 'Ustawienia nadrzędne UCP',
	'RT_LOCATION'                   => 'Pozycja wyświetlania',
	'RT_LOCATION_EXP'               => 'Wybierz pozycje wyświetlania Ostatnich Tematów.',
	'RT_TOP'                        => 'Wyświetlaj na górze',
	'RT_BOTTOM'                     => 'Wyświetlaj na dole',
	'RT_SIDE'                       => 'Wyświetlaj z boku',
	'RT_SORT_START_TIME'            => 'Sortuj po dacie rozpoczęcia tematu',
	'RT_SORT_START_TIME_EXP'        => 'Włącz sortowanie po dacie rozpoczęcia tematu, zamiast po dacie rozpoczęcia posta.',
	'RT_UNREAD_ONLY'                => 'Wyświetlaj tylko nieprzeczytane tematy',
	'RT_UNREAD_ONLY_EXP'            => 'Włącz wyświetlanie tylko nieprzeczytanych tematów (bez znaczenia, czy są to ostatnie posty, czy nie). Ta funkcja używa tych samych ustawień (z wyjątkiem forów/tematów itd.) jak normalny mod. Ważne: działa tylko dla zalogowanych użytkowników, goście otrzymają normalną listę.',
	'RT_RESET_DEFAULT'              => 'Resetuj ustawienia użytkowników',
	'RT_RESET_DEFAULT_EXP'          => 'Przywróć ustawienia użytkowników do tych ustawionych na tej stronie (najpierw należy wysłać zmiany).',

	//Enable for extensions
	'RT_NICKVERGESSEN_NEWSPAGE'     => 'Wsparcie dla rozszerzenia NewsPage',
	'RT_VIEW_ON'                    => 'Wyświetlaj ostatnie tematy na:',

	//Version checker
	'RT_VERSION_CHECK'              => 'Wersja',
	'RT_LATEST_VERSION'             => 'Ostatnia wersja',
	'RT_EXT_VERSION'                => 'Wersja rozszerzenia',
	'RT_VERSION_ERROR'              => 'Wystąpił błąd podczas sprawdzania wersji!',
	'RT_CHECK_UPDATE'               => 'Odwiedź <a href="http://www.avathar.be/bbdkp/index.php">avathar.be</a> aby sprawdzić, czy są dostępne aktualizacje.',

	//Donation
	'RT_DONATE_URL'                 => 'http://www.avathar.be/forum/app.php/page/donate',
	'PAYPAL_IMAGE_URL'              => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
	'PAYPAL_ALT'                    => 'Donacja przez PayPal',
	'RT_DONATE'                     => 'Darowizna dla RecentTopics',
	'RT_DONATE_SHORT'               => 'Przekaż darowiznę dla RecentTopics',
	'RT_DONATE_EXPLAIN'             => 'Rozszerzenie "Ostatnie Tematy" jest w pełni darmowe. Jest to projekt wykonywany po godzinach, dla zabawy, na który przeznaczam mój czas i pieniądze. Jeśli lubisz używać rozszerzenia RecentTopics, proszę rozważ dokonanie darowizny, będę bardzo wdzięczny, lecz nie czuj się do tego zobowiązany.',
	)
);
