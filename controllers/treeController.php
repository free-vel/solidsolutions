<?php
namespace Tree;
/**
 * tree page controller
 */
class ControllerTree {
    public $model;
    function __construct(InterfaceTree $model)
    {
        $this->model = $model;
    }
    /**
     * build and show tree page
     */
    public function index() {
        $tree = $this->model->getTree();
        $treeHtml = $this->model->buildTree($tree, 0);
        require_once('viewers/treeView.php');
    }
}
