<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2026 Andreas Vandenberghe
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace avathar\recenttopicsav\tests\event;

class ucp_listener_test extends \phpbb_test_case
{
	/** @var \avathar\recenttopicsav\event\ucp_listener */
	protected $listener;

	/** @var \phpbb\auth\auth|\PHPUnit\Framework\MockObject\MockObject */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request|\PHPUnit\Framework\MockObject\MockObject */
	protected $request;

	/** @var \phpbb\template\template|\PHPUnit\Framework\MockObject\MockObject */
	protected $template;

	/** @var \phpbb\user|\PHPUnit\Framework\MockObject\MockObject */
	protected $user;

	/** @var \phpbb\language\language|\PHPUnit\Framework\MockObject\MockObject */
	protected $language;

	/** @var \phpbb\db\driver\driver_interface|\PHPUnit\Framework\MockObject\MockObject */
	protected $db;

	public function setUp(): void
	{
		parent::setUp();

		$this->auth = $this->createMock('\phpbb\auth\auth');
		$this->config = new \phpbb\config\config(array(
			'rt_index'           => 1,
			'rt_sort_start_time' => 0,
			'rt_unread_only'     => 0,
			'rt_location'        => 'RT_TOP',
			'rt_number'          => 5,
		));
		$this->request = $this->createMock('\phpbb\request\request');
		$this->template = $this->createMock('\phpbb\template\template');
		$this->user = $this->getMockBuilder('\phpbb\user')
			->disableOriginalConstructor()
			->getMock();
		$this->language = $this->createMock('\phpbb\language\language');
		$this->db = $this->createMock('\phpbb\db\driver\driver_interface');
	}

	protected function set_listener()
	{
		$this->listener = new \avathar\recenttopicsav\event\ucp_listener(
			$this->auth,
			$this->config,
			$this->request,
			$this->template,
			$this->user,
			$this->language,
			$this->db
		);
	}

	public function test_getSubscribedEvents()
	{
		$this->assertEquals(array(
			'core.ucp_prefs_view_data',
			'core.ucp_prefs_view_update_data',
			'core.ucp_register_data_after',
		), array_keys(\avathar\recenttopicsav\event\ucp_listener::getSubscribedEvents()));
	}

	public function test_ucp_prefs_set_data()
	{
		$this->set_listener();

		$event = new \phpbb\event\data(array(
			'data'    => array(
				'rt_enable'          => 1,
				'rt_location'        => 'RT_BOTTOM',
				'rt_number'          => 10,
				'rt_sort_start_time' => 1,
				'rt_unread_only'     => 0,
			),
			'sql_ary' => array(),
		));

		$this->listener->ucp_prefs_set_data($event);

		$this->assertEquals(1, $event['sql_ary']['user_rt_enable']);
		$this->assertEquals('RT_BOTTOM', $event['sql_ary']['user_rt_location']);
		$this->assertEquals(10, $event['sql_ary']['user_rt_number']);
		$this->assertEquals(1, $event['sql_ary']['user_rt_sort_start_time']);
		$this->assertEquals(0, $event['sql_ary']['user_rt_unread_only']);
	}

	public function test_ucp_prefs_get_data_no_submit()
	{
		$this->user->data = array(
			'user_rt_enable'          => 1,
			'user_rt_location'        => 'RT_TOP',
			'user_rt_number'          => 5,
			'user_rt_sort_start_time' => 0,
			'user_rt_unread_only'     => 0,
		);

		$this->request->method('variable')
			->willReturnCallback(function ($var, $default) {
				return $default;
			});

		$this->auth->method('acl_get')
			->willReturnCallback(function ($perm) {
				return ($perm === 'u_rt_view' || $perm === 'u_rt_enable');
			});

		$this->language->expects($this->once())
			->method('add_lang')
			->with('recenttopics_ucp', 'avathar/recenttopicsav');

		$this->template->expects($this->once())
			->method('assign_vars');

		$this->set_listener();

		$event = new \phpbb\event\data(array(
			'data'   => array(),
			'submit' => false,
		));

		$this->listener->ucp_prefs_get_data($event);

		$this->assertEquals(1, $event['data']['rt_enable']);
		$this->assertEquals('RT_TOP', $event['data']['rt_location']);
		$this->assertEquals(5, $event['data']['rt_number']);
	}

	public function test_ucp_prefs_get_data_on_submit()
	{
		$this->user->data = array(
			'user_rt_enable'          => 1,
			'user_rt_location'        => 'RT_TOP',
			'user_rt_number'          => 5,
			'user_rt_sort_start_time' => 0,
			'user_rt_unread_only'     => 0,
		);

		$this->request->method('variable')
			->willReturnCallback(function ($var, $default) {
				return $default;
			});

		// On submit, template should not be touched
		$this->template->expects($this->never())
			->method('assign_vars');

		$this->set_listener();

		$event = new \phpbb\event\data(array(
			'data'   => array(),
			'submit' => true,
		));

		$this->listener->ucp_prefs_get_data($event);

		// Data should still be merged
		$this->assertArrayHasKey('rt_enable', $event['data']);
	}

	public function test_ucp_register_set_data()
	{
		// Verify the SQL query is built and executed
		$this->db->expects($this->once())
			->method('sql_build_array')
			->with('UPDATE', $this->callback(function ($sql_ary) {
				return $sql_ary['user_rt_enable'] === 1
					&& $sql_ary['user_rt_location'] === 'RT_TOP'
					&& $sql_ary['user_rt_number'] === 5
					&& $sql_ary['user_rt_sort_start_time'] === 0
					&& $sql_ary['user_rt_unread_only'] === 0;
			}))
			->willReturn("user_rt_enable = 1");

		$this->db->expects($this->once())
			->method('sql_query');

		$this->set_listener();

		$event = new \phpbb\event\data(array(
			'user_id' => 3,
		));

		$this->listener->ucp_register_set_data($event);
	}
}
