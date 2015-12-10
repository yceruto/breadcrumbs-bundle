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

use Symfony\Component\HttpFoundation\Request;
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

        $requestStack = $this->getMockBuilder('Symfony\Component\HttpFoundation\RequestStack')->disableOriginalConstructor()->getMock();
        $requestStack->method('getCurrentRequest')->willReturn(Request::create($path));

        $breadcrumbsBuilder = new BreadcrumbsBuilder($route, $requestStack);
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
            'index' => array(
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
            'buy' => array(
                '/buy/', array(
                    'nodes' => array(
                        'route_index' => '/',
                        'route_buy_index' => '/buy/',
                    )
                )
            ),
            'buy_cellphones' => array(
                '/buy/cell-phones', array(
                    'nodes' => array(
                        'route_index' => '/',
                        'route_buy_index' => '/buy/',
                        'route_buy_cellphones' => '/buy/cell-phones',
                    )
                )
            ),
            'buy_apple' => array(
                '/buy/cell-phones/apple', array(
                    'nodes' => array(
                        'route_index' => '/',
                        'route_buy_index' => '/buy/',
                        'route_buy_cellphones' => '/buy/cell-phones',
                        'route_buy_apple' => '/buy/cell-phones/apple',
                    )
                )
            ),
        );
    }
}
