<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace paybas\recenttopics\migrations;

class release_2_0_6 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '2.0.6', '>=');
	}

	static public function depends_on()
	{
		return [
			'\paybas\recenttopics\migrations\release_2_0_5',
        ];
	}

	public function update_schema()
	{
		return [
			'add_columns'    => [
				$this->table_prefix . 'users' => [
					'user_rt_enable'          => ['BOOL', 1],
					'user_rt_alt_location'    => ['BOOL', 0],
					'user_rt_sort_start_time' => ['BOOL', 0],
					'user_rt_unread_only'     => ['BOOL', 0],
                ],
            ],
        ];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'    => [
				$this->table_prefix . 'users' => [
					'user_rt_enable',
					'user_rt_alt_location',
					'user_rt_sort_start_time',
					'user_rt_unread_only',
                ],
            ],
        ];
	}

	public function update_data()
	{
		return [
			['config.remove', ['rt_unreadonly']],
			['config.add', ['rt_unread_only', 0]],
			['config.add', ['rt_alt_location', 0]],
			['config.update', ['rt_version', '2.0.6']],
			['permission.add', ['u_rt_view']],
			['permission.add', ['u_rt_enable']],
			['permission.add', ['u_rt_alt_location']],
			['permission.add', ['u_rt_sort_start_time']],
			['permission.add', ['u_rt_unread_only']],
			['permission.permission_set', ['ROLE_USER_FULL', 'u_rt_view']],
			['permission.permission_set', ['ROLE_USER_FULL', 'u_rt_enable']],
			['permission.permission_set', ['ROLE_USER_FULL', 'u_rt_alt_location']],
			['permission.permission_set', ['ROLE_USER_FULL', 'u_rt_sort_start_time']],
			['permission.permission_set', ['ROLE_USER_FULL', 'u_rt_unread_only']],
			['permission.permission_set', ['GUESTS', 'u_rt_view', 'group']],
        ];
	}

	public function revert_data()
	{
		return [
			['config.remove', ['rt_unread_only']],
			['config.remove', ['rt_alt_location']],

			['permission.remove', ['u_rt_view']],
			['permission.remove', ['u_rt_enable']],
			['permission.remove', ['u_rt_alt_location']],
			['permission.remove', ['u_rt_sort_start_time']],
			['permission.remove', ['u_rt_unread_only']],

        ];
	}
}
