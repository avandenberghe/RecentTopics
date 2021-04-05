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

class release_2_1_0 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '2.1.0', '>=');
	}

	static public function depends_on()
	{
		return [
			'\paybas\recenttopics\migrations\release_2_0_6',
        ];
	}

	public function update_schema()
	{
		return [
			'drop_columns'    => [
				$this->table_prefix . 'users' => [
					'user_rt_alt_location',
                ],
            ],

			'add_columns'    => [
				$this->table_prefix . 'users' => [
					'user_rt_location'    => ['VCHAR:10', 'RT_TOP'],
                ],
            ],
        ];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'		=> [
				$this->table_prefix . 'users'		=> [
					'user_rt_location',
					'user_rt_alt_location',
                ],
            ],

        ];
	}

	public function update_data()
	{
		return [
			['config.update', ['rt_version', '2.1.0']],
			['config.remove', ['rt_alt_location']],
			['config.add',    ['rt_location', 'RT_TOP']],

			['permission.remove', ['u_rt_alt_location']],
			['permission.add', ['u_rt_location']],

			['permission.permission_set', ['ROLE_USER_FULL', 'u_rt_location']],

        ];
	}

	public function revert_data()
	{
		return [
			['config.remove', ['rt_location']],
			['permission.remove', ['u_rt_location']],

        ];
	}
}
