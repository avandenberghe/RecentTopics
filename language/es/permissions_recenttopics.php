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

$lang = array_merge(
	$lang, array(
	'ACL_U_RT_VIEW'            => 'Temas recientes: Puede ver la lista de temas recientes',
	'ACL_U_RT_ENABLE'          => 'Temas recientes: Puede activar o desactivar la lista de temas recientes',
	'ACL_U_RT_LOCATION'        => 'Temas recientes: Puede seleccionar la posición donde será mostrada',
	'ACL_U_RT_SORT_START_TIME' => 'Temas recientes: Puede cambiar el método de ordenación',
	'ACL_U_RT_UNREAD_ONLY'     => 'Temas recientes: Puede activar el módo “solo no leídos”',
	'ACL_U_RT_NUMBER'          => 'Temas recientes: puede establecer el número de temas por página',
	)
);
