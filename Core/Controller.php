<?php

namespace Core;

use Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser as NewFileinfoMimeTypeGuesser;

/**
 * Class Controller.
 *
 * @package Core
 */
abstract class Controller
{
    /**
     * Parameters from the matched route.
     *
     * @var array
     */
    protected $route_params = [];

    /**
     * The session.
     *
     * @var Session
     */
    protected $session;

    /**
     * The HTTP request.
     *
     * @var Request
     */
    protected $http_request;

    /**
     * Class constructor.
     *
     * @param array $route_params Parameters from the route.
     * @param Session $session Session.
     * @param Request $http_request Http request.
     */
    public function __construct(array $route_params, Session $session, Request $http_request)
    {
        $this->route_params = $route_params;
        $this->session      = $session;
        $this->http_request = $http_request;
    }

    /**
     * Magic method.
     *
     * Called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param string $name Method name.
     * @param array $args Arguments passed to the method.
     *
     * @return void
     * @throws Exception
     */
    public function __call(string $name, array $args): void
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                /** @var Response $response */
                $response = call_user_func_array([$this, $method], $args);
                $response->send();
                $this->after();
            }
        } else {
            throw new Exception("Method $method not found in controller " . get_class($this));
        }
    }

    /**
     * Before filter.
     *
     * Called before an action method.
     */
    protected function before()
    {
    }

    /**
     * After filter.
     *
     * Called after an action method.
     */
    protected function after()
    {
    }

    /**
     * Gets the absolute url.
     *
     * @param string $rel_url The relative url.
     *
     * @return string
     */
    protected function getAbsUrl(string $rel_url = ''): string
    {
        return $this->http_request->getSchemeAndHttpHost() . $rel_url;
    }

    /**
     * Gets the route parameter by name.
     *
     * @param string $name The name of parameter.
     * @param mixed $default (optional) The default value.
     *
     * @return mixed
     */
    protected function getRouteParam(string $name, $default = '')
    {
        return $this->route_params[$name] ?? $default;
    }

    /**
     * Renders the template.
     *
     * @param string $view
     * @param array $parameters
     * @param Response|null $response
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $content = View::getTemplate($view, $parameters, $this->http_request);

        if (null === $response) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_OK);
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * Redirect to a different page.
     *
     * @param string $url The relative URL.
     * @param array $headers (optional) Headers.
     *
     * @return JsonResponse|RedirectResponse
     */
    protected function redirect(string $url, array $headers = [])
    {
        if ($this->http_request->isXmlHttpRequest()) {
            return $this->sendJsonResponse(['redirect' => ['url' => $url]], 200, $headers);
        } else {
            return new RedirectResponse($url, 302, $headers);
        }
    }

    /**
     * Forbids not ajax request.
     *
     * @return void
     * @throws Exception
     */
    protected function forbidNotXmlHttpRequest(): void
    {
        if (! $this->http_request->isXmlHttpRequest()) {
            throw new Exception('No route matched.', 404);
        }
    }

    /**
     * Sends json response.
     *
     * @param mixed $data (optional) The data.
     * @param int $status (optional) The response status code
     * @param array $headers (optional) An array of response headers
     * @param bool $json (optional) If the data is already a JSON string
     *
     * @return JsonResponse
     */
    protected function sendJsonResponse(
        $data = null,
        int $status = 200,
        array $headers = [],
        bool $json = false
    ): JsonResponse {
        return new JsonResponse($data, $status, $headers, $json);
    }

    /**
     * Sends binary file response.
     *
     * @param \SplFileInfo|string $file The file to stream.
     * @param int $status The response status code.
     * @param array $headers An array of response headers
     * @param bool $public Files are public by default
     * @param string|null $content_disposition The type of Content-Disposition to set automatically with the filename
     * @param bool $auto_etag Whether the ETag header should be automatically set
     * @param bool $auto_last_modified Whether the Last-Modified header should be automatically set
     *
     * @return BinaryFileResponse
     */
    protected function sendBinaryFileResponse(
        $file,
        int $status = 200,
        array $headers = [],
        bool $public = true,
        string $content_disposition = null,
        bool $auto_etag = false,
        bool $auto_last_modified = true
    ): BinaryFileResponse {
        $response = new BinaryFileResponse(
            $file,
            $status,
            $headers,
            $public,
            $content_disposition,
            $auto_etag,
            $auto_last_modified
        );

        $mime_type_guesser = new NewFileinfoMimeTypeGuesser();

        $response->headers->set('Content-Type', $mime_type_guesser->guessMimeType($file));

        return $response;
    }
}
