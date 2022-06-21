<?php
namespace Tree;
interface InterfaceTree
{
    public function getTree();
    public function getBranch($path);
    public function addBranch($path, $title);
    public function updateTitle($path, $title);
    public function updateChildNum($path, $num);
    public function deleteBranch($path);
    public function sortTree($tree);
    public function buildTree($cats, $parent_id);
}