# views

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Code Climate][ico-cc]][link-cc]
[![Tests Coverage][ico-cc-coverage]][link-cc]

A simple PHP templating engine.

## Install

Via Composer

``` bash
$ composer require vakata/views
```

## Usage

``` php
use vakata\views\View;

// register template dirs
View::addDir('/path/to/templatedir');
View::addDir('/path/to/otherdir', 'other');

// a variable available in all templates
View::shareData("siteTitle", "test");

// variables available in all templates
View::shareData(["a" => 1, "b" => 2]);

// render a template from the first dir:
View::get('profile', ['user' => 'Test']);

// render a template from a named dir:
View::get('other::user', ['user' => 'Test']);

// the above is the same as
$v = new View('other::user');
$v->render(['user'=>'Test']);
```

A sample template may look like this:
```php
<?php $this->layout('master.layout', ['masterParam' => 'master-value']); ?>

Content

<?php $this->sectionStart("sidebar"); ?>
Here is some unfiltered content: <?= $b ?> 
Here is some filtered content: <?= $this->e($user) ?> 
<?php $this->sectionStop(); ?>

Here is some filtered and transformed content:
<?= $this->e($user, 'trim|strtouuper') ?> 

Include a child template:
<?= $this->insert('nameddir::include', ['optional' => 'params']); ?>
```

As for the master template (which can in turn have its own master template):
```php
I am master!

<?= $this->section("sidebar") ?>

<?= $masterParam ?> <?= $a ?>

The unnamed part of the above template:
<?= $this->section() ?>

```

Read more in the [API docs](docs/README.md)

## Testing

``` bash
$ composer test
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email github@vakata.com instead of using the issue tracker.

## Credits

- [vakata][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 

[ico-version]: https://img.shields.io/packagist/v/vakata/views.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/vakata/views/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/vakata/views.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/vakata/views.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/vakata/views.svg?style=flat-square
[ico-cc]: https://img.shields.io/codeclimate/github/vakata/views.svg?style=flat-square
[ico-cc-coverage]: https://img.shields.io/codeclimate/coverage/github/vakata/views.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/vakata/views
[link-travis]: https://travis-ci.org/vakata/views
[link-scrutinizer]: https://scrutinizer-ci.com/g/vakata/views/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/vakata/views
[link-downloads]: https://packagist.org/packages/vakata/views
[link-author]: https://github.com/vakata
[link-contributors]: ../../contributors
[link-cc]: https://codeclimate.com/github/vakata/views

