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
	//ACP Fórum
	'RECENT_TOPICS_LIST'            => 'Exibir tópicos recentes"',
	'RECENT_TOPICS_LIST_EXPLAIN'    => 'Ativar para exibir tópicos neste fórum na extensão "tópicos recentes"',

	//PCA título
	'RECENT_TOPICS'                 => 'Tópicos recentes',
	'RT_CONFIG'                     => 'Configuração',
	'RECENT_TOPICS_EXPLAIN'         => 'Nesta página você pode alterar as configurações específicas para a extensão Recent Topics. <br /> <br /> Fóruns específicos podem ser incluídos ou excluídos editando os respectivos fóruns em seu ACP. Certifique-se de verificar as permissões de usuário, que permitem aos usuários alterar algumas das configurações encontradas abaixo para si.',

	//configurações globais
	'RT_GLOBAL_SETTINGS'            => 'Configurações globais',
	'RT_DISPLAY_INDEX'              => 'Exibir na página de índice',
	'RT_NUMBER'                     => 'Número de tópicos recentes para mostrar',
	'RT_NUMBER_EXP'                 => 'Número máximo de tópicos a serem exibidos por página.',
	'RT_PAGE_NUMBER'                => 'Mostrar todas as páginas de tópicos recentes',
	'RT_PAGE_NUMBER_EXP'            => 'Verifique para substituir o número máximo de páginas exibidas.',
	'RT_PAGE_NUMBERMAX'             => 'Número máximo de páginas',
	'RT_PAGE_NUMBERMAX_EXP'         => 'Defina o máximo da página (1-999) para exibir na paginação dos tópicos recentes, a menos que seja substituído.',
	'RT_MIN_TOPIC_LEVEL'            => 'Nível mínimo do tipo de tópico',
	'RT_MIN_TOPIC_LEVEL_EXP'        => 'Determina o nível mínimo do tipo de tópico a ser exibido. Ele só exibirá tópicos do nível definido e mais alto.',
	'RT_ANTI_TOPICS'                => 'ID de tópico excluído',
	'RT_ANTI_TOPICS_EXP'            => 'Os IDs de tópicos a excluir, separados por "," (Exemplo: 7,9) <br />O valor 0 desabilita esse comportamento.',
	'RT_PARENTS'                    => 'Mostrar Fórum Pai',
	'RT_PARENTS_EXP'                => 'Exibir fóruns pai dentro da linha tópico de tópicos recentes.',

	//configuração geral para usuários anônimos
	'RT_OVERRIDABLE'                => 'UCP configurações substituíveis',
	'RT_LOCATION'                   => 'Exibir localização',
	'RT_LOCATION_EXP'               => 'Selecione o local para exibir tópicos recentes.',
	'RT_TOP'                        => 'Mostrar no topo',
	'RT_BOTTOM'                     => 'Mostrar no fundo',
	'RT_SIDE'                       => 'Mostrar no lado',
	'RT_SORT_START_TIME'            => 'Ordenar tópicos por a hora de início',
	'RT_SORT_START_TIME_EXP'        => 'Habilite para classificar tópicos recentes pela hora de início do tópico, em vez da última hora de publicação.',
	'RT_UNREAD_ONLY'                => 'Mostrar apenas tópicos não lidos',
	'RT_UNREAD_ONLY_EXP'            => 'Ativar para exibir somente tópicos não lidos (se eles são "recentes" ou não). Esta função usa as mesmas configurações (excluindo fóruns / tópicos etc.) como modo normal. Nota: isso só funciona para usuários conectados; Os convidados receberão a lista normal.',
	'RT_RESET_DEFAULT'              => 'Redefinir as configurações do usuário',
	'RT_RESET_DEFAULT_EXP'          => 'Redefinir as configurações do usuário para o padrão.',

	//Enable for extensions
	'RT_NICKVERGESSEN_NEWSPAGE'     => 'Suporte para o NewsPage Extension',
	'RT_VIEW_ON'                    => 'Exibir tópicos recentes em:',

	//Version checker
	'RT_VERSION_CHECK'				=> 'Verificação de Versão',
	'RT_LATEST_VERSION'				=> 'Última versão',
	'RT_EXT_VERSION'				=> 'Versão de extensão',
	'RT_VERSION_ERROR'				=> 'Não é possível verificar a versão mais recente!',
	'RT_CHECK_UPDATE'				=> 'Verifica <a href="http://www.avathar.be/bbdkp/index.php">avathar.be</a> para ver se há atualizações disponíveis.',

	//Donation
	'RT_DONATE_URL'             => 'http://www.avathar.be/bbdkp/app.php/page/donate',
	'PAYPAL_IMAGE_URL'          => 'https://www.paypalobjects.com/webstatic/en_US/i/btn/png/silver-pill-paypal-26px.png',
	'PAYPAL_ALT'                => 'Doe usando o PayPal',
	'RT_DONATE'					=> 'Doação para RecentTopics',
	'RT_DONATE_SHORT'			=> 'Faça uma doação para RecentTopics',
	'RT_DONATE_EXPLAIN'			=> 'RecentTopics é 100% gratuito. É um projeto de hobby no qual estou gastando meu tempo e dinheiro, apenas por diversão. Se você gosta de usar RecentTopics, considere fazer uma doação. Eu realmente apreciaria isto. Sem condições.',
	)
);
