<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitebaaf7c5fbf561bd5937618d1f38878e
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'League\\Plates\\' => 14,
        ),
        'G' => 
        array (
            'GenderDetector\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'League\\Plates\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/plates/src',
        ),
        'GenderDetector\\' => 
        array (
            0 => __DIR__ . '/..' . '/tuqqu/gender-detector/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitebaaf7c5fbf561bd5937618d1f38878e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitebaaf7c5fbf561bd5937618d1f38878e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
