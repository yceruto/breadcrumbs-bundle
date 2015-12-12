<?php

namespace Yceruto\Bundle\BreadcrumbsBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Yceruto\Bundle\BreadcrumbsBundle\BreadcrumbsNode;

class BreadcrumbsNodeTest extends TestCase
{
    public function testCreateAndGetter()
    {
        $node = new BreadcrumbsNode('/', 'index');
        $this->assertEquals('/', $node->getPath());
        $this->assertEquals('index', $node->getLabel());
    }

    public function testSetter()
    {
        $node = new BreadcrumbsNode();
        $node->setPath('/');
        $node->setLabel('index');

        $this->assertEquals('/', $node->getPath());
        $this->assertEquals('index', $node->getLabel());
    }

    public function testToString()
    {
        $node = new BreadcrumbsNode('/', 'index');
        $this->assertEquals('index', (string) $node);
    }
}
