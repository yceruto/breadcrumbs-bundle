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

class BreadcrumbsNode
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $label;

    /**
     * BreadcrumbsNode constructor.
     *
     * @param string $path
     * @param string $label
     */
    public function __construct($path = null, $label = null)
    {
        $this->path = $path;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return BreadcrumbsNode
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return BreadcrumbsNode
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function __toString()
    {
    	return $this->label;
    }

}
