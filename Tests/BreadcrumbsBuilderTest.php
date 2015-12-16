<?php

/*
 * This file is part of the BreadcrumbsBundle.
 *
 * (c) Yonel Ceruto <yonelceruto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yceruto\Bundle\BreadcrumbsBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Yceruto\Bundle\BreadcrumbsBundle\BreadcrumbsBuilder;

class BreadcrumbsBuilderTest extends TestCase
{
    public function testCreate()
    {
        $breadcrumbs = $this->createBreadcrumbsBuilder('/', new RouteCollection())
            ->create();

        $this->assertInstanceOf('Yceruto\Bundle\BreadcrumbsBundle\Breadcrumbs', $breadcrumbs);
        $this->assertEquals(0, $breadcrumbs->count());
    }

    /**
     * @dataProvider getCreateFromRequestData
     */
    public function testCreateFromRequest($path, $result)
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('_index', new Route('/'));
        $routeCollection->add('_foo', new Route('/foo', array('breadcrumbs_label' => 'Foo')));
        $routeCollection->add('_bar', new Route('/bar/'));
        $routeCollection->add('_bar_show', new Route('/bar/{id}'));
        $routeCollection->add('_bar_action', new Route('/bar/{id}/{action}'));

        $breadcrumbs = $this->createBreadcrumbsBuilder($path, $routeCollection)
            ->createFromRequest();

        $this->assertCount(count($result['nodes']), $breadcrumbs->getNodes());
        $nodes = $breadcrumbs->getNodes();
        $node = current($nodes);
        foreach ($result['nodes'] as $path => $label) {
            $this->assertEquals($path, $node->getPath());
            $this->assertEquals($label, $node->getLabel());
            $node = next($nodes);
        }
    }

    public function getCreateFromRequestData()
    {
        return array(
            'index' => array(
                '/',
                array('nodes' => array('/' => 'breadcrumbs._index')),
            ),
            'foo' => array(
                '/foo',
                array(
                    'nodes' => array(
                        '/' => 'breadcrumbs._index',
                        '/foo' => 'Foo',
                    ),
                ),
            ),
            'bar' => array(
                '/bar/',
                array(
                    'nodes' => array(
                        '/' => 'breadcrumbs._index',
                        '/bar/' => 'bar',
                    ),
                ),
            ),
            'bar_show' => array(
                '/bar/baz',
                array(
                    'nodes' => array(
                        '/' => 'breadcrumbs._index',
                        '/bar/' => 'bar',
                        '/bar/baz' => 'baz',
                    ),
                ),
            ),
            'bar_action' => array(
                '/bar/baz/edit',
                array(
                    'nodes' => array(
                        '/' => 'breadcrumbs._index',
                        '/bar/' => 'bar',
                        '/bar/baz' => 'baz',
                        '/bar/baz/edit' => 'edit',
                    ),
                ),
            ),
        );
    }

    /**
     * Create a Breadcrumbs Builder.
     *
     * @param string          $path
     * @param RouteCollection $collection
     *
     * @return BreadcrumbsBuilder
     */
    private function createBreadcrumbsBuilder($path, RouteCollection $collection)
    {
        $route = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Routing\Router')
            ->disableOriginalConstructor()
            ->getMock();
        $route->method('getRouteCollection')->willReturn($collection);
        $route->method('getContext')->willReturn(new RequestContext());

        $request = Request::create($path);

        if (class_exists('Symfony\Component\HttpFoundation\RequestStack')) {
            $requestStack = $this->getMockBuilder('Symfony\Component\HttpFoundation\RequestStack')
                ->disableOriginalConstructor()
                ->getMock();
            $requestStack->method('getCurrentRequest')->willReturn($request);
        } else {
            $requestStack = null;
        }

        $breadcrumbsBuilder = new BreadcrumbsBuilder($route, $requestStack);

        // BC with SF 2.3
        if (null === $requestStack) {
            $breadcrumbsBuilder->setRequest($request);
        }

        return $breadcrumbsBuilder;
    }
}
