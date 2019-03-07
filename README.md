# Importly

Register PHP imports at runtime.

## Installation

```
composer require nickolasburr/importly
```

## Usage

Instead of including imports statically:

```
<?php

namespace Example;

use External\Entity;
use Somewhere\Outside\ThisNamespace as Outsider;

class Name implements WordInterface
{
}
```

Invoke `include_imports` with the respective FQCN:

```
<?php

namespace Example;

\include_imports(Name::class);

class Name implements WordInterface
{
}
```

Then add the imports to `imports.json` in the module root directory:

```
{
  "imports": [
    {
      "class": "Example\\Name",
      "uses": [
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
