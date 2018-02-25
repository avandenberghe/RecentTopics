<?php
/**
 *
 * @package Recent Topics Extension
 * Spanish translation by Raul [ThE KuKa] (www.phpbb-es.com)
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
	'RT_ENABLE'              => 'Mostrar la lista de temas recientes',
	'RT_TOP'                 => 'Mostrar en la parte superior',
	'RT_BOTTOM'              => 'Mostrar en la parte inferior',
	'RT_SIDE'                => 'Mostrar en el lado derecho',
	'RT_LOCATION'            => 'Seleccionar posición',
	'RT_LOCATION_EXP'        => 'Elija una posición para mostrar la lista de temas recientes',
	'RT_NUMBER'              => 'Temas Recientes',
	'RT_NUMBER_EXP'          => 'Número de temas a mostrar.',
	'RT_SORT_START_TIME'     => 'Ordenar los temas recientes por la hora de inicio de los temas',
	'RT_SORT_START_TIME_EXP' => 'Los temas están ordenados de acuerdo con su respectiva fecha de inicio y no de acuerdo a la del último mensaje',
	'RT_UNREAD_ONLY'         => 'Mostrar solo los temas no leídos en la lista de temas recientes',
	)
);
