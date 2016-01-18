<?php

namespace Jeboehm\SwNamespaceHelper;

use InvalidArgumentException;
use Shopware\Models\Plugin\Plugin;

class NamespaceHelper
{
    /**
     * Register plugin namespace.
     *
     * @param string $pluginName        Name of the plugin.
     * @param string $directoryAppendix Append something to the path.
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    public static function registerPluginNamespace($pluginName, $directoryAppendix = '')
    {
        $path = self::getPath($pluginName);

        if (strlen($pluginName) < 1) {
            throw new InvalidArgumentException('Specify a plugin name.');
        }

        if ($directoryAppendix !== '') {
            $path = sprintf('%s%s/', $path, rtrim($directoryAppendix, '/'));
        }

        if (!is_dir($path)) {
            throw new InvalidArgumentException(sprintf('Plugin path "%s" doesn\'t exist.', $path));
        }

        self::registerBaseNamespace($pluginName, $path);
        self::registerModelNamespace($path);

        return true;
    }

    /**
     * Get path.
     *
     * @param string $pluginName Name of the plugin.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    protected static function getPath($pluginName)
    {
        $repository = Shopware()->Models()->getRepository('Shopware\Models\Plugin\Plugin');

        /** @var Plugin $plugin */
        $plugin = $repository->findOneBy(['name' => $pluginName]);

        if (!$plugin) {
            throw new InvalidArgumentException(sprintf('Plugin "%s" not found.', $pluginName));
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
        $modelDirectory = $path . 'Models/';

        if (is_dir($modelDirectory)) {
            Shopware()->Loader()->registerNamespace('Shopware\CustomModels', $modelDirectory);
            Shopware()->ModelAnnotations()->addPaths([$modelDirectory]);
        }
    }

    /**
     * Register base plugin namespace.
     *
     * @param string $pluginName Name of the plugin.
     * @param string $path       Plugin path.
     *
     * @return void
     */
    protected static function registerBaseNamespace($pluginName, $path)
    {
        Shopware()->Loader()->registerNamespace(
            sprintf('ShopwarePlugins\%s', $pluginName),
            $path
        );
    }
}
