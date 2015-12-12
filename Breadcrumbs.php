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
     * Add a node.
     *
     * @param string $path
     * @param string $label
     *
     * @return BreadcrumbsNode
     */
    public function add($path, $label)
    {
        $node = new BreadcrumbsNode($path, $label);
        $this->addNode($node);

        return $node;
    }

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
     * {@inheritdoc}
     */
    public function remove($index)
    {
        if (!isset($this->nodes[$index]) && !array_key_exists($index, $this->nodes)) {
            return;
        }

        $removed = $this->nodes[$index];
        unset($this->nodes[$index]);

        return $removed;
    }

    /**
     * @param BreadcrumbsNode $node
     *
     * @return bool
     */
    public function removeNode(BreadcrumbsNode $node)
    {
        $index = array_search($node, $this->nodes, true);

        if ($index === false) {
            return false;
        }

        unset($this->nodes[$index]);

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
    public function offsetExists($index)
    {
        return isset($this->nodes[$index]) || array_key_exists($index, $this->nodes);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($index)
    {
        return isset($this->nodes[$index]) ? $this->nodes[$index] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($index, $value)
    {
        if (isset($index)) {
            $this->nodes[$index] = $value;
        } else {
            $this->addNode($value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }
}
