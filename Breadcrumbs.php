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

class Breadcrumbs implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var BreadcrumbsNode[]
     */
    private $nodes = array();

    /**
     * Add a node
     *
     * @param string $name
     * @param string $path
     *
     * @return Breadcrumbs
     */
    public function add($name, $path)
    {
        return $this->addNode(new BreadcrumbsNode($name, $path));
    }

    /**
     * @param BreadcrumbsNode $node
     *
     * @return Breadcrumbs
     */
    public function addNode(BreadcrumbsNode $node)
    {
        if (!$this->containsNode($node)) {
            $this->nodes[$node->getName()] = $node;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($name)
    {
        if (!isset($this->nodes[$name]) && !array_key_exists($name, $this->nodes)) {
            return null;
        }

        $removed = $this->nodes[$name];
        unset($this->nodes[$name]);

        return $removed;
    }

    /**
     * @param BreadcrumbsNode $node
     *
     * @return bool
     */
    public function removeNode(BreadcrumbsNode $node)
    {
        $name = array_search($node, $this->nodes, true);

        if ($name === false) {
            return false;
        }

        unset($this->nodes[$name]);

        return true;
    }

    /**
     * @param BreadcrumbsNode $node
     *
     * @return bool
     */
    public function containsNode(BreadcrumbsNode $node)
    {
        return in_array($node, $this->nodes, true);
    }

    /**
     * @return BreadcrumbsNode[]
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->nodes);
    }

    /**
     * @return BreadcrumbsNode
     */
    public function first()
    {
        return reset($this->nodes);
    }

    /**
     * @return BreadcrumbsNode
     */
    public function last()
    {
        return end($this->nodes);
    }

    /**
     * @return BreadcrumbsNode
     */
    public function current()
    {
        return current($this->nodes);
    }

    /**
     * @return BreadcrumbsNode
     */
    public function next()
    {
        return next($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($name)
    {
        return isset($this->nodes[$name]) || array_key_exists($name, $this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($name)
    {
        return isset($this->nodes[$name]) ? $this->nodes[$name] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($name, $value)
    {
        if (!isset($name)) {
            $this->addNode($value);
        }

        $this->nodes[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }
}
