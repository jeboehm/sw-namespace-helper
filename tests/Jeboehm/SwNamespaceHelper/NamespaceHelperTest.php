<?php

use Jeboehm\SwNamespaceHelper\NamespaceHelper;
use Shopware\Models\Plugin\Plugin;

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
     * @expectedExceptionCode 300
     */
    public function testRegisterUnknownPlugin()
    {
        NamespaceHelper::registerPluginNamespace('adsasd');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 100
     */
    public function testRegisterEmptyPluginName()
    {
        NamespaceHelper::registerPluginNamespace('');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionCode 200
     */
    public function testInvalidPluginRegistration()
    {
        NamespaceHelper::registerPluginNamespace('TestInvalidPlugin');
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $plugin = new Plugin();
        $plugin->setName('TestInvalidPlugin');
        $plugin->setSource('Local');
        $plugin->setNamespace('Backend');
        $plugin->setLabel('Invalid Plugin');
        $plugin->setVersion('1.0');

        $manager = Shopware()->Models();
        $manager->persist($plugin);
        $manager->flush($plugin);
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        $manager = Shopware()->Models();
        $repository = $manager->getRepository('Shopware\Models\Plugin\Plugin');

        /** @var Plugin $plugin */
        $plugin = $repository->findOneBy(['name' => 'TestInvalidPlugin']);
        $manager->remove($plugin);
        $manager->flush($plugin);
    }
}
