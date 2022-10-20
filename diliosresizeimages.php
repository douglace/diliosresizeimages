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

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(_PS_MODULE_DIR_. 'diliosresizeimages/vendor/autoload.php')) {
    require_once _PS_MODULE_DIR_.  'diliosresizeimages/vendor/autoload.php';
}

class Diliosresizeimages extends Module
{
   
    /**
     * @param array $tabs
     */
    public $tabs = [];

    /**
     * @param Dilios\Diliosresizeimages\Repository $repository
     */
    protected $repository;

    public function __construct()
    {
        $this->name = 'diliosresizeimages';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Dilios';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;
        
        $this->tabs = array(
            array(
                'name'=> $this->trans('Resize images', array(), 'Modules.Severalcardpayment.Admin'),
                'class_name'=>'AdminDriImage',
                'parent'=>'AdminParentModulesSf',
            )
        );

        $this->repository = new Dilios\Diliosresizeimages\Repository($this);
        parent::__construct();
        $this->displayName = $this->l('Redimensionner les images');
        $this->description = $this->l('Redimensionner les images');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install() && $this->repository->install();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->repository->uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        Tools::redirectAdmin(
            $this->context->link->getAdminLink('AdminDriImage')
        );
    }

    

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
}
