# Symfony QA Bundle

Bundle to check the quality of the changes in a Symfony project.

Run it
============

Just commit!

```bash
$ git commit
```

![Screehot of QaBundle](https://raw.githubusercontent.com/frieser/qa-bundle/master/screenshot.png)

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require --dev frieserlabs/qa-bundle "~0.1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
         if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            // ...
             
            $bundles[] = new Frieserlabs\Bundle\QABundle\FrieserlabsQABundle();
            
            // ...
        }

        // ...
    }

    // ...
}
```
Step 3: Enable Composer script handler
-------------------------
You have to enable the script handler in your composer.json to auto install the git hooks in your project:
```json
    "scripts": {
        ...
        "post-install-cmd": [
            ...
            "Frieserlabs\\Bundle\\QABundle\\Composer\\ScriptHandler::EnableGitHooks",
            ...
        ],
        ...
        "post-update-cmd": [
            ...
            "Frieserlabs\\Bundle\\QABundle\\Composer\\ScriptHandler::EnableGitHooks",
            ...
        ]
        ...
    },
```
Configuration
============

For example:
```yml
frieserlabs_qa:
  pre_commit:
    tools:
      phplint:
      phpunit:
      phpcs:
        critical: false
      phpcs_fixer:
        critical: false
      phpmd:
        critical: false
      composer_check:
```
Create your own tools
============
TODO

TODO
============
TODO