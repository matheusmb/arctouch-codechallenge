<?php
/**
 * This file is part of the ArcTouch CodeChallenge requested during the Interview Process.
 *
 * @package CodeChallenge
 * @author Matheus Muller Borges
 * @version 0.0.1
 */
namespace CodeChallenge;

/**
 * Class Router
 * @package CodeChallenge
 */
class Router {
	/** @var  array The user routes */
	private $routes;

	/** @var callable Default handler used when can`t match a route */
	private $defaultHandler;

	/**
	 * Router constructor.
	 *
	 * @param callable $defaultHandler  Default handler used when can`t match a route. The handler shouldn't output contents.
	 */
	public function __construct($defaultHandler) {
		$this->defaultHandler = $defaultHandler;
	}

	/**
	 * @param string $path           The Regular Expression that defines the route.
	 * @param callable $handler      The handler shall return its output as a string.
	 */
	public function addRoute($path, $handler) {
		$this->routes[] = new Route($path, $handler);
	}

	/**
	 * Execute the route handler that matches the given $path. If route is found, it execute and returns the content of
	 * the default handler.
	 *
	 * @param $path     The path used to match a route.
	 *
	 * @return string   The route string output.
	 */
	public function doRoute($path) {
		foreach($this->routes as $route) {
			$matches = $route->doMatch($path);

			if(false !== $route->doMatch($path)) {
				return $route->exec($matches);
			}
		}

		// Not found a handler for the given path
		return call_user_func($this->defaultHandler);
	}
}