<?php
namespace app\common\model;

use app\common\library\Time;
use youyi\util\StringUtil;

class UserTokenInfoModel extends BaseModel
{
    protected $name = CMS_PREFIX . 'user_token_info';
    protected $pk   = array('user_id', 'access_id', 'device_id');

    const STATUS_USABLE   = 1;
    const STATUS_DISABLED = 2;
    const STATUS_EXPIRED  = 3;

    //自动完成
    protected $auto   = ['update_time'];
    protected $insert = ['create_time'];
    protected $update = [];

    public function createUserTokenInfo($userId, $accessId, $deviceId)
    {
        $data['user_id']   = $userId;
        $data['access_id'] = $accessId;
        $data['device_id'] = $deviceId;

        $data['status']      = UserTokenInfoModel::STATUS_USABLE;
        $data['token']       = StringUtil::getRandString(18);
        $expireTime          = Time::daysAfter(30);
        $data['expire_time'] = date('Y-m-d H:i:s', $expireTime); //30天后过期

        //成功返回1
        $result = $this->save($data);
        if (!$result) {
            return false;
        }

        //联合主键，find设置方法；顺序与pk字段一致
        $pk            = ['user_id' => $userId, 'access_id' => $accessId, 'device_id' => $deviceId];
        $userPushToken = UserTokenInfoModel::find($pk);

        return $userPushToken;
    }

    public function updateUserTokenInfo($userId, $accessId, $deviceId)
    {
        $where = [
            ['user_id', '=', $userId],
            ['access_id', '=', $accessId],
            ['device_id', '=', $deviceId],
        ];
        $userToken = self::where($where)->find();

        $data['status']      = UserTokenInfoModel::STATUS_USABLE;
        $data['token']       = StringUtil::getRandString(18);
        $expireTime          = Time::daysAfter(30);
        $data['expire_time'] = date('Y-m-d H:i:s', $expireTime); //30天后过期

        //成功返回1
        $result = $userToken->save($data);
        if (!$result) {
            return false;
        }

        return $userToken;
    }

    public function findByUserId($userId, $accessId, $deviceId)
    {
        //联合主键，find设置方法；顺序与pk字段一致
        $pk = ['user_id' => $userId, 'access_id' => $accessId, 'device_id' => $deviceId];
        return $this->find($pk);
    }

    public function resetUserTokenInfo($userId, $accessId, $deviceId)
    {
        $userTokenInfo = $this->findByUserId($userId, $accessId, $deviceId);
        if (!$userTokenInfo) {
            return false;
        }

        $data['user_id']   = $userId;
        $data['access_id'] = $accessId;
        $data['device_id'] = $deviceId;

        $data['status']      = UserTokenInfoModel::STATUS_USABLE;
        $data['token']       = StringUtil::getRandString(18);
        $expireTime          = Time::daysAfter(30);
        $data['expire_time'] = date('Y-m-d H:i:s', $expireTime); //30天后过期

        //成功返回1
        $result = $this->save($data);
        if (!$result) {
            return false;
        }

        //联合主键，find设置方法；顺序与pk字段一致
        $pk            = ['user_id' => $userId, 'access_id' => $accessId, 'device_id' => $deviceId];
        $userTokenInfo = UserTokenInfoModel::find($pk);

        return $userTokenInfo;
    }

}
