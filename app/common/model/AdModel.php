<?php
namespace app\common\model;

use think\Model;

/**
 *   广告模型
 */
class AdModel extends BaseModel
{
    protected $name = CMS_PREFIX . 'ad';

    const TYPE_HEADLINE = 1; //首页滚动图
    const TYPE_BANNER   = 2; //banner广告图

    protected $pk = 'id';
    //自动完成
    protected $auto   = [];
    protected $insert = ['create_time'];
    protected $update = [];

    public static function onAfterInsert($ad)
    {
        AdModel::clearCache($ad);
    }
    public static function onAfterUpdate($ad)
    {
        AdModel::clearCache($ad);
    }
    public static function onAfterDelete($ad)
    {
        AdModel::clearCache($ad);
    }

    //关联表:中间表
    public function adSlots()
    {
        return $this->belongsToMany('AdSlotModel', config('database.prefix') . CMS_PREFIX . 'ad_serving', 'slot_id', 'ad_id');
    }

    //关联表:图片
    public function image()
    {
        return $this->hasOne('ImageModel', 'image_id', 'image_id');
    }

    //关联表:投放时间段
    public function adServings()
    {
        return $this->hasMany('AdServingModel', 'ad_id', 'id');
    }

    //清理缓存
    public static function clearCache($ad)
    {
        $ad = $ad->toArray();
        if (isset($ad['slot_id'])) {
            if ($ad['slot_id'] == 1) {
                cache('headline', null);
            }
            if ($ad['slot_id'] == 3) {
                cache('banner', null);
            }
        }
    }
}
