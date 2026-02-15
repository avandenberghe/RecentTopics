<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace avathar\recenttopicsav\controller;

use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\language\language;
use avathar\recenttopicsav\core\recenttopics;

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
	 * @param \avathar\recenttopicsav\core\recenttopics		$functions
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
	 * Display the page app.php/rt/ (full header/footer)
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function display()
	{
		return $this->render_page('recent_topics_page.html');
	}

	/**
	 * Display the page app.php/rt/simple (no header/footer, for iframe embedding)
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function display_simple()
	{
		return $this->render_page('recent_topics_simple.html');
	}

	/**
	 * @param string $template
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	private function render_page($template)
	{
		$this->language->add_lang(['info_acp_recenttopics', 'recenttopics'], 'avathar/recenttopicsav');

		if (isset($this->config['rt_index']) && $this->config['rt_index'])
		{
			$this->rt_functions->display_recent_topics();
		}

		return $this->helper->render($template, $this->language->lang('RECENT_TOPICS'));
	}
}
