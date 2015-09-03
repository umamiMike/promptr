<?php

    class Promptr
    {

        private $name;
        private $topic_id;
        private $trending;//not using yet but just you wait!!!
        private $example;//not using yet but just you wait!!!
        private $id;

        function __construct($name, $topic_id= 0, $trending = 0, $example = 0, $id = null)

        {
            $this->name = $name;
            $this->topic_id  = (int) $topic_id;
            $this->trending = (int) $trending;
            $this->example = (int) $example;
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
            $temp_name = str_replace(["'"], "''", $this->getName());

            $GLOBALS['DB']->exec("INSERT INTO promptrs (name,topic_id,trending,example)
                                    VALUES ('{$temp_name}',
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

        function updateTrending($new_index)
        {
            $GLOBALS['DB']->exec("UPDATE promptrs SET trending = '{$new_index}' WHERE id = {$this->getId()};");
            $this->setTrending($new_index);
        }

        static function getTrendingPromptrs()
        {
            $top = [];
            $query = $GLOBALS['DB']->query("SELECT * FROM promptrs ORDER BY trending DESC LIMIT 5;");
            foreach($query as $promptr){
                $name = $promptr['name'];
                $topic_id = $promptr['topic_id'];
                $trending = $promptr['trending'];
                $example = $promptr['example'];
                $id = $promptr['id'];
                $new_P = new Promptr($name, $topic_id, $trending, $example, $id);
                array_push($top, $new_P);
            }
            return $top;
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

        function addQuestion($question)
        {
            $GLOBALS['DB']->exec("INSERT INTO promptrs_questions (question_id, promptr_id) VALUES ({$question->getId()}, {$this->getId()});");
        }

        function getQuestions()
        {
            $query = $GLOBALS['DB']->query("SELECT questions.* FROM
                promptrs JOIN promptrs_questions ON (promptrs.id = promptrs_questions.promptr_id)
                         JOIN questions ON (promptrs_questions.question_id = questions.id)
                         WHERE promptrs.id = {$this->getId()};");
            $returned_questions = $query->fetchAll(PDO::FETCH_ASSOC);
            $questions = array();
            foreach($returned_questions as $question){
                $quest = $question['question'];
                $description = $question['description'];
                $id = $question['id'];
                $new_question = new Question($quest, $description, $id);
                array_push($questions, $new_question);
            }
            return $questions;
        }



    }

 ?>
