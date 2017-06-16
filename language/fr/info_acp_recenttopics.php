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
	//forum acp
	'RECENT_TOPICS_LIST'            => 'Afficher les sujets récents',
	'RECENT_TOPICS_LIST_EXPLAIN'    => 'Si activé, les sujets de ce forum seront affichés dans la liste des sujets récents.',

	//acp title
	'RECENT_TOPICS'                 => 'Sujets récents',
	'RT_CONFIG'                     => 'Configuration',
	'RECENT_TOPICS_EXPLAIN'         => 'Sur cette page il est possible de modifier les paramètres spécifiques de l’extension « Sujets récents ».<br /><br />Les forums peuvent être inclus ou exclus de la liste des sujets récents en modifiant leurs paramètres respectifs depuis le « Panneau d’administration », onglet « FORUMS ».<br />Il est recommandé de vérifier les autorisations des utilisateurs leur permettant de modifier par eux-mêmes certains paramètres présents ci-dessous.',

	//User Overridable settings. these apply for anon users and can be overridden by UCP
	'RT_OVERRIDABLE'                => 'Paramètres personnalisables depuis le « Panneau de l’utilisateur »',
	'RT_DISPLAY_INDEX'              => 'Permet d’afficher la liste des sujets récents sur la page de l’index du forum.',
	'RT_LOCATION'                   => 'Emplacement des sujets récents',
	'RT_LOCATION_EXP'               => 'Permet de sélectionner l’emplacement où afficher la liste des sujets récents (option applicable uniquement aux styles basés sur « prosilver »).',
	'RT_TOP'                        => 'Au-dessus de la liste des forums',
	'RT_BOTTOM'                     => 'En dessous de la liste des forums',
	'RT_SIDE'                       => 'Sur le coté droit de la liste des forums',
	'RT_SORT_START_TIME'            => 'Tri des sujets récents',
	'RT_SORT_START_TIME_EXP'        => 'Permet d’afficher les sujets récents triés selon la date de création du sujet en lieu et place de la date du dernier message.',
	'RT_UNREAD_ONLY'                => 'Affichage uniquement des sujets non lus',
	'RT_UNREAD_ONLY_EXP'            => 'Permet d’afficher uniquement les sujets non lus qu’ils soient récents ou non. Cette fonctionnalité utilise les mêmes paramètres qu’en temps normal (excluant les forums, sujets, etc.). Note : Cette option est uniquement dédiée aux utilisateurs connectés; les invités verront la liste « normale ».',

	//global settings
	'RT_GLOBAL_SETTINGS'            => 'Paramètres globaux',
	'RT_NUMBER'                     => 'Nombre de sujets récents affichés',
	'RT_NUMBER_EXP'                 => 'Permet de saisir le nombre de sujets récents à afficher sur la page de l’index du forum.',
	'RT_PAGE_NUMBER'                => 'Pagination des sujets récents',
	'RT_PAGE_NUMBER_EXP'            => 'Permet de désactiver l’affichage de la pagination des sujets récents. Décocher la case pour activer la pagination. Si la pagination est active, voir l’option « Nombre maximal de pages » pour choisir le nombre de pages à afficher.',
	'RT_MIN_TOPIC_LEVEL'            => 'Niveau minimum du type de sujets affichés',
	'RT_MIN_TOPIC_LEVEL_EXP'        => 'Permet de sélectionner le niveau minimum du type de sujets à afficher dans la liste des sujets récents. Les sujets correspondants au niveau paramétré et aux niveaux supérieurs seront affichés.',
	'RT_ANTI_TOPICS'                => 'Exclusion de sujets de l’affichage',
	'RT_ANTI_TOPICS_EXP'            => 'Permet de saisir les ID séparés par une virgule (exemple : 7,9), des sujets à exclure de l’affichage de la liste des sujets récents.<br />Pour afficher tous les sujets saisir la valeur 0.',
	'RT_PARENTS'                    => 'Affichage du forum parent',
	'RT_PARENTS_EXP'                => 'Permet d’afficher les forums parents dans l’arborescence des forums de la liste des sujets récents.',
	'RT_RESET_DEFAULT'              => 'Réinitialisation des paramètres utilisateur',
	'RT_RESET_DEFAULT_EXP'          => 'Permet de réinitialiser les paramètres par défaut (ceux sur cette page) aux utilisateurs.',
	'RT_PAGE_NUMBERMAX'             => 'Nombre maximal de pages',
	'RT_PAGE_NUMBERMAX_EXP'         => 'Permet de saisir le nombre maximal de pages (1 à 999) à afficher dans la pagination des sujets récents.',

	//Enable for extensions
	'RT_NICKVERGESSEN_NEWSPAGE'     => 'Support de l’extension « NewsPage »',
	'RT_VIEW_ON'                    => 'Permet d’afficher les sujets récents sur les pages de l’extension :',

	)
);
