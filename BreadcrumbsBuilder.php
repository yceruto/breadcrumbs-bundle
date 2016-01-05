<?php

/*
 * This file is part of the BreadcrumbsBundle.
 *
 * (c) Yonel Ceruto <yonelceruto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yceruto\Bundle\BreadcrumbsBundle;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

/**
 * This builder works in 2 modes:
 *
 *  * 2.3 compatibility mode where you must call setRequest whenever the Request changes.
 *  * 2.4+ mode where you must pass a RequestStack instance in the constructor.
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class BreadcrumbsBuilder
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router, RequestStack $requestStack = null)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    /**
     * BC with SF 2.3
     *
     * @param Request|null $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Create a empty breadcrumb
     *
     * @return Breadcrumbs
     */
    public function create()
    {
        return new Breadcrumbs();
    }

    /**
     * Create a breadcrumb through current request path
     *
     * @return Breadcrumbs
     */
    public function createFromRequest()
    {
        $breadcrumbs = new Breadcrumbs();

        $paths = $this->getBreadcrumbsPaths();
        foreach ($paths as $path) {
            if ($node = $this->createBreadcrumbsNode($path)) {
                $breadcrumbs->addNode($node);
            }
        }

        return $breadcrumbs;
    }

    /**
     * Get all breadcrumbs paths from current request path
     *
     * @return array of string
     */
    private function getBreadcrumbsPaths()
    {
        $parts = array();
        $pathInfo = trim($this->getRequest()->getPathInfo(), '/');

        if ($pathInfo) {
            $parts = explode('/', $pathInfo);
        }

        array_unshift($parts, '/');

        $path = '';
        $paths = array();
        foreach ($parts as $part) {
            $path .= $part;
            $paths[] = $path;

            if (strlen($part) > 1) {
                $path .= '/';
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * Create a breadcrumbs node from path
     *
     * @param string $path
     *
     * @return BreadcrumbsNode|bool
     */
    private function createBreadcrumbsNode($path)
    {
        // use $baseUrl for no prod environments e.g dev 'app_dev.php'
        $baseUrl = $this->getRequest()->getBaseUrl();

        $matcher = new TraceableUrlMatcher($this->router->getRouteCollection(), $this->router->getContext());
        $traces = $matcher->getTraces($path);

        foreach ($traces as $trace) {
            if (TraceableUrlMatcher::ROUTE_MATCHES == $trace['level']) {
                $route = $this->router->getRouteCollection()->get($trace['name']);

                // get label through settings
                $label = $route->getDefault('breadcrumbs_label');

                if (empty($label)) {
                    // get label through path
                    $compiledRoute = $route->compile();
                    $vars = $compiledRoute->getVariables();
                    if (preg_match($compiledRoute->getRegex(), $path, $match)) {
                        $label = trim($match[end($vars)], '/');
                    }
                }

                if (empty($label)) {
                    // get label through route name
                    $label = 'breadcrumbs.'.$trace['name'];
                }

                $node = new BreadcrumbsNode();
                $node->setLabel($label);
                $node->setPath($baseUrl.$path);

                return $node;
            }
        }

        return false;
    }

    private function getRequest()
    {
        return $this->requestStack ? $this->requestStack->getCurrentRequest() : $this->request;
    }
}
