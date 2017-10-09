# Codeception Cleverreach Module
[![Build Status](https://travis-ci.org/portrino/codeception-cleverreach-module.svg?branch=master)](https://travis-ci.org/portrino/codeception-cleverreach-module)

This package provides cleverreach testing for codeception

# Installation
```bash
composer require --dev portrino/codeception-cleverreach-module
```
# Usage
You can use this module like other codeception modules. By adding 'CleverReach' to the enabled modules in your 
codeception suite configuration.

# Enable module and setup the configuration variables
The variables could be set in config file directly or via an environment variable: %API_KEY%
```bash
modules:
    enabled:
        - CleverReach:
            depends: PhpBrowser
            client_id: ADD_YOUR_CLIENT_ID_HERE
            login_name: ADD_YOUR_LOGIN_NAME_HERE
            password: ADD_YOUR_PASSWORD_HERE
```
# Update Codeception build

```bash
codecept build
```

# Implement the cept / cest

```bash
$I->seeUserIsSubscribedForNewsletter($email, $groupId);
```