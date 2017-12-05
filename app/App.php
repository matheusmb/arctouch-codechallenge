<?php
/**
 * This file is part of the ArcTouch CodeChallenge requested during the Interview Process.
 *
 * @package CodeChallengeSite
 * @author Matheus Muller Borges
 * @version 0.0.1
 */
namespace CodeChallengeSite;

/**
 * Class App
 * @package CodeChallengeSite
 */
class App {
	/** @var \Twig_Environment  */
	private $twig;

	/** @var \CodeChallenge\Router  */
	private $router;

	/** @var \CodeChallenge\Api  */
	private $api;

	public function __construct() {
		$loader     = new \Twig_Loader_Filesystem( dirname(__DIR__) . '/views' );
		$this->twig = new \Twig_Environment( $loader );


		$this->api = new \CodeChallenge\Api();
		$this->router = new \CodeChallenge\Router( [ $this, 'defaultResponseHandler' ] );
		$this->buildRoutes();
	}

	/**
	 * Create and assign handlers for website routes.
	 */
	private function buildRoutes() {
		$this->router->addRoute('(?:/?([0-9]*)$)', [$this, 'indexResponseHandler']);
		$this->router->addRoute('/search', [$this, 'searchResponseHandler']);
		$this->router->addRoute('/details/([0-9]+)$', [$this, 'detailsResponseHandler']);
	}

	/**
	 * Handle the index page (Upcoming Movies List)
	 *
	 * @param int $page The page number
	 *
	 * @return string The Upcoming Movies List html
	 */
	public function indexResponseHandler($page=1) {
		$page = ($page < 1) ? 1 : $page;
		$upcoming = $this->api->getUpcoming($page);

		return $this->twig->render('index.twig', [
			'page_title' => 'Upcoming Movies',
			'cur_page' => $page,
			'total_pages' => $upcoming['total_pages'],
			'movies'  =>  $upcoming['results'],
			'base_url'  =>  $this->api->getImagesBaseUrl()
		]);
	}

	/**
	 * Handle the search results page. Gets the search query from HTTP Post param 'query'.
	 *
	 * @return string The search results html if query param where given. Otherwise returns the index page.
	 */
	public function searchResponseHandler() {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$query = $_POST['query'];
			$search = $this->api->searchMovie($query);

			return $this->twig->render('index.twig', [
				'page_title' => 'Search results for: '.$query,
				'movies'  =>  $search['results'],
				'base_url'  =>  $this->api->getImagesBaseUrl()
			]);
		} else {
			return $this->indexResponseHandler(1);
		}
	}

	/**
	 * Handles the movie details page.
	 *
	 * @param $movieId integer The movie TMDb id
	 *
	 * @return string The movie details html page.
	 */
	public function detailsResponseHandler($movieId) {
		$movie = $this->api->getMovie($movieId);

		return $this->twig->render('movie-details.twig', [
			'movie'  =>  $movie,
			'base_url'  =>  $this->api->getImagesBaseUrl()
		]);
	}

	/**
	 * Default handler when no route matched the query string.
	 *
	 * @return string The rendered html page.
	 */
	public function defaultResponseHandler() {
		return $this->twig->render('index.twig', [
			'page_title' => 'Page not found',
		]);
	}


	/**
	 * Execute the application. Prints its outputs to the browser.
	 */
	public function run() {
		$query = $_SERVER['QUERY_STRING'];
		echo $this->router->doRoute($query);
	}
}