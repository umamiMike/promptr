<?php
// PROMPTR.HTML.TWIG
/////////////////////////////////////////////////////////////////
////////////////// BEGIN ADMIN PAGES ////////////////////////////
/////////////////////////////////////////////////////////////////
// PROMPTR-ADMIM.TWIG
// this route is manually entered and used only to populate the database
$app->get("/admin", function() use ($app){
var_dump(__FUNCTION__);
  $topics = Topic::getAll();
  $all_promptrs = Promptr::getAll();
  // find promptrs that need questions (newly added -- need to do this to get promptr id)
  $empty_promptrs = [];
  foreach($all_promptrs as $promptr){
      if(empty($promptr->getQuestions())){
          array_push($empty_promptrs, $promptr);
      }
  }

  return $app['twig']->render('promptr-admin.html.twig', array(
                              'topics' => $topics,
                              'promptrs' => $empty_promptrs));
});
// renders after adding a blank promptr
$app->post("/admin", function() use ($app){
  $promptr_name = $_POST['promptr_name'];
  $topic_id = $_POST['topic_id'];
  $new_promptr = new Promptr($promptr_name, $topic_id);
  $new_promptr->save();
  $topics = Topic::getAll();
  $all_promptrs = Promptr::getAll();
  // find promptrs that need questions (newly added -- need to do this to get promptr id)
  $empty_promptrs = [];
  foreach($all_promptrs as $promptr){
      if(empty($promptr->getQuestions())){
          array_push($empty_promptrs, $promptr);
      }
  }
  return $app['twig']->render('promptr-admin.html.twig', array(
                              'topics' => $topics,
                              'promptrs' => $empty_promptrs));
});
// //Admin page after all promptr delete -- refreshes admin page with topics only
$app->delete("/admin", function() use ($app){
  Promptr::deleteAll();
  return $app['twig']->render("promptr-admin.html.twig", array(
                              'topics' => Topic::getAll(),
                              'promptrs' => Promptr::getAll()));
});
//  UNPOPULATED HOME PAGE -- SHOULD ONLY BE REACHED AFTER DELETE ALL PEFORMED
// ON ADMIN PAGE
$app->delete("/", function() use ($app){
  Promptr::deleteAll();
  Topic::deleteAll();
  Question::deleteAll();
  Answer::deleteAll();
  return $app['twig']->render('index.html.twig', array('topics' => Topic::getAll(), 'promptrs' => Promptr::getAll()));
});


 ?>
