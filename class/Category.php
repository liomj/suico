<?php declare(strict_types=1);

namespace XoopsModules\Suico;

/**
 * Extended User Profile
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       (c) 2000-2016 XOOPS Project (www.xoops.org)
 * @license             GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 * @since               2.3.0
 * @author              Jan Pedersen
 * @author              Taiwen Jiang <phppp@users.sourceforge.net>
 */

/**
 * @copyright       (c) 2000-2016 XOOPS Project (www.xoops.org)
 */
class Category extends \XoopsObject
{
    public $cat_id;
    public $cat_title;
    public $cat_description;
    public $cat_weight;

    public function __construct()
    {
        $this->initVar('cat_id', \XOBJ_DTYPE_INT, null, true);
        $this->initVar('cat_title', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_description', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('cat_weight', \XOBJ_DTYPE_INT);
    }

    /**
     * Get {@link XoopsThemeForm} for adding/editing categories
     *
     * @param mixed $action URL to submit to or false for $_SERVER['REQUEST_URI']
     *
     * @return object
     */
    public function getForm($action = false)
    {
        if (false === $action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = $this->isNew() ? \sprintf(\_AM_SUICO_ADD, \_AM_SUICO_CATEGORY) : \sprintf(\_AM_SUICO_EDIT, \_AM_SUICO_CATEGORY);
        require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->addElement(new \XoopsFormText(\_AM_SUICO_TITLE, 'cat_title', 35, 255, $this->getVar('cat_title')));
        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new \XoopsFormHidden('id', $this->getVar('cat_id')));
        }
        $form->addElement(new \XoopsFormTextArea(\_AM_SUICO_DESCRIPTION, 'cat_description', $this->getVar('cat_description', 'e')));
        $form->addElement(new \XoopsFormText(\_AM_SUICO_WEIGHT, 'cat_weight', 35, 35, $this->getVar('cat_weight', 'e')));
        $form->addElement(new \XoopsFormHidden('op', 'save'));
        $form->addElement(new \XoopsFormButton('', 'submit', \_SUBMIT, 'submit'));

        return $form;
    }
}
