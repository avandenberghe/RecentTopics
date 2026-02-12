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
		// Consider this migration done if rt_version is already 3.0.0+
		// This handles upgrades from paybas/recenttopics where all data already exists
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '3.0.0', '>=');
	}

	/**
	 * Check if the old paybas/recenttopics columns already exist
	 */
	private function column_exists($table, $column)
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . $table, $column);
	}

	static public function depends_on()
	{
		return array(
			'\phpbb\db\migration\data\v320\v320',
		);
	}

	public function update_schema()
	{
		$schema = array('add_columns' => array());

		// Only add columns that don't already exist (handles upgrade from paybas/recenttopics)
		if (!$this->column_exists('forums', 'forum_recent_topics'))
		{
			$schema['add_columns'][$this->table_prefix . 'forums'] = array(
				'forum_recent_topics' => array('TINT:1', 1),
			);
		}

		$user_columns = array(
			'user_rt_enable'          => array('BOOL', 1),
			'user_rt_sort_start_time' => array('BOOL', 0),
			'user_rt_unread_only'     => array('BOOL', 0),
			'user_rt_location'        => array('VCHAR:10', 'RT_TOP'),
			'user_rt_number'          => array('UINT', 5),
		);

		$missing_user_columns = array();
		foreach ($user_columns as $column => $definition)
		{
			if (!$this->column_exists('users', $column))
			{
				$missing_user_columns[$column] = $definition;
			}
		}

		if (!empty($missing_user_columns))
		{
			$schema['add_columns'][$this->table_prefix . 'users'] = $missing_user_columns;
		}

		// If all columns already exist, return empty schema
		if (empty($schema['add_columns']))
		{
			return array();
		}

		return $schema;
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
			// Config (config.add silently skips if key already exists)
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

			// Update version to 3.0.0
			array('config.update', array('rt_version', '3.0.0')),

			// ACP module - use if callback to skip if already exists
			array('if', array(
				array('module.exists', array('acp', 'ACP_CAT_DOT_MODS', 'RECENT_TOPICS')),
				false,
				array('module.add', array(
					'acp',
					'ACP_CAT_DOT_MODS',
					'RECENT_TOPICS',
				)),
			)),
			array('if', array(
				array('module.exists', array('acp', 'RECENT_TOPICS', array(
					'module_basename' => '\avathar\recenttopics\acp\recenttopics_module',
					'modes'           => array('recenttopics_config'),
				))),
				false,
				array('module.add', array(
					'acp',
					'RECENT_TOPICS',
					array(
						'module_basename' => '\avathar\recenttopics\acp\recenttopics_module',
						'modes'           => array('recenttopics_config'),
					),
				)),
			)),

			// Permissions (permission.add silently skips if already exists)
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
