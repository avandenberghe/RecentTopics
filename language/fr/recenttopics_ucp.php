<?php
/**
*
* Recent Topics extension for the phpBB Forum Software package.
* French translation by by ForumsFaciles (http://www.forumsfaciles.fr) &  Galixte (http://www.galixte.com) & Sajaki (http://www.avathar.be)
*
* @copyright (c) 2015 PayBas
* @license GNU General Public License, version 2 (GPL-2.0)
*
* Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
*
*/

/**
* DO NOT CHANGE
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

$lang = array_merge($lang, array(
	'RT_ENABLE'              => 'Afficher la liste des sujets récents',
	'RT_TOP'                 => 'Au-dessus de la liste des forums',
	'RT_BOTTOM'              => 'En dessous de la liste des forums',
	'RT_SIDE'                => 'Sur le coté droit de la liste des forums',
	'RT_LOCATION'            => 'Sélectionner l’emplacement de la liste des sujets récents',
	'RT_LOCATION_EXP'        => 'Permet de sélectionner l’emplacement où afficher la liste des sujets récents.',
	'RT_NUMBER'              => 'Nombre de sujets récents affichés',
	'RT_NUMBER_EXP'          => 'Permet de saisir le nombre maximum de sujets récents à afficher par page.',
	'RT_SORT_START_TIME'     => 'Trier les sujets récents',
	'RT_SORT_START_TIME_EXP' => 'Permet d’afficher les sujets récents triés selon la date de création du sujet en lieu et place de la date du dernier message.',
	'RT_UNREAD_ONLY'         => 'Afficher uniquement les sujets non lus dans la liste des sujets récents',
	)
);
