<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Question.php";
    require_once __DIR__."/../src/Answer.php";
    require_once __DIR__."/../src/Topic.php";
    require_once __DIR__."/../src/Promptr.php";

    $app = new Silex\Application();

    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=promptr_app';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
      'twig.path' => __DIR__.'/../views'));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();


    $app->get("/admin", function() use ($app){
        $topics = Topic::getAll();
        $promptrs = Promptr::getAll();
        return $app['twig']->render('promptr-admin.twig', array('topics' => $topics, 'promptrs' => $promptrs));
    });

    $app->get("/", function() use ($app){
        $topics = Topic::getAll();
        $promptrs = Promptr::getAll();
        return $app['twig']->render('index.html.twig', array('topics' => $topics, 'promptrs' => $promptrs));
    });




    $app->get("/promptr/{id}", function($id) use ($app){
        $promptr = Promptr::find($id);
        return $app['twig']->render('promptr.html.twig', array ('promptr' => $promptr,'questions' => $promptr->getQuestions()));

    });
    // will populate a promptr with questions/descriptions
    $app->post("/promptr/{id}", function($id) use ($app){
        $promptr = Promptr::find($id);
        $new_question_text = $_POST['question'];
        $new_description = $_POST['description'];
        $new_question = new Question($new_question_text, $new_description);
        $new_question->save();
        $promptr->addQuestion($new_question->getId());
        return $app['twig']->render('promptr.html.twig', array (
                                    'promptr' => $promptr,
                                    'questions' => $promptr->getQuestions()));

    });

    // run through a promptr

    $app->post("/promptrs", function() use ($app){
        $promptr_name = $_POST['promptr_name'];
        $new_promptr = new Promptr($promptr_name);
        $new_promptr->save();
        return $app['twig']->render('promptrs.html.twig', array (
                                    'promptrs' => Promptr::getAll()));
    });

    $app->get("/deleteAllPromptrs", function() use ($app){
        Promptr::deleteAll();
        return $app['twig']->render('promptrs.html.twig', array ('promptrs' => Promptr::getAll()));
    });


    $app->get("/topic/{id}", function($id) use ($app){
        $topic = Topic::find($id);
        $promptrs = $topic->getPromptrs();
        return $app['twig']->render("topic.html.twig", array('topic' => $topic, 'promptrs' => $promptrs));
    });

    $app->get("promptr/{id}", function($id) use ($app){
        $promptr = Promptr::find($id);
        $questions = $promptr->getQuestions();
        return $app['twig']->render("promptr.html.twig", array('promptr' => $promptr, 'questions' => $questions));
    });

    // first page of promptr run - displays first question in promptr
    // question array
    $app->get("/promptr/{id}/question", function($id) use ($app){
        $promptr = Promptr::find($id);
        $first_question = $promptr->getQuestions()[0];
        return $app['twig']->render('question.html.twig', array(
                                    'question' => $first_question,
                                    'promptr' => $promptr));

    });

    // following pages of promptr run
    $app->post("/promptr/{id}/question/{quid}", function($id, $quid) use ($app){
        $end_flag = false;
        ++$quid;
        $answer_field = $_POST['answer'];
        $new_answer = new Answer($answer_field, $quid);
        $new_answer->save();
        $question = Question::findById($quid);
        $promptr = Promptr::find($id);
        if($question != null){

            $question->addAnswer($new_answer->getId());

            $questions = $promptr->getQuestions();
            $last_question = end($questions);


            if ($question == $last_question)
            {
                $end_flag = true;
            }
        }

        return $app['twig']->render('question.html.twig', array(
                                    'question' => $question,
                                    'end' => $end_flag,
                                    'promptr' => $promptr));
    });




    return $app;
?>
