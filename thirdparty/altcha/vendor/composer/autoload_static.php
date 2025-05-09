<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit74542f3b97c4f1cea78c98aff3f869e2
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'AltchaOrg\\Altcha\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'AltchaOrg\\Altcha\\' => 
        array (
            0 => __DIR__ . '/..' . '/altcha-org/altcha/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit74542f3b97c4f1cea78c98aff3f869e2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit74542f3b97c4f1cea78c98aff3f869e2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit74542f3b97c4f1cea78c98aff3f869e2::$classMap;

        }, null, ClassLoader::class);
    }
}
