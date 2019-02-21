<?php
namespace app\index\controller;

class Video extends Base
{
    protected $model;
    public function __construct()
    {
        $this->model = model('video');
    }
    public function getVideo()
    {
        $videoList = ['videoList'=>$this->model->searchData([],"*",0)];
        echo json_encode($videoList);
    }
    public function setVideo(){
        // echo json_encode( input('post.'));
        // exit;
        $videoInfo = input('post.');
        $result = $this->model->addData($videoInfo);
        echo json_encode(['insertedNum'=>$result]);
    }
}