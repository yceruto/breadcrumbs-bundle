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
     * @param BreadcrumbsNode $node
     *
     * @return Breadcrumbs
     */
    public function addNode(BreadcrumbsNode $node)
    {
        if (!$this->containsNode($node)) {
            $this->nodes[] = $node;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($key)
    {
        if (!isset($this->nodes[$key]) && !array_key_exists($key, $this->nodes)) {
            return null;
        }

        $removed = $this->nodes[$key];
        unset($this->nodes[$key]);

        return $removed;
    }

    /**
     * @param BreadcrumbsNode $node
     *
     * @return bool
     */
    public function removeNode(BreadcrumbsNode $node)
    {
        $key = array_search($node, $this->nodes, true);

        if ($key === false) {
            return false;
        }

        unset($this->nodes[$key]);

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
    public function offsetExists($key)
    {
        return isset($this->nodes[$key]) || array_key_exists($key, $this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($key)
    {
        return isset($this->nodes[$key]) ? $this->nodes[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($key, $value)
    {
        if (!isset($key)) {
            $this->addNode($value);
        }

        $this->nodes[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }
}
