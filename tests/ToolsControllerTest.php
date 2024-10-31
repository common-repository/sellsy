<?php

use PHPUnit\Framework\TestCase;

define( 'ABSPATH', dirname( dirname( __FILE__ ) ) . '/' );

class ToolsControllerTest extends TestCase
{
    /**
     * @covers \com\sellsy\sellsy\controllers\ToolsController::isJson
     * @group tools
     */
    public function testIsJsonTrue()
    {
        $a = '{ "name":"John", "age":30, "car":null }';
        $this->assertTrue(\com\sellsy\sellsy\controllers\ToolsController::isJson($a));
    }

    /**
     * @covers \com\sellsy\sellsy\controllers\ToolsController::isJson
     * @group tools
     */
    public function testIsJsonFalse()
    {
        $a = "lorem ipsum";
        $this->assertFalse(\com\sellsy\sellsy\controllers\ToolsController::isJson($a));
    }

}