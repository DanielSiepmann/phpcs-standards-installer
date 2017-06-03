# PHPCS Standards Installer
A composer plugin to install third-party PHPCS standards, especially those which
do not use the composer package type.

## Build Status
[![Build Status](https://travis-ci.org/timoschaefer/phpcs-standards-installer.svg)](https://travis-ci.org/timoschaefer/phpcs-standards-installer)

---

## Why?
Because installing PHPCS globally is not always an option, and manually copying
full standard sets or referencing their `ruleset.xml` files by full name is a pain.

Referencing or globally installing a Composer plugin is a walk in the park comparatively.

## Installation
You've got the option of either referencing the plugin in your project's `composer.json` file,
causing it to be used for that **project only**:

```json
{
    "require": {
        "ts/phpcs-standards-installer": "^1.0"
    }
}
```

Or installing the plugin globally, in which case it'll be used for every Composer run
without the need to reference the plugin again:
```bash
composer global require "ts/phpcs-standards-installer:^1.0"
```

## Disclaimer
Slapped together within an hour to scratch a personal itch.

## License
Released under the [MIT License](./LICENSE).
