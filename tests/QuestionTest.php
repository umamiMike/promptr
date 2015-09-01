<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    //All tests passed

    require_once "src/Question.php";

    $server = 'mysql:host=localhost;dbname=promptr_app_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);


    class QuestionTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Question::deleteAll();
        }

        function test_save()
        {
            //Arrange
            $test_field = "What is their name?";
            $test_description = "What you want to call your character.";
            $test_question = new Question($test_field, $test_description);

            //Act
            $test_question->save();
            //var_dump($test_question);

            //Assert
            $result = Question::getAll();
            //var_dump($result);
            $this->assertEquals($test_question, $result[0]);
        }

        function test_getAll()
        {
            //Arrange
            $test_field = "What is their name?";
            $test_description = "What you want to call your character.";
            $test_question = new Question($test_field, $test_description);
            $test_question->save();

            $test_field2= "What is their profession?";
            $test_description2 = "What you want your character to do.";
            $test_question2 = new Question($test_field2, $test_description2);
            $test_question2->save();

            //Act
            $result = Question::getAll();

            //Assert
            $this->assertEquals([$test_question, $test_question2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $test_field = "What is their name?";
            $test_description = "What you want to call your character.";
            $test_question = new Question($test_field, $test_description);
            $test_question->save();

            $test_field2= "What is their profession?";
            $test_description2 = "What you want your character to do.";
            $test_question2 = new Question($test_field2, $test_description2);
            $test_question2->save();

            //Act
            Question::deleteAll();

            //Assert
            $result = Question::getAll();
            $this->assertEquals([], $result);
        }

        function test_findById()
        {
            //Arrange
            $test_field = "What is their name?";
            $test_description = "What you want to call your character.";
            $test_question = new Question($test_field, $test_description);
            $test_question->save();

            $test_field2= "What is their profession?";
            $test_description2 = "What you want your character to do.";
            $test_question2 = new Question($test_field2, $test_description2);
            $test_question2->save();

            //Act
            $result = Question::findById($test_question->getId());

            //Assert
            $this->assertEquals($test_question, $result);

        }


      }

      ?>
