<?php
namespace Tree;
/**
 * tree page model
 */
class ModelTree implements InterfaceTree
{
    protected $db;
    public $step = 100;
    public function __construct($db){
        $this->db = $db;
    }
    /**
     * get full tree from base
     * @return array
     */
    public function getTree(){
        $res = $this->db->query("SELECT * FROM `tree` WHERE 1 ORDER BY `path` ASC");
        return $this->sortTree($res);
    }
    /**
     * get branch from base
     * @param path integer
     * @return array
     */
    public function getBranch($path){
        $res = $this->db->query("SELECT * FROM `tree` WHERE `path` = '".$path."'");
        return $res[0];
    }
    /**
     * add branch to base
     * @param path integer
     * @param title string
     */
    public function addBranch($path, $title){
        $this->db->query("INSERT INTO `tree` SET `title` = '".$title."', `path` = '".$path."'");
    }
    /**
     * update child_num for branch
     * @param path integer
     * @param num integer
     */
    public function updateChildNum($path, $num){
        $this->db->query("UPDATE `tree` SET `child_num` = '".$num."' WHERE `path` = '".$path."'");
    }
    /**
     * update title for branch
     * @param path integer
     * @param title string
     */
    public function updateTitle($path, $title){
        $this->db->query("UPDATE `tree` SET `title` = '".$title."' WHERE `path` = '".$path."'");
    }
    /**
     * delete branch
     * @param path integer
     */
    public function deleteBranch($path){
        $parent_id = $path;
        if($path > $this->step) $parent_id = round($path/$this->step, 0);
        $this->db->query("DELETE FROM `tree` WHERE `path` LIKE '".$path."%'");
        $this->db->query("UPDATE `tree` SET `child_num` = child_num-1 WHERE `path` = '".$parent_id."'");
    }
    /**
     * sort tree
     * @param mess array
     * @return array
     */
    public function sortTree($mess){
        if (!is_array($mess)) {
            return false;
        }
        $tree = array();
        foreach ($mess as $value) {
            $parent_id = 0;
            if($value['path'] > $this->step) $parent_id = round($value['path']/$this->step, 0);
            $tree[$parent_id][] = $value;
        }
        return $tree;
    }
    /**
     * recursive build html for tree
     * @param cats array
     * @param parent_id integer
     * @return html or false if array is empty
     */
    public function buildTree($cats, $parent_id)
    {
        if (is_array($cats) && isset($cats[$parent_id])) {
            $add_class = ($parent_id==0)?'class="tree"':'';
            $tree = '<ul '.$add_class.'>';
            foreach ($cats[$parent_id] as $cat) {
                $tree .= '<li data-path="'.$cat['path'].'"><a href="#">'.$cat['title'].'</a>';
                $tree .= $this->buildTree($cats, $cat['path']);
                $tree .= '</li>';
            }
            $tree .= '</ul>';
        } else {
            return false;
        }
        return $tree;
    }
}