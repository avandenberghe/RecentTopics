<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace avathar\recenttopicsav\event;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\language\language;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class ucp_listener implements EventSubscriberInterface
{
	/**
	* @var auth
	*/
	protected $auth;

	/**
	* @var config
	*/
	protected $config;

	/**
	* @var request
	*/
	protected $request;

	/**
	* @var template
	*/
	protected $template;

	/**
	* @var user
	*/
	protected $user;

	/**
	 * @var language
	 */
	protected $language;

	/* @var driver_interface */
	protected $db;

	/**
	 * ucp_listener constructor.
	 *
	 * @param auth             $auth
	 * @param config           $config
	 * @param request          $request
	 * @param template         $template
	 * @param user             $user
	 * @param language         $language
	 * @param driver_interface $db
	 */
	public function __construct(auth $auth,
		config $config,
		request $request,
		template $template,
		user $user,
		language $language,
		driver_interface $db
	)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->language = $language;
		$this->db = $db;
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return array(
		'core.ucp_prefs_view_data'        => 'ucp_prefs_get_data',
		'core.ucp_prefs_view_update_data' => 'ucp_prefs_set_data',
		'core.ucp_register_data_after'		  => 'ucp_register_set_data'
		);
	}

	/**
	 * @param $event
	 */
	public function ucp_prefs_get_data($event)
	{
		// Request the user option vars and add them to the data array
		$event['data'] = array_merge(
			$event['data'], array(
			'rt_enable'          => $this->request->variable('rt_enable', (int) $this->user->data['user_rt_enable']),
			'rt_location'        => $this->request->variable('rt_location', $this->user->data['user_rt_location']),
			'rt_number'          => $this->request->variable('rt_number', (int) $this->user->data['user_rt_number']),
			'rt_sort_start_time' => $this->request->variable('rt_sort_start_time', (int) $this->user->data['user_rt_sort_start_time']),
			'rt_unread_only'     => $this->request->variable('rt_unread_only', (int) $this->user->data['user_rt_unread_only']),
			)
		);

		// Output the data vars to the template (except on form submit)
		if (!$event['submit'] && $this->auth->acl_get('u_rt_view'))
		{
			$this->language->add_lang('recenttopics_ucp', 'avathar/recenttopicsav');

			$template_vars = array();

			// if authorised for one of these then set ucp master template variable to true
			if ($this->auth->acl_get('u_rt_enable') || $this->auth->acl_get('u_rt_location') || $this->auth->acl_get('u_rt_sort_start_time') || $this->auth->acl_get('u_rt_unread_only'))
			{
				$template_vars += array(
				'S_RT_SHOW' => true,
				);
			}

			if ($this->auth->acl_get('u_rt_enable'))
			{
				$template_vars += array(
				'A_RT_ENABLE' => true,
				'S_RT_ENABLE' => $event['data']['rt_enable'],
				);
			}

			if ($this->auth->acl_get('u_rt_location'))
			{

				$template_vars += array(
					'A_RT_LOCATION' => true,
				);

				$display_types = array (
					'RT_TOP'    => $this->language->lang('RT_TOP'),
					'RT_BOTTOM' => $this->language->lang('RT_BOTTOM'),
					'RT_SIDE'   => $this->language->lang('RT_SIDE'),
				);

				foreach ($display_types as $key => $display_type)
				{
					$this->template->assign_block_vars(
						'location_row',
						array(
							'VALUE'    => $key,
							'SELECTED' => ($event['data']['rt_location'] == $key) ? ' selected="selected"' : '',
							'OPTION'   => $display_type,
						)
					);
				}
			}

			if ($this->auth->acl_get('u_rt_number'))
			{
				$template_vars += array(
					'A_RT_NUMBER' => true,
					'RT_NUMBER' => $event['data']['rt_number'],
				);
			}

			if ($this->auth->acl_get('u_rt_sort_start_time'))
			{
				$template_vars += array(
				'A_RT_SORT_START_TIME' => true,
				'S_RT_SORT_START_TIME' => $event['data']['rt_sort_start_time'],
				);
			}

			if ($this->auth->acl_get('u_rt_unread_only'))
			{
				$template_vars += array(
				'A_RT_UNREAD_ONLY' => true,
				'S_RT_UNREAD_ONLY' => $event['data']['rt_unread_only'],
				);
			}

			$this->template->assign_vars($template_vars);
		}
	}

	/**
	 * @param $event
	 */
	public function ucp_prefs_set_data($event)
	{
		$event['sql_ary'] = array_merge(
			$event['sql_ary'], array(
			'user_rt_enable'          => $event['data']['rt_enable'],
			'user_rt_location'        => $event['data']['rt_location'],
			'user_rt_number'          => $event['data']['rt_number'],
			'user_rt_sort_start_time' => $event['data']['rt_sort_start_time'],
			'user_rt_unread_only'     => $event['data']['rt_unread_only'],
			)
		);
	}

	/**
	 * After new user registration, set rt user parameters to default;
	 * @param $event
	 */
	public function ucp_register_set_data($event)
	{

		$sql_ary = array(
			'user_rt_enable'      => (int) $this->config['rt_index'],
			'user_rt_sort_start_time'     => (int) $this->config['rt_sort_start_time'] ,
			'user_rt_unread_only'      => (int) $this->config['rt_unread_only'],
			'user_rt_location'      => $this->config['rt_location'],
			'user_rt_number'      => ((int) $this->config['rt_number'] > 0 ? (int) $this->config['rt_number'] : 5 )
		);

		$sql = 'UPDATE ' . USERS_TABLE . '
			SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
			WHERE user_id = ' . (int) $event['user_id'];

		$this->db->sql_query($sql);
	}
}
