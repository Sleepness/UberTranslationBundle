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

In `app/config/config.yml` you must to add some configuration for make bundle works as expected.
Here is how you config might look:

``` yml
sleepness_uber_translation:
  memcached:
      host: localhost
      port: 11211
  supported_locales: [en, uk]
```

### Step 4: Routing configuration

In `app/config/routing.yml` you must to include bundle routes:

``` yml
uber:
    resource: "@SleepnessUberTranslationBundle/Resources/config/routing.yml"]
```

To be continue...
