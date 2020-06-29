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

class release_2_2_8 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '2.2.8', '>=');
	}

	static public function depends_on()
	{
		return array(
			'\paybas\recenttopics\migrations\release_2_2_7',
		);
	}

	public function update_data()
	{
		return array(
			array('config.update', array('rt_version', '2.2.8')),
			array('custom', array(array($this, 'fix_rt_number'))),
			array('permission.permission_set', array('ROLE_USER_FULL', 'u_rt_view')),
		);

	}

	/**
	 * in 2.7 the column was created with a wrong default value so we need to reset it.
	 */
	public function fix_rt_number()
	{
		$sql = 'UPDATE ' . $this->table_prefix . 'users' . " SET user_rt_number = " . ((int) $this->config['rt_number'] > 0 ? (int) $this->config['rt_number'] : 5 );
		$this->db->sql_query($sql);
	}

}
