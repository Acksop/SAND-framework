<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Config\Resource;

/**
 * ComposerResource tracks the PHP version and Composer dependencies.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ComposerResource implements SelfCheckingResourceInterface, \Serializable
{
    private static $runtimeVersion;
    private static $runtimeVendors;
    private $versions;
    private $vendors;

    public function __construct()
    {
        self::refresh();
        $this->versions = self::$runtimeVersion;
        $this->vendors = self::$runtimeVendors;
    }

    private static function refresh()
    {
        if (null !== self::$runtimeVersion) {
            return;
        }

        self::$runtimeVersion = array();
        self::$runtimeVendors = array();

        foreach (get_loaded_extensions() as $ext) {
            self::$runtimeVersion[$ext] = phpversion($ext);
        }

        foreach (get_declared_classes() as $class) {
            if ('C' === $class[0] && 0 === strpos($class, 'ComposerAutoloaderInit')) {
                $r = new \ReflectionClass($class);
                $v = dirname(dirname($r->getFileName()));
                if (file_exists($v . '/composer/installed.json')) {
                    self::$runtimeVendors[$v] = @filemtime($v . '/composer/installed.json');
                }
            }
        }
    }

    public function getVendors()
    {
        return array_keys($this->vendors);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return __CLASS__;
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($timestamp)
    {
        self::refresh();

        if (self::$runtimeVersion !== $this->versions) {
            return false;
        }

        return self::$runtimeVendors === $this->vendors;
    }

    public function serialize()
    {
        return serialize(array($this->versions, $this->vendors));
    }

    public function unserialize($serialized)
    {
        list($this->versions, $this->vendors) = unserialize($serialized);
    }
}
