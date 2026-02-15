<?php
/**
 *
 * @package Recent Topics Extension
 * Swedish translation
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
	'ACL_U_RT_VIEW'            => 'Senaste trådar: kan visa senaste trådar.',
	'ACL_U_RT_ENABLE'          => 'Senaste trådar: kan aktivera eller inaktivera visning av senaste trådar.',
	'ACL_U_RT_LOCATION'        => 'Senaste trådar: kan välja visningsplats för senaste trådar.',
	'ACL_U_RT_SORT_START_TIME' => 'Senaste trådar: kan ändra sorteringsordning för trådar.',
	'ACL_U_RT_UNREAD_ONLY'     => 'Senaste trådar: kan ändra inställning för att bara visa olästa trådar.',
	'ACL_U_RT_NUMBER'          => 'Senaste trådar: kan ändra antal senaste trådar att visa per sida.',
	)
);
