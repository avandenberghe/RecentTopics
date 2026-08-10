<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace avathar\recenttopics\controller;

/**
 * Contract for the standalone Recent Topics page controller.
 *
 * Nothing type-hints this interface: services.yml wires the concrete page_controller and routing.yml
 * resolves the route by service id and method name, so it is a convention marker rather than a seam
 * anything depends on. It is kept because phpBB extensions conventionally pair a controller with an
 * interface.
 *
 * page_controller is the only implementation. display_simple(), the second route, is deliberately
 * left out of the contract — an alternative implementation only has to provide the full page.
 */
interface page_interface
{
	/**
	 * Display the page
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 * @access public
	 */
	public function display();
}
