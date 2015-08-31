
<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    //All tests passed

    require_once 'src/Answer.php';
    
    $server = 'mysql:host=localhost;dbname=promptr_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class myClassTest extends PHPUnit_Framework_TestCase
    {
        protected function tear_down()
        {
            Answer::deleteAll();
        }
        function test_save()
        {
            //Arrange
            $test_field = "Joe";
            $test_quest_id = 1;
            $test_answer = new Answer($test_field, $test_quest_id);

            //Act
            $test_answer->save();

            //Assert
            $result = $test_answer->getAll();
            $this->assertEquals($test_answer, $result[0]);
        }


      }

      ?>
