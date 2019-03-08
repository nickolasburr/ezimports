<?php
/**
 * Get array of import objects from imports.json.
 *
 * @return object[]
 */
function get_imports($file = 'imports.json') {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $objects = json_decode($content, true);

        if (isset($objects['imports'])) {
            return $objects['imports'];
        }
    }

    return [];
}

/**
 * Get array of imports for specific class.
 */
function get_class_imports($class) {
    $imports = get_imports();

    /* Remove preceding backslashes from class. */
    $class = trim($class, '\\');

    foreach ($imports as $import) {
        if (isset($import['class']) && $class === $import['class']) {
            return $import['uses'];
        }
    }

    return [];
}

if (!function_exists('include_imports')) {
    /**
     * Register PHP imports at runtime.
     *
     * @param string|null $class
     * @return void
     */
    function include_imports(string $class = null) {
        /** @var array $imports */
        $imports = get_class_imports($class);

        /** @var ReflectionClass $context */
        $context = new \ReflectionClass($class);

        foreach ($imports as $import) {
            /** @var string $use */
            $use = $import['use'] ?? null;

            if ($use === null) {
                throw new \Exception('No import path was specified.');
            }

            /** @var ReflectionClass $entity */
            $entity = new \ReflectionClass($use);

            /** @var string $source The source class the alias references. */
            $source = $entity->getName();

            /** @var string $alias */
            $alias = $import['as'] ?? null;

            /**
             * If an alias was explicitly set,
             * use it instead of short name.
             */
            if ($alias !== null) {
                /**
                 * An alias only has context inside the scope
                 * of the file where it was included. As such,
                 * it should only contain a short name without
                 * the preceding namespace.
                 */
                $alias = '\\' . trim($alias, '\\');
                $alias = substr($alias, strrpos($alias, '\\') + 1);
                $alias = $context->getNamespaceName() . '\\' . $alias;
            } else {
                /** @var string $alias The alias (target) for the source class. */
                $alias = $context->getNamespaceName() . '\\' . $entity->getShortName();
            }

            if (!class_exists($alias)) {
                class_alias($source, $alias);
            }
        }
    }
}
