<?php

class Answer {
    private $answer;
    private $question_id;
    private $id;

    function __construct($ans, $quest_id, $id = null)
    {
        $this->answer = $ans;
        $this->question_id = (int) $quest_id;
        $this->id = $id;
    }

    function getAnswer()
    {
        return $this->answer;
    }

    function setAnswer($new_answer)
    {
        $this->answer = $new_answer;
    }

    function setQuestionId($new_question_id)
    {
        $this->question_id = $new_question_id;
    }

    function getQuestionId()
    {
        return $this->question_id;
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
        $GLOBALS['DB']->exec("INSERT INTO answers (answer, question_id) VALUES ('{$this->getAnswer()}', {$this->getQuestionId()});");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM answers WHERE id = {$this->getId()};");
    }

    function update($new_answer)
    {
        $GLOBALS['DB']->exec("UPDATE answers (answer) WHERE id = {$this->getId()} VALUES ('{$new_answer}');");
        $this->answer = $new_answer;
    }

    static function findById($search_id)
    {
        $found_answer = null;
        $returned_answers = Answer::getAll();
        foreach($returned_answers as $answer){
            $id = $answer->getId();
            if($search_id == $id){
                $found_answer = $answer;
            }
        }
        return $found_answer;
    }


    static function getAll()
    {
        $returned_answers = $GLOBALS['DB']->query("SELECT * FROM answers;");
        $answers = [];
        foreach($returned_answers as $answer){
            $field = $answer['answer'];
            $quest_id = $answer['question_id'];
            $id = $answer['id'];
            $new_answer = new Answer($field, $quest_id, $id);
            array_push($answers, $new_answer);
        }
        return $answers;
    }

    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM answers;");
    }


}


 ?>
