<?php

    class Topic
    {
        private $name;
        private $id;

        function __construct($name, $id=null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function save()
        {
            $temp_name = str_replace(["'"], "''", $this->getName());
            $GLOBALS['DB']->exec("INSERT INTO topics (name) VALUES ('{$temp_name}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE topics SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->name = $new_name;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM topics WHERE id = {$this->getId()};");
        }

        static function getAll()
        {
            $returned_topics = $GLOBALS['DB']->query("SELECT * FROM topics ORDER BY name;");
            $topics = array();
            foreach($returned_topics as $topic){
                $name = $topic['name'];
                $id = $topic['id'];
                $new_topic = new Topic($name, $id);
                array_push($topics, $new_topic);
            }
            return $topics;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM topics;");
        }

        static function find($search_id)
        {
            $found_topic = null;
            $topics = Topic::getAll();
            foreach($topics as $topic){
                $id = $topic->getId();
                if($search_id == $id){
                    $found_topic = $topic;
                }
            }
            return $found_topic;
        }

        function getPromptrs()
        {
            $returned_promptrs = $GLOBALS['DB']->query("SELECT * FROM promptrs WHERE topic_id = {$this->getId()};");
            $promptrs = array();
            foreach($returned_promptrs as $promptr){
                $name = $promptr['name'];
                $topic_id = $promptr['topic_id'];
                $trending = $promptr['trending'];
                $example = $promptr['example'];
                $id = $promptr['id'];
                $new_promptr = new Promptr($name, $topic_id, $trending, $example, $id);
                array_push($promptrs, $new_promptr);
            }
            return $promptrs;
        }

        function addPromptr($promptr)
        {
            $GLOBALS['DB']->exec("INSERT INTO promptrs (name, topic_id, trending, example) VALUES ('{$promptr->getName()}',{$promptr->getTopicId()},{$promptr->getTrending()}, {$promptr->getExample()});");
        }


    }


 ?>
