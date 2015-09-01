
<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    //All tests passed

    require_once 'src/Answer.php';

    $server = 'mysql:host=localhost;dbname=promptr_app_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AnswerTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Answer::deleteAll();
        }
        function test_save()
        {
            //Arrange
            $test_field = "Joe Save";
            $test_quest_id = 1;
            $test_answer = new Answer($test_field, $test_quest_id);

            //Act
            $test_answer->save();

            //Assert
            $result = Answer::getAll();
            //var_dump($result);
            $this->assertEquals($test_answer, $result[0]);
        }

        function test_getAll()
        {
            //Arrange
            $test_field = "Joe GetAll";
            $test_quest_id = 1;
            $test_answer = new Answer($test_field, $test_quest_id);
            $test_answer->save();

            $test_field2 = "Red GetAll";
            $test_quest_id2 = 2;
            $test_answer2 = new Answer($test_field2, $test_quest_id2);
            $test_answer2->save();

            //Act
            $result = Answer::getAll();

            //Assert
            $this->assertEquals([$test_answer, $test_answer2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $test_field = "Joe Delete";
            $test_quest_id = 1;
            $test_answer = new Answer($test_field, $test_quest_id);
            $test_answer->save();

            $test_field2 = "Red Delete";
            $test_quest_id2 = 2;
            $test_answer2 = new Answer($test_field2, $test_quest_id2);
            $test_answer2->save();

            //Act
            Answer::deleteAll();

            //Assert
            $result = Answer::getAll();
            $this->assertEquals([], $result);
        }


      }

      ?>
