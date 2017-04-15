<?php
/**
 *
 * @package Recent Topics Extension
 * Spanish translation by Raul [ThE KuKa] (www.phpbb-es.com)
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
	//PCA forum
	'RECENT_TOPICS_LIST'            => 'Ver en "temas recientes"',
	'RECENT_TOPICS_LIST_EXPLAIN'    => 'Serán los temas de este foro los que se mostrarán en el índice de "temas recientes"?',

	//PCA título
	'RECENT_TOPICS'                 => 'Temas Recientes',
	'RECENT_TOPICS_EXPLAIN'         => 'Aquí puedes cambiar la configuración de temas recientes. <br /> <br /> Foro individual puede ser incluido o excluido cambiando su configuración en el PCA. <br /> Además, a través de la configuración de esta página, se puede permiten a los usuarios editar el propio cierta medida los aspectos',
	'RT_CONFIG'                     => 'configuración',

	//configuración general para los usuarios anónimos
	'RT_OVERRIDABLE'                => 'Instituciones a las que los miembros del panel tiene prioridad',
	'RT_DISPLAY_INDEX'              => 'Mostrar en la página de índice',
	'RT_LOCATION'                   => 'Ubicación de la pantalla',
	'RT_LOCATION_EXP'               => 'Elegir un lugar para mostrar los últimos temas.',
	'RT_TOP'                        => 'Mostrar en la parte superior',
	'RT_BOTTOM'                     => 'Mostrar en el fondo',
	'RT_SIDE'                       => 'Mostrar en el lado derecho',
	'RT_SORT_START_TIME'            => 'Ordenar temas por la hora de inicio',
	'RT_SORT_START_TIME_EXP'        => 'Habilitar para ordenar los temas recientes sobre la hora de inicio del tema, en lugar de la última que se respondió.',
	'RT_UNREAD_ONLY'                => 'Mostrar sólo temas no leídos',
	'RT_UNREAD_ONLY_EXP'            => 'Esta opción sólo mostrará temas no leídos (tanto si son "recientes" o no). Esta función utiliza la misma configuración (excluyendo forums/topics etc.) que el modo normal. Nota: esto sólo funciona para usuarios registrados e identificados; los invitados podrán obtener la lista normal.',

	//ajustes globales
	'RT_GLOBAL_SETTINGS'            => 'Ajustes globales',
	'RT_NUMBER'                     => 'Temas recientes',
	'RT_NUMBER_EXP'                 => 'Número de temas mostrados en el índice.',
	'RT_PAGE_NUMBER'                => 'Páginas de temas recientes',
	'RT_PAGE_NUMBER_EXP'            => 'Puede mostrar más temas recientes usando una pequeña paginación. Solo tiene que introducir 1 para desactivar esta función. Si introduce 0, habrá tantas páginas como sean necesarias para mostrar todos los temas.',
	'RT_MIN_TOPIC_LEVEL'            => 'Tipo de nivel mínimo del tema',
	'RT_MIN_TOPIC_LEVEL_EXP'        => 'Determina el tipo de nivel mínimo del tema a mostrar. Se mostrará temas del nivel establecido, y superior.',
	'RT_ANTI_TOPICS'                => 'Temas excluidos',
	'RT_ANTI_TOPICS_EXP'            => 'Separados por "," (Por ejemplo: 7,9)<br />Si no quiere excluir un tema, simplemente introduzca 0.',
	'RT_PARENTS'                    => 'Mostrar foros padre',
	'RT_PARENTS_EXP'                => 'Mostrar foros padre dentro de la fila tema, de temas recientes.',
	'RT_ONLY_SUBSCRIBED'            => 'Mostrar foros subscriptos',
	'RT_ONLY_SUBSCRIBED_EXP'        => 'Mostrar solamente temas de foros subscriptos.',

	//extensiones
	'RT_VIEW_ON'                    => 'Ver temas recientes en:',

	)
);
