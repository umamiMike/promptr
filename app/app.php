<?php

require_once __DIR__."/../vendor/autoload.php";
//adding the model files
foreach (glob(__DIR__."/../model/*.php") as $filename)
{
    require_once $filename;
}
//adding the config
    require_once __DIR__."/config.php";

    $app = new Silex\Application();
    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
      'twig.path' => __DIR__.'/../views'));

    $app->register(new Silex\Provider\UrlGeneratorServiceProvider());

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();


    require_once __DIR__."/routes/questionsController.php";
    require_once __DIR__."/routes/adminController.php";


    // INDEX.HTML.TWIG
    // home page displays list of topics, popular promptrs, and option to create a new promptr
    $app->get("/", function() use ($app){
      $topics = Topic::getAll();
      $promptrs = Promptr::getAll();
      $pop_promptrs = Promptr::getTrendingPromptrs();
      return $app['twig']->render('index.html.twig', array(
        'topics' => $topics,
        'promptrs' => $promptrs,
        'pop_promptrs' => $pop_promptrs));
      });


    $app->get("/topic/{id}", function($id) use ($app){
        $topic = Topic::find($id);
        $promptrs = $topic->getPromptrs();
        return $app['twig']->render("topic.html.twig", array('topic' => $topic, 'promptrs' => $promptrs));
    });

    $app->post("/topic/{id}", function($id) use ($app){
    $topic = Topic::find($id);
    $name = $_POST['name'];
    $promptr = new Promptr($name, $topic->getId());
    $promptr->save();
    $promptrs = Promptr::getAll();
    return $app['twig']->render('topic.html.twig', array('topic' => $topic, 'promptrs' => $promptrs));

    });

        $app->post("/create-topic", function() use ($app){
        $topic_name = $_POST['topic_name'];
        $topic = new Topic($topic_name);
        $topic->save();
        $promptrs = $topic->getPromptrs();
        return $app['twig']->render("topic.html.twig", array('topic' => $topic, 'promptrs' => $promptrs));
    });



    $app->get("/promptr/{id}", function($id) use ($app){
        $promptr = Promptr::find($id);
        $topic_id = $promptr->getTopicId();
        $topic = Topic::find($topic_id);
        return $app['twig']->render('promptr.html.twig', array (
                                    'promptr' => $promptr,
                                    'questions' => $promptr->getQuestions(), 'topic' => $topic));

    });
// PROMPTR.HTML.TWIG
// CONTINUE CREATING NEW PROMPTR ROUTE
    $app->post("/promptr/{id}", function($id) use ($app){
        $promptr = Promptr::find($id);
        $topic = Topic::find($promptr->getTopicId());
        $new_question_text = $_POST['question'];
        $new_description = $_POST['description'];
        $new_question = new Question($new_question_text, $new_description);
        $new_question->save();
        $promptr->addQuestion($new_question);
        return $app['twig']->render('promptr.html.twig', array (
                                    'promptr' => $promptr,
                                    'questions' => $promptr->getQuestions(), 'topic' => $topic));
    });
// PROMPTRS.HTML.TWIG
// ADD PROMPTR -- adds a prompter and displays promptrs within the topic
    $app->post("/promptrs", function() use ($app){
        $promptr_name = $_POST['promptr_name'];
        $topic_id = $_POST['topic_id'];
        $new_promptr = new Promptr($promptr_name,$topic_id);
        $new_promptr->save();
        return $app['twig']->render('promptrs.html.twig', array (
                                    'promptrs' => Promptr::getAll(),
                                    'topic' => $topic_id,
                                    'topic_picked' => true));// flag for included template
    });

    $app->get("/topic/{id}", function($id) use ($app){
        $topic = Topic::find($id);
        $promptrs = $topic->getPromptrs();
        $allT = Topic::getAll();
        return $app['twig']->render("topic.html.twig", array(
                                    'topic' => $topic,
                                    'promptrs' => $promptrs,
                                    'all_topics' => $allT));
    });
// PROMPTR.HTML.TWIG
//delete question from NEW PROMPTR route -- then displays promptr page




    $app->get("promptr/{id}", function($id) use ($app){
        $promptr = Promptr::find($id);
        $questions = $promptr->getQuestions();
        return $app['twig']->render("promptr.html.twig", array('promptr' => $promptr, 'questions' => $questions));
    });

//delete question route

    $app->delete("/promptr/{id}/delete_question/{qId}", function($id, $qId) use ($app){
        $question_id = $qId;
        $promptr = Promptr::find($id);
        $topic = Topic::find($promptr->getTopicId());
        $question = Question::findById($question_id);
        $question->delete();
        $questions = $promptr->getQuestions();
        return $app['twig']->render("promptr.html.twig", array(
                                    'promptr' => $promptr,
                                    'questions' => $questions, 'topic' => $topic));

    });


    return $app;
?>
