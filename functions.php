<?php
/**
 * functions.php
 */
use NickolasBurr\EzImports\Config;

if (!function_exists('include_imports')) {
    /**
     * Register PHP imports at runtime.
     *
     * @param string $class FQCN of class/interface/trait/etc.
     * @param string $module The module name in 'vendor/module' format.
     * @return void
     */
    function include_imports($class, $module) {
        /* Trim any leading/trailing backslashes from class. */
        $class = trim($class, '\\');

        /** @var string $namespace */
        $namespace = Config::getNamespaceFromFqcn($class);

        /* Trim any leading/trailing slashes from module. */
        $module = trim($module, '/');

        /** @var array $imports */
        $imports = Config::getClassImports($class, $module);

        foreach ($imports as $import) {
            /** @var string $use */
            $use = $import['use'] ?? null;

            if ($use === null) {
                /** @todo: Provide more informative exception message. */
                throw new \Exception('No import path was specified.');
            }

            /** @var string $short */
            $short = Config::getShortNameFromFqcn($import['as'] ?? $use);

            /** @var string $alias */
            $alias = $namespace . '\\' . $short;

            if (!class_exists($alias)) {
                class_alias($use, $alias);
            }
        }
    }
}
