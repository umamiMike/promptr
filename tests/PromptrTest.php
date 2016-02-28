<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "model/Promptr.php";
    require_once "model/Topic.php";
    require_once "model/Question.php";
    require_once 'testConnect.php';


    class PromptrTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
           Topic::deleteAll();
           Promptr::deleteAll();
           Question::deleteAll();
        }


        function test_getId()
        {
            $promptr1_name = "mikes 5 tips";
            $promptr1_id = 200;
            $test_promptr = new Promptr($promptr1_name);
            $test_promptr->save();

            $result = $test_promptr->getId();

            $this->assertEquals(is_numeric($result),true);

        }

        function test_getName()
        {
            $promptr1_name = "mikes 5 tips";
            $promptr1_id = 200;
            $test_promptr = new Promptr($promptr1_name);
            $test_promptr->save();

            $result = $test_promptr->getName();

            $this->assertEquals($promptr1_name,$result);

        }

        function test_getTopicId()
        {
            $promptr1_name = "mikes 5 tips";
            $topic_id  = 20;
            $promptr1_id = 200;
            $test_promptr = new Promptr($promptr1_name,$topic_id);
            $test_promptr->save();

            $result = $test_promptr->getTopicId();

            $this->assertEquals($topic_id,$result);


        }

        function testSave()
        {
            $promptr1_name = "mikes 5 tips";
            $topic_id  = 20;
            $promptr1_id = 200;
            $test_promptr = new Promptr($promptr1_name,$topic_id);
            $test_promptr->save();

            $result = Promptr::getAll();
            //var_dump($result);
            $this->assertEquals([$test_promptr],$result);


        }

        function testDelete()
        {
          $promptr1_name = "mikes 5 tips";
          $topic_id  = 20;
          $promptr1_id = 200;
          $test_promptr = new Promptr($promptr1_name,$topic_id);
          $test_promptr->save();
          $test_promptr->delete();

          $result = Promptr::getAll();
          //var_dump($result);
          $this->assertEquals([],$result);

        }
        //
        function testDeleteAll()
        {
            $promptr1_name = "mikes 5 tips";
            $topic_id  = 20;
            $test_promptr = new Promptr($promptr1_name,$topic_id);
            $test_promptr->save();

            $promptr2_name = "ians 5 tips";
            $topic_id = 20;
            $test_promptr2 = new Promptr($promptr2_name, $topic_id);
            $test_promptr2->save();

            Promptr::deleteAll();
            $result = Promptr::getAll();

            $this->assertEquals([], $result);
        }

        function testGetAll()
        {
            $promptr1_name = "mikes 5 tips";
            $topic_id  = 20;
            $test_promptr = new Promptr($promptr1_name,$topic_id);
            $test_promptr->save();

            $promptr2_name = "ians 5 tips";
            $topic_id = 20;
            $test_promptr2 = new Promptr($promptr2_name, $topic_id);
            $test_promptr2->save();

            $result = Promptr::getAll();

            $this->assertEquals([$test_promptr, $test_promptr2], $result);
        }

        function testFind()
        {
            $promptr1_name = "mikes 5 tips";
            $topic_id  = 20;
            $test_promptr = new Promptr($promptr1_name,$topic_id);
            $test_promptr->save();

            $promptr2_name = "ians 5 tips";
            $topic_id = 20;
            $test_promptr2 = new Promptr($promptr2_name, $topic_id);
            $test_promptr2->save();

            $result = Promptr::find($test_promptr->getId());

            $this->assertEquals($test_promptr, $result);
        }

        function testUpdateName()
        {
          $promptr1_name = "mikes 5 tips";
          $topic_id  = 20;
          $promptr1_id = 200;
          $test_promptr = new Promptr($promptr1_name,$topic_id);

          $test_promptr->save();

          $promptr2_name = "ians 5 tips";
          $test_promptr->updateName($promptr2_name);
          $promptrs = Promptr::getAll();
          $result = $promptrs[0]->getName();

          $this->assertEquals($promptr2_name, $result);
        }

        function test_addQuestion()
        {
            $name = "mikes 5 top interview questions";
            $test_promptr = new Promptr($name);
            $test_promptr->save();

            $question = "What is your biggest weakness punk?";
            $description = "This question goes straight for the jugular";
            $test_question = new Question($question, $description);
            $test_question->save();
            $test_promptr->addQuestion($test_question);

            $result = Question::getAll();
            $end_question = new Question($question, $description, $result[0]->getId());

            $this->assertEquals([$end_question], $result);
        }

        function test_getQuestions()
        {
            $name = "mikes 5 top interview questions";
            $test_promptr = new Promptr($name);
            $test_promptr->save();

            $question = "What is your biggest weakness punk?";
            $description = "This question goes straight for the jugular";
            $test_question = new Question($question, $description);
            $test_question->save();
            $test_promptr->addQuestion($test_question);

            $question2 = "Who are you really?";
            $description2 = "I mean really really";
            $test_question2 = new Question($question2, $description2);
            $test_question2->save();
            $test_promptr->addQuestion($test_question2);

            $result = Question::getAll();
            $end_question = new Question($question, $description, $result[0]->getId());
            $end_question2= new Question($question2, $description2, $result[1]->getId());

            $this->assertEquals([$end_question,$end_question2], $result);
        }
        // this test fails...returns top five trending promptrs, but the ids don't match up.
        function test_getTrendingPromptrs()
        {
            $name = "mikes 5 top interview questions";
            $topic_id = 5;
            $trending_index = 3;
            $test_promptr = new Promptr($name, $topic_id, $trending_index);
            $test_promptr->save();

            $name2 = "mikes 50 top interview questions";
            $trending_index2 = 4;
            $test_promptr2 = new Promptr($name2, $topic_id, $trending_index2);
            $test_promptr2->save();

            $name3 = "mikes 500 top interview questions";
            $trending_index3 = 5;
            $test_promptr3 = new Promptr($name3, $topic_id, $trending_index3);
            $test_promptr3->save();

            $name4 = "mikes 5000 top interview questions";
            $trending_index4 = 6;
            $test_promptr4 = new Promptr($name4, $topic_id, $trending_index4);
            $test_promptr4->save();

            $name5 = "mikes 50000 top interview questions";
            $trending_index5 = 7;
            $test_promptr5 = new Promptr($name5, $topic_id, $trending_index5);
            $test_promptr5->save();

            $name6 = "mikes 500000 top interview questions";
            $trending_index6 = 8;
            $test_promptr6 = new Promptr($name6, $topic_id, $trending_index6);
            $test_promptr6->save();

            //Act
            $result = Promptr::getTrendingPromptrs();

            //Assert
            $this->assertEquals([$test_promptr6, $test_promptr5, $test_promptr4, $test_promptr3, $test_promptr2], $result);
        }

    }
?>
