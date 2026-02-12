<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Flattened migration for avathar/recenttopics 3.0.0
 * Combines all previous paybas/recenttopics migrations (2.0.0 - 2.2.15)
 */

namespace avathar\recenttopics\migrations;

class release_3_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '3.0.0', '>=');
	}

	static public function depends_on()
	{
		return array(
			'\phpbb\db\migration\data\v320\v320',
		);
	}

	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'forums' => array(
					'forum_recent_topics' => array('TINT:1', 1),
				),
				$this->table_prefix . 'users' => array(
					'user_rt_enable'          => array('BOOL', 1),
					'user_rt_sort_start_time' => array('BOOL', 0),
					'user_rt_unread_only'     => array('BOOL', 0),
					'user_rt_location'        => array('VCHAR:10', 'RT_TOP'),
					'user_rt_number'          => array('UINT', 5),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'forums' => array(
					'forum_recent_topics',
				),
				$this->table_prefix . 'users' => array(
					'user_rt_enable',
					'user_rt_sort_start_time',
					'user_rt_unread_only',
					'user_rt_location',
					'user_rt_number',
				),
			),
		);
	}

	public function update_data()
	{
		return array(
			// Config
			array('config.add', array('rt_version', '3.0.0')),
			array('config.add', array('rt_number', '5')),
			array('config.add', array('rt_page_number', 0)),
			array('config.add', array('rt_page_numbermax', '10')),
			array('config.add', array('rt_anti_topics', 0)),
			array('config.add', array('rt_parents', 1)),
			array('config.add', array('rt_index', 1)),
			array('config.add', array('rt_min_topic_level', 0)),
			array('config.add', array('rt_on_newspage', 0)),
			array('config.add', array('rt_sort_start_time', 0)),
			array('config.add', array('rt_unread_only', 0)),
			array('config.add', array('rt_location', 'RT_TOP')),

			// ACP module
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'RECENT_TOPICS',
			)),
			array('module.add', array(
				'acp',
				'RECENT_TOPICS',
				array(
					'module_basename' => '\avathar\recenttopics\acp\recenttopics_module',
					'modes'           => array('recenttopics_config'),
				),
			)),

			// Permissions
			array('permission.add', array('u_rt_view', true)),
			array('permission.add', array('u_rt_enable', true)),
			array('permission.add', array('u_rt_sort_start_time', true)),
			array('permission.add', array('u_rt_unread_only', true)),
			array('permission.add', array('u_rt_location', true)),
			array('permission.add', array('u_rt_number', true)),

			// Permission role assignments
			array('permission.permission_set', array('ROLE_USER_FULL', 'u_rt_view', 'role', true)),
			array('permission.permission_set', array('ROLE_USER_FULL', 'u_rt_enable', 'role', true)),
			array('permission.permission_set', array('ROLE_USER_FULL', 'u_rt_sort_start_time', 'role', true)),
			array('permission.permission_set', array('ROLE_USER_FULL', 'u_rt_unread_only', 'role', true)),
			array('permission.permission_set', array('ROLE_USER_FULL', 'u_rt_location', 'role', true)),
			array('permission.permission_set', array('ROLE_USER_FULL', 'u_rt_number', 'role', true)),

			// Permission group assignments
			array('permission.permission_set', array('REGISTERED', 'u_rt_view', 'group', true)),
			array('permission.permission_set', array('GUESTS', 'u_rt_view', 'group', true)),
		);
	}

	public function revert_data()
	{
		return array(
			// Config
			array('config.remove', array('rt_version')),
			array('config.remove', array('rt_number')),
			array('config.remove', array('rt_page_number')),
			array('config.remove', array('rt_page_numbermax')),
			array('config.remove', array('rt_anti_topics')),
			array('config.remove', array('rt_parents')),
			array('config.remove', array('rt_index')),
			array('config.remove', array('rt_min_topic_level')),
			array('config.remove', array('rt_on_newspage')),
			array('config.remove', array('rt_sort_start_time')),
			array('config.remove', array('rt_unread_only')),
			array('config.remove', array('rt_location')),

			// Permissions
			array('permission.remove', array('u_rt_view', true)),
			array('permission.remove', array('u_rt_enable', true)),
			array('permission.remove', array('u_rt_sort_start_time', true)),
			array('permission.remove', array('u_rt_unread_only', true)),
			array('permission.remove', array('u_rt_location', true)),
			array('permission.remove', array('u_rt_number', true)),

			// ACP module
			array('module.remove', array(
				'acp',
				'RECENT_TOPICS',
				array(
					'module_basename' => '\avathar\recenttopics\acp\recenttopics_module',
					'modes'           => array('recenttopics_config'),
				),
			)),
			array('module.remove', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'RECENT_TOPICS',
			)),
		);
	}
}
