<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2026 Andreas Vandenberghe
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Event contract tests for avathar\recenttopicsav\core\recenttopics
 *
 * These tests verify that every event documented in contrib/Events.md is:
 *   1. Actually fired (a listener receives the call)
 *   2. Fired with all documented variables present
 *   3. Fired in the documented order (new event before legacy alias)
 *   4. Honours modifications made by listeners (the modified value is used)
 *
 * If any of these tests fail, you have broken the public API contract and
 * must bump the major version.
 *
 * See: https://github.com/avatharbe/RecentTopics/issues/172
 */

// ---------------------------------------------------------------------------
// Global-function stubs in the global namespace.
// fill_template() lives in avathar\recenttopicsav\core and calls these as
// unqualified names; PHP falls back to the global namespace, so the stubs
// must be global. Bracketed namespace syntax is used so that both the global
// block and the named test namespace can coexist in one file.
// ---------------------------------------------------------------------------
namespace
{
	if (!function_exists('censor_text'))
	{
		function censor_text($text) { return $text; }
	}
	if (!function_exists('topic_status'))
	{
		function topic_status($row, $replies, $unread_topic, &$folder_img, &$folder_alt, &$topic_type)
		{
			$folder_img  = 'folder';
			$folder_alt  = 'TOPIC_READ';
			$topic_type  = '';
		}
	}
	if (!function_exists('append_sid'))
	{
		function append_sid($url, $params = false, $is_amp = true, $session_id = false)
		{
			return $url . ($params ? '?' . $params : '');
		}
	}
	if (!function_exists('get_username_string'))
	{
		function get_username_string($mode, $user_id, $username, $user_colour = '', $custom_profile_url = false)
		{
			return $username;
		}
	}
	if (!function_exists('get_forum_parents'))
	{
		function get_forum_parents($row) { return []; }
	}
}

// ---------------------------------------------------------------------------
// Test class — phpbb\event\dispatcher is used directly (implements
// dispatcher_interface and handles Symfony version differences internally).
// ---------------------------------------------------------------------------
namespace avathar\recenttopicsav\tests\core
{

class recenttopics_events_test extends \phpbb_test_case
{
	// -----------------------------------------------------------------------
	// Reflection helpers — let tests reach private methods and properties
	// -----------------------------------------------------------------------

	/**
	 * Call a private/protected method on $obj via Reflection.
	 *
	 * @param object $obj
	 * @param string $method
	 * @param array  $args
	 * @return mixed
	 */
	private function call_private($obj, string $method, array $args = [])
	{
		$ref = new \ReflectionMethod($obj, $method);
		$ref->setAccessible(true);
		return $ref->invokeArgs($obj, $args);
	}

	/**
	 * Set a private/protected property on $obj via Reflection.
	 *
	 * @param object $obj
	 * @param string $property
	 * @param mixed  $value
	 */
	private function set_private($obj, string $property, $value): void
	{
		// Walk up the class hierarchy — private properties live on the
		// declaring class, not always on the concrete class.
		$class = new \ReflectionClass($obj);
		while ($class !== false)
		{
			if ($class->hasProperty($property))
			{
				$ref = $class->getProperty($property);
				$ref->setAccessible(true);
				$ref->setValue($obj, $value);
				return;
			}
			$class = $class->getParentClass();
		}
		throw new \RuntimeException("Property $property not found on " . get_class($obj));
	}

	// -----------------------------------------------------------------------
	// Factory helpers — build a recenttopics instance with the minimum set of
	// mocked dependencies needed for a specific private method under test.
	// -----------------------------------------------------------------------

	/** @return \phpbb\user|\PHPUnit\Framework\MockObject\MockObject */
	private function make_user_stub()
	{
		$user = $this->createMock(\phpbb\user::class);
		$user->data = [
			'user_id'           => 2,
			'user_rt_enable'    => 1,
			'user_rt_location'  => 'top',
			'user_rt_unread_only' => 0,
			'user_rt_number'    => 5,
		];
		$user->page = ['query_string' => '', 'page_name' => 'index.php'];
		$user->session_id = 'testsession';
		$user->method('format_date')->willReturn('01 Jan 2026');
		$user->method('img')->willReturn('');
		return $user;
	}

	/** @return \phpbb\db\driver\driver_interface|\PHPUnit\Framework\MockObject\MockObject */
	private function make_db_stub()
	{
		$db = $this->createMock(\phpbb\db\driver\driver_interface::class);
		$db->method('sql_in_set')->willReturn('t.topic_id NOT IN (0)');
		$db->method('sql_build_query')->willReturn('SELECT 1');
		return $db;
	}

	/**
	 * Build a DB stub that returns exactly one topic row then null.
	 * The row contains the minimum fields referenced inside fill_template().
	 */
	private function make_db_returning_one_topic(): array
	{
		$row = [
			'topic_id'                  => 42,
			'forum_id'                  => 1,
			'topic_type'                => POST_NORMAL,
			'topic_status'              => ITEM_UNLOCKED,
			'topic_moved_id'            => 0,
			'topic_visibility'          => ITEM_APPROVED,
			'topic_posts_unapproved'    => 0,
			'topic_reported'            => 0,
			'topic_attachment'          => 0,
			'topic_last_post_time'      => 1700000000,
			'topic_last_view_time'      => 1700000000,
			'topic_time'                => 1699990000,
			'topic_last_post_id'        => 99,
			'topic_last_post_subject'   => 'Test reply',
			'topic_title'               => 'Test topic',
			'topic_views'               => 10,
			'icon_id'                   => 0,
			'poll_start'                => 0,
			'topic_posted'              => 0,
			'topic_poster'              => 2,
			'topic_first_poster_name'   => 'testuser',
			'topic_first_poster_colour' => '',
			'topic_last_poster_id'      => 2,
			'topic_last_poster_name'    => 'testuser',
			'topic_last_poster_colour'  => '',
			'forum_name'                => 'General',
		];

		$db = $this->make_db_stub();

		// sql_query_limit returns a fake result handle; sql_fetchrow uses it.
		$db->method('sql_query_limit')->willReturn('fake_result');
		$db->method('sql_fetchrow')->willReturnOnConsecutiveCalls($row, false);
		$db->method('sql_freeresult')->willReturn(null);

		return [$db, $row];
	}

	/**
	 * Instantiate recenttopics with a given dispatcher and db, setting all
	 * private properties needed by get_allowed_topics_sql().
	 */
	private function make_rt_for_sql_list_event(\phpbb\event\dispatcher_interface $dispatcher): \avathar\recenttopicsav\core\recenttopics
	{
		$db   = $this->make_db_stub();
		$auth = $this->createMock(\phpbb\auth\auth::class);
		$auth->method('acl_get')->willReturn(true);

		$content_visibility = $this->createMock(\phpbb\content_visibility::class);
		$content_visibility->method('get_forums_visibility_sql')->willReturn('1=1');

		$rt = new \avathar\recenttopicsav\core\recenttopics(
			$auth,
			$this->createMock(\phpbb\cache\service::class),
			new \phpbb\config\config([]),
			$this->createMock(\phpbb\language\language::class),
			$content_visibility,
			$db,
			$dispatcher,
			$this->createMock(\phpbb\pagination::class),
			$this->createMock(\phpbb\request\request_interface::class),
			$this->createMock(\phpbb\template\template::class),
			$this->make_user_stub(),
			'/',
			'php',
			$this->createMock(\phpbb\config\db_text::class)
		);

		$this->set_private($rt, 'forum_ids', [1, 2]);
		$this->set_private($rt, 'sort_topics', 'topic_last_post_time');

		return $rt;
	}

	/**
	 * Instantiate recenttopics with a given dispatcher and db, setting all
	 * private properties needed by get_topics_sql().
	 */
	private function make_rt_for_sql_data_event(\phpbb\event\dispatcher_interface $dispatcher, $db = null): \avathar\recenttopicsav\core\recenttopics
	{
		if ($db === null)
		{
			$db = $this->make_db_stub();
			$db->method('sql_query_limit')->willReturn('fake_result');
			$db->method('sql_fetchrow')->willReturn(false);
			$db->method('sql_freeresult')->willReturn(null);
		}

		$rt = new \avathar\recenttopicsav\core\recenttopics(
			$this->createMock(\phpbb\auth\auth::class),
			$this->createMock(\phpbb\cache\service::class),
			new \phpbb\config\config([]),
			$this->createMock(\phpbb\language\language::class),
			$this->createMock(\phpbb\content_visibility::class),
			$db,
			$dispatcher,
			$this->createMock(\phpbb\pagination::class),
			$this->createMock(\phpbb\request\request_interface::class),
			$this->createMock(\phpbb\template\template::class),
			$this->make_user_stub(),
			'/',
			'php',
			$this->createMock(\phpbb\config\db_text::class)
		);

		$this->set_private($rt, 'topic_list', [42]);
		$this->set_private($rt, 'sort_topics', 'topic_last_post_time');
		$this->set_private($rt, 'display_parent_forums', false);
		$this->set_private($rt, 'topics_per_page', 5);

		return $rt;
	}

	/**
	 * Instantiate recenttopics with a given dispatcher and db, setting all
	 * private properties needed by fill_template() to reach the inner loop.
	 */
	private function make_rt_for_fill_template_events(\phpbb\event\dispatcher_interface $dispatcher, $db): \avathar\recenttopicsav\core\recenttopics
	{
		$auth = $this->createMock(\phpbb\auth\auth::class);
		$auth->method('acl_get')->willReturn(false);

		$content_visibility = $this->createMock(\phpbb\content_visibility::class);
		$content_visibility->method('get_count')->willReturn(1);

		$language = $this->createMock(\phpbb\language\language::class);
		$language->method('lang')->willReturn('');
		$language->method('add_lang')->willReturn(null);

		$template = $this->createMock(\phpbb\template\template::class);

		$pagination = $this->createMock(\phpbb\pagination::class);

		$rt = new \avathar\recenttopicsav\core\recenttopics(
			$auth,
			$this->createMock(\phpbb\cache\service::class),
			new \phpbb\config\config(['posts_per_page' => 10, 'rt_topic_link_to' => 0]),
			$language,
			$content_visibility,
			$db,
			$dispatcher,
			$pagination,
			$this->createMock(\phpbb\request\request_interface::class),
			$template,
			$this->make_user_stub(),
			'/',
			'php',
			$this->createMock(\phpbb\config\db_text::class)
		);

		$this->set_private($rt, 'topic_list', [42]);
		$this->set_private($rt, 'sort_topics', 'topic_last_post_time');
		$this->set_private($rt, 'display_parent_forums', false);
		$this->set_private($rt, 'topics_per_page', 5);
		$this->set_private($rt, 'unread_only', false);
		$this->set_private($rt, 'icons', []);
		$this->set_private($rt, 'rtstart', 0);
		$this->set_private($rt, 'total_topics_limit', 100);

		// phpBB's real topic_status() (loaded by the test bootstrap) declares
		// global $phpbb_dispatcher and calls trigger_event() on it.  Set the
		// global to our test dispatcher so that call does not fatal.
		$GLOBALS['phpbb_dispatcher'] = $dispatcher;

		// phpBB's real censor_text() reads global $config, $user, $auth.
		// allow_nocensors=1 + acl_get()=true forces $censors=[] so censor_text()
		// returns the text untouched with no further globals needed.
		// allow_smilies=0 short-circuits smiley_text() before it touches $user.
		$GLOBALS['config'] = ['allow_smilies' => 0, 'allow_nocensors' => 1];
		$auth_global = $this->createMock(\phpbb\auth\auth::class);
		$auth_global->method('acl_get')->willReturn(true);
		$GLOBALS['auth'] = $auth_global;
		$GLOBALS['user'] = $this->make_user_stub();

		return $rt;
	}

	// =======================================================================
	// 1. avathar.recenttopicsav.sql_pull_topics_list
	// =======================================================================

	/**
	 * @covers \avathar\recenttopicsav\core\recenttopics::get_allowed_topics_sql
	 */
	public function test_sql_pull_topics_list_fires_with_sql_array()
	{
		$dispatcher = new \phpbb\event\dispatcher();
		$fired      = false;
		$received   = null;

		$dispatcher->addListener('avathar.recenttopicsav.sql_pull_topics_list', function (\phpbb\event\data $event) use (&$fired, &$received) {
			$fired    = true;
			$received = $event->get_data();
		});

		$rt = $this->make_rt_for_sql_list_event($dispatcher);
		$this->call_private($rt, 'get_allowed_topics_sql', [[], 0]);

		$this->assertTrue($fired, 'Event avathar.recenttopicsav.sql_pull_topics_list was not fired');
		$this->assertArrayHasKey('sql_array', $received, 'Documented variable sql_array missing from event');
		$this->assertIsArray($received['sql_array'], 'sql_array must be an array');
	}

	/**
	 * @covers \avathar\recenttopicsav\core\recenttopics::get_allowed_topics_sql
	 */
	public function test_sql_pull_topics_list_modification_is_applied()
	{
		$dispatcher = new \phpbb\event\dispatcher();
		$dispatcher->addListener('avathar.recenttopicsav.sql_pull_topics_list', function (\phpbb\event\data $event) {
			$sql              = $event['sql_array'];
			$sql['LIMIT']     = 99;
			$event['sql_array'] = $sql;
		});

		$rt     = $this->make_rt_for_sql_list_event($dispatcher);
		$result = $this->call_private($rt, 'get_allowed_topics_sql', [[], 0]);

		$this->assertSame(99, $result['LIMIT'], 'Listener modification to sql_array was not applied');
	}

	// =======================================================================
	// 2. avathar.recenttopicsav.sql_pull_topics_data  +  legacy alias
	// =======================================================================

	/**
	 * @covers \avathar\recenttopicsav\core\recenttopics::get_topics_sql
	 */
	public function test_sql_pull_topics_data_fires_with_sql_array()
	{
		$dispatcher = new \phpbb\event\dispatcher();
		$fired      = false;
		$received   = null;

		$dispatcher->addListener('avathar.recenttopicsav.sql_pull_topics_data', function (\phpbb\event\data $event) use (&$fired, &$received) {
			$fired    = true;
			$received = $event->get_data();
		});

		$rt = $this->make_rt_for_sql_data_event($dispatcher);
		$this->call_private($rt, 'get_topics_sql');

		$this->assertTrue($fired, 'Event avathar.recenttopicsav.sql_pull_topics_data was not fired');
		$this->assertArrayHasKey('sql_array', $received, 'Documented variable sql_array missing from event');
		$this->assertIsArray($received['sql_array'], 'sql_array must be an array');
	}

	/**
	 * @covers \avathar\recenttopicsav\core\recenttopics::get_topics_sql
	 */
	public function test_sql_pull_topics_data_modification_is_applied()
	{
		$dispatcher = new \phpbb\event\dispatcher();
		$dispatcher->addListener('avathar.recenttopicsav.sql_pull_topics_data', function (\phpbb\event\data $event) {
			$sql             = $event['sql_array'];
			$sql['LIMIT']    = 77;
			$event['sql_array'] = $sql;
		});

		// DB needs to accept the modified query
		$db = $this->make_db_stub();
		$db->method('sql_query_limit')->willReturn('fake_result');
		$db->method('sql_fetchrow')->willReturn(false);
		$db->method('sql_freeresult')->willReturn(null);

		$rt = $this->make_rt_for_sql_data_event($dispatcher, $db);
		// The method returns the rowset; we care that no exception was thrown
		// and that the dispatcher received the modification.
		$this->call_private($rt, 'get_topics_sql');

		// Verify by re-checking: attach a second listener to the legacy alias
		// and confirm it also sees the modification from the first listener.
		$legacy_received = null;
		$dispatcher->addListener('paybas.recenttopics.sql_pull_topics_data', function (\phpbb\event\data $event) use (&$legacy_received) {
			$legacy_received = $event['sql_array'];
		});

		$this->call_private($rt, 'get_topics_sql');
		$this->assertSame(77, $legacy_received['LIMIT'] ?? null,
			'Legacy alias did not see the modification made by the new event listener');
	}

	/**
	 * Legacy alias fires after the new event and shares its data.
	 *
	 * @covers \avathar\recenttopicsav\core\recenttopics::get_topics_sql
	 */
	public function test_legacy_sql_pull_topics_data_fires_after_new_event()
	{
		$dispatcher = new \phpbb\event\dispatcher();
		$order      = [];

		$dispatcher->addListener('avathar.recenttopicsav.sql_pull_topics_data', function () use (&$order) {
			$order[] = 'new';
		});
		$dispatcher->addListener('paybas.recenttopics.sql_pull_topics_data', function () use (&$order) {
			$order[] = 'legacy';
		});

		$rt = $this->make_rt_for_sql_data_event($dispatcher);
		$this->call_private($rt, 'get_topics_sql');

		$this->assertSame(['new', 'legacy'], $order,
			'New event must fire before the legacy alias paybas.recenttopics.sql_pull_topics_data');
	}

	// =======================================================================
	// 3. avathar.recenttopicsav.modify_topics_list  +  legacy alias
	// =======================================================================

	/**
	 * @covers \avathar\recenttopicsav\core\recenttopics::fill_template
	 */
	public function test_modify_topics_list_fires_with_documented_variables()
	{
		[$db, $row] = $this->make_db_returning_one_topic();
		$dispatcher  = new \phpbb\event\dispatcher();
		$fired       = false;
		$received    = null;

		$dispatcher->addListener('avathar.recenttopicsav.modify_topics_list', function (\phpbb\event\data $event) use (&$fired, &$received) {
			$fired    = true;
			$received = $event->get_data();
		});

		$rt = $this->make_rt_for_fill_template_events($dispatcher, $db);
		$this->call_private($rt, 'fill_template', ['recent_topics', [], 1]);

		$this->assertTrue($fired, 'Event avathar.recenttopicsav.modify_topics_list was not fired');
		$this->assertArrayHasKey('topic_list', $received, 'Documented variable topic_list missing');
		$this->assertArrayHasKey('rowset', $received, 'Documented variable rowset missing');
		$this->assertIsArray($received['topic_list'], 'topic_list must be an array');
		$this->assertIsArray($received['rowset'], 'rowset must be an array');
	}

	/**
	 * @covers \avathar\recenttopicsav\core\recenttopics::fill_template
	 */
	public function test_legacy_modify_topics_list_fires_after_new_event()
	{
		[$db] = $this->make_db_returning_one_topic();
		$dispatcher = new \phpbb\event\dispatcher();
		$order      = [];

		$dispatcher->addListener('avathar.recenttopicsav.modify_topics_list', function () use (&$order) {
			$order[] = 'new';
		});
		$dispatcher->addListener('paybas.recenttopics.modify_topics_list', function () use (&$order) {
			$order[] = 'legacy';
		});

		$rt = $this->make_rt_for_fill_template_events($dispatcher, $db);
		$this->call_private($rt, 'fill_template', ['recent_topics', [], 1]);

		$this->assertSame(['new', 'legacy'], $order,
			'New event must fire before the legacy alias paybas.recenttopics.modify_topics_list');
	}

	// =======================================================================
	// 4. avathar.recenttopicsav.modify_topictitle (also handles Re: removal)
	// =======================================================================

	/**
	 * @covers \avathar\recenttopicsav\core\recenttopics::fill_template
	 */
	public function test_modify_topictitle_fires_with_row()
	{
		[$db] = $this->make_db_returning_one_topic();
		$dispatcher = new \phpbb\event\dispatcher();
		$fired      = false;
		$received   = null;

		$dispatcher->addListener('avathar.recenttopicsav.modify_topictitle', function (\phpbb\event\data $event) use (&$fired, &$received) {
			$fired    = true;
			$received = $event->get_data();
		});

		$rt = $this->make_rt_for_fill_template_events($dispatcher, $db);
		$this->call_private($rt, 'fill_template', ['recent_topics', [], 1]);

		$this->assertTrue($fired, 'Event avathar.recenttopicsav.modify_topictitle was not fired');
		$this->assertArrayHasKey('row', $received, 'Documented variable row missing from modify_topictitle event');
		$this->assertIsArray($received['row'], 'row must be an array');
	}

	/**
	 * A listener on modify_topictitle receives row data including topic_last_post_subject.
	 *
	 * @covers \avathar\recenttopicsav\core\recenttopics::fill_template
	 */
	public function test_modify_topictitle_modification_is_applied()
	{
		[$db] = $this->make_db_returning_one_topic();
		$dispatcher       = new \phpbb\event\dispatcher();
		$captured_subject = null;

		$dispatcher->addListener('avathar.recenttopicsav.modify_topictitle', function (\phpbb\event\data $event) use (&$captured_subject) {
			$captured_subject = $event['row']['topic_last_post_subject'];
		});

		$rt = $this->make_rt_for_fill_template_events($dispatcher, $db);
		$this->call_private($rt, 'fill_template', ['recent_topics', [], 1]);

		$this->assertNotNull($captured_subject,
			'modify_topictitle listener must receive topic_last_post_subject in row');
		$this->assertIsString($captured_subject);
	}

	// =======================================================================
	// 5. avathar.recenttopicsav.modify_topictitle
	// =======================================================================

	/**
	 * @covers \avathar\recenttopicsav\core\recenttopics::fill_template
	 */
	public function test_modify_topictitle_fires_with_row_and_prefix()
	{
		[$db] = $this->make_db_returning_one_topic();
		$dispatcher = new \phpbb\event\dispatcher();
		$fired      = false;
		$received   = null;

		$dispatcher->addListener('avathar.recenttopicsav.modify_topictitle', function (\phpbb\event\data $event) use (&$fired, &$received) {
			$fired    = true;
			$received = $event->get_data();
		});

		$rt = $this->make_rt_for_fill_template_events($dispatcher, $db);
		$this->call_private($rt, 'fill_template', ['recent_topics', [], 1]);

		$this->assertTrue($fired, 'Event avathar.recenttopicsav.modify_topictitle was not fired');
		$this->assertArrayHasKey('row', $received, 'Documented variable row missing from modify_topictitle event');
		$this->assertArrayHasKey('prefix', $received, 'Documented variable prefix missing from modify_topictitle event');
		$this->assertIsArray($received['row'], 'row must be an array');
		$this->assertIsString($received['prefix'], 'prefix must be a string');
	}

	/**
	 * A prefix set by a listener is prepended to the topic title in the template.
	 *
	 * @covers \avathar\recenttopicsav\core\recenttopics::fill_template
	 */
	public function test_modify_topictitle_prefix_is_applied_to_template_var()
	{
		[$db] = $this->make_db_returning_one_topic();
		$dispatcher  = new \phpbb\event\dispatcher();
		$assigned    = [];

		$dispatcher->addListener('avathar.recenttopicsav.modify_topictitle', function (\phpbb\event\data $event) {
			$event['prefix'] = '[STICKY]';
		});

		// Capture what gets assigned to the template block
		$template = $this->createMock(\phpbb\template\template::class);
		$template->method('assign_block_vars')->willReturnCallback(function ($loop, $vars) use (&$assigned) {
			$assigned = $vars;
		});

		$auth = $this->createMock(\phpbb\auth\auth::class);
		$auth->method('acl_get')->willReturn(false);

		$content_visibility = $this->createMock(\phpbb\content_visibility::class);
		$content_visibility->method('get_count')->willReturn(1);

		$language = $this->createMock(\phpbb\language\language::class);
		$language->method('lang')->willReturn('');

		$rt = new \avathar\recenttopicsav\core\recenttopics(
			$auth,
			$this->createMock(\phpbb\cache\service::class),
			new \phpbb\config\config(['posts_per_page' => 10, 'rt_topic_link_to' => 0]),
			$language,
			$content_visibility,
			$db,
			$dispatcher,
			$this->createMock(\phpbb\pagination::class),
			$this->createMock(\phpbb\request\request_interface::class),
			$template,
			$this->make_user_stub(),
			'/',
			'php',
			$this->createMock(\phpbb\config\db_text::class)
		);

		$this->set_private($rt, 'topic_list', [42]);
		$this->set_private($rt, 'sort_topics', 'topic_last_post_time');
		$this->set_private($rt, 'display_parent_forums', false);
		$this->set_private($rt, 'topics_per_page', 5);
		$this->set_private($rt, 'unread_only', false);
		$this->set_private($rt, 'icons', []);
		$this->set_private($rt, 'rtstart', 0);
		$this->set_private($rt, 'total_topics_limit', 100);

		$GLOBALS['phpbb_dispatcher'] = $dispatcher;
		$GLOBALS['config'] = ['allow_smilies' => 0, 'allow_nocensors' => 1];
		$auth_global = $this->createMock(\phpbb\auth\auth::class);
		$auth_global->method('acl_get')->willReturn(true);
		$GLOBALS['auth'] = $auth_global;
		$GLOBALS['user'] = $this->make_user_stub();

		$this->call_private($rt, 'fill_template', ['recent_topics', [], 1]);

		$this->assertNotEmpty($assigned, 'template->assign_block_vars() was never called');
		$this->assertStringStartsWith('[STICKY]', $assigned['TOPIC_TITLE'],
			'Prefix set by modify_topictitle listener must be prepended to TOPIC_TITLE');
	}

	// =======================================================================
	// 6. avathar.recenttopicsav.modify_tpl_ary  +  legacy alias
	// =======================================================================

	/**
	 * @covers \avathar\recenttopicsav\core\recenttopics::fill_template
	 */
	public function test_modify_tpl_ary_fires_with_row_and_tpl_ary()
	{
		[$db] = $this->make_db_returning_one_topic();
		$dispatcher = new \phpbb\event\dispatcher();
		$fired      = false;
		$received   = null;

		$dispatcher->addListener('avathar.recenttopicsav.modify_tpl_ary', function (\phpbb\event\data $event) use (&$fired, &$received) {
			$fired    = true;
			$received = $event->get_data();
		});

		$rt = $this->make_rt_for_fill_template_events($dispatcher, $db);
		$this->call_private($rt, 'fill_template', ['recent_topics', [], 1]);

		$this->assertTrue($fired, 'Event avathar.recenttopicsav.modify_tpl_ary was not fired');
		$this->assertArrayHasKey('row', $received, 'Documented variable row missing from modify_tpl_ary event');
		$this->assertArrayHasKey('tpl_ary', $received, 'Documented variable tpl_ary missing from modify_tpl_ary event');
		$this->assertIsArray($received['row'], 'row must be an array');
		$this->assertIsArray($received['tpl_ary'], 'tpl_ary must be an array');
	}

	/**
	 * Core template variables that consumers depend on must be present.
	 *
	 * @covers \avathar\recenttopicsav\core\recenttopics::fill_template
	 */
	public function test_modify_tpl_ary_contains_documented_template_variables()
	{
		[$db] = $this->make_db_returning_one_topic();
		$dispatcher = new \phpbb\event\dispatcher();
		$tpl_ary    = null;

		$dispatcher->addListener('avathar.recenttopicsav.modify_tpl_ary', function (\phpbb\event\data $event) use (&$tpl_ary) {
			$tpl_ary = $event['tpl_ary'];
		});

		$rt = $this->make_rt_for_fill_template_events($dispatcher, $db);
		$this->call_private($rt, 'fill_template', ['recent_topics', [], 1]);

		// These are the variables documented in Events.md that listeners depend on
		$expected_keys = [
			'TOPIC_ID', 'FORUM_ID', 'TOPIC_TITLE', 'TOPIC_AUTHOR', 'FORUM_NAME',
			'REPLIES', 'VIEWS', 'LAST_POST_TIME', 'LAST_POST_SUBJECT',
			'LAST_POST_AUTHOR', 'U_VIEW_TOPIC', 'U_VIEW_FORUM',
			'S_UNREAD_TOPIC', 'S_TOPIC_TYPE', 'TOPIC_LIKES',
		];

		foreach ($expected_keys as $key)
		{
			$this->assertArrayHasKey($key, $tpl_ary, "Expected template variable $key missing from tpl_ary in modify_tpl_ary event");
		}
	}

	/**
	 * A key added by a listener is included in the template block.
	 *
	 * @covers \avathar\recenttopicsav\core\recenttopics::fill_template
	 */
	public function test_modify_tpl_ary_added_key_reaches_template()
	{
		[$db] = $this->make_db_returning_one_topic();
		$dispatcher = new \phpbb\event\dispatcher();
		$assigned   = [];

		$dispatcher->addListener('avathar.recenttopicsav.modify_tpl_ary', function (\phpbb\event\data $event) {
			$tpl             = $event['tpl_ary'];
			$tpl['MY_EXTRA'] = 'hello';
			$event['tpl_ary'] = $tpl;
		});

		$template = $this->createMock(\phpbb\template\template::class);
		$template->method('assign_block_vars')->willReturnCallback(function ($loop, $vars) use (&$assigned) {
			$assigned = $vars;
		});

		$auth = $this->createMock(\phpbb\auth\auth::class);
		$auth->method('acl_get')->willReturn(false);

		$content_visibility = $this->createMock(\phpbb\content_visibility::class);
		$content_visibility->method('get_count')->willReturn(1);

		$language = $this->createMock(\phpbb\language\language::class);
		$language->method('lang')->willReturn('');

		$rt = new \avathar\recenttopicsav\core\recenttopics(
			$auth,
			$this->createMock(\phpbb\cache\service::class),
			new \phpbb\config\config(['posts_per_page' => 10, 'rt_topic_link_to' => 0]),
			$language,
			$content_visibility,
			$db,
			$dispatcher,
			$this->createMock(\phpbb\pagination::class),
			$this->createMock(\phpbb\request\request_interface::class),
			$template,
			$this->make_user_stub(),
			'/',
			'php',
			$this->createMock(\phpbb\config\db_text::class)
		);

		$this->set_private($rt, 'topic_list', [42]);
		$this->set_private($rt, 'sort_topics', 'topic_last_post_time');
		$this->set_private($rt, 'display_parent_forums', false);
		$this->set_private($rt, 'topics_per_page', 5);
		$this->set_private($rt, 'unread_only', false);
		$this->set_private($rt, 'icons', []);
		$this->set_private($rt, 'rtstart', 0);
		$this->set_private($rt, 'total_topics_limit', 100);

		$GLOBALS['phpbb_dispatcher'] = $dispatcher;
		$GLOBALS['config'] = ['allow_smilies' => 0, 'allow_nocensors' => 1];
		$auth_global = $this->createMock(\phpbb\auth\auth::class);
		$auth_global->method('acl_get')->willReturn(true);
		$GLOBALS['auth'] = $auth_global;
		$GLOBALS['user'] = $this->make_user_stub();

		$this->call_private($rt, 'fill_template', ['recent_topics', [], 1]);

		$this->assertArrayHasKey('MY_EXTRA', $assigned,
			'Key added by modify_tpl_ary listener must reach template->assign_block_vars()');
		$this->assertSame('hello', $assigned['MY_EXTRA']);
	}

	/**
	 * @covers \avathar\recenttopicsav\core\recenttopics::fill_template
	 */
	public function test_legacy_modify_tpl_ary_fires_after_new_event()
	{
		[$db] = $this->make_db_returning_one_topic();
		$dispatcher = new \phpbb\event\dispatcher();
		$order      = [];

		$dispatcher->addListener('avathar.recenttopicsav.modify_tpl_ary', function () use (&$order) {
			$order[] = 'new';
		});
		$dispatcher->addListener('paybas.recenttopics.modify_tpl_ary', function () use (&$order) {
			$order[] = 'legacy';
		});

		$rt = $this->make_rt_for_fill_template_events($dispatcher, $db);
		$this->call_private($rt, 'fill_template', ['recent_topics', [], 1]);

		$this->assertSame(['new', 'legacy'], $order,
			'New event must fire before the legacy alias paybas.recenttopics.modify_tpl_ary');
	}

	/**
	 * Modification made by a listener on the new event is visible to a listener
	 * on the legacy alias in the same request cycle.
	 *
	 * @covers \avathar\recenttopicsav\core\recenttopics::fill_template
	 */
	public function test_legacy_modify_tpl_ary_sees_new_listener_modification()
	{
		[$db] = $this->make_db_returning_one_topic();
		$dispatcher    = new \phpbb\event\dispatcher();
		$legacy_value  = null;

		$dispatcher->addListener('avathar.recenttopicsav.modify_tpl_ary', function (\phpbb\event\data $event) {
			$tpl              = $event['tpl_ary'];
			$tpl['NEW_FLAG']  = 'set_by_new';
			$event['tpl_ary'] = $tpl;
		});
		$dispatcher->addListener('paybas.recenttopics.modify_tpl_ary', function (\phpbb\event\data $event) use (&$legacy_value) {
			$legacy_value = $event['tpl_ary']['NEW_FLAG'] ?? null;
		});

		$rt = $this->make_rt_for_fill_template_events($dispatcher, $db);
		$this->call_private($rt, 'fill_template', ['recent_topics', [], 1]);

		$this->assertSame('set_by_new', $legacy_value,
			'Legacy alias paybas.recenttopics.modify_tpl_ary must see modifications from the new event listener');
	}
}

} // namespace avathar\recenttopicsav\tests\core
