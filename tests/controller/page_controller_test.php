<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2026 Andreas Vandenberghe
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace avathar\recenttopicsav\tests\controller;

use Symfony\Component\HttpFoundation\Response;

class page_controller_test extends \phpbb_test_case
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface|\PHPUnit\Framework\MockObject\MockObject */
	protected $db;

	/** @var \phpbb\controller\helper|\PHPUnit\Framework\MockObject\MockObject */
	protected $helper;

	/** @var \phpbb\language\language|\PHPUnit\Framework\MockObject\MockObject */
	protected $language;

	/** @var \avathar\recenttopicsav\core\recenttopics|\PHPUnit\Framework\MockObject\MockObject */
	protected $rt_functions;

	/** @var \phpbb\user|\PHPUnit\Framework\MockObject\MockObject */
	protected $user;

	public function setUp(): void
	{
		parent::setUp();

		$this->config = new \phpbb\config\config(array(
			'rt_page_enable' => 1,
		));

		$this->db = $this->createMock('\phpbb\db\driver\driver_interface');
		$this->helper = $this->createMock('\phpbb\controller\helper');
		$this->language = $this->createMock('\phpbb\language\language');

		$this->rt_functions = $this->getMockBuilder('\avathar\recenttopicsav\core\recenttopics')
			->disableOriginalConstructor()
			->getMock();

		$this->user = $this->getMockBuilder('\phpbb\user')
			->disableOriginalConstructor()
			->getMock();
	}

	protected function get_controller()
	{
		return new \avathar\recenttopicsav\controller\page_controller(
			$this->config,
			$this->db,
			$this->helper,
			$this->language,
			$this->rt_functions,
			$this->user
		);
	}

	public function test_display_enabled()
	{
		$this->config['rt_page_enable'] = 1;

		$this->rt_functions->expects($this->once())
			->method('display_recent_topics');

		$this->language->method('lang')
			->with('RECENT_TOPICS')
			->willReturn('Recent Topics');

		$this->language->expects($this->once())
			->method('add_lang')
			->with(array('info_acp_recenttopics', 'recenttopics'), 'avathar/recenttopicsav');

		$response = new Response();
		$this->helper->expects($this->once())
			->method('render')
			->with('recent_topics_page.html', 'Recent Topics')
			->willReturn($response);

		$controller = $this->get_controller();
		$result = $controller->display();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $result);
		$this->assertSame($response, $result);
	}

	public function test_display_disabled()
	{
		$this->config['rt_page_enable'] = 0;

		$this->rt_functions->expects($this->never())
			->method('display_recent_topics');

		$this->language->method('lang')
			->willReturn('Recent Topics');

		$response = new Response();
		$this->helper->method('render')->willReturn($response);

		$controller = $this->get_controller();
		$result = $controller->display();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $result);
	}

	public function test_display_simple()
	{
		$this->config['rt_page_enable'] = 1;

		$this->rt_functions->expects($this->once())
			->method('display_recent_topics');

		$this->language->method('lang')
			->willReturn('Recent Topics');

		$this->language->expects($this->once())
			->method('add_lang');

		// Mock the DB query for force_style('pbwow3')
		$this->db->method('sql_escape')
			->willReturnArgument(0);
		$this->db->method('sql_query')
			->willReturn('result');
		$this->db->method('sql_fetchrow')
			->willReturn(false);
		$this->db->method('sql_freeresult');

		$response = new Response();
		$this->helper->expects($this->once())
			->method('render')
			->with('recent_topics_simple.html', 'Recent Topics')
			->willReturn($response);

		$controller = $this->get_controller();
		$result = $controller->display_simple();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $result);
		$this->assertSame($response, $result);
	}

	public function test_display_simple_with_style_found()
	{
		$this->config['rt_page_enable'] = 1;

		$this->rt_functions->expects($this->once())
			->method('display_recent_topics');

		$this->language->method('lang')
			->willReturn('Recent Topics');

		// Mock the DB to return a pbwow3 style row
		$style_row = array(
			'style_id' => 2,
			'style_name' => 'PBWoW3',
			'style_path' => 'pbwow3',
			'style_active' => 1,
		);
		$this->db->method('sql_escape')
			->willReturnArgument(0);
		$this->db->method('sql_query')
			->willReturn('result');
		$this->db->method('sql_fetchrow')
			->willReturn($style_row);
		$this->db->method('sql_freeresult');

		$response = new Response();
		$this->helper->method('render')->willReturn($response);

		$controller = $this->get_controller();
		$result = $controller->display_simple();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $result);
		// Verify user style was set
		$this->assertEquals($style_row, $this->user->style);
	}
}
