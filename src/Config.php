<?php
/**
 * Config.php
 */
namespace NickolasBurr\EzImports;

final class Config
{
    /** @constant DEFAULT_IMPORTS_FILE_NAME */
    const DEFAULT_IMPORTS_FILE_NAME = 'imports.json';

    /** @constant IMPORTS_FILE_NAME_AFFIX */
    const IMPORTS_FILE_NAME_AFFIX = '_IMPORTS_FILE_NAME';

    /** @constant IMPORTS_FILE_PATH_AFFIX */
    const IMPORTS_FILE_PATH_AFFIX = '_IMPORTS_FILE_PATH';

    /** @constant EZIMPORTS_MODULE_PATH_CONST_KEY */
    const EZIMPORTS_MODULE_PATH_CONST_KEY = 'EZIMPORTS_MODULE_PATH';

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
        return defined(self::EZIMPORTS_MODULE_PATH_CONST_KEY)
            ? constant(self::EZIMPORTS_MODULE_PATH_CONST_KEY)
            : dirname(__DIR__);
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
     * Get array of imports for specific module class.
     *
     * @param string $class
     * @param string $module
     * @return array
     */
    public static function getClassImports(string $class, string $module): array
    {
        /** @var string $filePath */
        $filePath = self::getImportsFilePath($module);

        if (!file_exists($filePath)) {
            return [];
        }

        /** @var string $content */
        $content = file_get_contents($filePath);

        if ($content === false) {
            /** @var string $message */
            $message = sprintf("Unable to read file %s\n", $filePath);

            throw new \Exception($message);
        }

        /** @var array|null $entries */
        $entries = json_decode($content, true) ?? null;

        if ($entries !== null) {
            foreach ($entries as $entity) {
                if (isset($entity['class']) && $class === $entity['class']) {
                    return $entity['imports'] ?? [];
                }
            }
        }

        return [];
    }

    /**
     * Get absolute path to imports file
     * of the module utilizing ezimports.
     *
     * @param string|null $module
     * @return string
     */
    public static function getImportsFilePath(?string $module = null): ?string
    {
        if ($module !== null) {
            /** @var string $prefix */
            $prefix = str_replace('-', '', $module);
            $prefix = str_replace('/', '_', $prefix);
            $prefix = strtoupper($prefix);

            /** @var string $fileNameConstKey */
            $fileNameConstKey = $prefix . self::IMPORTS_FILE_NAME_AFFIX;

            /** @var string $fileName The imports file basename, which defaults to imports.json. */
            $fileName = defined($fileNameConstKey)
                ? constant($fileNameConstKey)
                : self::DEFAULT_IMPORTS_FILE_NAME;

            /** @var string $filePathConstKey */
            $filePathConstKey = $prefix . self::IMPORTS_FILE_PATH_AFFIX;

            /** @var string $filePath */
            $filePath = defined($filePathConstKey)
                ? constant($filePathConstKey)
                : self::getModulePath($module) . DIRECTORY_SEPARATOR . $fileName;

            return $filePath;
        }

        return null;
    }

    /**
     * Get absolute path to the module utilizing ezimports.
     *
     * @param string $module
     * @return string
     * @todo: If applicable, replace '/' in $module for
     *        Windows compatibility.
     */
    public static function getModulePath(string $module): string
    {
        return dirname(self::getEzImportsVendorPath()) . DIRECTORY_SEPARATOR . $module;
    }

    /**
     * Get namespace from FQCN.
     *
     * @param string|null $class
     * @return string
     */
    public static function getNamespaceFromFqcn(string $class = null): string
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
    public static function getShortNameFromFqcn(string $class = null): string
    {
        if ($class === null) {
            throw new \Exception('Invalid class name was given.');
        }

        $class = '\\' . trim($class, '\\');
        return substr($class, strrpos($class, '\\') + 1);
    }
}
