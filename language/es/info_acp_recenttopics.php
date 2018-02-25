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
	// PCA foro
	'RECENT_TOPICS_LIST'            => 'Mostrar en “Temas Recientes”',
	'RECENT_TOPICS_LIST_EXPLAIN'    => 'Activar para mostrar los temas de este foro en “Temas Recientes”',

	//PCA título
	'RECENT_TOPICS'                 => 'Temas Recientes',
	'RT_CONFIG'                     => 'Configuración',
	'RECENT_TOPICS_EXPLAIN'         => 'En esta página puede cambiar las opciones especificas para la extensión “Temas Recientes”.<br /><br />Foros específicos pueden ser incluídos o excluídos editando los respectivos foros en el PCA.<br />Asegúrese también de comprobar los permisos de sus usuarios, los cuales permiten a los usuarios cambiar individualmente algunas de las opciones encontradas abajo.',

	//ajustes globales
	'RT_GLOBAL_SETTINGS'            => 'Opciones globales',
	'RT_DISPLAY_INDEX'              => 'Mostrar en el índice',
	'RT_NUMBER'                     => 'Temas Recientes',
	'RT_NUMBER_EXP'                 => 'Número de temas a mostrar.',
	'RT_PAGE_NUMBER'                => 'Páginas de temas recientes',
	'RT_PAGE_NUMBER_EXP'            => 'Activar esta opción desactivará la paginación de la lista de temas recientes',
	'RT_PAGE_NUMBERMAX'		=> 'Número máximo de páginas',
	'RT_PAGE_NUMBERMAX_EXP'		=> 'Definir el número máximo de páginas (1-999)',
	'RT_MIN_TOPIC_LEVEL'            => 'Nivel de tema mínimo',
	'RT_MIN_TOPIC_LEVEL_EXP'        => 'Determina el nivel de tema mínimo para poder se mostrado. Solo mostrará temas del nivel especificado y superior.',
	'RT_ANTI_TOPICS'                => 'Temas excluidos',
	'RT_ANTI_TOPICS_EXP'            => 'Las IDs de los temas a excluír, separados por "," (Por ejemplo: 7,9)<br />Si no quiere excluir un tema, simplemente introduzca 0.',
	'RT_PARENTS'                    => 'Mostrar foros padre',
	'RT_PARENTS_EXP'                => 'Mostrar foros padre dentro de la fila del tema de “Temas Recientes”.',

	// Opciones modificables por el usuario. Afectan a los usuarios anónimos y pueden ser sobreescritas por el PCU
	'RT_OVERRIDABLE'                => 'Opciones sobreescribibles del PCU',
	'RT_LOCATION'                   => 'Posición',
	'RT_LOCATION_EXP'               => 'Elija un lugar para mostrar la lista de temas recientes.',
	'RT_TOP'                        => 'Mostrar en la parte superior',
	'RT_BOTTOM'                     => 'Mostrar en la parte inferior',
	'RT_SIDE'                       => 'Mostrar en el lado derecho',
	'RT_SORT_START_TIME'            => 'Ordenar temas por la hora de inicio',
	'RT_SORT_START_TIME_EXP'        => 'Habilitar para ordenar la lista de temas recientes en base a la hora de inicio del tema, en lugar de la de la última respesta.',
	'RT_UNREAD_ONLY'                => 'Mostrar solo temas no leídos',
	'RT_UNREAD_ONLY_EXP'            => 'Activar para mostrar solo temas no leídos (tanto si son “recientes” o no). Esta función utiliza la misma configuración (excluyendo foros, temas, etc.) que el modo normal. Nota: esto sólo funciona para usuarios identificados; los invitados verán la lista normal.',
	'RT_RESET_DEFAULT'              => 'Reiniciar la configuración de los usuarios',
	'RT_RESET_DEFAULT_EXP'          => 'Devuelve la configuración independiente de cada usuario de “Temas Recientes” al valor por defecto.',

	// extensiones
	'RT_NICKVERGESSEN_NEWSPAGE'     => 'Soporte para la extensión “NewsPage”',
	'RT_VIEW_ON'                    => 'Ver “Temas Recientes” en:',

	//Donation
	'RT_DONATE_URL'             => 'http://www.avathar.be/bbdkp/app.php/page/donate',
	'PAYPAL_IMAGE_URL'          => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
	'PAYPAL_ALT'                => 'Donar usando PayPal',
	'RT_DONATE'					=> 'Donar a RecentTopics',
	'RT_DONATE_SHORT'			=> 'Haga una donación a RecentTopics',
	'RT_DONATE_EXPLAIN'			=> 'RecentTopics es 100% gratis. Es un proyecto que hago en mi tiempo libre donde invierto mi tiempo y dinero por gusto. Si disfruta utilizando RecentTopics, por favor considere hacer una donación. Sin ataduras.',
	)
);
