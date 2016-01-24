<?hh

namespace Pi;

class Version
{
    /**
     * Current Pi Version.
     */
    const VERSION = '0.0.2';

    /**
     * Compares a Pi version with the current one.
     *
     * @param string $version Pi version to compare.
     *
     * @return int -1 if older, 0 if it is the same, 1 if version passed as argument is newer.
     */
    public static function compare($version)
    {
        $currentVersion = str_replace(' ', '', strtolower(self::VERSION));
        $version = str_replace(' ', '', $version);

        return version_compare($version, $currentVersion);
    }
}
