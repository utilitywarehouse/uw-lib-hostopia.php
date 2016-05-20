# uw-lib-hostopia.php

[![CircleCI](https://circleci.com/gh/utilitywarehouse/uw-lib-hostopia.php.svg?style=svg&circle-token=e1c2ca6b3760a44836316c7e15b2bbc93812ffcf)](https://circleci.com/gh/utilitywarehouse/uw-lib-hostopia.php)

SDK for managing e-mail accounts hosted on Hostopia platform.

### Usage

TBD

### Tests

* `make test-spec` - object behaviour tests
* `make test-integration` - integration tests with Hostopia SOAP API

Add the following lines to your `phpunit.xml` (before closing `</phpunit>` tag) before running integration tests and populate them with test api credentials:

```
<php>
    <env name="UW_HOSTOPIA_USERNAME" value=""/>
    <env name="UW_HOSTOPIA_PASSWORD" value=""/>
</php>
```

or export these variables in your shell:

```
export UW_HOSTOPIA_USERNAME=""
export UW_HOSTOPIA_PASSWORD=""
```

Run `make test` to execute all of the above.