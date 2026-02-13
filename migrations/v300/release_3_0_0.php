<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Release migration for avathar/recenttopics 3.0.0
 * Combines all previous paybas/recenttopics migrations (2.0.0 - 2.2.15)
 */

namespace avathar\recenttopics\migrations\v300;

class release_3_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['rt_version'])
			&& version_compare($this->config['rt_version'], '3.0.0', '>=');
	}

	static public function depends_on()
	{
		return array(
			'\avathar\recenttopics\migrations\basics\rt_config',
		);
	}

	public function update_data()
	{
		return array(
			array('config.add', array('rt_version', '3.0.0')),
			array('custom', array(array($this, 'update_version'))),
		);
	}

	/**
	 * Force rt_version to 3.0.0 (handles upgrade where config.add skipped because key existed)
	 */
	public function update_version()
	{
		$this->config->set('rt_version', '3.0.0');
	}

	public function revert_data()
	{
		return array(
			array('config.remove', array('rt_version')),
		);
	}
}
