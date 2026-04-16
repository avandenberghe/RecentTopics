<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace avathar\recenttopicsav\acp;

/**
 * Class recenttopics_module
 *
 * @package avathar\recenttopicsav\acp
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
		$db = $phpbb_container->get('dbal.conn');
		$ext_manager = $phpbb_container->get('ext.manager');

		$language = $phpbb_container->get('language');
		$language->add_lang('ucp');
		$language->add_lang('viewforum');

		$this->tpl_name = 'acp_recenttopics';
		$this->page_title = $language->lang('RECENT_TOPICS');

		$form_key = 'acp_recenttopics';
		add_form_key($form_key);

		//version check
		$ext_meta_manager = $ext_manager->create_extension_metadata_manager('avathar/recenttopicsav');
		$meta_data  = $ext_meta_manager->get_metadata();
		$ext_version  = $meta_data['version'];
		$latest_version  = $this->version_check($meta_data, $request->variable('versioncheck_force', false));

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key($form_key))
			{
				trigger_error($language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			/*
			* acp options for everyone
			*/

			// Maximum number of pages
			$rt_page_numbermax = $request->variable('rt_page_numbermax', 0);
			$config->set('rt_page_numbermax', $rt_page_numbermax);

			//Show all recent topic pages
			$rt_page_number = $request->variable('rt_page_number', '');
			$config->set('rt_page_number', $rt_page_number == 'on' ? 1 : 0 );

			// Minimum topic type level
			$rt_min_topic_level = $request->variable('rt_min_topic_level', 0);
			$config->set('rt_min_topic_level', $rt_min_topic_level);

			// variable should be '' as it is a string ("1, 2, 3928") here, not an integer.
			$rt_anti_topics = $request->variable('rt_anti_topics', '');
			$ants = explode(',', $rt_anti_topics);
			$check_ants = true;
			foreach ($ants as $ant)
			{
				if (!is_numeric($ant))
				{
					$check_ants = false;
				}
			}

			if ($check_ants)
			{
				$config->set('rt_anti_topics', $rt_anti_topics);
			}

			$rt_parents = $request->variable('rt_parents', false);
			$config->set('rt_parents', $rt_parents);

			$rt_topic_link_to = $request->variable('rt_topic_link_to', 0);
			$config->set('rt_topic_link_to', $rt_topic_link_to);

			/*
			 *  default positions, modifiable by ucp
			 */
			//number of most recent topics shown per page
			$rt_number = $request->variable('rt_number', 5);
			$config->set('rt_number', $rt_number);

			$rt_enable = $request->variable('rt_enable', 0);
			$config->set('rt_index', $rt_enable);

			$rt_viewforum = $request->variable('rt_viewforum', 0);
			$config->set('rt_viewforum', $rt_viewforum);

			$rt_viewforum_location = $request->variable('rt_viewforum_location', '');
			$old_vf_location = $config['rt_viewforum_location'];
			$config->set('rt_viewforum_location', $rt_viewforum_location);

			$rt_location = $request->variable('rt_location', '');
			$old_location = $config['rt_location'];
			$config->set('rt_location', $rt_location);

			// Propagate location changes to users who still have the old default
			/** @var \phpbb\db\driver\driver_interface $db */
			$db = $phpbb_container->get('dbal.conn');
			if ($rt_viewforum_location !== $old_vf_location)
			{
				$db->sql_query('UPDATE ' . USERS_TABLE . " SET user_rt_viewforum_location = '" . $db->sql_escape($rt_viewforum_location) . "' WHERE user_rt_viewforum_location = '" . $db->sql_escape($old_vf_location) . "'");
			}
			if ($rt_location !== $old_location)
			{
				$db->sql_query('UPDATE ' . USERS_TABLE . " SET user_rt_location = '" . $db->sql_escape($rt_location) . "' WHERE user_rt_location = '" . $db->sql_escape($old_location) . "'");
			}

			$rt_sort_start_time = $request->variable('rt_sort_start_time', false);
			$config->set('rt_sort_start_time', $rt_sort_start_time);

			$rt_unread_only = $request->variable('rt_unread_only', false);
			$config->set('rt_unread_only', $rt_unread_only);

			$rt_page_enable = $request->variable('rt_page_enable', 0);
			$config->set('rt_page_enable', $rt_page_enable);

			// Advertisement block
			$rt_ads_enable = $request->variable('rt_ads_enable', 0);
			$config->set('rt_ads_enable', $rt_ads_enable);

			$rt_ads_code = $request->variable('rt_ads_code', '', true);
			$config_text = $phpbb_container->get('config_text');
			$config_text->set('rt_ads_code', $rt_ads_code);

			// Display options
			$rt_show_likes = $request->variable('rt_show_likes', 0);
			$config->set('rt_show_likes', $rt_show_likes);

			$rt_side_show_date = $request->variable('rt_side_show_date', 0);
			$config->set('rt_side_show_date', $rt_side_show_date);

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

		$vf_display_types = array (
			'RT_TOP'    => $language->lang('RT_TOP'),
			'RT_BOTTOM' => $language->lang('RT_BOTTOM'),
		);

		foreach ($vf_display_types as $key => $display_type)
		{
			$template->assign_block_vars(
				'vf_location_row',
				array(
					'VALUE'    => $key,
					'SELECTED' => ($config['rt_viewforum_location'] == $key) ? ' selected="selected"' : '',
					'OPTION'   => $display_type,
				)
			);
		}

		$topic_link_options = array(
			0 => $language->lang('RT_TOPIC_LINK_FIRST'),
			1 => $language->lang('RT_TOPIC_LINK_LAST'),
			2 => $language->lang('RT_TOPIC_LINK_UNREAD'),
		);

		foreach ($topic_link_options as $key => $topic_link_option)
		{
			$template->assign_block_vars(
				'topiclink_row',
				array(
					'VALUE'    => $key,
					'SELECTED' => ($config['rt_topic_link_to'] == $key) ? ' selected="selected"' : '',
					'OPTION'   => $topic_link_option,
				)
			);
		}

		$helper = $phpbb_container->get('controller.helper');
		$config_text = $phpbb_container->get('config_text');

		$template->assign_vars(
			array(
				'U_ACTION'           => $this->u_action,
				'U_RT_PAGE'          => $helper->route('avathar_recenttopicsav_page', [], true, false, \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL),
				'U_RT_SIMPLE_PAGE'   => $helper->route('avathar_recenttopicsav_simple', [], true, false, \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL),
				'RT_INDEX'           => (int) $config['rt_index'],
			'RT_VIEWFORUM'       => (int) $config['rt_viewforum'],
				'RT_PAGE_NUMBER'     => ($config['rt_page_number'] == '1') ? 'checked="checked"' : '',
				'RT_PAGE_NUMBERMAX'  => (int) $config['rt_page_numbermax'],
				'RT_ANTI_TOPICS'     => $config['rt_anti_topics'],
				'RT_PARENTS'         => $config['rt_parents'],
				'RT_NUMBER'          => (int) $config['rt_number'],
				'RT_SORT_START_TIME' => (int) $config['rt_sort_start_time'],
				'RT_UNREAD_ONLY'     => (int) $config['rt_unread_only'],
				'RT_PAGE_ENABLE'     => (int) $config['rt_page_enable'],
				'RT_ADS_ENABLE'      => (int) $config['rt_ads_enable'],
				'RT_ADS_CODE'        => $config_text->get('rt_ads_code'),
				'RT_SHOW_LIKES'      => (int) $config['rt_show_likes'],
				'RT_SIDE_SHOW_DATE'  => (int) $config['rt_side_show_date'],
				'S_POSTLOVE'         => $phpbb_container->has('avathar.postlove.topic_likes'),
				'S_RT_OK'            => version_compare($ext_version, $latest_version, '=='),
				'S_RT_OLD'           => version_compare($ext_version, $latest_version, '<'),
				'S_RT_DEV'           => version_compare($ext_version, $latest_version, '>'),
				'EXT_VERSION'          => $ext_version,
				'U_VERSIONCHECK_FORCE' => append_sid($this->u_action . '&versioncheck_force=1'),
				'RT_LATESTVERSION'     => $latest_version,
			)
		);

		//reset user preferences
		if ($request->is_set_post('rt_reset_default'))
		{
			$sql_ary = array(
				'user_rt_enable'      => (int) $config['rt_index'],
				'user_rt_sort_start_time'     => (int) $config['rt_sort_start_time'] ,
				'user_rt_unread_only'   => (int) $config['rt_unread_only'],
				'user_rt_location'      => $config['rt_location'],
				'user_rt_viewforum_location' => $config['rt_viewforum_location'],
				'user_rt_number'      => ((int) $config['rt_number'] > 0 ? (int) $config['rt_number'] : 5 )
			);

			$sql = 'UPDATE ' . USERS_TABLE . '
            SET ' . $db->sql_build_array('UPDATE', $sql_ary);

			$db->sql_query($sql);
		}

	}

	/**
	 * Retrieve latest version using phpBB's file_downloader
	 *
	 * @param      $meta_data
	 * @param bool $force_update Ignores cached data. Defaults to false.
	 * @param int  $ttl          Cache version information for $ttl seconds. Defaults to 86400 (24 hours).
	 * @return string|bool       Latest version string, or false on failure
	 */
	private function version_check($meta_data, $force_update = false, $ttl = 86400)
	{
		global $phpbb_container;
		$cache = $phpbb_container->get('cache');

		$latest_version = $cache->get('recenttopics_versioncheck');

		if ($latest_version === false || $force_update)
		{
			$host = $meta_data['extra']['version-check']['host'];
			$path = $meta_data['extra']['version-check']['directory'];
			$file = $meta_data['extra']['version-check']['filename'];
			$ssl = !empty($meta_data['extra']['version-check']['ssl']);
			$port = $ssl ? 443 : 80;

			$file_downloader = new \phpbb\file_downloader();
			$response = $file_downloader->get($host, $path, $file, $port);
			$error = $file_downloader->get_error_string();

			if (!empty($error) || empty($response))
			{
				$cache->destroy('recenttopics_versioncheck');
				return false;
			}

			$version_data = json_decode($response, true);
			if (empty($version_data['stable']['3.3']['current']))
			{
				return false;
			}

			$latest_version = $version_data['stable']['3.3']['current'];
			$cache->put('recenttopics_versioncheck', $latest_version, $ttl);
		}

		return $latest_version;
	}
}
