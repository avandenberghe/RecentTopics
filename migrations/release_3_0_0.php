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
		return isset($this->config['rt_version'])
			&& version_compare($this->config['rt_version'], '3.0.0', '>=')
			&& $this->db_tools->sql_column_exists($this->table_prefix . 'users', 'user_rt_enable')
			&& $this->db_tools->sql_column_exists($this->table_prefix . 'forums', 'forum_recent_topics');
	}

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

			// Ensure version is set to 3.0.0
			array('custom', array(array($this, 'update_version'))),

			// Clean up ALL old recenttopics modules (paybas + broken avathar attempts)
			// so that module.add below can create them fresh with proper nested set
			array('custom', array(array($this, 'cleanup_old_modules'))),

			// ACP modules via phpBB's module.add (handles nested set tree correctly)
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

			// Permissions and role/group assignments
			array('custom', array(array($this, 'add_permissions'))),
		);
	}

	/**
	 * Force rt_version to 3.0.0 (handles upgrade where config.add skipped because key existed)
	 */
	public function update_version()
	{
		$this->config->set('rt_version', '3.0.0');
	}

	/**
	 * Remove ALL old recenttopics modules and paybas remnants so module.add can work cleanly.
	 * Uses phpBB's module tool to maintain the nested set tree integrity.
	 */
	public function cleanup_old_modules()
	{
		// Remove old paybas/recenttopics from ext table
		$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "ext WHERE ext_name = 'paybas/recenttopics'");

		// Remove old paybas migration entries
		$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "migrations WHERE migration_name LIKE '%paybas%recenttopics%'");

		// Use phpBB's module tool for proper nested set handling
		$module_tool = $GLOBALS['phpbb_container']->get('migrator.tool.module');

		// Remove old paybas child module if it exists
		if ($module_tool->exists('acp', 'RECENT_TOPICS', '\\paybas\\recenttopics\\acp\\recenttopics_module'))
		{
			$module_tool->remove('acp', 'RECENT_TOPICS', array(
				'module_basename' => '\\paybas\\recenttopics\\acp\\recenttopics_module',
				'modes'           => array('recenttopics_config'),
			));
		}

		// Remove old avathar child module from previous broken attempts
		if ($module_tool->exists('acp', 'RECENT_TOPICS', '\\avathar\\recenttopics\\acp\\recenttopics_module'))
		{
			$module_tool->remove('acp', 'RECENT_TOPICS', array(
				'module_basename' => '\\avathar\\recenttopics\\acp\\recenttopics_module',
				'modes'           => array('recenttopics_config'),
			));
		}

		// Remove RECENT_TOPICS category if it exists
		if ($module_tool->exists('acp', 'ACP_CAT_DOT_MODS', 'RECENT_TOPICS'))
		{
			$module_tool->remove('acp', 'ACP_CAT_DOT_MODS', 'RECENT_TOPICS');
		}
	}

	/**
	 * Add permissions with graceful handling for missing roles
	 */
	public function add_permissions()
	{
		$permissions = array(
			'u_rt_view', 'u_rt_enable', 'u_rt_sort_start_time',
			'u_rt_unread_only', 'u_rt_location', 'u_rt_number',
		);

		// Add permissions (skip if already exist)
		foreach ($permissions as $permission)
		{
			$sql = 'SELECT auth_option_id FROM ' . $this->table_prefix . "acl_options
				WHERE auth_option = '" . $this->db->sql_escape($permission) . "'";
			$result = $this->db->sql_query($sql);
			$exists = (bool) $this->db->sql_fetchfield('auth_option_id');
			$this->db->sql_freeresult($result);

			if (!$exists)
			{
				$sql_ary = array(
					'auth_option'   => $permission,
					'is_global'     => 1,
					'is_local'      => 0,
					'founder_only'  => 0,
				);
				$sql = 'INSERT INTO ' . $this->table_prefix . 'acl_options ' . $this->db->sql_build_array('INSERT', $sql_ary);
				$this->db->sql_query($sql);
			}
		}

		// Set role permissions for ROLE_USER_FULL if it exists
		$sql = 'SELECT role_id FROM ' . $this->table_prefix . "acl_roles WHERE role_name = 'ROLE_USER_FULL'";
		$result = $this->db->sql_query($sql);
		$role_id = (int) $this->db->sql_fetchfield('role_id');
		$this->db->sql_freeresult($result);

		if ($role_id)
		{
			foreach ($permissions as $permission)
			{
				$sql = 'SELECT auth_option_id FROM ' . $this->table_prefix . "acl_options
					WHERE auth_option = '" . $this->db->sql_escape($permission) . "'";
				$result = $this->db->sql_query($sql);
				$auth_option_id = (int) $this->db->sql_fetchfield('auth_option_id');
				$this->db->sql_freeresult($result);

				if ($auth_option_id)
				{
					$sql = 'SELECT role_id FROM ' . $this->table_prefix . "acl_roles_data
						WHERE role_id = $role_id AND auth_option_id = $auth_option_id";
					$result = $this->db->sql_query($sql);
					$already_set = (bool) $this->db->sql_fetchfield('role_id');
					$this->db->sql_freeresult($result);

					if (!$already_set)
					{
						$sql_ary = array(
							'role_id'        => $role_id,
							'auth_option_id' => $auth_option_id,
							'auth_setting'   => 1,
						);
						$sql = 'INSERT INTO ' . $this->table_prefix . 'acl_roles_data ' . $this->db->sql_build_array('INSERT', $sql_ary);
						$this->db->sql_query($sql);
					}
				}
			}
		}

		// Set group permissions for REGISTERED and GUESTS (u_rt_view only)
		$groups = array('REGISTERED', 'GUESTS');
		foreach ($groups as $group_name)
		{
			$sql = 'SELECT group_id FROM ' . $this->table_prefix . "groups
				WHERE group_name = '" . $this->db->sql_escape($group_name) . "'";
			$result = $this->db->sql_query($sql);
			$group_id = (int) $this->db->sql_fetchfield('group_id');
			$this->db->sql_freeresult($result);

			if ($group_id)
			{
				$sql = 'SELECT auth_option_id FROM ' . $this->table_prefix . "acl_options
					WHERE auth_option = 'u_rt_view'";
				$result = $this->db->sql_query($sql);
				$auth_option_id = (int) $this->db->sql_fetchfield('auth_option_id');
				$this->db->sql_freeresult($result);

				if ($auth_option_id)
				{
					$sql = 'SELECT group_id FROM ' . $this->table_prefix . "acl_groups
						WHERE group_id = $group_id
							AND auth_option_id = $auth_option_id
							AND forum_id = 0";
					$result = $this->db->sql_query($sql);
					$already_set = (bool) $this->db->sql_fetchfield('group_id');
					$this->db->sql_freeresult($result);

					if (!$already_set)
					{
						$sql_ary = array(
							'group_id'       => $group_id,
							'forum_id'       => 0,
							'auth_option_id' => $auth_option_id,
							'auth_role_id'   => 0,
							'auth_setting'   => 1,
						);
						$sql = 'INSERT INTO ' . $this->table_prefix . 'acl_groups ' . $this->db->sql_build_array('INSERT', $sql_ary);
						$this->db->sql_query($sql);
					}
				}
			}
		}
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
			array('custom', array(array($this, 'remove_permissions'))),

			// ACP modules via phpBB's module.remove
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

	/**
	 * Remove permissions
	 */
	public function remove_permissions()
	{
		$permissions = array(
			'u_rt_view', 'u_rt_enable', 'u_rt_sort_start_time',
			'u_rt_unread_only', 'u_rt_location', 'u_rt_number',
		);

		foreach ($permissions as $permission)
		{
			$sql = 'SELECT auth_option_id FROM ' . $this->table_prefix . "acl_options
				WHERE auth_option = '" . $this->db->sql_escape($permission) . "'";
			$result = $this->db->sql_query($sql);
			$auth_option_id = (int) $this->db->sql_fetchfield('auth_option_id');
			$this->db->sql_freeresult($result);

			if ($auth_option_id)
			{
				$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "acl_roles_data WHERE auth_option_id = $auth_option_id");
				$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "acl_groups WHERE auth_option_id = $auth_option_id");
				$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "acl_users WHERE auth_option_id = $auth_option_id");
			}

			$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "acl_options WHERE auth_option = '" . $this->db->sql_escape($permission) . "'");
		}
	}
}
