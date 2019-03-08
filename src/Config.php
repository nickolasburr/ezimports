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
        /** @var string $modulePath */
        $modulePath = dirname(__DIR__);

        return defined('EZIMPORTS_MODULE_PATH') ? EZIMPORTS_MODULE_PATH : $modulePath;
    }

    /**
     * Get absolute path to imports.json file
     * of the module utilizing ezimports.
     *
     * @param string $package
     * @return string|null
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
        return dirname(dirname(self::getEzImportsModulePath())) . DIRECTORY_SEPARATOR . $package;
    }
}
