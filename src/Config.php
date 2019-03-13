<?php
/**
 * Config.php
 */
namespace NickolasBurr\EzImports;

final class Config
{
    /** @constant EZIMPORTS_FILE_BASENAME_AFFIX */
    const EZIMPORTS_FILE_BASENAME_AFFIX = '_EZIMPORTS_FILE_BASENAME';

    /** @constant EZIMPORTS_FILE_PATH_AFFIX */
    const EZIMPORTS_FILE_PATH_AFFIX = '_EZIMPORTS_FILE_PATH';

    /**
     * Get absolute path to ezimports module.
     * EZIMPORTS_MODULE_PATH should only be
     * defined at the project level, not at
     * the module level.
     *
     * @return string
     */
    public static function getEzImportsModulePath(): string
    {
        return defined('EZIMPORTS_MODULE_PATH') ? EZIMPORTS_MODULE_PATH : dirname(__DIR__);
    }

    /**
     * Get absolute path to vendor directory
     * where ezimports module is installed.
     *
     * @return string
     */
    public static function getEzImportsVendorPath(): string
    {
        return dirname(self::getEzImportsModulePath());
    }

    /**
     * Get array of imports for specific package class.
     *
     * @param string $class
     * @param string $package
     * @return array
     */
    public static function getClassImports(string $class, string $package): array
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
                return $import['uses'] ?? [];
            }
        }

        return [];
    }

    /**
     * Get absolute path to imports file
     * of the module utilizing ezimports.
     *
     * @param string|null $package
     * @return string
     */
    public static function getImportsFilePath(?string $package = null): ?string
    {
        if ($package !== null) {
            /** @var string $prefix */
            $prefix = str_replace('-', '', $package);
            $prefix = str_replace('/', '_', $prefix);
            $prefix = strtoupper($prefix);

            /** @var string $fileNameConstKey */
            $fileNameConstKey = $prefix . self::EZIMPORTS_FILE_BASENAME_AFFIX;

            /** @var string $fileName The imports file basename, which defaults to imports.json. */
            $fileName = defined($fileNameConstKey) ? constant($fileNameConstKey) : 'imports.json';

            /** @var string $filePathConstKey */
            $filePathConstKey = $prefix . self::EZIMPORTS_FILE_PATH_AFFIX;

            /** @var string $filePath */
            $filePath = defined($filePathConstKey)
                ? constant($filePathConstKey)
                : self::getModulePath($package) . DIRECTORY_SEPARATOR . $fileName;

            return $filePath;
        }

        return null;
    }

    /**
     * Get absolute path to the module utilizing ezimports.
     *
     * @param string $package
     * @return string
     * @todo: If applicable, replace '/' in $package for
     *        Windows compatibility.
     */
    public static function getModulePath(string $package)
    {
        return dirname(self::getEzImportsVendorPath()) . DIRECTORY_SEPARATOR . $package;
    }

    /**
     * Get namespace from FQCN.
     *
     * @param string|null $class
     * @return string
     */
    public static function getNamespaceFromFqcn(string $class = null)
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
    public static function getShortNameFromFqcn(string $class = null)
    {
        if ($class === null) {
            throw new \Exception('Invalid class name was given.');
        }

        $class = '\\' . trim($class, '\\');
        return substr($class, strrpos($class, '\\') + 1);
    }
}
