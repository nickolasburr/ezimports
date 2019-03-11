# ezimports

Register PHP imports at runtime.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)

## Installation

```
composer require nickolasburr/ezimports
```

## Configuration

Prior to searching for the client module/project, ezimports will look
for the following constants and, if found, will use them instead.

`EZIMPORTS_MODULE_PATH`: The path to the ezimports module directory. Defaults to `vendor/nickolasburr/ezimports`.
`<VENDOR>_<PACKAGE>_EZIMPORTS_FILE_BASENAME`: Imports file basename. Defaults to `imports.json`.
`<VENDOR>_<PACKAGE>_EZIMPORTS_FILE_PATH`: Imports file path. Defaults to `vendor/<VENDOR>/<PACKAGE>/imports.json`.

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

Invoke `include_imports` with the package name and FQCN:

```php
<?php

namespace Example;

\include_imports('vendor/package', Name::class);

class Name implements WordInterface
{
}
```

Then add the imports to `imports.json` in the module root directory:

```json
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
