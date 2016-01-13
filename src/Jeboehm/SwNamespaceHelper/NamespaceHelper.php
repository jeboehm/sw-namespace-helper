<?php

namespace Jeboehm\SwNamespaceHelper;

use InvalidArgumentException;

class NamespaceHelper
{
    /**
     * Register plugin namespace.
     *
     * @param string $pluginName Name of the plugin
     * @param string $type       Plugin type (backend, frontend)
     * @param string $namespace  Plugin namespace (local, community, commercial)
     */
    public static function registerPluginNamespace($pluginName, $type, $namespace = 'local')
    {
        $path = self::getPath($pluginName, $type, $namespace);

        if (!is_dir($path)) {
            throw new InvalidArgumentException(sprintf('Plugin path %s doesn\'t exist.', $path));
        }

        self::registerModelNamespace($path);
    }

    /**
     * Get path.
     *
     * @param string $pluginName Name of the plugin
     * @param string $type       Plugin type (backend, frontend)
     * @param string $namespace  Plugin namespace (local, community, commercial)
     *
     * @return string
     */
    protected static function getPath($pluginName, $type, $namespace)
    {
        return Shopware()->AppPath(
            sprintf(
                'Plugins/%s/%s/%s',
                ucfirst(strtolower($namespace)),
                ucfirst(strtolower($type)),
                $pluginName
            )
        );
    }

    /**
     * Register custom model namespace.
     *
     * @param string $path Plugin path.
     *
     * @return void
     */
    protected static function registerModelNamespace($path)
    {
        Shopware()->Loader()->registerNamespace('Shopware\CustomModels', $path . 'Models/');
    }
}
