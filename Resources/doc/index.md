Getting Started With SleepnessUberTranslationBundle
==================================

## Installation

### Step 1: Download bundle using composer

Add SleepnessUberTranslationBundle by running the command:

``` bash
$ php composer.phar require sleepness/uber-translation-bundle "@dev"
```

Composer will install the bundle to your project's `vendor/sleepness` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sleepness\UberTranslationBundle\SleepnessUberTranslationBundle(),
        new Sleepness\UberTranslationBundle\Tests\Fixtures\TestApp\TestBundle\TestBundle(), // required for testing environment
    );
}
```

### Step 3: Bundle configuration

In `app/config/config.yml` enable translator and add bundle configuration:

``` yaml
# app/config/config.yml

framework:
    translator: ~

sleepness_uber_translation:
  memcached:
      host: localhost
      port: 11211
  supported_locales: [en, uk]
```

## Usage

### Import translations

Import translations into memcached by running console command `uber:translations:import locale BundleName`

Example:

``` bash
$ php app/console uber:translations:import en,uk AcmeDemoBundle
```

### Export translations

Export translations from memcached by running console command `uber:translations:export BundleName`

Example:

``` bash
$ php app/console uber:translations:export AcmeDemoBundle
```

### Delete translations

Delete all translations from memcached by running console command `uber:translations:purge`

Example:

``` bash
$ php app/console uber:translations:purge
```
