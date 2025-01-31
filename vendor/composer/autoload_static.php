<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2f07e0cd075704b763590282b1dce599
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Laraphant\\Blogcrud\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Laraphant\\Blogcrud\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2f07e0cd075704b763590282b1dce599::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2f07e0cd075704b763590282b1dce599::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2f07e0cd075704b763590282b1dce599::$classMap;

        }, null, ClassLoader::class);
    }
}
