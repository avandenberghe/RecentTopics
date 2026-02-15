<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 * Spanish translation by Raul [ThE KuKa] (www.phpbb-es.com)
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
	'RECENT_TOPICS'    => 'Temas Recientes',
	'RT_NO_TOPICS'		=> 'No hay nuevos temas que mostrar.',
	'VIEWING_RECENT_TOPICS'	=> 'Viendo <a href="%s">Temas Recientes</a>',
	'EXTENSION_REQUIRES_330'	=> 'Esta extensión requiere phpBB 3.3.0 o superior.',
	)
);
