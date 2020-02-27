<?php
/**
 * functions.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, which
 * is bundled with this package in the file LICENSE.txt.
 *
 * It is also available on the Internet at the following URL:
 * https://raw.githubusercontent.com/nickolasburr/ezimports/master/LICENSE
 *
 * @package       NickolasBurr\EzImports
 * @copyright     Copyright (C) 2020 Nickolas Burr <nickolasburr@gmail.com>
 * @license       MIT License
 */
use NickolasBurr\EzImports\Config;

if (!function_exists('include_imports')) {
    /**
     * @param string $class FQCN of class/interface/trait/etc.
     * @param string $module The module name in 'vendor/module' format.
     * @return void
     */
    function include_imports($class, $module) {
        /** @var string $fqcn */
        $fqcn = trim($class, '\\');

        /** @var string $namespace */
        $namespace = Config::getNamespaceFromFqcn($fqcn);

        /** @var array $imports */
        $imports = Config::getClassImports($fqcn, $module);

        /** @var array $import */
        foreach ($imports as $import) {
            /** @var string $use */
            $use = $import['use'] ?? null;

            if ($use !== null) {
                /** @var string $short */
                $short = Config::getShortNameFromFqcn($import['as'] ?? $use);

                /** @var string $alias */
                $alias = $namespace . '\\' . $short;

                if (!class_exists($alias)) {
                    class_alias($use, $alias);
                }
            } else {
                throw new Exception('No import path was specified.');
            }
        }
    }
}
