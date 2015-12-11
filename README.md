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

### How to work

Suppose I have the follows routes and translation:

```yaml
# Config/routing.yml

_index:
	path: /
	defaults: { _controller: ... }
	
_category:
	path: /{category}
	defaults: { _controller: ... }
	
_category_product:
	path: /{category}/{product}
	defaults: { _controller: ... }
```

```yaml
# Resources/translations/messages.yml
breadcrumbs._index: Home
```

later by request `/foo/bar` the `render_breadcrumbs` function returns:

```html
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="/foo">Foo</a></li>
    <li class="active">Bar</li>
</ol>
```

If your application does not use translation, you can set the label on the route definition:

```yaml
_index:
	path: /
	defaults: { _controller: ..., breadcrumbs_label: 'Home' }
```

Advance Usage
-------------

### Create the custom breadcrumbs

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
        {% if not loop.last %}
            <li><a href="{{ node.path }}">{% if loop.first %}<i class="fa fa-home"></i>{% endif %} {{ node.label|trans }}</a></li>
        {% else %}
            <li class="active">{% if loop.first %}<i class="fa fa-home"></i>{% endif %} {{ node.label|trans }}</li>
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

