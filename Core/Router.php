<?php

namespace Core;

use Exception;

/**
 * Class Router.
 *
 * @package Core
 */
class Router
{
    /**
     * Associative array of routes (the routing table).
     *
     * @var array
     */
    protected $routes = [];

    /**
     * Parameters from the matched route.
     *
     * @var array
     */
    protected $params = [];

    /**
     * Adds a route to the routing table.
     *
     * @param string $route The route URL.
     * @param array $params Parameters (controller, action, etc.).
     *
     * @return void
     */
    public function add(string $route, array $params = []): void
    {
        // Convert the route to a regular expression: escape forward slashes.
        $route = preg_replace('/\//', '\\/', $route);

        // Convert variables e.g. {controller}.
        $route = preg_replace('/{([a-z]+)}/', '(?P<\1>[a-z-]+)', $route);

        // Convert variables with custom regular expressions e.g. {id:\d+}.
        $route = preg_replace('/{([a-z]+):([^}]+)}/', '(?P<\1>\2)', $route);

        // Add start and end delimiters, and case insensitive flag.
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * Gets all the routes from the routing table.
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Matches the route to the routes in the routing table, setting the $params
     * property if a route is found.
     *
     * @param string $url The route URL.
     *
     * @return bool True if a match found, false otherwise.
     */
    public function match(string $url): bool
    {
        foreach ($this->getRoutes() as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                // Get named capture group values.
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;

                return true;
            }
        }

        return false;
    }

    /**
     * Gets the currently matched parameters.
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Dispatch the route, creating the controller object and running the
     * action method.
     *
     * @param string $url The route URL.
     *
     * @return void
     *
     * @throws Exception
     */
    public function dispatch(string $url): void
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {
                global $session, $http_request;

                $controller_object = new $controller($this->getParams(), $session, $http_request);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if (preg_match('/action$/i', $action) == 0) {
                    $controller_object->$action();
                } else {
                    throw new Exception("Method $action in controller $controller cannot be called directly");
                }
            } else {
                throw new Exception('No route matched.', 404);
            }
        } else {
            throw new Exception('No route matched.', 404);
        }
    }

    /**
     * Converts the string.
     *
     * With hyphens to StudlyCaps, e.g. post-authors => PostAuthors.
     *
     * @param string $string The string to convert.
     *
     * @return string
     */
    protected function convertToStudlyCaps(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Converts the string.
     *
     * With hyphens to camelCase, e.g. add-new => addNew.
     *
     * @param string $string The string to convert.
     *
     * @return string
     */
    protected function convertToCamelCase(string $string): string
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Removes the query string variables from the URL (if any).
     *
     * As the full query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table. For example:
     *
     *   URL                           $_SERVER['QUERY_STRING']  Route
     *   -------------------------------------------------------------------
     *   localhost                     ''                        ''
     *   localhost/?                   ''                        ''
     *   localhost/?page=1             page=1                    ''
     *   localhost/posts?page=1        posts&page=1              posts
     *   localhost/posts/index         posts/index               posts/index
     *   localhost/posts/index?page=1  posts/index&page=1        posts/index
     *
     * A URL of the format localhost/?page (one variable name, no value) won't
     * work however. (NB. The .htaccess file converts the first ? to a & when
     * it's passed through to the $_SERVER variable).
     *
     * @param string $url The full URL.
     *
     * @return string The URL with the query string variables removed.
     */
    protected function removeQueryStringVariables(string $url): string
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }

    /**
     * Gets the namespace for the controller class. The namespace defined in the
     * route parameters is added if present.
     *
     * @return string The request URL.
     */
    protected function getNamespace(): string
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->getParams())) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }
}
