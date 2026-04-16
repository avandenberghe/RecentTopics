<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 *
 */

namespace avathar\recenttopicsav\event;

use avathar\recenttopicsav\core\recenttopics;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\language\language;
use phpbb\request\request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/* @var recenttopics */
	protected $rt_functions;

	/** @var config */
	protected $config;

	/** @var request */
	protected $request;

	/** @var helper */
	protected $helper;

	/** @var language */
	protected $language;

	/**
	 * listener constructor.
	 *
	 * @param recenttopics $functions
	 * @param config       $config
	 * @param request      $request
	 * @param helper       $helper
	 * @param language     $language
	 */
	public function __construct(recenttopics $functions, config $config, request $request, helper $helper, language $language)
	{
		$this->rt_functions = $functions;
		$this->config = $config;
		$this->request = $request;
		$this->helper = $helper;
		$this->language = $language;
	}

	/**
	 * Get subscribed events
	 *
	 * @return array
	 * @static
	 */
	public static function getSubscribedEvents()
	{
		return array(
			'core.index_modify_page_title'           => 'display_rt',
			'core.viewforum_generate_page_after'     => 'display_rt_viewforum',
			'core.viewonline_overwrite_location'     => 'viewonline_overwrite_location',
			'core.acp_manage_forums_request_data'    => 'acp_manage_forums_request_data',
			'core.acp_manage_forums_initialise_data' => 'acp_manage_forums_initialise_data',
			'core.acp_manage_forums_display_form'    => 'acp_manage_forums_display_form',
			'core.permissions'                       => 'add_permission',

			// Events added by this extension
			'avathar.recenttopicsav.topictitle_remove_re'  => 'topictitle_remove_re',
		);
	}

	// The main magic
	public function display_rt()
	{
		if (isset($this->config['rt_index']) && $this->config['rt_index'])
		{
			$this->rt_functions->display_recent_topics();
		}
	}

	/**
	 * Display recent topics on viewforum page
	 */
	public function display_rt_viewforum()
	{
		if (isset($this->config['rt_viewforum']) && $this->config['rt_viewforum'])
		{
			$this->rt_functions->display_recent_topics('recent_topics', 'viewforum');
		}
	}

	/**
	 * Show users viewing Recent Topics on the Who Is Online page
	 *
	 * @param \phpbb\event\data $event
	 */
	public function viewonline_overwrite_location($event)
	{
		if (isset($event['on_page'][1]) && $event['on_page'][1] === 'app')
		{
			if (strpos($event['row']['session_page'], 'app.php/rt/simple') !== false)
			{
				$event['location'] = $this->language->lang('VIEWING_RECENT_TOPICS');
				$event['location_url'] = $this->helper->route('avathar_recenttopicsav_simple');
			}
			else if (strpos($event['row']['session_page'], 'app.php/rt') !== false)
			{
				$event['location'] = $this->language->lang('VIEWING_RECENT_TOPICS');
				$event['location_url'] = $this->helper->route('avathar_recenttopicsav_page');
			}
		}
	}

	// Submit form (add/update)
	/**
	 * @param $event
	 */
	public function acp_manage_forums_request_data($event)
	{
		$array = $event['forum_data'];
		$array['forum_recent_topics'] = $this->request->variable('forum_recent_topics', 1);
		$event['forum_data'] = $array;
	}

	// Default settings for new forums
	/**
	 * @param $event
	 */
	public function acp_manage_forums_initialise_data($event)
	{
		if ($event['action'] == 'add')
		{
			$array = $event['forum_data'];
			$array['forum_recent_topics'] = '1';
			$event['forum_data'] = $array;
		}
	}

	// ACP forums template output
	/**
	 * @param $event
	 */
	public function acp_manage_forums_display_form($event)
	{
		$array = $event['template_data'];
		$array['RECENT_TOPICS'] = $event['forum_data']['forum_recent_topics'];
		$event['template_data'] = $array;
	}

	/**
	 * Add permissions
	 * @param array $event
	 * @return null
	 * @access public
	 */
	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['u_rt_view'] = array('lang' => 'ACL_U_RT_VIEW', 'cat' => 'misc');
		$permissions['u_rt_enable'] = array('lang' => 'ACL_U_RT_ENABLE', 'cat' => 'misc');
		$permissions['u_rt_location'] = array('lang' => 'ACL_U_RT_LOCATION', 'cat' => 'misc');
		$permissions['u_rt_sort_start_time'] = array('lang' => 'ACL_U_RT_SORT_START_TIME', 'cat' => 'misc');
		$permissions['u_rt_unread_only'] = array('lang' => 'ACL_U_RT_UNREAD_ONLY', 'cat' => 'misc');
		$permissions['u_rt_number'] = array('lang' => 'ACL_U_RT_NUMBER', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}

	/**
	 * Remove "Re: " from post subject
	 *
	 * @param \phpbb\event\data		$event  The event object
	 * @return void
	 * @access public
	 */
	public function topictitle_remove_re($event)
	{
		if (isset($event['row']['topic_last_post_subject']))
		{
			$array = (array) $event['row'];
			$lastpost = $array['topic_last_post_subject'];
			$array['topic_last_post_subject'] = preg_replace('/^Re: /', '', $lastpost);
			$event['row'] = $array;
		}
	}

}
