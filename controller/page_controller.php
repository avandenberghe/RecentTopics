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

use \Symfony\Component\HttpFoundation\Response;

class page_controller
{
	/**
	 * @var \phpbb\auth\auth
	 */
	protected $auth;
	/**
	 * @var \phpbb\config\config
	 */
	protected $config;
	/**
	 * @var \phpbb\controller\helper
	 */
	protected $helper;
	/**
	 * @var \phpbb\template\template
	 */
	protected $template;
	/**
	 * @var \phpbb\db\driver\driver_interface
	 */
	protected $db;
	/**
	 * @var \phpbb\request\request
	 */
	protected $request;
	/**
	 * @var \phpbb\user
	 */
	protected $user;
	/**
	 * @var string
	 */
	protected $phpEx;
	/**
	 * @var \phpbb\pagination
	 */
	protected $pagination;
	/**
	 * @var \phpbb\extension\manager
	 */
	protected $phpbb_extension_manager;
	/**
	 * @var string
	 */
	protected $ext_path;
	/**
	 * @var string
	 */
	protected $ext_path_web;
	/**
	 * @var string
	 */
	protected $ext_path_images;
	/**
	 * @var string
	 */
	protected $root_path;

	/* @var recenttopics */
	protected $rt_functions;

	/**
	 * page constructor.
	 *
	 * @param \phpbb\config\config              $config
	 * @param \phpbb\controller\helper          $helper
	 * @param \phpbb\auth\auth                  $auth
	 * @param \phpbb\template\template          $template
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\request\request            $request
	 * @param \phpbb\user                       $user
	 * @param \phpbb\pagination                 $pagination
	 * @param $php_ext
	 * @param \phpbb\path_helper                $path_helper
	 * @param \phpbb\extension\manager          $phpbb_extension_manager
	 * @param $root_path
	 */
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\controller\helper $helper,
		\phpbb\auth\auth $auth,
		\phpbb\template\template $template,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		\phpbb\user $user,
		\phpbb\pagination $pagination,
		$php_ext,
		\phpbb\path_helper $path_helper,
		\phpbb\extension\manager $phpbb_extension_manager,
		$root_path,
	    \paybas\recenttopics\core\recenttopics $functions
	)
	{
		$this->config       = $config;
		$this->helper       = $helper;
		$this->auth         = $auth;
		$this->template     = $template;
		$this->db           = $db;
		$this->request      = $request;
		$this->user         = $user;
		$this->pagination   = $pagination;
		$this->php_ext      = $php_ext;
		$this->path_helper  = $path_helper;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->ext_path     = $this->phpbb_extension_manager->get_extension_path('paybas/recenttopics', true);
		$this->ext_path_web = $this->path_helper->get_web_root_path($this->ext_path);
		$this->root_path  = $root_path;
		$this->rt_functions = $functions;
	}

	/**
	 * Display the page
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 * @throws http_exception
	 * @access public
     */
	public function display()
	{
		$page = "recent_topics_page.html";

		if (isset($this->config['rt_index']) && $this->config['rt_index'])
		{
			$this->rt_functions->display_recent_topics();
		}

		// Load the requested page by route
		try
		{
			$response = $this->helper->render($page, $this->user->lang['RECENT_TOPICS']);
		}
		catch (\phpbb\pages\exception\base $e)
		{
			throw new http_exception(404, 'PAGE_NOT_AVAILABLE', array($page));
		}

		return $response;

	}

}
