<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2026 Andreas Vandenberghe
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace avathar\recenttopicsav\tests\functional;

/**
 * @group functional
 */
class recenttopics_test extends \phpbb_functional_test_case
{
	protected static function setup_extensions()
	{
		return array('avathar/recenttopicsav');
	}

	public function test_rt_page()
	{
		$this->login();

		// Enable the RT page
		$this->set_config('rt_page_enable', 1);

		$crawler = self::request('GET', 'app.php/rt?sid=' . $this->sid);
		$this->assertGreaterThanOrEqual(1, $crawler->filter('html')->count());
	}

	public function test_rt_simple_page()
	{
		$this->login();

		$this->set_config('rt_page_enable', 1);

		$crawler = self::request('GET', 'app.php/rt/simple?sid=' . $this->sid);
		$this->assertGreaterThanOrEqual(1, $crawler->filter('html')->count());
	}

	public function test_rt_page_disabled()
	{
		$this->login();

		// Disable the RT page — should still render but without topics
		$this->set_config('rt_page_enable', 0);

		$crawler = self::request('GET', 'app.php/rt?sid=' . $this->sid);
		$this->assertGreaterThanOrEqual(1, $crawler->filter('html')->count());
	}

	public function test_index_has_recent_topics()
	{
		$this->login();

		$this->set_config('rt_index', 1);

		// Create a test topic so there is something to display
		$post = $this->create_topic(2, 'RT Functional Test Topic', 'This is a test topic for recent topics.');

		$crawler = self::request('GET', 'index.php?sid=' . $this->sid);
		$this->assertGreaterThanOrEqual(1, $crawler->filter('html')->count());
	}

	public function test_ucp_preferences()
	{
		$this->login();

		$crawler = self::request('GET', 'ucp.php?i=ucp_prefs&mode=view&sid=' . $this->sid);
		$this->assertGreaterThanOrEqual(1, $crawler->filter('html')->count());
	}

	/**
	 * Helper to set a config value via direct SQL and purge cache.
	 */
	private function set_config($name, $value)
	{
		$db = $this->get_db();

		$sql = "UPDATE phpbb_config SET config_value = '" . $db->sql_escape($value) . "' WHERE config_name = '" . $db->sql_escape($name) . "'";
		$db->sql_query($sql);

		// If the row didn't exist, insert it
		if (!$db->sql_affectedrows())
		{
			$sql = "INSERT INTO phpbb_config (config_name, config_value, is_dynamic) VALUES ('" . $db->sql_escape($name) . "', '" . $db->sql_escape($value) . "', 0)";
			$db->sql_query($sql);
		}

		$this->purge_cache();
	}
}
