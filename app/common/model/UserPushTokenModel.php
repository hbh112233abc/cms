<?php
namespace app\common\model;

use app\common\library\Os;

class UserPushTokenModel extends BaseModel
{
    protected $name = CMS_PREFIX . 'user_push_token';
    protected $pk   = array('user_id', 'access_id', 'device_id');

    const STATUS_LOGIN  = 1;
    const STATUS_LOGOUT = 2;

    //自动完成
    protected $auto   = ['update_time'];
    protected $insert = ['create_time'];
    protected $update = [];

    public function createUserPushToken($userId, $accessId, $deviceId, $os, $pushToken)
    {
        $userPushToken = $this->findByUserId($userId, $accessId, $deviceId);
        if (!$userPushToken->isEmpty()) {
            $data['user_id']    = $userId;
            $data['access_id']  = $accessId;
            $data['device_id']  = $deviceId;
            $data['status']     = UserPushTokenModel::STATUS_LOGIN;
            $data['push_token'] = $pushToken;

            $userPushToken->save($data);
        } else {
            $data['user_id']    = $userId;
            $data['access_id']  = $accessId;
            $data['device_id']  = $deviceId;
            $data['status']     = UserPushTokenModel::STATUS_LOGIN;
            $data['os']         = $os;
            $data['push_token'] = $pushToken;

            $result = $this->save($data);
            if (!$result) {
                return false;
            }
        }

        //联合主键，find设置方法；顺序与pk字段一致
        $pk            = ['user_id' => $userId, 'access_id' => $accessId, 'device_id' => $deviceId];
        $userPushToken = UserPushTokenModel::where($pk)->find();

        return $userPushToken;
    }

    public function findByUserId($userId, $accessId, $deviceId)
    {
        $where['user_id']   = $userId;
        $where['access_id'] = $accessId;
        $where['device_id'] = $deviceId;

        $resultSet = $this->where($where)->findOrEmpty();

        return $resultSet;
    }

    public function logout($userId, $accessId, $deviceId)
    {
        $where = [
            ['user_id', '=', $userId],
            ['access_id', '=', $accessId],
            ['device_id', '=', $deviceId],
        ];
        $result = self::where($where)->update(['status' => UserPushTokenModel::STATUS_LOGOUT]);

        return $result;
    }

    public function getAndroidPushTokens($userId, $accessId = '')
    {
        if (empty($accessId)) {
            $accessId = config('middleware_access_id');
        }
        $where = [
            'user_id'   => $userId,
            'access_id' => $accessId,
            'status'    => UserPushTokenModel::STATUS_LOGIN,
            'os'        => Os::Android,
        ];

        $UserPushTokenModel = new UserPushTokenModel();
        $resultSet          = $UserPushTokenModel->where($where)->order('update_time desc')->select();
        if (count($resultSet) == 0) {
            return false;
        }

        return $resultSet;
    }

    public function getIosPushTokens($userId, $accessId = '')
    {
        if (empty($accessId)) {
            $accessId = config('middleware_access_id');
        }
        $where = [
            'user_id'   => $userId,
            'access_id' => $accessId,
            'status'    => UserPushTokenModel::STATUS_LOGIN,
            'os'        => Os::iOS,
        ];

        $UserPushTokenModel = new UserPushTokenModel();
        $resultSet          = $UserPushTokenModel->where($where)->order('update_time desc')->select();
        if (count($resultSet) == 0) {
            return false;
        }

        return $resultSet;
    }
}
