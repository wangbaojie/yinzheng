<?php

namespace app\api_v1\controller;


use controller\BasicApi;
use think\Db;

class Tender extends BasicApi
{
    public function index($no)
    {
        $res = Db::name('tender')->where('is_deleted', '<>', 1)->where('no', $no)->find();
        if ($res) {
          $text="<span style='color:green'>(有效)</span>";
          $text2="<span style='color:green'>(未开标)</span>";
          if($res['open_time']+$res['guarantee_term']*86400<time()){
             $text="<span style='color:red'>(已过期)</span>";
          }
           if($res['open_time']<time()){
             $text2="<span style='color:red'>(已开标)</span>";
          }
          
         $data=[
             '项目名称'=>$res['project_name'],
             '受益人'=>$res['Beneficiary'],
             '开标时间'=>date("Y-m-d",$res['open_time']).$text2,
             '担保金额(小写)'=>"￥".$res['guarantee_num']."元",
             '担保金额(大写)'=>$res['guarantee_char'],
             '公司名称'=>$res['company_name'],
             '保函有效期截止'=>date("Y-m-d",$res['open_time']+$res['guarantee_term']*86400).$text,
             '详情'=>$res['content']
         ];
            $this->success('查询成功', $data);
        } else {
            $this->error('未查询到相应信息');
        }
    }
}
