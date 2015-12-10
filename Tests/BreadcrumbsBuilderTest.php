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

use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Yceruto\Bundle\BreadcrumbsBundle\BreadcrumbsBuilder;

class BreadcrumbsBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getCreateData
     */
    public function testCreate($path, $result)
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('route_index', new Route('/'));
        $routeCollection->add('route_about', new Route('/about'));
        $routeCollection->add('route_buy_index', new Route('/buy/'));
        $routeCollection->add('route_buy_cellphones', new Route('/buy/cell-phones'));
        $routeCollection->add('route_buy_apple', new Route('/buy/cell-phones/apple'));

        $route = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Routing\Router')->disableOriginalConstructor()->getMock();
        $route->method('getRouteCollection')->willReturn($routeCollection);
        $route->method('getContext')->willReturn(new RequestContext());

        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->disableOriginalConstructor()->getMock();
        $request->method('getPathInfo')->willReturn($path);

        $requestStack = $this->getMockBuilder('Symfony\Component\HttpFoundation\RequestStack')->disableOriginalConstructor()->getMock();
        $requestStack->method('getCurrentRequest')->willReturn($request);

        $breadcrumbsBuilder = new BreadcrumbsBuilder($requestStack, $route);
        $breadcrumbs = $breadcrumbsBuilder->create();

        $this->assertCount(count($result['nodes']), $breadcrumbs->getNodes());
        $nodes = $breadcrumbs->getNodes();
        $node = current($nodes);
        foreach ($result['nodes'] as $name => $path) {
            $this->assertEquals($name, $node->getName());
            $this->assertEquals($path, $node->getPath());
            $node = next($nodes);
        }
    }

    public function getCreateData()
    {
        return array(
            'home' => array(
                '/', array('nodes' => array('route_index' => '/'))
            ),
            'about' => array(
                '/about', array(
                    'nodes' => array(
                        'route_index' => '/',
                        'route_about' => '/about',
                    )
                )
            ),
            'sell' => array(
                '/sell/', array(
                    'nodes' => array(
                        'route_index' => '/',
                        'route_buy_index' => '/sell/',
                    )
                )
            ),
            'filter_1' => array(
                '/sell/cell-phones', array(
                    'nodes' => array(
                        'route_index' => '/',
                        'route_buy_index' => '/sell/',
                        'route_buy_cellphones' => '/sell/cell-phones',
                    )
                )
            ),
            'filter_2' => array(
                '/sell/cell-phones/apple', array(
                    'nodes' => array(
                        'route_index' => '/',
                        'route_buy_index' => '/sell/',
                        'route_buy_cellphones' => '/sell/cell-phones',
                        'route_buy_apple' => '/sell/cell-phones/apple',
                    )
                )
            ),
        );
    }
}
