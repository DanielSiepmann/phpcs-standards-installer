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

## How does it work?
By copying/symlinking the code standard directory containing the standard's sniffs to
the PHPCS `Standards` folder it is available to reference like those that ship with PHPCS.

My use case was the installation of [Slevomat Coding Standard](https://github.com/slevomat/coding-standard),
and cherry-picking its sniffs, which becomes really cumbersome. By making it available the sniffs
can be referenced simply by name.

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
