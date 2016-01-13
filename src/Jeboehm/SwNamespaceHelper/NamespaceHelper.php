<?php

namespace Jeboehm\SwNamespaceHelper;

use InvalidArgumentException;
use Shopware\Models\Plugin\Plugin;

class NamespaceHelper
{
    /**
     * Register plugin namespace.
     *
     * @param string $pluginName Name of the plugin
     */
    public static function registerPluginNamespace($pluginName)
    {
        $path = self::getPath($pluginName);

        if (!is_dir($path)) {
            throw new InvalidArgumentException(sprintf('Plugin path %s doesn\'t exist.', $path));
        }

        self::registerModelNamespace($path);
    }

    /**
     * Get path.
     *
     * @param string $pluginName Name of the plugin
     *
     * @return string
     */
    protected static function getPath($pluginName)
    {
        $repository = Shopware()->Models()->getRepository('Shopware\Models\Plugin\Plugin');

        /** @var Plugin $plugin */
        $plugin = $repository->findOneBy(['name' => $pluginName]);

        if (!$plugin) {
            throw new InvalidArgumentException(sprintf('Plugin %s not found.', $pluginName));
        }

        return Shopware()->AppPath(
            sprintf(
                'Plugins/%s/%s/%s',
                $plugin->getSource(),
                $plugin->getNamespace(),
                $plugin->getName()
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
