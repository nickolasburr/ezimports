<?php
/**
 * Config.php
 */
namespace NickolasBurr\EzImports;

class Config
{
    /**
     * Get absolute path to ezimports module.
     *
     * @return string
     */
    public static function getEzImportsModulePath()
    {
        return defined('EZIMPORTS_MODULE_PATH') ? EZIMPORTS_MODULE_PATH : dirname(__DIR__);
    }

    /**
     * Get absolute path to vendor directory
     * where ezimports module is installed.
     *
     * @return string
     */
    public static function getEzImportsVendorPath()
    {
        return defined('EZIMPORTS_VENDOR_PATH') ? EZIMPORTS_VENDOR_PATH : dirname(self::getEzImportsModulePath());
    }

    /**
     * Get array of imports for specific package class.
     *
     * @param string $package
     * @param string $class
     * @return array
     * @todo: Return bool/null instead of empty array, which can be misleading.
     */
    public static function getClassImports($package, $class)
    {
        /** @var string $filePath */
        $filePath = self::getImportsFilePath($package);

        if (!file_exists($filePath)) {
            return [];
        }

        $content = file_get_contents($filePath);
        $objects = json_decode($content, true);
        $imports = $objects['imports'] ?? null;

        if ($imports === null) {
            return [];
        }

        foreach ($imports as $import) {
            if (isset($import['class']) && $class === $import['class']) {
                return $import['uses'] ?? false;
            }
        }

        return [];
    }

    /**
     * Get absolute path to imports.json file
     * of the module utilizing ezimports.
     *
     * @param string $package
     * @return string
     */
    public static function getImportsFilePath($package)
    {
        /** @var string $fileName The filename of the imports file, which defaults to imports.json. */
        $fileName = defined('EZIMPORTS_FILENAME') ? EZIMPORTS_FILENAME : 'imports.json';

        /** @var string $filePath */
        $filePath = self::getModulePath($package) . DIRECTORY_SEPARATOR . $fileName;

        return defined('EZIMPORTS_FILE_PATH') ? EZIMPORTS_FILE_PATH : $filePath;
    }

    /**
     * Get absolute path to the module utilizing ezimports.
     *
     * @param string $package
     * @return string|bool
     * @todo: str_replace 'vendor/package' with DIRECTORY_SEPARATOR for compatibility.
     */
    public static function getModulePath($package)
    {
        return dirname(self::getEzImportsVendorPath()) . DIRECTORY_SEPARATOR . $package;
    }

    /**
     * Get namespace from FQCN.
     *
     * @param string|null $class
     * @return string
     */
    public static function getNamespaceFromFqcn($class = null)
    {
        if ($class === null) {
            throw new \Exception('Invalid class name was given.');
        }

        $class = '\\' . trim($class, '\\');
        return substr($class, 0, strrpos($class, '\\'));
    }

    /**
     * Get short name from FQCN.
     *
     * @param string|null $class
     * @return string
     */
    public static function getShortNameFromFqcn($class = null)
    {
        if ($class === null) {
            throw new \Exception('Invalid class name was given.');
        }

        $class = '\\' . trim($class, '\\');
        return substr($class, strrpos($class, '\\') + 1);
    }
}
