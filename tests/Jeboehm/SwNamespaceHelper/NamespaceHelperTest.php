<?php

use Jeboehm\SwNamespaceHelper\NamespaceHelper;

class NamespaceHelperTest extends Enlight_Components_Test_Plugin_TestCase
{
    public function testRegisterPluginNamespace()
    {
        $result = NamespaceHelper::registerPluginNamespace('Cron');

        $this->assertTrue($result);
    }

    public function testRegisterPluginNamespaceAndLoadClass()
    {
        NamespaceHelper::registerPluginNamespace('RestApi');

        $this->assertStringEndsWith(
            'StaticResolver.php',
            Shopware()->Loader()->getClassPath('ShopwarePlugins\RestApi\Components\StaticResolver')
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRegisterUnknownPlugin()
    {
        NamespaceHelper::registerPluginNamespace('adsasd');
    }
}
