<?php

namespace app\admin\controller;

use app\common\model\ImageModel;
use think\facade\Config;
use think\facade\Filesystem;
use think\facade\View;

/**
 * 图片控制器
 */
class Image extends Base
{
    use \app\common\controller\Image; //使用trait

    /**
     * 图片上传,格式不正确时，提示裁前，根据设置，截取图片
     * upcrop
     * */
    public function upcrop()
    {
        $fsConfig = Config::get('filesystem');
        $fsDriver = $fsConfig['disks'][$fsConfig['default']];
        $imageId  = request()->param('imageId/d', 0);
        //id不存在时，图片上传
        if (empty($imageId)) {
            $tmpFile = request()->file('file');
            if (empty($tmpFile)) {
                return $this->result(null, 0, '请选择上传文件', 'json');
            }

            //图片规定尺寸
            $imgWidth  = request()->param('width/d', 0);
            $imgHeight = request()->param('height/d', 0);

            //缩略图尺寸
            $tbWidth  = request()->param('thumbWidth/d', 0);
            $tbHeight = request()->param('thumbHeight/d', 0);

            $check = $this->validate(
                ['file' => $tmpFile],
                ['file' => 'require|image|fileSize:4097152'],
                [
                    'file.require'  => '请上传图片',
                    'file.image'    => '不是图片文件',
                    'file.fileSize' => '图片太大了',
                ]
            );
            if ($check !== true) {
                return $this->error($check);
            }

            $saveName = Filesystem::putFile('images', $tmpFile);
            if (!$saveName) {
                // 上传失败获取错误信息
                return $this->error('上传失败');
            }
            $filePath = $fsDriver['root'] . DIRECTORY_SEPARATOR . $saveName;
            // halt($filePath);

            list($width, $height, $type) = getimagesize($filePath); //获得图片宽高类型

            $data = [
                'thumb_image_url' => $fsDriver['url'] . DIRECTORY_SEPARATOR . $saveName,
                'image_url'       => $fsDriver['url'] . DIRECTORY_SEPARATOR . $saveName,
                'create_time'     => date_time(),
                'remark'          => input('post.remark'),
            ];
            $ImageModel = new ImageModel();
            $imageId    = $ImageModel->insertGetId($data);

            $data['image_id'] = $imageId;

            if ($imgWidth > 0 && $imgHeight > 0) {
                if (!($width >= $imgWidth - 10 && $width <= $imgWidth + 10 && $height >= $imgHeight - 10 && $height <= $imgHeight + 10)) {
                    return $this->result($data, 1, 'image_need_crop', 'json');
                }
            }

            return $this->result($data, 1, '图片上传成功', 'json');
        }

        //图片裁剪
        $imageModel = ImageModel::findOrEmpty($imageId);
        if ($imageModel->isEmpty()) {
            return $this->error('图片不存在');
        }

        $thumbWidth  = request()->param('thumbWidth/d', 0); //截取后缩略图的宽
        $thumbHeight = request()->param('thumbHeight/d', 0); //截取后缩略图的高
        if (!$this->request->isAjax()) {
            View::assign('image', $imageModel);
            View::assign('thumbWidth', $thumbWidth);
            View::assign('thumbHeight', $thumbHeight);

            return View::fetch('resource/crop');
        }

        //获取图片截取的尺寸参数
        $degrees = request()->param('rotate/d', 0); //旋转的度数
        $scale   = request()->param('scale/d', 0); //缩放

        $x      = request()->param('x/d', 0); //源图的x点
        $y      = request()->param('y/d', 0); //源图的y点
        $width  = request()->param('width/d', 0); //源图截取的宽
        $height = request()->param('height/d', 0); //源图截取的高

        $realPath = public_path('public') . $imageModel->image_url;
        // halt($realPath);
        $file     = new \SplFileInfo($realPath);
        $srcImage = \think\Image::open($file);
        if (!$srcImage) {
            return $this->error('读取图片文件失败!');
        }

        //图片旋转
        $srcImage->rotate($degrees);

        //图片裁剪，并保存为数据库的缩略图
        $srcImage->crop($width, $height, $x, $y);

        $imgUrl         = $file->getPath() . DIRECTORY_SEPARATOR . 'tb_crop_' . $file->getFilename();
        $quality        = get_config('image_upload_quality', 80); //获取图片清晰度设置，默认是80
        list(, , $type) = getimagesize($file->getRealPath());
        $extension      = image_type_to_extension($type, 0);
        $srcImage->save($imgUrl, $extension, $quality, true);

        //图片压缩至目标大小，保存为数据库中的图片
        $srcImage->thumb($thumbWidth, $thumbHeight, \think\Image::THUMB_FIXED);
        $tbImgUrl = $file->getPath() . DIRECTORY_SEPARATOR . 'crop_' . $file->getFilename();
        $srcImage->save($tbImgUrl, $extension, $quality, true);

        $urlPath = str_replace(public_path('public'), '', $file->getPath());
        // halt($urlPath);

        $imageModel->image_url       = $urlPath . DIRECTORY_SEPARATOR . 'crop_' . $file->getFilename();
        $imageModel->thumb_image_url = $urlPath . DIRECTORY_SEPARATOR . 'tb_crop_' . $file->getFilename();
        $imageModel->save();

        $data = $imageModel;
        return $this->result($data, 1, '图片裁剪成功', 'json');
    }
}
