<?php declare(strict_types = 1);

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();
$DEBUG = getenv('debug');

#error_reporting(E_ALL);
#whoops = new \Whoops\Run;
#if ($DEBUG) {
#  $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
#} else {
#    $whoops->pushHandler(function($e){
#        echo 'Whoops! An error has occurred!';
#    });
#}
#$whoops->register();

$handlers = new \Enigma\ErrorHandler($DEBUG);
$handlers->register();

#throw new \Exception('DOH!!');
#trigger_error('HA HA');

$loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/templates');
$twig = new \Twig_Environment($loader);

#echo $_SERVER['REQUEST_URI'];
$path = strtok($_SERVER['REQUEST_URI'], '?');
$parts = explode("/", $path);
#echo count($parts);
if ($path == "/") {
  echo $twig->render('home.twig', array('name' => 'world'));
} elseif ($parts[1] == 'page') {
  $slug = $parts[2];
  try {
    $markdownStr = \Enigma\Models\File::retrieve($slug);
  } catch (\Enigma\FileNotFoundException $e) {
    header("HTTP/1.0 404 Not Found");
    echo $twig->render('404.twig');
    exit;
  }
  $renderer = new \Enigma\Views\Markdown($twig);
  $renderer->show($markdownStr);
} else {
  header("HTTP/1.0 404 Not Found");
  echo $twig->render('404.twig');
}
exit;
