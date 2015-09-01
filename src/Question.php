<?php

class Question {
    private $question;
    private $description;
    private $id;

    function __construct($quest, $desc, $id = null)
    {
        $this->question = $quest;
        $this->description = $desc;
        $this->id = $id;
    }

    function getQuestion()
    {
        return $this->question;
    }

    function setQuestion($new_quest)
    {
        $this->question = $new_quest;
    }

    function setDescription($new_description)
    {
        $this->description = (string) $new_description;
    }

    function getDescription()
    {
        return $this->description;
    }

    function setId($new_id)
    {
        $this->id = $new_id;
    }

    function getId()
    {
        return $this->id;
    }

    function save()
    {
        $GLOBALS['DB']->exec("INSERT INTO questions (question, description) VALUES ('{$this->getQuestion()}', '{$this->getDescription()}');");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM questions WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM answers WHERE question_id = {$this->getId()};");

    }

    function update($new_question, $new_description)
    {
        $GLOBALS['DB']->exec("UPDATE questions (question, description) WHERE id = {$this->getId()} VALUES ('{$new_question}', '{$new_description}');");
        $this->question = $new_question;
        $this->description = $new_description;
    }

    function getAnswers()
    {
        $answers = [];
        $returned_answers = $GLOBALS['DB']->query("SELECT * FROM answers WHERE question_id = {$this->getId()};");
        foreach($returned_answers as $answer){
            $field = $answer['answer'];
            $question_id = $answer['question_id'];
            $id = $answer['id'];
            $new_answer = new Answer($field, $question_id, $id);
            array_push($answers, $new_answer);
        }
        return $answers;
    }

    function addAnswer($id)
    {
        $GLOBALS['DB']->exec("INSERT INTO answers (question, description, id) WHERE id = {$id} VALUES ('{$this->getQuestion()}', '{$this->getDescription()}', {$this->getId()});");
    }

    static function findById($search_id)
    {
        $found_question = null;
        $returned_questions = Question::getAll();
        foreach($returned_questions as $question){
            $id = $question->getId();
            if($search_id == $id){
                $found_question = $question;
            }
        }
        return $found_question;
    }

    static function getAll()
    {
        $returned_questions = $GLOBALS['DB']->query("SELECT * FROM questions;");
        //var_dump($returned_questions);
        $questions = [];
        foreach($returned_questions as $question){
            $field = $question['question'];
            $description = $question['description'];
            $id = $question['id'];
            $new_question = new Question($field, $description, $id);
            array_push($questions, $new_question);
        }
        return $questions;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM questions;");
    }


}


 ?>
