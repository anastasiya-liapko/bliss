<?php

namespace Core;

use App\Config;
use App\SiteInfo;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class View.
 *
 * @package Core
 */
class View
{
    /**
     * Gets the content of a view template using Twig.
     *
     * @param string $template The template file.
     * @param array $args (optional) Associative array of data to display in the view.
     * @param Request|null $http_request = null (optional) The Http request.
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public static function getTemplate(string $template, array $args = [], $http_request = null): string
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig   = new Environment($loader);

            $url_function = new \Twig\TwigFunction('abs_url', function (string $rel_url = '') use ($http_request) {
                return ($http_request instanceof Request) ? $http_request->getSchemeAndHttpHost() . $rel_url :
                    SiteInfo::getSchemeAndHttpHost() . $rel_url;
            });

            $twig->addFunction($url_function);

            $twig->addGlobal('site_name', SiteInfo::NAME);
            $twig->addGlobal('is_dev_server', Config::isDevServer());
        }

        return $twig->render($template, $args);
    }

    /**
     * Renders a view template using Twig.
     *
     * @param string $template The template file.
     * @param array $args (optional) Associative array of data to display in the view.
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public static function renderTemplate(string $template, array $args = []): void
    {
        echo static::getTemplate($template, $args);
    }
}
