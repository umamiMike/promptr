
<?php
    require_once "src/Question.php";
    class myClassTest extends PHPUnit_Framework_TestCase
    {
        protected function tear_down()
        {
            Question::deleteAll();
        }
        
        function test_save()
        {
            $test_save = new save;

            $result = $test_myClass->myFunction();
            $this->assertEquals("data", $result);
        }


      }

      ?>
