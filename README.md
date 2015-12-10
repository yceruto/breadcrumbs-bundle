Breadcrumbs
===========

[![Build Status](https://travis-ci.org/yceruto/breadcrumbs-bundle.svg?branch=master)](https://travis-ci.org/yceruto/breadcrumbs-bundle)
[![Coverage Status](https://coveralls.io/repos/yceruto/breadcrumbs-bundle/badge.svg?branch=master&service=github)](https://coveralls.io/github/yceruto/breadcrumbs-bundle?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d5df66f3-377d-4f39-9875-bbda6e3d235d/mini.png)](https://insight.sensiolabs.com/projects/d5df66f3-377d-4f39-9875-bbda6e3d235d)
<sup><kbd>**SUPPORTS SYMFONY 2.x and 3.x**</kbd></sup>

A magic way to create breadcrumbs for symfony applications.

**Features**
* build the breadcrumbs through current request uri (Magic).
* build the custom breadcrumbs.
* render the custom breadcrumbs template.

Installation
------------

### Step 1: Download the Bundle

```bash
$ composer require yceruto/breadcrumbs-bundle
```

This command requires you to have Composer installed globally, as explained
in the [Composer documentation](https://getcomposer.org/doc/00-intro.md).

### Step 2: Enable the Bundle

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Yceruto\Bundle\BreadcrumbsBundle\BreadcrumbsBundle(),
        );
    }

    // ...
}
```

Basic Usage
-----------

### Render the breadcrumbs in your template

```twig
{{ render_breadcrumbs() }}
```

Advance Usage
-------------

### Create the custom breadcrumbs

```php
public function indexAction() 
{
	$breadcrumbs = $this->get('breadcrumbs_builder')->create();
	$breadcrumbs->add('name', '/');
	
	$node = new BreadcrumbsNode('name', '/');
	$breadcrumbs->addNode($node);
}
```

Render the custom breadcrumbs here.

```twig
{{ render_breadcrumbs(custom_breadcrumbs) }}
```

### Customize the breadcrumbs template

By default the breadcrumbs is rendered through `@Breadcrumbs/breadcrumbs/breadcrumbs.html.twig` template. You can override the default template creating your `app/Resources/views/breadcrumbs/breadcrumbs.html.twig` template in your project structure.

```twig
{# app/Resources/views/breadcrumbs/breadcrumbs.html.twig #}

<ol class="breadcrumb">
    {% for node in breadcrumbs %}
        {% set label = 'breadcrumbs.' ~ node.name %}
        {% if not loop.last %}
            <li><a href="{{ node.path }}"><i class="fa fa-home"></i> {{ label|trans }}</a></li>
        {% else %}
            <li class="active">{{ label|trans }}</li>
        {% endif %}
    {% endfor %}
</ol>
```

Override the default template passing the custom template path directly in the render function.

```twig
{{ render_breadcrumbs(template='breadcrumbs/custom_breadcrumbs.html.twig')
```

Resources
---------

You can run the unit tests with the following command:

    $ cd path/to/breadcrumbs-bundle/
    $ composer install
    $ phpunit

License
-------

This software is published under the [MIT License](LICENSE)

