<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @copyright (c) 2017 Sajaki
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Release migration for avathar/recenttopicsav 3.0.0
 * Combines all previous paybas/recenttopics migrations (2.0.0 - 2.2.15)
 */

namespace avathar\recenttopicsav\migrations\v300;

class release_3_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['rt_version'])
			&& version_compare($this->config['rt_version'], '3.0.0', '>=');
	}

	public static function depends_on()
	{
		return ['\avathar\recenttopicsav\migrations\basics\rt_config'];
	}

	public function update_data()
	{
		return [
			['config.add', ['rt_version', '3.0.0']],
			['custom', [[$this, 'update_version']]],
		];
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
		return [
			['config.remove', ['rt_version']],
		];
	}
}
