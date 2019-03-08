<?php
/**
 * functions.php
 */
use NickolasBurr\EzImports\Config;

if (!function_exists('include_imports')) {
    /**
     * Register PHP imports at runtime.
     *
     * @param string $package The module name in 'vendor/package' format.
     * @param string $class
     * @return void
     */
    function include_imports($package, $class) {
        /* Trim any leading/trailing slashes from package. */
        $package = trim($package, '/');

        /* Trim any leading/trailing backslashes from class. */
        $class = trim($class, '\\');

        /** @var array $imports */
        $imports = Config::getClassImports($package, $class);

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
