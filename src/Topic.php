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
            $GLOBALS['DB']->exec("INSERT INTO topics (name) VALUES ('{$this->getName()}');");
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

    }


 ?>
