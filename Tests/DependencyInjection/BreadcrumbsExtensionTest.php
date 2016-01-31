<?php

/*
 * This file is part of the BreadcrumbsBundle.
 *
 * (c) Yonel Ceruto <yonelceruto@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yceruto\Bundle\BreadcrumbsBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yceruto\Bundle\BreadcrumbsBundle\DependencyInjection\BreadcrumbsExtension;

class BreadcrumbsExtensionTest extends TestCase
{
    public function testLoadService()
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', false);
        $extension = new BreadcrumbsExtension();
        $extension->load(array(), $container);
        $this->assertTrue($container->has('breadcrumbs_builder'));
    }
}
