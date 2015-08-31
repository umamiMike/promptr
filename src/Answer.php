<?php

class Answer {
    private $question_id;
    private $answer;
    private $id;

    function __construct($ans, $quest_id, $id = null)
    {
        $this->question_id = $quest_id;
        $this->answer = $ans;
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

    function getAll()
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
        
    }


}


 ?>
