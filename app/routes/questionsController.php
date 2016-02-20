<?php

$app->get("/promptr/{id}/question", function($id) use ($app){
    $promptr = Promptr::find($id);
    $trending_index = $promptr->getTrending();
    $promptr->updateTrending(++$trending_index);
    Question::deleteTempQuestions();
    $shuffle = $_GET['shuffle'];
    $questions = $promptr->getQuestions();
    if($shuffle == "true"){
        shuffle($questions);
    }
    foreach($questions as $question){
        $question->saveTempQuestion();
    }
    if ($questions == []){
        return $app['twig']->render("promptr.html.twig", array('promptr' => $promptr, 'questions' => $questions, 'topic' => Topic::find($promptr->getTopicId())));
    }else{
    $temp_questions = Question::getTempQuestions();
    $first_question = $temp_questions[0];
    return $app['twig']->render('question.html.twig', array(
                                'question' => $first_question,
                                'promptr' => $promptr));
    }
});

// QUESTION.HTML.TWIG -- needs fixed -- if a question has been deleted, id # is skipped and
// end_flag = true. Need to somehow loop through just the questions in promptr->getQuestions
// the following pages of promptr run -- adding more answers
$app->post("/promptr/{id}/question/{quid}", function($id, $quid) use ($app){
    $promptr = Promptr::find($id);
    $end_flag = false;
    $answer_field = $_POST['answer'];
    $new_answer = new Answer($answer_field, $quid);
    $new_answer->save();
    ++$quid;
    $question = Question::findTempById($quid);
    $questions = Question::getTempQuestions();
    if($question != null){
        $question->addAnswer($new_answer->getId());
        $last_question = end($questions);
        if ($question == $last_question)
        {
            $end_flag = true;
        }
    }
    return $app['twig']->render('question.html.twig', array(
                                'question' => $question,
                                'end' => $end_flag,
                                'promptr' => $promptr, 'questions' => $questions));
});

// DISPLAY.HTML.TWIG
// DISPLAY FINISHED answers to promptr run


//will show the concatted together display of the list
$app->get("/promptr/{id}/display", function($id) use ($app){
    $promptr = Promptr::find($id);
    $questions = Question::getTempQuestions();
    Question::deleteTempQuestions();
    return $app['twig']->render('display.html.twig',array(
                                'promptr' => $promptr,
                                'questions' => $questions));
});



 ?>
