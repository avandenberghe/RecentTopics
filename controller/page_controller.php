<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace avathar\recenttopics\controller;

use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\language\language;
use avathar\recenttopics\core\recenttopics;

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
	 * page constructor.
	 *
	 * @param \phpbb\config\config              			$config
	 * @param \phpbb\controller\helper          			$helper
	 * @param \phpbb\language\language 						$language
	 * @param \avathar\recenttopics\core\recenttopics		$functions
	 */
	public function __construct(
		config $config,
		helper $helper,
		language $language,
		recenttopics $functions
	)
	{
		$this->config       = $config;
		$this->helper       = $helper;
		$this->language = $language;
		$this->rt_functions = $functions;
	}

	/**
	 * Display the page app.php/rt/
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 * @access public
	*/
	public function display()
	{
		$page = "avathar/recent_topics_page.html";
		$this->language->add_lang(['info_acp_recenttopics', 'recenttopics'], 'avathar/recenttopics');

		if (isset($this->config['rt_index']) && $this->config['rt_index'])
		{
			$this->rt_functions->display_recent_topics();
		}

		return $this->helper->render($page, $this->language->lang('RECENT_TOPICS'));
	}
}
