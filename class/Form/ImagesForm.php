<?php namespace XoopsModules\Yogurt\Form;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Module: Yogurt
 *
 * @category        Module
 * @package         yogurt
 * @author          XOOPS Development Team <https://xoops.org>
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GPL 2.0 or later
 * @link            https://xoops.org/
 * @since           1.0.0
 */

use Xmf\Request;
use XoopsModules\Yogurt;

require_once dirname(dirname(__DIR__)) . '/include/common.php';

$moduleDirName = basename(dirname(dirname(__DIR__)));
//$helper = Yogurt\Helper::getInstance();
$permHelper = new \Xmf\Module\Helper\Permission();

xoops_load('XoopsFormLoader');

/**
 * Class ImagesForm
 */
class ImagesForm extends \XoopsThemeForm
{
    public $targetObject;
    public $helper;

    /**
     * Constructor
     *
     * @param $target
     */
    public function __construct($target)
    {
        $this->helper       = $target->helper;
        $this->targetObject = $target;

        $title = $this->targetObject->isNew() ? sprintf(AM_YOGURT_IMAGES_ADD) : sprintf(AM_YOGURT_IMAGES_EDIT);
        parent::__construct($title, 'form', xoops_getenv('PHP_SELF'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        //include ID field, it's needed so the module knows if it is a new form or an edited form

        $hidden = new \XoopsFormHidden('cod_img', $this->targetObject->getVar('cod_img'));
        $this->addElement($hidden);
        unset($hidden);

        // Cod_img
        $this->addElement(new \XoopsFormLabel(AM_YOGURT_IMAGES_COD_IMG, $this->targetObject->getVar('cod_img'), 'cod_img'));
        // Title
        $this->addElement(new \XoopsFormText(AM_YOGURT_IMAGES_TITLE, 'title', 50, 255, $this->targetObject->getVar('title')), false);
        // Data_creation
        $this->addElement(new \XoopsFormTextDateSelect(AM_YOGURT_IMAGES_DATA_CREATION, 'data_creation', 0, strtotime($this->targetObject->getVar('data_creation'))));
        // Data_update
        $this->addElement(new \XoopsFormTextDateSelect(AM_YOGURT_IMAGES_DATA_UPDATE, 'data_update', 0, strtotime($this->targetObject->getVar('data_update'))));
        // Uid_owner
        $this->addElement(new \XoopsFormSelectUser(AM_YOGURT_IMAGES_UID_OWNER, 'uid_owner', false, $this->targetObject->getVar('uid_owner'), 1, false), false);
        // Url
        $this->addElement(new \XoopsFormTextArea(AM_YOGURT_IMAGES_URL, 'url', $this->targetObject->getVar('url'), 4, 47), false);
        // Private
        $this->addElement(new \XoopsFormText(AM_YOGURT_IMAGES_PRIVATE, 'private', 50, 255, $this->targetObject->getVar('private')), false);

        $this->addElement(new \XoopsFormHidden('op', 'save'));
        $this->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}