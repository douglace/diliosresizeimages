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

if(!class_exists('DriImageModel'));
    require_once _PS_MODULE_DIR_.'diliosresizeimages/classes/DriImageModel.php';


class AdminDriImageController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'dri_configs';
        $this->className = 'DriImageModel';
        $this->lang = false;
        $this->bootstrap = true;

        $this->deleted = false;
        $this->allow_export = true;
        $this->list_id = 'dri_configs';
        $this->identifier = 'id_dri_config';
        $this->_defaultOrderBy = 'id_dri_config';
        $this->_defaultOrderWay = 'ASC';
        $this->context = Context::getContext();

        $this->addRowAction('edit');

        parent::__construct();

        $preserve_ratio = [
            '1' => "Oui",
            '0' => "Non",
        ];

        //dump($this->context->link->getModuleLink($this->module->name, "cron"));
        //die;

        $this->fields_list = array(
            'id_dri_config'=>array(
                'title' => $this->trans('ID', [], 'Modules.Diliosresizeimages.Admin'),
                'align'=>'center',
                'class'=>'fixed-width-xs'
            ),
            'related_path'=>array(
                'title'=>$this->trans('Chemin', [], 'Modules.Diliosresizeimages.Admin'),
                'width'=>'auto',
            ),
            'width'=>array(
                'title'=>$this->trans('Largeur', [], 'Modules.Diliosresizeimages.Admin'),
                'width'=>'auto',
                'callback' => "displayDimention"
            ),
            'height'=>array(
                'title'=>$this->trans('Hauteur', [], 'Modules.Diliosresizeimages.Admin'),
                'width'=>'auto',
                'callback' => "displayDimention"
            ),
            'preserve_ratio'=>array(
                'title'=>$this->trans('Ratio', [], 'Modules.Diliosresizeimages.Admin'),
                'callback'=>'preserveRatio',
                'type' => 'select',
                'color' => 'color',
                'list' => $preserve_ratio,
                'filter_key' => 'a!preserve_ratio',
                'filter_type' => 'int',
                'order_key' => 'preserve_ratio',
            ),
            'conserve_original'=>array(
                'title'=>$this->trans('Original concerver', [], 'Modules.Diliosresizeimages.Admin'),
                'callback'=>'preserveRatio',
                'type' => 'select',
                'color' => 'color',
                'list' => $preserve_ratio,
                'filter_key' => 'a!conserve_original',
                'filter_type' => 'int',
                'order_key' => 'conserve_original',
            ),
            'active' => array(
                'title' => $this->trans('Status', [], 'Admin.Global'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'orderby' => false,
            ),
            'date_add'=>array(
                'title'=>$this->trans('Date de création', [], 'Modules.Diliosresizeimages.Admin'),
                'type'=>'datetime',
                'width'=>'auto',
            )
        );
    }

    public function preserveRatio($id, $row) {
        if($id) {
            return '<span>Oui</span>';
        }
        return '<span>Non</span>';
    }

    public function displayDimention($id, $row) {
        return "<span>$id px</span>";
    }

    public function renderList()
    {
        return $this->renderCronMessage().parent::renderList();
    }

    public function renderCronMessage()
    {
        $link = $this->context->link->getModuleLink($this->module->name, "cron");
        return "<p class='alert alert-info'>
            ".$this->trans("Lien cron", [], 'Modules.Diliosresizeimages.Admin')."
            <a href='".$link."'> ".$link."</a>
        </p>";
    }

    /**
     * AdminController::renderForm() override.
     *
     * @see AdminController::renderForm()
     */
    public function renderForm()
    {
        

        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Images', [], 'Admin.Catalog.Feature'),
                'icon' => 'icon-info-sign',
            ],
            'input' => [

                [
                    'type' => 'text',
                    'label' => $this->trans('Chemin', [], 'Admin.Global'),
                    'name' => 'related_path',
                    'desc' => $this->trans('ex: /modules/mymodule/image_path', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Largeur', [], 'Admin.Global'),
                    'name' => 'width',
                    'required' => true,
                    'col' => '4',
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Hauteur', [], 'Admin.Catalog.Feature'),
                    'name' => 'height',
                    'col' => '4',
                    'hint' => $this->trans('The public name for this attribute, displayed to the customers.', [], 'Admin.Catalog.Help') . '&nbsp;' . $this->trans('Invalid characters:', [], 'Admin.Notifications.Info') . ' <>;=#{}',
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Conserver l\'original', [], 'Admin.Global'),
                    'name' => 'conserve_original',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'conserve_original_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', [], 'Admin.Global'),
                        ],
                        [
                            'id' => 'conserve_original_off',
                            'value' => 0,
                            'label' => $this->trans('No', [], 'Admin.Global'),
                        ],
                    ],
                    'hint' => $this->trans('Enable the image ratio.', [], 'Admin.Shipping.Help'),
                ],

                [
                    'type' => 'switch',
                    'label' => $this->trans('Preserve ratio', [], 'Admin.Global'),
                    'name' => 'preserve_ratio',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'preserve_ratio_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', [], 'Admin.Global'),
                        ],
                        [
                            'id' => 'preserve_ratio_off',
                            'value' => 0,
                            'label' => $this->trans('No', [], 'Admin.Global'),
                        ],
                    ],
                    'hint' => $this->trans('Enable the image ratio.', [], 'Admin.Shipping.Help'),
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Status', [], 'Admin.Global'),
                    'name' => 'active',
                    'required' => false,
                    'class' => 't',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Yes', [], 'Admin.Global'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('No', [], 'Admin.Global'),
                        ],
                    ],
                    'hint' => $this->trans('Enable the configs.', [], 'Admin.Shipping.Help'),
                ],
            ],
        ];
        

        $this->fields_form['submit'] = [
            'title' => $this->trans('Save', [], 'Admin.Actions'),
        ];

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        return parent::renderForm();
    }
}
