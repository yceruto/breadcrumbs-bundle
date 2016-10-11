Symfony > Bundle > Breadcrumbs
==============================

[![Build Status](https://travis-ci.org/yceruto/breadcrumbs-bundle.svg?branch=master)](https://travis-ci.org/yceruto/breadcrumbs-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yceruto/breadcrumbs-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yceruto/breadcrumbs-bundle/?branch=master)
[![Coverage Status](https://img.shields.io/coveralls/yceruto/breadcrumbs-bundle/master.svg)](https://coveralls.io/github/yceruto/breadcrumbs-bundle?branch=master)
[![Packagist Version](https://img.shields.io/packagist/v/yceruto/breadcrumbs-bundle.svg)](https://packagist.org/packages/yceruto/breadcrumbs-bundle)
[![Packagist Download](https://img.shields.io/packagist/dt/yceruto/breadcrumbs-bundle.svg)](https://packagist.org/packages/yceruto/breadcrumbs-bundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d5df66f3-377d-4f39-9875-bbda6e3d235d/mini.png)](https://insight.sensiolabs.com/projects/d5df66f3-377d-4f39-9875-bbda6e3d235d)
<sup><kbd>**SUPPORTS SYMFONY 2.x and 3.x**</kbd></sup>

A friendly way to create breadcrumbs for symfony applications.

**Features**
* Build breadcrumbs through current request uri (default).
* Customize breadcrumbs nodes.
* Customize breadcrumbs template.

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

By default, the breadcrumb is builded through request path info.

```twig
{# app/Resources/views/base.html.twig #}

{{ render_breadcrumbs() }}
```

**That's it!**

How it work
-----------

Suppose I have the follows routes and translation:

```yaml
# app/config/routing.yml

_index:
	path: /
	defaults: { _controller: ... }
	
_store:
	path: /store
	defaults: { _controller: ... }
	
_category:
	path: /store/{category}
	defaults: { _controller: ... }
	
_category_product:
	path: /store/{category}/{product}
	defaults: { _controller: ... }
```

```yaml
# app/Resources/translations/messages.en.yml

breadcrumbs._index: Home
```

By request `/store/foo/bar` the `render_breadcrumbs` function returns:

```html
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="/store">Store</a></li>
    <li><a href="/store/foo">Foo</a></li>
    <li class="active">Bar</li>
</ol>
```

If your application does not use translation, you can set the label in route definition:

```yaml
_index:
	path: /
	defaults: { _controller: ..., breadcrumbs_label: 'Home' }
```

Translate the Breadcrumbs Interface
-----------------------------------

The breadcrumbs uses the same language as the underlying Symfony application, which
is usually configured in the `locale` option of the `app/config/parameters.yml`
file.

The strings that belong to the breadcrumbs interface are translated using the 
default `messages` domain.

In addition, make sure that the `translator` service is enabled in the
application (projects based on the Symfony Standard Edition have it disabled
by default):

```yaml
# app/config/config.yml
framework:
    translator: { fallbacks: [ "%locale%" ] }
```

Advanced Usage
--------------

### Create a custom breadcrumbs

```php
public function indexAction() 
{
	$breadcrumbs = $this->get('breadcrumbs_builder')->create();
	$breadcrumbs->add('/', 'home');
	
	// or
	
	$node = new BreadcrumbsNode();
	$node->setPath('/')
	$node->setLabel('home')
	$breadcrumbs->addNode($node);
	
	return $this->render('index.html.twig', array('custom_breadcrumbs' => $breadcrumbs))
}
```

Render the custom breadcrumbs:

```twig
{{ render_breadcrumbs(custom_breadcrumbs) }}
```

### Overriding Default BreadcrumbsBundle Template

As you start to incorporate BreadcrumbsBundle into your application, you will probably
find that you need to override the default template that is provided by
the bundle. Although the template name is not configurable, the Symfony
framework provides two ways to override the templates of a bundle.

 1. Define a new template of the same name in the `app/Resources` directory
 2. Create a new bundle that is defined as a child of `BreadcrumbsBundle`

#### Example: Overriding The Default breadcrumbs.html.twig

An example of overriding this breadcrumbs template is demonstrated below using first of the 
overriding options listed above.

Here is the default `breadcrumbs.html.twig` provided by the `BreadcrumbsBundle`:

```twig
<ol class="breadcrumb">
    {% for node in breadcrumbs %}
        {% if not loop.last %}
            <li><a href="{{ node.path }}">{{ node.label|trans|title }}</a></li>
        {% else %}
            <li class="active">{{ node.label|trans|title }}</li>
        {% endif %}
    {% endfor %}
</ol>
```

The following Twig template file is an example of a breadcrumbs file that might be used
to override the provided by the bundle.

```twig
<ol class="breadcrumb">
    {% for node in breadcrumbs %}
        {% set icon = loop.first ? '<i class="fa fa-home"></i>' %}
        
        {% if not loop.last %}
            <li><a href="{{ node.path }}">{{ icon|raw }}{{ node.label|trans|title }}</a></li>
        {% else %}
            <li class="active">{{ icon|raw }}{{ node.label|trans|title }}</li>
        {% endif %}
    {% endfor %}
</ol>
```

**1) Define New Template In app/Resources**

The easiest way to override a bundle's template is to simply place a new one in
your `app/Resources` folder. To override the breadcrumbs template located at
`Resources/views/breadcrumbs.html.twig` in the `BreadcrumbsBundle` directory, you would place
your new breadcrumbs template at `app/Resources/BreadcrumbsBundle/views/breadcrumbs.html.twig`.

As you can see the pattern for overriding templates in this way is to
create a folder with the name of the bundle class in the `app/Resources` directory.
Then add your new template to this folder, preserving the directory structure from the
original bundle.

Resources
---------

You can run the unit tests with the following command:

    $ cd path/to/breadcrumbs-bundle/
    $ composer install
    $ phpunit

License
-------

This software is published under the [MIT License](LICENSE)

