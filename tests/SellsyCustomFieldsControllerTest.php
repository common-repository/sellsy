<?php

use PHPUnit\Framework\TestCase;

define( 'ABSPATH', dirname( dirname( __FILE__ ) ) . '/' );

define('PLUGIN_NOM_LANG', 'sellsy');
if (!function_exists('__')) {
    function __($str) { return $str; }
}

class SellsyCustomFieldsControllerTest extends TestCase
{
    /**
     * @group cfSimpleText
     */
    public function testCheckSimpleTextWithMinMaxEmpty()
    {
        // INIT
        $name       = "Test";
        $default    = "Lorem ipsum";
        $min        = "";
        $max        = "";
        $f_value      = "abc";
        $f_value_size = strlen($f_value);
        $required     = "not required";



        // 1
        $a = new \com\sellsy\sellsy\controllers\SellsyCustomFieldsController();
        $b = $a->checkSimpleText([
            "api" => [
                "label"     => $name,
                "default"   => $default,
                "min"       => $min,
                "max"       => $max,
                "required"  => $required
            ],
            "form" => [
                "value" => ""
            ]
        ]);
        $this->assertSame(["success", $default], $b);




        // 2
        $a = new \com\sellsy\sellsy\controllers\SellsyCustomFieldsController();
        $b = $a->checkSimpleText([
            "api" => [
                "label"     => $name,
                "default"   => $default,
                "min"       => $min,
                "max"       => $max,
                "required"  => $required
            ],
            "form" => [
                "value" => "abc"
            ]
        ]);
        $this->assertSame(["success", $f_value], $b);
    }

    /**
     * @group cfSimpleText
     */
    public function testCheckSimpleTextWithValueOk()
    {
        // INIT
        $name       = "Test";
        $default    = "Lorem ipsum";
        $min        = 5;
        $max        = 20;
        $f_value      = "abcabc";
        $f_value_size = strlen($f_value);
        $required     = "required";

        $a = new \com\sellsy\sellsy\controllers\SellsyCustomFieldsController();
        $b = $a->checkSimpleText([
            "api" => [
                "label"     => $name,
                "default"   => $default,
                "min"       => $min,
                "max"       => $max,
                "required"  => $required
            ],
            "form" => [
                "value" => $f_value
            ]
        ]);
        $this->assertGreaterThanOrEqual($min, $f_value_size);
        $this->assertLessThanOrEqual($max, $f_value_size);
        $this->assertSame(["success", $f_value], $b);
    }

    /**
     * @group cfSimpleText
     */
    public function testCheckSimpleTextWithValueTooSmall()
    {
        // INIT
        //$name       = "Test";
        $default    = "Lorem ipsum";
        $min        = 5;
        $max        = 20;
        $f_value      = "abc";
        $f_value_size = strlen($f_value);
        $required     = "required";

        $a = new \com\sellsy\sellsy\controllers\SellsyCustomFieldsController();
        $b = $a->checkSimpleText([
            "api" => [
                "label"     => 'test',
                "default"   => $default,
                "min"       => $min,
                "max"       => $max,
                "required"  => $required
            ],
            "form" => [
                "value" => $f_value
            ]
        ]);
        $this->assertLessThanOrEqual($min, $f_value_size);
        $this->assertSame(["error", "Your value is too small. Min : 5"], $b);
    }

    /**
     * @group cfSimpleText
     */
    public function testCheckSimpleTextWithValueTooBig()
    {
        // INIT
        $name       = "Test";
        $default    = "Lorem ipsum";
        $min        = 5;
        $max        = 20;
        $f_value      = "012345678901234567890123456789";
        $f_value_size = strlen($f_value);

        $a = new \com\sellsy\sellsy\controllers\SellsyCustomFieldsController();
        $b = $a->checkSimpleText([
            "api" => [
                "label"     => $name,
                "default"   => $default,
                "min"       => $min,
                "max"       => $max,
                "required"  => false,
            ],
            "form" => [
                "value" => $f_value
            ]
        ]);
        $this->assertGreaterThanOrEqual($max, $f_value_size);
        $this->assertSame(["error", "Your value is too big. Max : 20"], $b);
    }





    /**
     * @group cfSimpleText
     */
    public function testCheckSimpleTextWithValueBigOkAndMinEmpty()
    {
        // INIT
        $name       = "Test";
        $default    = "Lorem ipsum";
        $min        = '';
        $max        = 20;
        $f_value      = "0123456789";
        $f_value_size = strlen($f_value);

        $a = new \com\sellsy\sellsy\controllers\SellsyCustomFieldsController();
        $b = $a->checkSimpleText([
            "api" => [
                "label"     => $name,
                "default"   => $default,
                "min"       => $min,
                "max"       => $max,
                "required"  => false,
            ],
            "form" => [
                "value" => $f_value
            ]
        ]);

        $this->assertEmpty($min);
        //$this->assertLessThanOrEqual(20, 10);
        $this->assertLessThanOrEqual($max, $f_value_size);
        $this->assertSame(["success", $f_value], $b);
    }

    /**
     * @group cfSimpleText
     */
    public function testCheckSimpleTextWithValueTooBigAndMinEmpty()
    {
        // INIT
        $name       = "Test";
        $default    = "Lorem ipsum";
        $min        = '';
        $max        = 20;
        $f_value      = "012345678901234567890123456789";
        $f_value_size = strlen($f_value);

        $a = new \com\sellsy\sellsy\controllers\SellsyCustomFieldsController();
        $b = $a->checkSimpleText([
            "api" => [
                "label"     => $name,
                "default"   => $default,
                "min"       => $min,
                "max"       => $max,
                "required"  => false,
            ],
            "form" => [
                "value" => $f_value
            ]
        ]);

        $this->assertEmpty($min);
        //$this->assertGreaterThanOrEqual(20, 30);
        $this->assertGreaterThanOrEqual($max, $f_value_size);
        $this->assertSame(["error", "Your value is too big. Max : 20"], $b);
    }
}
