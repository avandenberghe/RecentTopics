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
			array('config.set', array('rt_version', '3.0.0')),

			// Clean up old paybas/recenttopics remnants, then add modules and permissions
			array('custom', array(array($this, 'cleanup_paybas'))),
			array('custom', array(array($this, 'add_acp_modules'))),
			array('custom', array(array($this, 'add_permissions'))),
		);
	}

	/**
	 * Clean up old paybas/recenttopics remnants from ext, migrations, and modules tables
	 */
	public function cleanup_paybas()
	{
		// Remove old paybas/recenttopics from ext table
		$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "ext WHERE ext_name = 'paybas/recenttopics'");

		// Remove old paybas migration entries
		$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "migrations WHERE migration_name LIKE '%paybas%recenttopics%'");

		// Remove old paybas child module(s) from modules table
		$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "modules
			WHERE module_class = 'acp'
				AND (module_basename LIKE '%paybas%recenttopics%')");
	}

	/**
	 * Add ACP modules
	 */
	public function add_acp_modules()
	{
		// Check if RECENT_TOPICS category module exists
		$sql = 'SELECT module_id FROM ' . $this->table_prefix . "modules
			WHERE module_langname = 'RECENT_TOPICS'
				AND module_class = 'acp'
				AND module_basename = ''
				AND module_mode = ''";
		$result = $this->db->sql_query($sql);
		$category_id = (int) $this->db->sql_fetchfield('module_id');
		$this->db->sql_freeresult($result);

		if (!$category_id)
		{
			// Find ACP_CAT_DOT_MODS parent
			$sql = 'SELECT module_id FROM ' . $this->table_prefix . "modules
				WHERE module_langname = 'ACP_CAT_DOT_MODS'
					AND module_class = 'acp'";
			$result = $this->db->sql_query($sql);
			$parent_id = (int) $this->db->sql_fetchfield('module_id');
			$this->db->sql_freeresult($result);

			if ($parent_id)
			{
				$module_data = array(
					'module_enabled'  => 1,
					'module_display'  => 1,
					'module_basename' => '',
					'module_class'    => 'acp',
					'module_mode'     => '',
					'module_auth'     => '',
					'module_langname' => 'RECENT_TOPICS',
					'parent_id'       => $parent_id,
				);

				$sql = 'INSERT INTO ' . $this->table_prefix . 'modules ' . $this->db->sql_build_array('INSERT', $module_data);
				$this->db->sql_query($sql);
				$category_id = (int) $this->db->sql_nextid();
			}
		}

		if ($category_id)
		{
			// Check if avathar child module already exists
			$sql = 'SELECT module_id FROM ' . $this->table_prefix . "modules
				WHERE module_class = 'acp'
					AND (module_basename = 'avathar\\\\recenttopics\\\\acp\\\\recenttopics_module'
						OR module_basename = '\\\\avathar\\\\recenttopics\\\\acp\\\\recenttopics_module')
					AND parent_id = " . $category_id;
			$result = $this->db->sql_query($sql);
			$child_exists = (bool) $this->db->sql_fetchfield('module_id');
			$this->db->sql_freeresult($result);

			if (!$child_exists)
			{
				$module_data = array(
					'module_enabled'  => 1,
					'module_display'  => 1,
					'module_basename' => '\\avathar\\recenttopics\\acp\\recenttopics_module',
					'module_class'    => 'acp',
					'module_mode'     => 'recenttopics_config',
					'module_auth'     => '',
					'module_langname' => 'RECENT_TOPICS_CONFIG',
					'parent_id'       => $category_id,
				);

				$sql = 'INSERT INTO ' . $this->table_prefix . 'modules ' . $this->db->sql_build_array('INSERT', $module_data);
				$this->db->sql_query($sql);
			}
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

			// Permissions and modules via custom callable
			array('custom', array(array($this, 'remove_permissions'))),
			array('custom', array(array($this, 'remove_acp_modules'))),
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
			// Remove from acl_roles_data
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

			// Remove the option itself
			$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "acl_options WHERE auth_option = '" . $this->db->sql_escape($permission) . "'");
		}
	}

	/**
	 * Remove ACP modules
	 */
	public function remove_acp_modules()
	{
		// Remove avathar child module
		$sql = 'DELETE FROM ' . $this->table_prefix . "modules
			WHERE module_class = 'acp'
				AND (module_basename = 'avathar\\\\recenttopics\\\\acp\\\\recenttopics_module'
					OR module_basename = '\\\\avathar\\\\recenttopics\\\\acp\\\\recenttopics_module')";
		$this->db->sql_query($sql);

		// Remove RECENT_TOPICS category if it has no children left
		$sql = 'SELECT module_id FROM ' . $this->table_prefix . "modules
			WHERE module_langname = 'RECENT_TOPICS'
				AND module_class = 'acp'
				AND module_basename = ''";
		$result = $this->db->sql_query($sql);
		$category_id = (int) $this->db->sql_fetchfield('module_id');
		$this->db->sql_freeresult($result);

		if ($category_id)
		{
			$sql = 'SELECT COUNT(module_id) as child_count FROM ' . $this->table_prefix . "modules
				WHERE parent_id = $category_id";
			$result = $this->db->sql_query($sql);
			$child_count = (int) $this->db->sql_fetchfield('child_count');
			$this->db->sql_freeresult($result);

			if ($child_count === 0)
			{
				$this->db->sql_query('DELETE FROM ' . $this->table_prefix . "modules WHERE module_id = $category_id");
			}
		}
	}
}
