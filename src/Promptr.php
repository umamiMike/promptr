<?php

    class Promptr
    {

        private $name;
        private $topic_id;
        private $trending;//not using yet but just you wait!!!
        private $example;//not using yet but just you wait!!!
        private $id;

        function __construct($name,$topic_id=0,$trending = 0,$example = 0, $id = null)

        {
            $this->name = $name;
            $this->topic_id  = $topic_id;
            $this->trending = $trending;
            $this->example = $example;
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

        function getTopicId()
        {
            return $this->topic_id;
        }

        function getTrending()
        {
            return $this->trending;
        }

        function getExample()
        {
            return $this->example;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function setTopicId($new_topic_id)
        {
            $this->topic_id = $new_topic_id;
        }

        function setTrending($new_trend)
        {
            $this->trending = $new_trend;
        }

        function setExample($new_example)
        {
            $this->example = $new_example;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO promptrs (name,topic_id,trending,example)
                                    VALUES ('{$this->getName()}',
                                    {$this->getTopicId()},
                                    {$this->getTrending()},
                                    {$this->getExample()});");

            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM promptrs WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM promptrs_questions WHERE promptr_id = {$this->getId()};");
        }

        function updateName($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE promptrs SET name = '{$new_name}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
        }

        static function getAll()
        {
            $returned_promptrs = $GLOBALS['DB']->query("SELECT * FROM promptrs;");
            $promptrs = array();
            foreach($returned_promptrs as $promptr){

                $name = $promptr['name'];
                $id = $promptr['id'];
                $topic_id = $promptr['topic_id'];
                $example = $promptr['example'];
                $trending = $promptr['trending'];

                $new_promptr = new Promptr($name, $topic_id, $trending, $example, $id);
                array_push($promptrs,$new_promptr);

            }
            return $promptrs;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM promptrs;");
        }

        static function find($search_id)
        {
            $found_promptr = null;
            $promptrs = Promptr::getAll();
            foreach($promptrs as $promptr){
                $id = $promptr->getId();
                if($search_id == $id){
                    $found_promptr = $promptr;
                }
            }
            return $found_promptr;
        }




    }

 ?>
