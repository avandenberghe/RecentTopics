<?php
/**
 * Created by PhpStorm.
 * User: Nix
 * Date: 5/02/18
 * Time: 21:37
 */
namespace avathar\recenttopics\controller;

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
