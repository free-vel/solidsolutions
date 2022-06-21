<?php
namespace Tree;
/**
 * ajax controller for tree
 */
class ControllerAjax {
    public $model;
    function __construct(InterfaceTree $model)
    {
        $this->model = $model;
    }
    /**
     * add new root
     * @param post array
     * @return json
     */
    public function addRoot($post){
        $this->model->addBranch($post['path'], 'root');
        $return = array('error' => 0, 'text' => '');

        echo json_encode($return);
    }
    /**
     * add new branch
     * @param post array
     * @return json
     */
    public function add($post){
        $branch = $this->model->getBranch($post['path']);
        if($branch['child_num'] >= 99){
            echo json_encode(array('error' => 1, 'text' => 'too much nodes'));
        }
        $path = $branch['path']*$this->model->step + $branch['child_num'] + 1;

        $title = htmlspecialchars($post['title'], ENT_QUOTES);
        $return = array('error' => 0, 'text' => $title, 'path' => $path);
        $this->model->addBranch($path, $title);
        $this->model->updateChildNum($branch['path'], ($branch['child_num']+1));

        echo json_encode($return);
    }
    /**
     * delete branch
     * @param post array
     * @return json
     */
    public function delete($post){
        $this->model->deleteBranch($post['path']);
        $return = array('error' => 0, 'text' => '');

        echo json_encode($return);
    }
    /**
     * update branch
     * @param post array
     * @return json
     */
    public function update($post){
        $title = htmlspecialchars($post['title'], ENT_QUOTES);
        $this->model->updateTitle($post['path'], $title);
        $return = array('error' => 0, 'text' => $title);

        echo json_encode($return);
    }
}