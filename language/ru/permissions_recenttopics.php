<?php
/**
 *
 * @package Recent Topics Extension
 * Russian translation by HD321kbps
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

$lang = array_merge($lang, array(
	'ACL_U_RT_VIEW'				=> 'Последние темы: Может видеть последние темы ',
	'ACL_U_RT_ENABLE'			=> 'Последние темы: Может отключать и включать последние темы',
	'ACL_U_RT_LOCATION'			=> 'Последние темы: Может выбирать вид отображение последних тем',
	'ACL_U_RT_SORT_START_TIME'	=> 'Последние темы: Может менять порядок сортировки последних тем',
	'ACL_U_RT_UNREAD_ONLY'		=> 'Последние темы: Может включать режим только непрочитаных тем в последних темах',
	'ACL_U_RT_NUMBER'           => 'Последние темы: можно задать количество тем на страницу',
));
