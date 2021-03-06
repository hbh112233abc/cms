<?php
namespace app\common\model;

use app\common\exception\ModelException;
use app\common\library\ResultCode;
use think\facade\Cookie;
use think\Model;
use youyi\util\PregUtil;
use youyi\util\StringUtil;

class UserModel extends BaseModel
{
    protected $name = CMS_PREFIX . 'user';
    protected $pk   = 'user_id';

    const STATUS_DELETED = -1; //已删除
    const STATUS_APPLY   = 1; //申请
    const STATUS_ACTIVED = 2; //已激活
    const STATUS_FREEZED = 3; //已冻结

    //属性：status_text
    public function getStatusTextAttr($value, $data)
    {
        $status = [
            -1 => '已删除',
            1  => '申请',
            2  => '已激活',
            3  => '已冻结',
        ];

        return isset($status[$data['status']]) ? $status[$data['status']] : '未知状态';
    }

    //属性：status_html
    public function getStatusHtmlAttr($value, $data)
    {
        $status = [
            -1 => '<span class="label label-danger">已删除</span>',
            1  => '<span class="label label-default">申请</span>',
            2  => '<span class="label label-success">已激活</span>',
            3  => '<span class="label label-warning">已冻结</span>',
        ];

        return isset($status[$data['status']]) ? $status[$data['status']] : '未知状态';
    }

    //****表关联信息******
    //关联表：用户组
    public function groups()
    {
        return $this->belongsToMany('AuthGroupModel', config('database.prefix') . CMS_PREFIX . 'auth_group_access', 'group_id', 'uid');
    }

    //关联表：用户组中间表
    public function groupAccess()
    {
        return $this->hasMany('AuthGroupAccessModel', 'uid', 'user_id');
    }

    //自身扩展字段
    public function ext($key, $value = '')
    {
        $ext = $this->ext;
        if (empty($ext)) {
            $exts = array();
        } else {
            $exts = json_decode($ext, true);
        }

        if ($value === '') {
            return isset($exts[$key]) ? $exts[$key] : null;
        } else if ($value === null) {
            unset($exts[$key]);
        } else {
            $exts[$key] = $value;
        }

        $this->where('user_id', $this->user_id)->setField('ext', json_encode($exts));
    }

    public function createUser($mobile, $password, $nickname = '', $email = '', $account = '', $status = UserModel::STATUS_ACTIVED)
    {
        if (empty($nickname)) {
            $nickname = $mobile;
        }
        if ($this->findByMobile($mobile)) {
            throw new ModelException(ResultCode::E_USER_MOBILE_HAS_EXIST, '手机号已经存在');
        }
        if (empty($email)) {
            $email = $mobile . '@' . StringUtil::getRandString(6) . '.com';
        } else if ($this->findByEmail($email)) {
            throw new ModelException(ResultCode::E_USER_EMAIL_HAS_EXIST, '邮箱已经存在');
        }
        if (empty($account)) {
            $account = StringUtil::getRandString(12);
        } else if ($this->where(['account' => $account])->find()) {
            throw new ModelException(ResultCode::E_USER_ACCOUNT_HAS_EXIST, '帐号已经存在');
        }

        $user                = new UserModel();
        $user->mobile        = $mobile;
        $user->email         = $email;
        $user->account       = $account;
        $user->password      = encrypt_password($password, get_config('password_key'));
        $user->status        = $status;
        $user->nickname      = $nickname;
        $currentTime         = date('Y-m-d H:i:s');
        $user->register_time = $currentTime;

        //设置来源及入口url
        if (Cookie::has('from_referee') || Cookie::has('entrance_url')) {
            $user->from_referee = Cookie::get('from_referee');
            $user->entrance_url = Cookie::get('entrance_url');
        }

        $result = $user->save();
        if (!$result) {
            return false;
        }

        return $user;
    }

    /**
     * @param $username
     * @param $password
     * @return bool|model
     * @throws ModelException
     */
    public function checkUser($username, $password)
    {
        $user = null;
        if (PregUtil::isEmail($username)) {

            $user = $this->findByEmail($username);
            if (!$user) {
                throw new ModelException(ResultCode::E_USER_EMAIL_NOT_EXIST, '邮箱不存在');
            }
        } else {
            $user = $this->findByMobile($username);
            if (!$user) {
                throw new ModelException(ResultCode::E_USER_MOBILE_NOT_EXIST, '手机号不存在');
            }
        }

        $tempPassword = encrypt_password($password, get_config('password_key'));
        if ($tempPassword !== $user['password']) {
            throw new ModelException(ResultCode::E_USER_PASSWORD_INCORRECT, '密码不正确');
        }

        return $user;
    }

    public function markLogin($userId, $ip)
    {
        //$data['user_id'] = $userId;
        $data['last_login_time'] = date('Y-m-d H:i:s');
        $data['last_login_ip']   = $ip;

        return $this->where('user_id', $userId)->update($data);
    }

    public function setProfile($userId, $nickname, $sex = '', $headUrl = '', $qq = '', $weixin = '')
    {
        $user = self::where('user_id', $userId)->find();

        $user['nickname'] = $nickname;
        if ($sex) {
            $user['sex'] = $sex;
        }
        if ($headUrl) {
            $user['head_url'] = $headUrl;
        }
        if ($qq) {
            $user['qq'] = $qq;
        }
        if ($weixin) {
            $user['weixin'] = $weixin;
        }

        $result = $user->save();
        if ($result == false) {
            return false;
        }

        return $user;
    }

    public function modifyPassword($userId, $password)
    {
        $user             = self::where('user_id', $userId)->find();
        $newPassword      = encrypt_password($password, get_config('password_key'));
        $user['password'] = $newPassword;
        $user->allowField('password')->save();

        return $user;
    }

    public function findByMobile($mobile)
    {
        $where['mobile'] = $mobile;

        $users = $this->where($where)->limit(1)->select();
        if (count($users) <= 0) {
            return false;
        }

        return $users[0];
    }

    public function findByEmail($email)
    {
        $where['email'] = $email;

        $resultSet = $this->where($where)->limit(1)->select();
        if (count($resultSet) <= 0) {
            return false;
        }

        return $resultSet[0];
    }

    //修改用户
    public function editUser($data = [])
    {
        $data = empty($data) ? input('post.') : $data;

        //验证
        $validate = validate('User');
        $check    = $validate->scene('edit')->check($data);
        if ($check !== true) {
            $this->error = $validate->getError();
            return false;
        }

        $res = self::allowField(true)->update($data);
        if ($res === false) {
            $this->error = '修改失败';
            return false;
        }

        return true;
    }
}
