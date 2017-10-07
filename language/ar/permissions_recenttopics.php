<?php
/**
 *
 * @package Recent Topics Extension
 * Arabic translation by Bassel Taha Alhitary (www.alhitary.net)
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
	'ACL_U_RT_VIEW'            => 'يستطيع مُشاهدة الإضافة : أحدث المواضيع',
	'ACL_U_RT_ENABLE'          => 'يستطيع تفعيل أو تعطيل الإضافة : أحدث المواضيع',
	'ACL_U_RT_LOCATION'        => 'يستطيع تحديد مكان ظهور الإضافة : أحدث المواضيع',
	'ACL_U_RT_SORT_START_TIME' => 'يستطيع تعديل طريقة الترتيب للإضافة : أحدث المواضيع',
	'ACL_U_RT_UNREAD_ONLY'     => 'يستطيع ضبط المواضيع الغير مقروءة فقط للإضافة : أحدث المواضيع',
	)
);
