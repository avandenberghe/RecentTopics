<?php
/**
 *
 * @package Recent Topics Extension
 * Tradução Portuguesa by phpbbpt
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
	//PCA Fórum
	'RECENT_TOPICS_LIST'            => 'Exibir tópicos recentes"',
	'RECENT_TOPICS_LIST_EXPLAIN'    => 'Ativar para exibir tópicos neste fórum na extensão "tópicos recentes"',

	//PCA título
	'RECENT_TOPICS'                 => 'Tópicos recentes',
	'RT_CONFIG'                     => 'Configuração',
	'RECENT_TOPICS_EXPLAIN'         => 'Nesta página você pode alterar as configurações específicas para a extensão Recent Topics. <br /> <br /> Fóruns específicos podem ser incluídos ou excluídos editando os respectivos fóruns em seu ACP. Certifique-se de verificar as permissões de usuário, que permitem aos usuários alterar algumas das configurações encontradas abaixo para si.',

	//configuração geral para usuários anônimos
	'RT_OVERRIDABLE'                => 'UCP configurações substituíveis',
	'RT_DISPLAY_INDEX'              => 'Mostrar na página de índice',
	'RT_LOCATION'                   => 'Mostrar local',
	'RT_LOCATION_EXP'               => 'Selecionar local para exibir tópicos recentes.',
	'RT_TOP'                        => 'Mostrar no topo',
	'RT_BOTTOM'                     => 'Mostrar no fundo',
	'RT_SIDE'                       => 'Mostrar no lado direito',
	'RT_SORT_START_TIME'            => 'Ordenar tópicos por a hora de início',
	'RT_SORT_START_TIME_EXP'        => 'Habilite para classificar tópicos recentes pela hora de início do tópico, em vez da última hora de publicação.',
	'RT_UNREAD_ONLY'                => 'Mostrar apenas tópicos não lidos',
	'RT_UNREAD_ONLY_EXP'            => 'Ativar para exibir somente tópicos não lidos (se eles são "recentes" ou não). Esta função usa as mesmas configurações (excluindo fóruns / tópicos etc.) como modo normal. Nota: isso só funciona para usuários conectados; Os convidados receberão a lista normal.',

	//configurações globais
	'RT_GLOBAL_SETTINGS'            => 'Configurações globais',
	'RT_NUMBER'                     => 'tópicos recentes',
	'RT_NUMBER_EXP'                 => 'Número de tópicos a apresentar.',
	'RT_PAGE_NUMBER'                => 'Páginas de tópicos recentes',
	'RT_PAGE_NUMBER_EXP'            => 'Digite 1 para desativar a paginação de tópicos recentes.',
	'RT_MIN_TOPIC_LEVEL'            => 'Nível mínimo do tipo de tópico',
	'RT_MIN_TOPIC_LEVEL_EXP'        => 'Determina o nível mínimo do tipo de tópico a ser exibido. Ele só exibirá tópicos do nível definido e mais alto.',
	'RT_ANTI_TOPICS'                => 'Topicos excluídos',
	'RT_ANTI_TOPICS_EXP'            => 'Os IDs de tópicos a excluir, separados por "," (Exemplo: 7,9) </ b>',
	'RT_PARENTS'                    => 'Mostrar Fórum Pai',
	'RT_PARENTS_EXP'                => 'Exibir fóruns pai dentro da linha tópico de tópicos recentes.',
    'RT_RESET_DEFAULT'              => 'Reset configurações do usuário.',
	'RT_RESET_DEFAULT_EXP'          => 'Reset as configurações do usuário para  padrão.',

	//extensões
	'RT_NICKVERGESSEN_NEWSPAGE'     => 'Suporte para a extensão NewsPage',
    'RT_VIEW_ON'                    => 'Ver tópicos recentes:',

	)
);
