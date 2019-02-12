<?php


namespace app\tender\controller;

use controller\BasicAdmin;
use service\DataService;
use think\Db;

/**
 * 商城快递公司管理
 * Class Express
 * @package app\store\controller
 * @author Anyon <zoujingli@qq.com>
 * @date 2017/03/27 14:43
 */
class Index extends BasicAdmin
{
    public $table = 'tender';

    /**
     * @return array|string
     * 首页
     */
    public function index()
    {
        $this->title = '担保项目管理';
        $get = $this->request->get();
        $db = Db::name($this->table)->where('is_deleted', '<>', 1);
        if (isset($get['no']) && !empty(trim($get['no']))) {
            $no = trim($get['no']);
            $db->where('no', 'like', "%$no%");
        }
        if (isset($get['project_name']) && !empty(trim($get['project_name']))) {
            $project_name = trim($get['project_name']);
            $db->where('project_name', 'like', "%$project_name%");
        }
        if (isset($get['open_time']) && !empty(trim($get['open_time']))) {
            list($start, $end) = explode(' - ', trim($get['open_time']));
            $start = strtotime($start . ' 00:00:00');
            $end = strtotime($end . ' 23:59:59');
            $db->where("open_time", 'between', [$start, $end]);
        }
        return parent::_list($db);
    }

    /**
     * @return array|string
     * 增加
     */
    public function add()
    {
        $this->title = '添加担保项目管理';
        return $this->_form($this->table, 'form');
    }

    /**
     * @return array|string
     * 编辑
     */
    public function edit()
    {
        $this->title = '编辑担保项目管理';
        return $this->_form($this->table, 'form');
    }

    /**
     * 删除
     */
    public function del()
    {

        if (DataService::update($this->table)) {
            $this->success("删除成功！", '');
        }
        $this->error("删除失败，请稍候再试！");
    }

    public function _form_filter(&$data)
    {
        if ($this->request->isPost()) {
            $data['open_time'] = strtotime($data['open_time']);
            if (empty($data['id'])) {
                $data['no'] = "ZY" . time() . rand(1000, 9999);
                $data['created_at'] = time();
            }
        } else {
            if (!empty($data)) {
                $data['open_time'] = date("Y-m-d H:i:s", $data['open_time']);
            }
        }
    }
}
