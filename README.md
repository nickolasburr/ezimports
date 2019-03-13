# ezimports

Register PHP imports via JSON.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)

## Installation

```
composer require nickolasburr/ezimports
```

## Configuration

Prior to searching, ezimports looks for the following constants:

`EZIMPORTS_MODULE_PATH`: The path to the ezimports module directory. Defaults to `vendor/nickolasburr/ezimports`.
`<VENDOR>_<MODULE>_EZIMPORTS_FILE_BASENAME`: Imports file basename. Defaults to `imports.json`.
`<VENDOR>_<MODULE>_EZIMPORTS_FILE_PATH`: Imports file path. Defaults to `vendor/<VENDOR>/<MODULE>/imports.json`.

## Usage

Instead of including imports statically:

```php
<?php

namespace Example;

use Dictionary\WordInterface;
use External\Entity;
use Somewhere\Outside\ThisNamespace as Outsider;

class Name implements WordInterface
{
}
```

Invoke `include_imports` with FQCN and module name:

```php
<?php

namespace Example;

\include_imports(Name::class, '<VENDOR>/<MODULE>');

class Name implements WordInterface
{
}
```

Next, add the imports to `imports.json` in the module root directory:

```js
{
  "imports": [
    {
      "class": "Example\\Name",
      "uses": [
        {
          "use": "Dictionary\\WordInterface"
        },
        {
          "use": "External\\Entity"
        },
        {
          "use": "Somewhere\\Outside\\ThisNamespace",
          "as": "Outsider"
        }
      ]
    }
  ]
}
```

Alternatively, specify the path via `<VENDOR>_<MODULE>_EZIMPORTS_FILE_PATH`.
The easiest way to achieve this is to add a bootstrap file to the module,
which can be loaded first.

```php
<?php
/**
 * bootstrap.php
 *
 * As an example, assume the module is
 * named 'nickolasburr/testimports'.
 */

if (!defined('NICKOLASBURR_TESTIMPORTS_EZIMPORTS_FILE_PATH')) {
  define('NICKOLASBURR_TESTIMPORTS_EZIMPORTS_FILE_PATH', '/path/to/module/imports.json');
}
```
