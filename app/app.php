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




    $app->get("/", function() use ($app){
        $topics = Topic::getAll();
        $promptrs = Promptr::getAll();
        return $app['twig']->render('index.html.twig', array('topics' => $topics, 'promptrs' => $promptrs));
    });

    $app->post("/admin_start", function() use ($app){
        $topic_name = $_POST['topic'];
        $topic = new Topic($topic_name);
        $topic->save();
        $admin = true;
        $questions = Question::getAll();
        return $app['twig']->render('admin.html.twig', array('admin' => $admin, 'topic' => $topic, 'questions' => $questions));
    });

    $app->get("/admin_start", function() use ($app){
        $admin = true;
        $questions = Question::getAll();
        return $app['twig']->render('admin.html.twig', array('admin' => $admin, 'questions' => $questions));
    });

    return $app;
?>
