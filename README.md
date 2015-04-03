[![Build Status](https://travis-ci.org/Sleepness/UberTranslationBundle.svg?branch=develop)](https://travis-ci.org/Sleepness/UberTranslationBundle)  [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Sleepness/UberTranslationBundle/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Sleepness/UberTranslationBundle/?branch=develop) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/8fa61a7b-988e-497c-b471-dba222bb4bb5/mini.png)](https://insight.sensiolabs.com/projects/8fa61a7b-988e-497c-b471-dba222bb4bb5)

SleepnessUberTranslationBundle
=====================

This Bundle provides a tool to use [Memcached](http://memcached.org/) translations and ease translations management.

Introduction
------------

This Bundle uses [Memcached](http://memcached.org/) as a recourse of your translations. Translation mechanisms allow your client/customer to edit and override any translations directly from the website.

### Separation of concern

The use of this tool allows to clear separation between "key" and "value". Developers are responsible for defining new keys and removing old keys, while client/customer is responsible for translating the website.

### Symfony compatibility

This bundle works on any symfony 2.0+ version.

### Storage layer

This Bundle uses [Memcached](http://memcached.org/) as a storage. Please see [wiki](https://code.google.com/p/memcached/wiki/NewStart?tm=6) for the information how install and use Memcached.

Documentation
-------------

The bulk of the documentation is stored in the [`Resources/doc/index.md`](https://github.com/Sleepness/UberTranslationBundle/blob/develop/Resources/doc/index.md) file in this bundle.

Installation
------------

The installation of Bundle instructions are located in the documentation.

Configuration
-------------

All the configuration instructions are located in the documentation.

Contributing
------------

Pull requests are welcome.

License
-------

See the complete license in the bundle: [`Resources/meta/LICENSE`](https://github.com/Sleepness/UberTranslationBundle/blob/develop/Resources/meta/LICENSE)
