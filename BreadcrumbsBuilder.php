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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;

class BreadcrumbsBuilder
{

    /**
     * @var TraceableUrlMatcher
     */
    private $matcher;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack, Router $router)
    {
        $this->requestStack = $requestStack;
        $this->matcher = new TraceableUrlMatcher($router->getRouteCollection(), $router->getContext());
    }

    /**
     * Create a Breadcrumb from request path
     *
     * @return Breadcrumbs
     */
    public function create()
    {
        $breadcrumbs = new Breadcrumbs();

        $paths = $this->getPaths();
        foreach ($paths as $path) {
            if ($node = $this->createNode($path)) {
                $breadcrumbs->addNode($node);
            }
        }

        return $breadcrumbs;
    }

    /**
     * @return array of string
     */
    private function getPaths()
    {
        $pathInfo = trim($this->requestStack->getCurrentRequest()->getPathInfo(), '/');
        $parts = $pathInfo ? explode('/', $pathInfo) : array();
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
     * @param string $path
     *
     * @return boolean|BreadcrumbsNode
     */
    private function createNode($path)
    {
        $baseUrl = $this->requestStack->getCurrentRequest()->getBaseUrl();
        $traces = $this->matcher->getTraces($path);

        foreach ($traces as $trace) {
            if (TraceableUrlMatcher::ROUTE_MATCHES == $trace['level']) {
                $node = new BreadcrumbsNode();
                $node->setName($trace['name']);
                $node->setPath($baseUrl . $trace['path']);

                return $node;
            }
        }

        return false;
    }
}
