<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Promptr.php";
    require_once "src/Topic.php";

    $server = 'mysql:host=localhost;dbname=promptr_app_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PromptrTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
           Topic::deleteAll();
           Promptr::deleteAll();
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

        // function testDelete()
        // {
        //
        // }
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

        // function testUpdate()
        // {
        //
        // }

    }
?>
