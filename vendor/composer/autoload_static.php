<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita3b90fc70c2240992b368ff2e4896266
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WooExtraAddonsInc\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WooExtraAddonsInc\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita3b90fc70c2240992b368ff2e4896266::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita3b90fc70c2240992b368ff2e4896266::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita3b90fc70c2240992b368ff2e4896266::$classMap;

        }, null, ClassLoader::class);
    }
}