<?php

use Jeboehm\SwNamespaceHelper\NamespaceHelper;

class NamespaceHelperTest extends Enlight_Components_Test_Plugin_TestCase
{
    public function testRegisterPluginNamespace()
    {
        $result = NamespaceHelper::registerPluginNamespace('Debug');

        $this->assertTrue($result);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRegisterUnknownPlugin()
    {
        NamespaceHelper::registerPluginNamespace('adsasd');
    }
}
