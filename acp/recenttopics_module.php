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

use paybas\recenttopics\core\admin;

/**
 * Class recenttopics_module
 *
 * @package paybas\recenttopics\acp
 */
class recenttopics_module extends admin
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
		$ext_meta_manager = $ext_manager->create_extension_metadata_manager('paybas/recenttopics', $phpbb_container->get('template'));
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
			$ants = explode(",", $rt_anti_topics);
			$checkants=true;
			foreach($ants as $ant) {
			    if (!is_numeric($ant)) {$checkants=false; }
			}
			if ($checkants) {$config->set('rt_anti_topics', $rt_anti_topics);}

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
				'RT_INDEX'           => (int) $config['rt_index'],
				'RT_PAGE_NUMBER'     => ($config['rt_page_number'] == '1') ? 'checked="checked"' : '',
				'RT_PAGE_NUMBERMAX'  => (int) $config['rt_page_numbermax'],
				'RT_ANTI_TOPICS'     => $config['rt_anti_topics'],
				'RT_PARENTS'         => $config['rt_parents'],
				'RT_NUMBER'          => (int) $config['rt_number'],
				'RT_SORT_START_TIME' => (int) $config['rt_sort_start_time'],
				'RT_UNREAD_ONLY'     => (int) $config['rt_unread_only'],
				'RT_ON_NEWSPAGE'     => $config['rt_on_newspage'],
				'S_RT_NEWSPAGE'      => $ext_manager->is_enabled('nickvergessen/newspage'),
				'S_RT_OK'            => version_compare($ext_version, $latest_version, '=='),
				'S_RT_OLD'           => version_compare($ext_version, $latest_version, '<'),
				'S_RT_DEV'           => version_compare($ext_version, $latest_version, '>'),
				'EXT_VERSION'          => $ext_version,
				'U_VERSIONCHECK_FORCE' => append_sid($this->u_action . '&amp;versioncheck_force=1'),
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
				'user_rt_number'      => ((int) $config['rt_number'] > 0 ? (int) $config['rt_number'] : 5 )
			);

			$sql = 'UPDATE ' . USERS_TABLE . '
            SET ' . $db->sql_build_array('UPDATE', $sql_ary);

			$db->sql_query($sql);
		}

	}

	/**
	 * retrieve latest version
	 * @param      $meta_data
	 * @param bool $force_update Ignores cached data. Defaults to false.
	 * @param int  $ttl          Cache version information for $ttl seconds. Defaults to 86400 (24 hours).
	 * @return bool|mixed
	 * @throws \Exception
	 */
	public final function version_check($meta_data, $force_update = false, $ttl = 86400)
	{
		global $phpbb_container;
		$cache = $phpbb_container->get('cache');
		$ext_manager = $phpbb_container->get('ext.manager');
		$pemfile = '';
		$versionurl = ($meta_data['extra']['version-check']['ssl'] == '1' ? 'https://': 'http://') .
			$meta_data['extra']['version-check']['host'].$meta_data['extra']['version-check']['directory'].'/'.$meta_data['extra']['version-check']['filename'];
		$ssl = $meta_data['extra']['version-check']['ssl'] == '1' ? true: false;
		if ($ssl)
		{
			//https://davidwalsh.name/php-ssl-curl-error
			$pemfile = $ext_manager->get_extension_path('paybas/recenttopics', true) . 'core/mozilla.pem';
			if (!(file_exists($pemfile) && is_readable($pemfile)))
			{
				$ssl = false;
			}
		}

		//get latest productversion from cache
		$latest_version = $cache->get('recenttopics_versioncheck');

		//if update is forced or cache expired then make the call to refresh latest productversion
		if ($latest_version === false || $force_update)
		{
			$data = parent::curl($versionurl, $pemfile, $ssl, false, false, false);
			if (0 === count($data) )
			{
				$cache->destroy('recenttopics_versioncheck');
				return false;
			}

			$response = $data['response'];
			$latest_version = json_decode($response, true);
			$latest_version = $latest_version['stable']['3.2']['current'];

			//put this info in the cache
			$cache->put('recenttopics_versioncheck', $latest_version, $ttl);

		}

		return $latest_version;
	}
}
