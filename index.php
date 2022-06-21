<?php
ini_set('display_errors', 1);

require_once('./db/db.php');
require_once('./interfaces/treeInterface.php');
require_once('./models/treeModel.php');
$db = new Db();

$model = new Tree\ModelTree($db);

$action = $_REQUEST['action'] ?? 'tree';
switch($action)
{
    case "tree" :
        require_once('./controllers/treeController.php');
        $cont = new Tree\ControllerTree($model);
        $cont->index();
        break;
    case "ajax" :
        $type = $_REQUEST['type'];
        $post['path'] = $_POST['path'] ?? 1;
        $post['title'] = $_POST['title'] ?? 'root';
        require_once('./controllers/ajaxController.php');
        $cont = new Tree\ControllerAjax($model);
        $cont->$type($post);
        break;
    default :
        require_once("404.php"); // "404"
        break;
}