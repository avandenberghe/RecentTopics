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

class release_2_1_1 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '2.1.1', '>=');
	}

	static public function depends_on()
	{
		return [
			'\paybas\recenttopics\migrations\release_2_1_0',
        ];
	}

	public function update_data()
	{
		return [
			['config.update', ['rt_version', '2.1.1']],
			['config.update', ['rt_location', 'RT_SIDE']],
			['custom', [[$this, 'update_default_loc']]],
        ];

	}

	public function update_default_loc()
	{
		$sql = 'UPDATE ' . USERS_TABLE . ' SET user_rt_location = \'RT_SIDE\' ';
		$this->db->sql_query($sql);
	}

}
