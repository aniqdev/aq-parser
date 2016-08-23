<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit920844333485d70c18c57609e87d17fe
{
    public static $prefixLengthsPsr4 = array (
        'b' => 
        array (
            'baibaratsky\\WebMoney\\' => 21,
        ),
        'A' => 
        array (
            'Automattic\\WooCommerce\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'baibaratsky\\WebMoney\\' => 
        array (
            0 => __DIR__ . '/..' . '/baibaratsky/php-wmsigner',
            1 => __DIR__ . '/..' . '/baibaratsky/php-webmoney',
        ),
        'Automattic\\WooCommerce\\' => 
        array (
            0 => __DIR__ . '/..' . '/automattic/woocommerce/src/WooCommerce',
        ),
    );

    public static $prefixesPsr0 = array (
        'I' => 
        array (
            'Imagine' => 
            array (
                0 => __DIR__ . '/..' . '/imagine/imagine/lib',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit920844333485d70c18c57609e87d17fe::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit920844333485d70c18c57609e87d17fe::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit920844333485d70c18c57609e87d17fe::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
