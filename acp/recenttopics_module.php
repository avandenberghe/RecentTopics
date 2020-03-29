<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace paybas\recenttopics\acp;

/**
 * Class recenttopics_module
 *
 * @package paybas\recenttopics\acp
 */
class recenttopics_module
{
	public $u_action;
	/**
	 * @param $id
	 * @param $mode
	 * @throws \Exception
	 *
	 */
	public function main($id, $mode)
	{
		global $phpbb_container;

		$config = $phpbb_container->get('config');
		$request = $phpbb_container->get('request');
		$template = $phpbb_container->get('template');
		$user = $phpbb_container->get('user');
		$db = $phpbb_container->get('dbal.conn');
		$ext_manager = $phpbb_container->get('ext.manager');

		$language = $phpbb_container->get('language');
		$language->add_lang('acp/common');
		$language->add_lang('ucp');
		$language->add_lang('viewforum');

		$this->tpl_name = 'acp_recenttopics';
		$this->page_title = $language->lang('RECENT_TOPICS');

		$form_key = 'acp_recenttopics';
		add_form_key($form_key);

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key($form_key))
			{
				trigger_error($language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			/*
			* acp options for everyone
			*/

			//enable-disable paging
			$rt_page_number = $request->variable('rt_page_number', '');
			$config->set('rt_page_number', $rt_page_number == 'on' ? 1 : 0 );

			// maximum number of pages
			$rt_page_numbermax = $request->variable('rt_page_numbermax', 0);
			$config->set('rt_page_numbermax', $rt_page_numbermax);

			$rt_min_topic_level = $request->variable('rt_min_topic_level', 0);
			$config->set('rt_min_topic_level', $rt_min_topic_level);

			// variable should be '' as it is a string ("1, 2, 3928") here, not an integer.
			$rt_anti_topics = $request->variable('rt_anti_topics', '');
			$config->set('rt_anti_topics', $rt_anti_topics);

			$rt_parents = $request->variable('rt_parents', false);
			$config->set('rt_parents', $rt_parents);

			// Enable on other extension pages?
			$rt_on_newspage = $request->variable('rt_on_newspage', 0);
			$config->set('rt_on_newspage', $rt_on_newspage);

			/*
			 *  default positions, modifiable by ucp
	         */
			//number of most recent topics shown per page
			$rt_number = $request->variable('rt_number', 5);
			$config->set('rt_number', $rt_number);

			$rt_enable = $request->variable('rt_enable', 0);
			$config->set('rt_index', $rt_enable);

			$rt_location = $request->variable('rt_location', '');
			$config->set('rt_location', $rt_location);

			$rt_sort_start_time = $request->variable('rt_sort_start_time', false);
			$config->set('rt_sort_start_time', $rt_sort_start_time);

			$rt_unread_only = $request->variable('rt_unread_only', false);
			$config->set('rt_unread_only', $rt_unread_only);

			trigger_error($language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		$topic_types = array (
			0 => $language->lang('POST') ,
			1 => $language->lang('POST_STICKY'),
			2 => $language->lang('ANNOUNCEMENTS'),
			3 => $language->lang('GLOBAL_ANNOUNCEMENT'),
		);

		foreach ($topic_types as $key => $topic_type)
		{
			$template->assign_block_vars(
				'topiclevel_row',
				array(
					'VALUE'    => $key,
					'SELECTED' => ($config['rt_min_topic_level'] == $key) ? ' selected="selected"' : '',
					'OPTION'   => $topic_type,
				)
			);
		}

		$display_types = array (
			'RT_TOP'    => $language->lang('RT_TOP'),
			'RT_BOTTOM' => $language->lang('RT_BOTTOM'),
			'RT_SIDE'   => $language->lang('RT_SIDE'),
		);

		foreach ($display_types as $key => $display_type)
		{
			$template->assign_block_vars(
				'location_row',
				array(
					'VALUE'    => $key,
					'SELECTED' => ($config['rt_location'] == $key) ? ' selected="selected"' : '',
					'OPTION'   => $display_type,
				)
			);
		}

		$template->assign_vars(
			array(
				'U_ACTION'           => $this->u_action,
				'RT_INDEX'           => isset($config['rt_index']) ? $config['rt_index'] : false,
				'RT_PAGE_NUMBER'     => ((isset($config['rt_page_number']) ? $config['rt_page_number'] : '') == '1') ? 'checked="checked"' : '',
				'RT_PAGE_NUMBERMAX'  => isset($config['rt_page_numbermax']) ? $config['rt_page_numbermax'] : '',
				'RT_ANTI_TOPICS'     => isset($config['rt_anti_topics']) ? $config['rt_anti_topics'] : '',
				'RT_PARENTS'         => isset($config['rt_parents']) ? $config['rt_parents'] : false,
				'RT_NUMBER'          => isset($config['rt_number']) ? $config['rt_number'] : '',
				'RT_SORT_START_TIME' => isset($config['rt_sort_start_time']) ? $config['rt_sort_start_time'] : false,
				'RT_UNREAD_ONLY'     => isset($config['rt_unread_only']) ? $config['rt_unread_only'] : false,
				'RT_ON_NEWSPAGE'     => isset($config['rt_on_newspage']) ? $config['rt_on_newspage'] : false,
				'S_RT_NEWSPAGE'      => $ext_manager->is_enabled('nickvergessen/newspage'))
		);

		//reset user preferences
		if ($request->is_set_post('rt_reset_default'))
		{
			$sql_ary = array(
				'user_rt_enable'      => (int) $this->config['rt_index'],
				'user_rt_sort_start_time'     => (int) $this->config['rt_sort_start_time'] ,
				'user_rt_unread_only'      => (int) $this->config['rt_unread_only'],
				'user_rt_location'      => $db->sql_escape($this->config['rt_location']),
				'user_rt_number'      => ((int) $this->config['rt_number'] > 0 ? (int) $this->config['rt_number'] : 5 )
			);

			$sql = 'UPDATE ' . USERS_TABLE . '
            SET ' . $db->sql_build_array('UPDATE', $sql_ary);

			$db->sql_query($sql);
		}
	}
}
