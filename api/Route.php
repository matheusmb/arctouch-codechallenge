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
 * Class Route
 * @package CodeChallenge
 */
class Route {
	/** @var  string    The regular expression for the route */
	private $expr;

	/** @var  callable  The handler for the the route */
	private $handler;


	/**
	 * Creates a route for the given regular expression and handler.
	 *
	 * @param string $expr       The regular expresion that will be used to match the requests.
	 * @param callable $handler  The callback that will be executed during the request. The callback shall return its
	 * output as string. The function parameters are given by the Regular Expression match groups.
	 */
	public function __construct($expr, $handler) {
		$this->expr = $expr;
		$this->handler = $handler;
	}


	/**
	 * Match the given path with the Route Regex
	 * @param $path
	 *
	 * @return array|bool   Returns false if the path doesn't match the route. Otherwise returns and array with the
	 * matched groups from the Regex.
	 */
	public function doMatch($path) {
		$match = preg_match('@^'.$this->expr.'@', $path, $matches);

		if( $match ) {
			return array_slice($matches, 1);
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function getExpression() {
		return $this->expr;
	}

	/**
	 * Execute the handler function.
	 * @param array $params An array with the arguments for the handler function.
	 *
	 * @return string
	 */
	public function exec($params) {
		return call_user_func_array($this->handler, $params);
	}
}