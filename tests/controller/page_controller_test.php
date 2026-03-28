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

class page_controller_test extends \phpbb_database_test_case
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\controller\helper|\PHPUnit\Framework\MockObject\MockObject */
	protected $helper;

	/** @var \phpbb\language\language|\PHPUnit\Framework\MockObject\MockObject */
	protected $language;

	/** @var \avathar\recenttopicsav\core\recenttopics|\PHPUnit\Framework\MockObject\MockObject */
	protected $rt_functions;

	/** @var \phpbb\user|\PHPUnit\Framework\MockObject\MockObject */
	protected $user;

	protected static function setup_extensions()
	{
		return array('avathar/recenttopicsav');
	}

	public function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/users.xml');
	}

	public function setUp(): void
	{
		parent::setUp();

		$this->db = $this->new_dbal();

		$this->config = new \phpbb\config\config(array(
			'rt_page_enable' => 1,
		));

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

	public function test_display_simple_with_pbwow3()
	{
		$this->config['rt_page_enable'] = 1;

		$this->rt_functions->expects($this->once())
			->method('display_recent_topics');

		$this->language->method('lang')
			->willReturn('Recent Topics');

		$this->language->expects($this->once())
			->method('add_lang');

		$response = new Response();
		$this->helper->expects($this->once())
			->method('render')
			->with('recent_topics_simple.html', 'Recent Topics')
			->willReturn($response);

		$controller = $this->get_controller();
		$result = $controller->display_simple();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $result);
		$this->assertSame($response, $result);

		// Verify that the user style was changed to pbwow3
		// The force_style method queries the DB for the style
		// Since we have pbwow3 in fixtures, the user style should be set
	}

	public function test_display_simple_without_pbwow3()
	{
		// Remove the pbwow3 style from the DB to test fallback
		$this->db->sql_query("DELETE FROM phpbb_styles WHERE style_path = 'pbwow3'");

		$this->config['rt_page_enable'] = 1;

		$this->rt_functions->expects($this->once())
			->method('display_recent_topics');

		$this->language->method('lang')
			->willReturn('Recent Topics');

		$response = new Response();
		$this->helper->method('render')->willReturn($response);

		$controller = $this->get_controller();
		$result = $controller->display_simple();

		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $result);
	}
}
