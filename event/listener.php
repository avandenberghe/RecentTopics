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

namespace paybas\recenttopics\event;

use paybas\recenttopics\core\recenttopics;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/* @var recenttopics */
	protected $rt_functions;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/**
	 * listener constructor.
	 *
	 * @param \paybas\recenttopics\core\recenttopics $functions
	 * @param \phpbb\config\config                   $config
	 * @param \phpbb\request\request                 $request
	 */
	public function __construct(recenttopics $functions, \phpbb\config\config $config, \phpbb\request\request $request)
	{
		$this->rt_functions = $functions;
		$this->config = $config;
		$this->request = $request;
	}

	/**
	 * Get subscribed events
	 *
	 * @return array
	 * @static
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.index_modify_page_title'           => 'display_rt',
			'nickvergessen.newspage.newspage'        => 'display_rt_newspage',
			'core.acp_manage_forums_request_data'    => 'acp_manage_forums_request_data',
			'core.acp_manage_forums_initialise_data' => 'acp_manage_forums_initialise_data',
			'core.acp_manage_forums_display_form'    => 'acp_manage_forums_display_form',
			'core.permissions'                       => 'add_permission',

			// Events added by this extension
			'paybas.recenttopics.topictitle_remove_re'  => 'topictitle_remove_re',
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

	// nickvergessen's newspage ext
	public function display_rt_newspage()
	{
		if (isset($this->config['rt_on_newspage']) && $this->config['rt_on_newspage'])
		{
			$this->rt_functions->display_recent_topics();
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
	 * @event paybas.recenttopics.topictitle_remove_re
	 * remove "Re: " from post subject
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
