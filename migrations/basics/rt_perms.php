<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Permissions migration: user permissions and role/group assignments
 */

namespace avathar\recenttopicsav\migrations\basics;

class rt_perms extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		$sql = 'SELECT auth_option_id FROM ' . $this->table_prefix . "acl_options
			WHERE auth_option = 'u_rt_view'";
		$result = $this->db->sql_query($sql);
		$exists = (bool) $this->db->sql_fetchfield('auth_option_id');
		$this->db->sql_freeresult($result);

		return $exists;
	}

	public static function depends_on()
	{
		return ['\avathar\recenttopicsav\migrations\basics\rt_module_add'];
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'add_permissions']]],
		];
	}

	/**
	 * Add permissions with graceful handling for missing roles
	 */
	public function add_permissions()
	{
		$permissions = [
			'u_rt_view', 'u_rt_enable', 'u_rt_sort_start_time',
			'u_rt_unread_only', 'u_rt_location', 'u_rt_number',
		];

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
				$sql_ary = [
					'auth_option'   => $permission,
					'is_global'     => 1,
					'is_local'      => 0,
					'founder_only'  => 0,
				];
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
						$sql_ary = [
							'role_id'        => $role_id,
							'auth_option_id' => $auth_option_id,
							'auth_setting'   => 1,
						];
						$sql = 'INSERT INTO ' . $this->table_prefix . 'acl_roles_data ' . $this->db->sql_build_array('INSERT', $sql_ary);
						$this->db->sql_query($sql);
					}
				}
			}
		}

		// Set group permissions for REGISTERED and GUESTS (u_rt_view only)
		$groups = ['REGISTERED', 'GUESTS'];
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
						$sql_ary = [
							'group_id'       => $group_id,
							'forum_id'       => 0,
							'auth_option_id' => $auth_option_id,
							'auth_role_id'   => 0,
							'auth_setting'   => 1,
						];
						$sql = 'INSERT INTO ' . $this->table_prefix . 'acl_groups ' . $this->db->sql_build_array('INSERT', $sql_ary);
						$this->db->sql_query($sql);
					}
				}
			}
		}
	}

	public function revert_data()
	{
		return [
			['custom', [[$this, 'remove_permissions']]],
		];
	}

	/**
	 * Remove permissions
	 */
	public function remove_permissions()
	{
		$permissions = [
			'u_rt_view', 'u_rt_enable', 'u_rt_sort_start_time',
			'u_rt_unread_only', 'u_rt_location', 'u_rt_number',
		];

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
