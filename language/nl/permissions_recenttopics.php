<?php
/**
 *
 * @package Recent Topics Extension
 * English translation by PayBas, Sajaki
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
	'ACL_U_RT_VIEW'            => 'Recente Onderwerpen: kan «Recente Onderwerpen» zien.',
	'ACL_U_RT_ENABLE'          => 'Recente Onderwerpen: kan «Recente Onderwerpen» activeren of desactiveren.',
	'ACL_U_RT_LOCATION'        => 'Recente Onderwerpen: kan plaatstinstelling wijzigen in gebruikerspaneel.',
	'ACL_U_RT_SORT_START_TIME' => 'Recente Onderwerpen: kan sorteringsvolgorde wijzigen in gebruikerspaneel.',
	'ACL_U_RT_UNREAD_ONLY'     => 'Recente Onderwerpen: kan keuze ter weergave van ongelezen onderwerpen wijzigen.',
	'ACL_U_RT_NUMBER'          => 'Recente Onderwerpen: kan het standaard aantal onderwerpen per pagina wijzigen.',
	)
);
