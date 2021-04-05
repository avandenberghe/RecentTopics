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

class release_2_1_3 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '2.1.3', '>=');
	}

	static public function depends_on()
	{
		return [
			'\paybas\recenttopics\migrations\release_2_1_2',
        ];
	}

	public function update_data()
	{
		return [
			['config.update', ['rt_version', '2.1.3']],
			['custom', [[$this, 'update_default_loc']]],
        ];

	}

	public function update_default_loc()
	{
		$rt_location = 'RT_TOP';
		$this->config->set('rt_location', $rt_location);
		$sql = 'UPDATE ' . USERS_TABLE . " SET user_rt_location = '" . $rt_location . "' ";
		$this->db->sql_query($sql);
	}

}
