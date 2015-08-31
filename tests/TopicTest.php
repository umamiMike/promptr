<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Topic.php";

    $server = 'mysql:host=localhost;dbname=promptr_app_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class TopicTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Topic::deleteAll();
        }

        function test_getName()
        {
            $name = "Writing";
            $test_topic = new Topic($name);

            $result = $test_topic->getName();

            $this->assertEquals($name, $result);
        }

        function test_getId()
        {
            $name = "Writing";
            $id = 111;
            $test_topic = new Topic($name, $id);

            $result = $test_topic->getId();

            $this->assertEquals($id, $result);
        }

        function test_setName()
        {
            $name = "Writing";
            $test_topic = new Topic($name);

            $name2 = "Interviews";
            $test_topic->setName($name2);
            $result = $test_topic->getName();

            $this->assertEquals($name2, $result);
        }

        function test_save()
        {
            $name = "Writing";
            $test_topic = new Topic($name);
            $test_topic->save();

            $result = Topic::getAll();

            $this->assertEquals($test_topic, $result[0]);
        }

        function test_getAll()
        {
            $name = "Writing";
            $test_topic = new Topic($name);
            $test_topic->save();

            $name2 = "Interview";
            $test_topic2 = new Topic($name2);
            $test_topic2->save();

            $result = Topic::getAll();

            $this->assertEquals([$test_topic2, $test_topic], $result);
        }

        function test_deleteAll()
        {
            $name = "Writing";
            $test_topic = new Topic($name);
            $test_topic->save();

            $name2 = "Interview";
            $test_topic2 = new Topic($name2);
            $test_topic2->save();

            Topic::deleteAll();
            $result = Topic::getAll();

            $this->assertEquals([], $result);
        }

        function test_find()
        {
            $name = "Writing";
            $test_topic = new Topic($name);
            $test_topic->save();

            $name2 = "Interview";
            $test_topic2 = new Topic($name2);
            $test_topic2->save();

            $result = Topic::find($test_topic->getId());

            $this->assertEquals($test_topic, $result);
        }
    }
?>
