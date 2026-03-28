<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2026 Andreas Vandenberghe (avathar)
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace avathar\recenttopicsav\migrations\v304;

class release_3_0_4 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->config->offsetExists('rt_ads_enable');
	}

	public static function depends_on()
	{
		return ['\avathar\recenttopicsav\migrations\v303\release_3_0_3'];
	}

	public function update_data()
	{
		return [
			['config.add', ['rt_ads_enable', 0]],
			['config_text.add', ['rt_ads_code', '']],
			['config.update', ['rt_version', '3.0.4']],
		];
	}

	public function revert_data()
	{
		return [
			['config.remove', ['rt_ads_enable']],
			['config_text.remove', ['rt_ads_code']],
		];
	}
}
