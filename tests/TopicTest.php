<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Topic.php";
    require_once "src/Promptr.php";

    $server = 'mysql:host=localhost;dbname=promptr_app_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class TopicTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Topic::deleteAll();
            Promptr::deleteAll();
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

        function test_delete()
        {
            $name = "Writing";
            $test_topic = new Topic($name);
            $test_topic->save();

            $name2 = "Interview";
            $test_topic2 = new Topic($name2);
            $test_topic2->save();

            $test_topic->delete();
            $result = Topic::getAll();

            $this->assertEquals([$test_topic2], $result);
        }

        function test_update()
        {
            $name = "Writing";
            $test_topic = new Topic($name);
            $test_topic->save();

            $name2 = "Interview";

            $test_topic->update($name2);
            $topics = Topic::getAll();
            $result = $topics[0]->getName();

            $this->assertEquals($name2, $result);
        }

        function test_getPromptrs()
        {
            $name = "Writing";
            $test_topic = new Topic($name);
            $test_topic->save();

            $name2 = "Interview";
            $test_topic2 = new Topic($name2);
            $test_topic2->save();

            $promptr_name = "mikes 5 tips";
            $topic_id = $test_topic->getId();
            $test_promptr = new Promptr($promptr_name, $topic_id);
            $test_promptr->save();

            $promptr_name2 = "ians 5 tips";
            $topic_id = $test_topic->getId();
            $test_promptr2 = new Promptr($promptr_name2, $topic_id);
            $test_promptr2->save();

            $result = $test_topic->getPromptrs();

            $this->assertEquals([$test_promptr, $test_promptr2], $result);
        }
    }
?>
