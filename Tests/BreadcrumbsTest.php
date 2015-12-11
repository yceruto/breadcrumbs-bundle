<?php


namespace Yceruto\Bundle\BreadcrumbsBundle\Tests;

use Yceruto\Bundle\BreadcrumbsBundle\Breadcrumbs;
use Yceruto\Bundle\BreadcrumbsBundle\BreadcrumbsNode;

class BreadcrumbsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    public function setUp()
    {
        $this->breadcrumbs = new Breadcrumbs();
    }

    public function testAdd()
    {
        $node = $this->breadcrumbs->add('/', 'index');

        $this->assertInstanceOf('Yceruto\Bundle\BreadcrumbsBundle\BreadcrumbsNode', $node);
        $this->assertEquals('/', $node->getPath());
        $this->assertEquals('index', $node->getLabel());
    }

    public function testAddNode()
    {
        $node = new BreadcrumbsNode();
        $node->setPath('/');
        $node->setLabel('index');

        $this->breadcrumbs->addNode($node);
        $nodes = $this->breadcrumbs->getNodes();

        $this->assertCount(1, $nodes);
        $this->assertEquals($node, $nodes[0]);
    }

    public function testRemove()
    {
        $node = new BreadcrumbsNode();
        $node->setPath('/');
        $node->setLabel('index');

        $this->breadcrumbs->addNode($node);

        $this->assertCount(1, $this->breadcrumbs->getNodes());
        $this->breadcrumbs->remove(0);
        $this->assertCount(0, $this->breadcrumbs->getNodes());
    }

    public function testRemoveNode()
    {
        $node = $this->breadcrumbs->add('/', 'index');

        $this->assertCount(1, $this->breadcrumbs->getNodes());
        $this->breadcrumbs->removeNode($node);
        $this->assertCount(0, $this->breadcrumbs->getNodes());
    }

    public function testContainNode()
    {
        $node = $this->breadcrumbs->add('/', 'index');
        $this->assertTrue($this->breadcrumbs->containsNode($node));
    }

    public function testCountable()
    {
        $this->breadcrumbs->add('/', 'index');
        $this->assertEquals(1, $this->breadcrumbs->count());
    }

    public function testIteratorAggregate()
    {
        $this->breadcrumbs->add('/', 'index');
        $this->assertInstanceOf('\ArrayIterator', $this->breadcrumbs->getIterator());
    }

    public function testArrayAccess()
    {
        $node1 = new BreadcrumbsNode();
        $node1->setPath('/');
        $node1->setLabel('index');
        $node2 = new BreadcrumbsNode();
        $node2->setPath('/foo');
        $node2->setLabel('foo');

        $this->breadcrumbs[] = $node1;
        $this->breadcrumbs[23] = $node2;

        $this->assertCount(2, $this->breadcrumbs);
        $this->assertTrue(isset($this->breadcrumbs[0]));
        $this->assertEquals($node1, $this->breadcrumbs[0]);
        $this->assertEquals($node2, $this->breadcrumbs[23]);
        $this->assertNull($this->breadcrumbs[1]);

        unset($this->breadcrumbs[0]);
        $this->assertCount(1, $this->breadcrumbs);
    }

    public function testPositionMethods()
    {
        $node1 = $this->breadcrumbs->add('/', 'index');
        $node2 = $this->breadcrumbs->add('/foo', 'foo');

        $this->assertEquals($node1, $this->breadcrumbs->current());
        $this->assertEquals($node2, $this->breadcrumbs->next());
        $this->assertEquals($node1, $this->breadcrumbs->first());
        $this->assertEquals($node2, $this->breadcrumbs->last());
    }
}
