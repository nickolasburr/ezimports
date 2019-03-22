# ezimports

Register PHP imports via JSON.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Constants](#constants)
- [Usage](#usage)
- [Benefits](#benefits)
- [Caveats](#caveats)

## Installation

```
composer require nickolasburr/ezimports
```

## Configuration

The imports JSON file should contain an array of objects,
each representing an entity and its subsequent imports.
For each entity object, the following properties should
be specified:

- `class`: The FQCN of the class, interface, or trait.
- `imports`: An array of import objects.
  + `use`: The FQCN of the class, interface, or trait to import.
  + `as`: [OPTIONAL] The alias to use in place of the short name. Defaults to short name.

## Constants

Prior to searching, ezimports looks for the following constants:

- `EZIMPORTS_MODULE_PATH`: The path to the ezimports module directory. Defaults to `vendor/nickolasburr/ezimports`.
- `<VENDOR>_<MODULE>_IMPORTS_FILE_NAME`: Imports file basename. Defaults to `imports.json`.
- `<VENDOR>_<MODULE>_IMPORTS_FILE_PATH`: Imports file path. Defaults to `vendor/<VENDOR>/<MODULE>/imports.json`.

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
[
  {
    "class": "Example\\Name",
    "imports": [
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
```

Alternatively, specify the path via `<VENDOR>_<MODULE>_IMPORTS_FILE_PATH`.
The easiest way to achieve this is to add a bootstrap file to the module,
which can be loaded first.

```php
<?php
/**
 * bootstrap.php
 *
 * As an example, assume the module is
 * named 'nickolasburr/testmodule'.
 */

if (!defined('NICKOLASBURR_TESTMODULE_IMPORTS_FILE_PATH')) {
    define('NICKOLASBURR_TESTMODULE_IMPORTS_FILE_PATH', '/path/to/module/imports.json');
}
```

## Benefits

The ezimports mission statement is simple:

- Reduce size of PHP source files
- Provide an easier way to map dependencies

## Caveats

Just like anything else, there are tradeoffs, such as:

- Less performant (no formal benchmarks currently)
- Less secure (but not _insecure_ when managed properly)
