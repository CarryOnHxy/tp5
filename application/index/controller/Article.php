<?php
namespace app\index\controller;
use think\Db;

class Article extends Base
{
    protected $model;
    public function __construct()
    {
        $this->model = model('article');
    }
    public function getArticle()
    {
        $articleId = input('id');
        if ($articleId) {
            $articleList = ['article' => $this->model->searchData(['id' => $articleId], "*", 1)];
        } else {
            $articleList = ['articleList' => $this->model->searchData([], "*", 0)];
        }
        echo json_encode($articleList);
    }
    public function getArticleInCate()
    {
        $categroryId = input('categroryId');
        $articleList = ['articleList' => $this->model->searchData(['categrory_id' => $categroryId], '*', 0)];
        echo json_encode($articleList);
    }
    public function setArticle()
    {
        $articleData = input('post.');
        $this->model->addData($articleData);
    }
    public function selectArticleByKey()
    {
        //SELECT * FROM tp_article WHERE detail LIKE '%的%'
        $queryKey = input('queryKey');
        $articleList =$this->model->field('*')->where('detail','like',"%$queryKey%")->select();
        echo json_encode(['articleList'=>$articleList]);
    }
} 