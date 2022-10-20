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

class DriImageModel extends ObjectModel
{
    /**
     * @param int $id_dri_config
     */
    public $id_dri_config;

    /**
     * @param string $related_path
     */
    public $related_path;

    /**
     * @param int $width
     */
    public $width;

    /**
     * @param int $height
     */
    public $height;

    /**
     * @param int $active
     */
    public $active = 1;

    /**
     * @param bool $preserve_ratio
     */
    public $preserve_ratio = 0;

    /**
     * @param bool $conserve_original
     */
    public $conserve_original = 0;

    /**
     * @param string $date_add
     */
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'dri_configs',
        'primary' => 'id_dri_config',
        'multilang' => false,
        'fields' => array(
            'related_path' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'required' => true,
            ),
            'width' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt',
                'required' => true,
            ),
            'height' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt',
                'required' => false,
            ),
            'preserve_ratio' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'conserve_original' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDate'
            )
        ),
    );
}