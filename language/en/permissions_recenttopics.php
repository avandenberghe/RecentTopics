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
	'ACL_U_RT_VIEW'            => 'Recent Topics: can view Recent topics.',
	'ACL_U_RT_ENABLE'          => 'Recent Topics: can enable or disable Displaying Recent Topics.',
	'ACL_U_RT_LOCATION'        => 'Recent Topics: can select display location of Recent topics blocks.',
	'ACL_U_RT_SORT_START_TIME' => 'Recent Topics: can change topic sort order.',
	'ACL_U_RT_UNREAD_ONLY'     => 'Recent Topics: can change setting to only display unread topics.',
	'ACL_U_RT_NUMBER'          => 'Recent Topics: can change setting of number of recent topics to show per page.',
	)
);
