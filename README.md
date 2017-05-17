LoggableBundle
===================

This bundle allows you to simply add a Loggable Doctrine Annotation
to a doctrine entity class to enable logging of all entity changes.

Installation
------------

### Composer
```
composer require incompass/loggable-bundle
```

Usage
-----

Add the Loggable annotation to your doctrine entities.

```
/**
 * @Incompass\LoggableBundle\Loggable()
 */
```

Update your database schema
```
php bin/console doctrine:schema:update --force
```

Entities changes will now be logged in the log table.

Contributors
------------

Joe Mizzi (casechek/incompass)
Mike Bates (casechek/incompass)
