<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace paybas\recenttopics\controller;

use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\language\language;
use paybas\recenttopics\core\recenttopics;
use Symfony\Component\HttpFoundation\Response;

class page_controller implements page_interface
{
	/**
	 * @var \phpbb\config\config
	 */
	protected $config;

	/**
	 * @var \phpbb\controller\helper
	 */
	protected $helper;

	/**
	 * @var language
	 */
	protected $language;

	/* @var recenttopics */
	protected $rt_functions;

	/**
	 * @var \Symfony\Component\HttpFoundation\Response
	 */
	protected $response;

	/**
	 * page constructor.
	 *
	 * @param \phpbb\config\config              			$config
	 * @param \phpbb\controller\helper          			$helper
	 * @param \phpbb\language\language 						$language
	 * @param \paybas\recenttopics\core\recenttopics		$functions
	 * @param \Symfony\Component\HttpFoundation\Response	$response
	 */
	public function __construct(
		config $config,
		helper $helper,
		language $language,
		recenttopics $functions,
		Response $response
	)
	{
		$this->config       = $config;
		$this->helper       = $helper;
		$this->language = $language;
		$this->rt_functions = $functions;
		$this->response = $response;
	}

	/**
	 * Display the page app.php/rt/
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 * @access public
	*/
	public function display()
	{
		$page = "recent_topics_page.html";
		$this->language->add_lang(['info_acp_recenttopics', 'recenttopics'], 'paybas/recenttopics');

		if (isset($this->config['rt_index']) && $this->config['rt_index'])
		{
			$this->rt_functions->display_recent_topics();
		}

		// Load the requested page by route
		$this->response = $this->helper->render($page, $this->language->lang('RECENT_TOPICS'));

		return $this->response;
	}
}
