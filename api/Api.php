<?php
/**
 * This file is part of the ArcTouch CodeChallenge requested during the Interview Process.
 *
 * @package CodeChallenge
 * @author Matheus Muller Borges
 * @version 0.0.1
 */
namespace CodeChallenge;

require_once dirname(__DIR__).'/bootstrap.php';


/**
 * Class Api
 * @package CodeChallenge
 */
class Api {
	/** @var  Router */
	private $router;

	/**
	 * @var \Tmdb\Client
	 */
	private $client;

	/**
	 * Holds the TMDb images base url from the TMDB Configuration API.
	 * @var string
	 */
	private $imagesBaseUrl;


	public function __construct() {
		$token = new \Tmdb\ApiToken('1f54bd990f1cdfb230adb312546d765d');
		$this->client = new \Tmdb\Client($token, [ 'secure' => false ]);


		$this->loadImagesBaseUrl();
		$this->buildRoutes();
	}

	/**
	 * Build the routes for the API
	 */
	private function buildRoutes() {
		$this->router = new Router(function() {
			return ['status_message' => 'The resource you requested could not be found'];
		});

		$this->router->addRoute('/v1/movie/(.*)',  [$this, 'getMovie']);
		$this->router->addRoute('/v1/upcoming/(.*)',  [$this, 'getUpcoming']);

		$this->router->addRoute('/v1/search/:query', [$this, 'searchMovie']);
	}

	/**
	 * Returns the TMBb movie data for the given movie id
	 * @param int $id TMDb movie id
	 *
	 * @return array The movie data
	 */
	public function getMovie($id) {
		try {
			$movie = $this->client->getMoviesApi()->getMovie($id);
			return $movie;
		} catch(\Exception $e) {
			return ['status' => $e->getMessage()];
		}
	}

	/**
	 * Get TMDb upcoming movies list
	 * @param int $page     The page number to retrieve
	 *
	 * @return mixed TBMDb
	 */
	public function getUpcoming($page=1) {
		$genreData = $this->client->getGenresApi()->getMovieGenres();
		$data['genres'] = $genreData['genres'];

		$genresCollection =$this->createGenreCollection($genreData['genres']);

		$data = $this->client->getMoviesApi()->getUpcoming(['page' => $page]);


		foreach($data['results'] as &$result) {
			foreach($result['genre_ids'] as $genre_id) {
				$genre = $genresCollection->getGenre($genre_id);
				$result['genres'][] = $genre->getName();
			}
		}

		return $data;
	}

	/**
	 * Search for movies with the given query.
	 * @param $query
	 *
	 * @return mixed    TMDb movie search results.
	 */
	public function searchMovie($query) {
		return $this->client->getSearchApi()->searchMovies($query);
	}

	/**
	 * Process the API request
	 * @param $req
	 *
	 * @return string The API response for the request
	 */
	public function processRequest($req) {
		return $this->router->doRoute($req);
	}

	/* TODO: The following methods shall be moved to an specific class that manipulates and holds data from TMDb API */

	/**
	 * Loads the Image Base URL from TMDb API
	 */
	private function loadImagesBaseUrl() {
		$configs = $this->client->getConfigurationApi()->getConfiguration();
		$this->imagesBaseUrl = $configs['images']['base_url'];
	}

	/**
	 * @return string TBDb images base url.
	 */
	public function getImagesBaseUrl() {
		return $this->imagesBaseUrl;
	}

	/**
	 * Creates a Genre Collection with the given genres list data.
	 * @param array $genres_data Data from TMDb genres list
	 *
	 * @return \Tmdb\Model\Collection\Genres
	 */
	private function createGenreCollection($genres_data) {
		$genresCollection = new \Tmdb\Model\Collection\Genres();

		foreach($genres_data as $genre) {
			$newGenre = new \Tmdb\Model\Genre();
			$newGenre->setId($genre['id']);
			$newGenre->setName($genre['name']);
			$genresCollection->addGenre( $newGenre );
		}

		return $genresCollection;
	}
}


