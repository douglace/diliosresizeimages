<?php
/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

namespace  Dilios\Diliosresizeimages\Classes;

use Db;
use DbQuery;
use ImageManager;

class DriImage 
{
    public static function getBasePath()
    {
        return _PS_ROOT_DIR_;
    }

    public static function getOriginalTag()
    {
        return "--DRI-ORIGINAL";
    }

    public static function getImagesExt()
    {
        return [
            'png',
            'jpg',
            'jpeg',
            'gif',
        ];
    }

    public static function resizeImages()
    {
        $configs = self::getConfigs();
        if(!empty($configs)) {
            $base_path = self::getBasePath();
            $valid_ext = self::getImagesExt();
            foreach($configs as $config)
            {
                if(!empty($config['related_path']) && $config['related_path'][0] == "/")
                {
                    $path = $base_path.$config['related_path'];
                } else 
                {
                    $path = $base_path.DIRECTORY_SEPARATOR.$config['related_path'];
                }

                if(file_exists($path))
                {
                    $files = array_diff(scandir($path), array('.', '..'));
                    foreach($files as $file){
                        $ext = pathinfo($file, PATHINFO_EXTENSION);
                        $filename = pathinfo($file, PATHINFO_FILENAME);
                        if(strpos($filename, self::getOriginalTag()) !== false){
                            continue;
                        }
                        if(in_array(strtolower($ext), $valid_ext)){
                            
                            self::resizeImage(
                                $path,
                                $filename,
                                $ext,
                                (int)$config['width'],
                                (int)$config['height'],
                                (bool)$config['preserve_ratio'],
                                (bool)$config['conserve_original'],
                            );
                        }
                        
                    }
                }
            }
        }
        
    }

    public static function resizeImage($path, $filename, $ext, $width, $height = 0, $preserve_ratio = false, $conserve_original = false)
    {
        $original_file = $path.DIRECTORY_SEPARATOR.$filename.self::getOriginalTag().".".$ext;
        $image_file = $path.DIRECTORY_SEPARATOR.$filename.".".$ext;
        
        if($conserve_original && !file_exists($original_file))
        {
            copy($image_file, $original_file);
        }

        return ImageManager::resize(
            file_exists($original_file) ? $original_file : $image_file,
            $image_file,
            $width,
            ($preserve_ratio ? null : ($height ? $height : null)),
            $ext,
            true
        );
        

        
    }

    public static function getConfigs()
    {
        $q = new DbQuery();
        $q->select('*')
            ->from('dri_configs')
            ->where('active=1')
        ;
        return Db::getInstance()->executeS($q);
    }
}