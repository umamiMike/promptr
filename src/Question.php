<?php

class Question {
    private $question;
    private $description;
    private $id;

    function __construct($quest, $desc, $id = null)
    {
        $this->$question = $quest;
        $this->$description = $desc;
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
        
    }


}


 ?>
