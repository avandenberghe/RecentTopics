<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */
namespace avathar\recenttopicsav\controller;

interface page_interface
{
		/**
		 * Display the page
		 *
		 * @param string $route The route name for a page
		 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
		 * @access public
		 */
		public function display();
}
