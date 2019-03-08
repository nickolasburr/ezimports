# ezimports

Register PHP imports at runtime.

## Installation

```
composer require nickolasburr/ezimports
```

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

Invoke `include_imports` with the respective FQCN:

```php
<?php

namespace Example;

\include_imports(Name::class);

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
