# ArcTouch CodeChallenge Project

## Development
This project was developed using: PhpStorm as IDE and Composer for dependency management. It was developed on a Windows 10 Home Machine using XAMPP/PHP 5.6. It was tested running docker on an Ubuntu VM. (I had some issues running Docker on my Windows machine).

### Time spent
The development of this simple prototype took around ~6hrs of code development plus ~2hrs for documentation and deployment.

### Improvements
The project was implemented having a timed box constraint in mind. It's meant to be functional but still lacks many features. Here's a list of some TODOs:

* Invalid requests: handle invalid requests TMDb API, as well to Web Service and Web App
* Error handling: catch exceptions from php-tmdb api
* Improve Router/Routes using interfaces for route handling
* Check and specify class responsibilities
* Provide a better structure for route handling case number of routes grows (move to separate files/folders)

# Architecture
The proposed architecture is a simple Web Service API integrated with a Web Application. The key idea is to share the models and requests between the Website and the Web Service API. It allows server side rendering as well client side, by AJAX calls to the Web Service. The data consumed by the Application Website is the same that could be consumed by, for example, mobile applications.

## Components
* Router: A very simple router which handles responses based on its input query.
* API: A WebService API which load and handles the data from the TMDb API.
* Web App: Uses the router and the API internally to render the web pages.

The available Web Service API requests are:
* `/api/v1/movie/:movieId` - Returns the data for the given movieId
* `/api/v1/upcoming/:page` - Returns the list of upcoming movies for the `page` number.
* `/api/v1/search/:string` - Returns the movie search by title results for the given `string`

For now, the WebApp isn`t making use of the Web Service API on client side (i.e. no ajax calls were implemented).

# Build instructions
Clone this repository and run `composer install` to download the dependencies.

## Third-party libraries
The following Third-party libraries were used in this project:
* **Bootstrap v4.0**: The Bootstrap were used to create the user interface using the "Album" example. It allowed a fast prototyping while offering a reasonable UI and UX.
* **twig/twg v1.35.0**: The Twig were used as template engine. Separating PHP code from Template allows an abstraction of code structures/processing, as well enhances template maitainability. readability and collaboration.
* **php-tmdb/api v2.1.11**: The TMDb has a list of user contributed libraries. The php-tmdb-api by Michael Roterman were chosen by its popularity among the other libraries, as well by the feature set. Considering that it`s being developed an MVP, at first there's no need to reinvent the wheel. Also it offers out-of-the-box caching capabilities.
