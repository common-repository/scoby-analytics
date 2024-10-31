# RebelCode - PSR7

[PSR-7] message implementation that also provides common utility methods.

This package is an informal fork of [guzzlehttp/psr] v1.8.2, with the following changes:

1. The root namespace is renamed to `RebelCode`
2. The target PHP version is lowered to 7.1

Some other package-level changes include tweaks to the Psalm config and using Docker for testing and building.

# Purpose

Due to a lack of dependency management in WordPress, plugins that use the same 3rd party libraries, but at different
versions, will cause conflicts.

For instance, one plugin autoloads Guzzle v6.x and another attempts to autoload Guzzle v7.x. Only one of the two plugins
will have its version autoloaded; the other will be using a version that it was not intended to use, and undefined
class/method errors, type errors and invalid invocations will ensue.

As such, this package exists so that RebelCode's existing Guzzle dependents can replace Guzzle with an alternative
implementation, all the while benefiting from the tried-and-tested code that Guzzle relies on. This is also the reason
why the PHP version requirement was downgraded to 7.1 - this is the minimum requirement for RebelCode's plugins.

**Important**: This package is not intended for open use. Doing so would re-establish the original problem, which is
that of different WordPress plugins using the same 3rd party dependencies. Yes, it sucks. What developers need is for
WordPress to incorporate some form of [dependency management][trac-issue] into Core.

# Installation

```
composer require rebelcode/psr7
```

# Credits

Credits for this package go mostly to the Guzzle team ✌

[PSR-7]: https://www.php-fig.org/psr/psr-7

[PSR-18]: https://www.php-fig.org/psr/psr-18

[rebelcode/wp-http]: https://github.com/rebelcode/wp-http

[guzzlehttp/psr]: https://github.com/guzzle/psr7

[guzzlehttp/guzzle]: https://github.com/guzzle/guzzle

[trac-issue]: https://core.trac.wordpress.org/ticket/22316
