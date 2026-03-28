<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 * Portuguese translation by phpbbpt
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
	'ACL_U_RT_VIEW'            => 'Tópicos recentes: pode ver',
	'ACL_U_RT_ENABLE'          => 'Tópicos recentes: pode ativar ou desativar',
	'ACL_U_RT_LOCATION'        => 'Tópicos recentes: pode selecionar o local de exibição',
	'ACL_U_RT_SORT_START_TIME' => 'Tópicos recentes: pode alterar a ordem de classificação',
	'ACL_U_RT_UNREAD_ONLY'     => 'Tópicos recentes: pode definir o modo não lido somente',
	'ACL_U_RT_NUMBER'          => 'Tópicos recentes: pode definir o número de tópicos por página',
	)
);
