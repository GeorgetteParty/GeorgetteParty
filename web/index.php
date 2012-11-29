<?php

define('GP_ROOT_PATH', __DIR__ . '/../');

$loader = require_once GP_ROOT_PATH . 'vendor/autoload.php';
//$loader->add('Goutte\Story', __DIR__.'/src');

use Silex\Application;
use Symfony\Component\Finder\Finder;
use dflydev\markdown\MarkdownParser;

// Utils (some monkey coding)

//function build_cache_for_page ($pageId, $pagesPath, $cachePath) {
//
//}

function is_localhost () {
    return (in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1','::1',)));
}

// App

$app = new Application();

if (is_localhost()) {
    $app['debug'] = true;
}

// Twig

$twig_loader = new Twig_Loader_Filesystem(array(
    GP_ROOT_PATH . 'view',
//    GP_ROOT_PATH . 'cache/pages',
));
$twig = new Twig_Environment($twig_loader, array(
    'cache' => GP_ROOT_PATH . 'cache',
));


// Pages

$pages = array();

$finder = new Finder();
$finder->files()->in(GP_ROOT_PATH . 'pages');

foreach ($finder as $file) {
    $pages[$file->getRelativePathname()] = file_get_contents($file->getPathname());
}

// Route Aliases

$app->get('/', function(Application $app) {
    return $app->redirect('page/1');
});

$app->get('/porte/{id}', function (Application $app, $id) {
    return $app->redirect('../page/'.$id);
})->assert('id', '\d+');

// Show a Page

$app->get('/page/{id}', function (Application $app, $id) use ($twig, $pages) {

    if (!isset($pages[$id])) $app->abort(404, "Page {$id} does not exist.");

    // Parse markdown
    $markdownParser = new MarkdownParser();
    $page = $markdownParser->transformMarkdown($pages[$id]);

    // Add page links
    $page = preg_replace('!page (\d+)!', '<a href="../page/$1">$0</a>', $page);

    $page = $twig->render('page.html.twig', array('page' => $page));

    return $page;

})->assert('id', '\d+');


$app->run();
