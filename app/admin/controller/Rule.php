<?php

namespace app\admin\controller;

use app\common\model\AuthGroupAccessModel;
use app\common\model\AuthGroupModel;
use app\common\model\AuthRuleModel;
use app\common\model\UserModel;
use think\facade\Cache;
use think\facade\View;

/**
 * 权限管理控制器
 */
class Rule extends Base
{

    /**
     * 权限列表
     */
    public function index()
    {
        $AuthRuleModel = new AuthRuleModel();
        $data          = $AuthRuleModel->getTreeDataBelongto('tree', 'id', 'title', 'id', 'pid', 'admin');

        View::assign('data', $data);
        return View::fetch('index');
    }

    /**
     * 添加权限
     */
    public function add()
    {
        $data = input('post.');
        unset($data['id']);

        //验证规则唯一性
        $rule = [
            'name|权限规则' => 'require|unique:' . config('database.prefix') . CMS_PREFIX . 'auth_rule,name',
        ];
        $check = $this->validate($data, $rule);
        if ($check !== true) {
            return $this->error($check);
        }

        $AuthRuleModel = new AuthRuleModel();
        $result        = $AuthRuleModel->addData($data);
        if ($result) {
            return $this->success('添加成功', url('Rule/index'));
        } else {
            return $this->error('添加失败');
        }
    }

    /**
     * 修改权限
     */
    public function edit()
    {
        $data = input('post.');
        $map  = [
            'id' => $data['id'],
        ];
        $AuthRuleModel = new AuthRuleModel();
        $result        = $AuthRuleModel->editData($map, $data);
        if ($result) {
            return $this->success('修改成功', url('Rule/index'));
        } else {
            return $this->error('修改失败');
        }
    }

    /**
     * 删除权限
     */
    public function delete()
    {
        $id  = input('param.id');
        $map = [
            'id' => $id,
        ];
        $AuthRuleModel = new AuthRuleModel();
        $result        = $AuthRuleModel->deleteData($map);
        if ($result) {
            return $this->success('删除成功', url('Rule/index'));
        } else {
            return $this->error('请先删除子权限');
        }
    }

    /**
     * 菜单排序
     */
    public function order()
    {
        $data = input('post.');
        $arr  = [];
        foreach ($data as $k => $v) {
            $arr[] = [
                'id'   => $k,
                'sort' => empty($v) && $v !== '0' ? null : $v,
            ];
        }
        $AuthRuleModel = new AuthRuleModel();
        $result        = $AuthRuleModel->allowField(true)->saveAll($arr);
        if ($result) {
            return $this->success('排序成功', url('Rule/index'));
        } else {
            return $this->error('排序失败');
        }
    }

    /**
     * 设置菜单值
     */
    public function setMenu()
    {
        $id     = input('id/d', 0);
        $isMenu = input('is_menu/s', 'false');
        $isMenu = $isMenu === 'true' ? true : false;

        $AuthRuleModel = new AuthRuleModel();
        $result        = $AuthRuleModel->update(['id' => $id, 'is_menu' => $isMenu]);
        if ($result) {
            return $this->success('修改成功', url('Rule/index'));
        } else {
            return $this->error('修改失败');
        }
    }

    //*******************用户组**********************
    /**
     * 用户组列表
     */
    public function group()
    {
        $data = AuthGroupModel::select();
        View::assign('data', $data);
        return view('group');
    }

    /**
     * 添加用户组
     */
    public function addGroup()
    {
        $data = input('post.');
        unset($data['id']);

        $AuthGroupModel = new AuthGroupModel();
        $result         = $AuthGroupModel->addData($data);
        if ($result) {
            return $this->success('添加成功', url('Rule/group'));
        } else {
            return $this->error('添加失败');
        }
    }

    /**
     * 修改用户组
     */
    public function editGroup()
    {
        $data = input('post.');
        $map  = [
            'id' => $data['id'],
        ];

        $AuthGroupModel = new AuthGroupModel();
        $result         = $AuthGroupModel->editData($map, $data);
        if ($result) {
            return $this->success('修改成功', url('Rule/group'));
        } else {
            return $this->error('修改失败');
        }
    }

    /**
     * 删除用户组
     */
    public function deleteGroup()
    {
        $id  = input('param.id');
        $map = [
            'id' => $id,
        ];
        $AuthGroupModel = new AuthGroupModel();
        $result         = $AuthGroupModel->deleteData($map);
        if ($result !== false) {
            return $this->success('删除成功', url('Rule/group'));
        } else {
            return $this->error('删除失败');
        }
    }

    //*****************权限-用户组*****************
    /**
     * 分配权限
     */
    public function ruleGroup()
    {
        if (request()->isPost()) {
            $groupId = input('post.id/d', 0);
            $rules   = implode(',', input('post.rule_ids/a', []));
            $result  = AuthGroupModel::where('id', $groupId)->update(['rules' => $rules]);
            if ($result !== false) {
                $groupUserIds = AuthGroupAccessModel::where('group_id', $groupId)->column('uid');
                foreach ($groupUserIds as $uid) {
                    Cache::delete('menu_' . $uid);
                }
                return $this->success('操作成功', url('Rule/group'));
            } else {
                return $this->error('操作失败');
            }
        }

        $id = input('param.id');
        // 获取用户组数据
        $groupData          = AuthGroupModel::where('id', $id)->find();
        $groupData['rules'] = explode(',', $groupData['rules']);
        // 获取规则数据
        $AuthRuleModel = new AuthRuleModel();
        $ruleData      = $AuthRuleModel->getTreeData('level', 'id', 'title');
        // 分组信息
        $groups = AuthGroupModel::field('id, title')->select();
        $assign = [
            'group_data' => $groupData,
            'rule_data'  => $ruleData,
            'groups'     => $groups,
        ];
        View::assign($assign);

        return View::fetch('ruleGroup');
    }

    //******************用户-用户组*******************
    /**
     * 添加成员
     */
    public function checkUser()
    {
        $groupId              = input('param.group_id');
        $AuthGroupModel       = new AuthGroupModel();
        $groupName            = $AuthGroupModel->where('id', $groupId)->value('title');
        $AuthGroupAccessModel = new AuthGroupAccessModel();
        $uids                 = $AuthGroupAccessModel->where('group_id', $groupId)->column('uid');

        if (request()->isAjax()) {
            $username = input('param.username', '');
            // 判断用户名是否为空
            if (empty($username)) {
                $userList = '';
            } else {
                $UserModel = new UserModel();
                $userList  = $UserModel->where('mobile|email', 'like', "%$username%")->field('user_id,mobile,email')->select();
            }
            if (empty($userList)) {
                return $this->error('未找到相关用户');
            }

            foreach ($userList as $k => $user) {
                if (in_array($user['user_id'], $uids)) {
                    $userList[$k]['isInGroup'] = 1;
                } else {
                    $userList[$k]['isInGroup'] = 0;
                    $userList[$k]['setUrl']    = url('Rule/addUserToGroup', ['uid' => $user['user_id'], 'group_id' => $groupId, 'username' => $user['mobile']]);
                }
            }

            return $this->success('找到的相关用户', null, $userList);
        }

        $assign = [
            'group_name' => $groupName,
            'uids'       => $uids,
        ];
        View::assign($assign);

        //当前分组成员
        $AuthGroupAccessModel = new AuthGroupAccessModel();
        $userIds              = $AuthGroupAccessModel->where('group_id', $groupId)->column('uid');
        $UserModel            = new UserModel();
        $userList             = $UserModel->where('user_id', 'in', $userIds)->field('user_id,mobile')->select();
        View::assign('userList', $userList);

        //未加入分组的用户
        $outUserList = $UserModel->where('user_id', 'not in', $userIds)->field('user_id,mobile')->select();
        View::assign('outUserList', $outUserList);

        return View::fetch('checkUser');
    }

    /**
     * 添加用户到用户组
     */
    public function addUserToGroup()
    {
        $data = input('param.');
        $map  = [
            'uid'      => $data['uid'],
            'group_id' => $data['group_id'],
        ];
        $AuthGroupAccessModel = new AuthGroupAccessModel();
        $count                = $AuthGroupAccessModel->where($map)->count();
        if ($count == 0) {
            $res = $AuthGroupAccessModel->addData($data);
            if ($res) {
                Cache::tag('menu')->rm($data['uid']);
                return $this->success('操作成功');
            } else {
                return $this->error('操作失败');
            }
        } else {
            return $this->error('已经是相关用户组了');
        }
    }

    /**
     * 将用户移除用户组
     */
    public function deleteUserFromGroup()
    {
        $map                  = input('param.');
        $AuthGroupAccessModel = new AuthGroupAccessModel();
        $result               = $AuthGroupAccessModel->deleteData($map);
        if ($result) {
            Cache::tag('menu')->rm($map['uid']);
            return $this->success('操作成功', url('Rule/userList'));
        } else {
            return $this->error('操作失败');
        }
    }

    /**
     * 管理员列表
     */
    public function userList()
    {
        $AuthGroupAccessModel = new AuthGroupAccessModel();
        $uids                 = $AuthGroupAccessModel->distinct(true)->column('uid');
        if (!empty($uids)) {
            $UserModel = new UserModel();
            $list      = $UserModel->where('user_id', 'in', $uids)->paginate(20, false, ['query' => input('param.')]);
            View::assign(['data' => $list]);
            View::assign('pages', $list->render());
        }

        return View::fetch('userList');
    }

    /**
     * 添加管理员
     */
    public function addAdmin()
    {
        if (request()->isPost()) {
            $data              = input('post.');
            $data['user_type'] = 1;
            $userModel         = new UserModel;
            $newUserId         = $userModel->addUser($data);
            if ($newUserId) {
                if (!empty($data['group_ids'])) {
                    $group = [];
                    foreach ($data['group_ids'] as $k => $v) {
                        $group[] = [
                            'uid'      => $newUserId,
                            'group_id' => $v,
                        ];
                    }
                    $AuthGroupAccessModel = new AuthGroupAccessModel();
                    $AuthGroupAccessModel->insertAll($group);
                }
                Cache::tag('menu')->rm($newUserId);
                // 操作成功
                return $this->success('添加成功', url('Rule/userList'));
            } else {
                // 操作失败
                return $this->error($userModel->getError());
            }
        }

        $AuthGroupModel = new AuthGroupModel();
        $data           = $AuthGroupModel->select();
        $assign         = [
            'data' => $data,
        ];
        View::assign($assign);

        return View::fetch('addAdmin');
    }

    /**
     * 修改管理员
     */
    public function editAdmin()
    {
        if (request()->isPost()) {
            $data = input('post.');
            // 组合where数组条件
            $uid = $data['user_id'];

            // 修改权限
            $AuthGroupAccessModel = new AuthGroupAccessModel();
            $AuthGroupAccessModel->deleteData(['uid' => $uid]);
            $group = [];
            foreach ($data['group_ids'] as $k => $v) {
                $group[] = [
                    'uid'      => $uid,
                    'group_id' => $v,
                ];
            }
            $AuthGroupAccessModel->insertAll($group);
            Cache::tag('menu')->rm($uid);

            $userModel = new UserModel;
            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = encrypt_password($data['password']);
            }

            $result = $userModel->editUser($data);
            if ($result) {
                // 操作成功
                return $this->success('编辑成功', url('Rule/editAdmin', ['id' => $uid]));
            } else {
                $errorMsg = $userModel->getError();
                if (empty($errorMsg)) {
                    return $this->success('编辑成功', url('Rule/editAdmin', ['id' => $uid]));
                } else {
                    // 操作失败
                    return $this->error($errorMsg);
                }
            }
        }

        $id = input('param.id/d', 0);
        // 获取用户数据
        $user = UserModel::find($id);
        View::assign('user', $user);

        //用户所属分组
        $AuthGroupAccessModel = new AuthGroupAccessModel();
        $userGroups           = $AuthGroupAccessModel->where('uid', $id)->column('group_id');
        View::assign('userGroups', $userGroups);

        //分组列表
        $AuthGroupModel = new AuthGroupModel();
        $groups         = $AuthGroupModel->select();
        View::assign('groups', $groups);

        return View::fetch('editAdmin');
    }
}
