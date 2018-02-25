<?php
/**
 *
 * @package Recent Topics Extension
 * English translation by PayBas
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
	'ACL_U_RT_VIEW'            => 'Recent Topics: can view.',
	'ACL_U_RT_ENABLE'          => 'Recent Topics: can enable or disable',
	'ACL_U_RT_LOCATION'        => 'Recent Topics: can select display location',
	'ACL_U_RT_SORT_START_TIME' => 'Recent Topics: can change sort order',
	'ACL_U_RT_UNREAD_ONLY'     => 'Recent Topics: can set unread-only mode',
	'ACL_U_RT_NUMBER'          => 'Recent Topics: can set number of topics per page',
	)
);
