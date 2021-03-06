<?php

namespace Saxulum\SaxulumControllerProvider\Provider;

use Saxulum\SaxulumControllerProvider\Map\Map;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\WebTestCase;

class SaxulumControllerProviderTest extends WebTestCase
{
    public function testInjectController()
    {
        $client = $this->createClient();

        $client->request('GET', '/container');
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals('ok', $client->getResponse()->getContent());

        $client->request('GET', '/service');
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals('ok', $client->getResponse()->getContent());
    }

    public function createApplication()
    {
        $app = new Application();
        $app['debug'] = true;

        $app->register(new ServiceControllerServiceProvider());
        $app->register(new SaxulumControllerProvider());

        $map = $app['controller.map'];
        /** @var Map $map */

        $map
            ->addController()
                ->setNamespace('Saxulum\SaxulumControllerProvider\Controller\ContainerExampleController')
                ->setServiceId('saxulum.saxulumcontrollerprovider.controller.containerinjectcontroller')
                ->setInjectContainer(true)
            ->end()
                ->addController()
                ->setNamespace('Saxulum\SaxulumControllerProvider\Controller\ServiceExampleController')
                ->setServiceId('saxulum.saxulumcontrollerprovider.controller.serviceController')
                ->setInjectionKeys(array('test.data'))
                ->addMethod()
                    ->setName('setTestData1')
                    ->setInjectionKeys(array('test.data'))
                ->end()
                ->addMethod()
                    ->setName('setTestData2')
                    ->setInjectionKeys(array('test.data'))
                ->end()
            ->end()
        ;

        $app['test.data'] = array(
            'key1' => 'value1'
        );

        return $app;
    }
}
