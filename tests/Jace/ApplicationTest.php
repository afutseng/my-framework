<?php

namespace Jace;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    protected $_app = null;

    public function setUp()
    {
        $this->_app = new Application();
    }

    /**
     * @dataProvider provider
     */
    public function testIndexController(
        $request_uri,
        $exp_controller,
        $exp_action,
        $exp_response
        )
    {
        $_SERVER = [
            "REQUEST_URI" => $request_uri,
        ];

        ob_start();
        $this->_app->run(__DIR__ . '/config.ini');
        $controllerName = $this->_app->getControllerName();
        $actionName = $this->_app->getActionName();
        $this->assertEquals($exp_controller, $controllerName);
        $this->assertEquals($exp_action, $actionName);

        $result = ob_get_clean();
        $this->assertEquals($exp_response, $result);
    }

    public function provider()
    {
        return [
            ['/', 'index', 'index', 'INDEX'],
            ['/test/abc', 'test', 'abc', 'TEST']
        ];
    }

    public function testTestController()
    {
        $_SERVER = [
            "REQUEST_URI" => "/test/abc",
        ];

        ob_start();
        $this->_app->run(__DIR__ . '/config.ini');
        $controllerName = $this->_app->getControllerName();
        $actionName = $this->_app->getActionName();
        $this->assertEquals('test', $controllerName);
        $this->assertEquals('abc', $actionName);

        $result = ob_get_clean();
        $this->assertEquals('TEST', $result);
    }

    /**
     * @expectedException Exception
     */
    public function testErrorController()
    {
        $_SERVER['REQUEST_URI'] = '/index/error';
        $this->_app->run(__DIR__ . '/config.ini');
    }


}