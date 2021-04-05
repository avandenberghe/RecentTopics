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

class release_2_2_7 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '2.2.7', '>=');
	}

	static public function depends_on()
	{
		return [
			'\paybas\recenttopics\migrations\release_2_2_6',
        ];
	}

	public function update_data()
	{
		return [
			['config.update', ['rt_version', '2.2.7']],
			['permission.add', ['u_rt_number']],
			['permission.permission_set', ['ROLE_USER_FULL', 'u_rt_number']]
        ];
	}

	public function revert_data()
	{
		return [
			['permission.remove', ['u_rt_number']],
			['config.update', ['rt_version', '2.2.6']],
        ];
	}

	public function update_schema()
	{
		return [
			'add_columns'    => [
				$this->table_prefix . 'users' => [
					'user_rt_number'      => ['UINT', 5],
                ],
            ],
        ];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'    => [
				$this->table_prefix . 'users' => [
					'user_rt_number',
                ],
            ],
        ];
	}
}
