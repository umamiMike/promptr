
<?php
    require_once "src/myClass.php";
    class myClassTest extends PHPUnit_Framework_TestCase
    {
        function test_myClass()
        {
            $test_myClass = new myClass;

            $result = $test_myClass->myFunction();
            $this->assertEquals("data", $result);
        }


      }

      ?>
