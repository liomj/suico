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

use XoopsModules\Suico;

/**
 * Class Field
 */
class Field extends \XoopsObject
{
    public $field_id;
    public $cat_id;
    public $field_type;
    public $field_valuetype;
    public $field_name;
    public $field_title;
    public $field_description;
    public $field_required;
    public $field_maxlength;
    public $field_weight;
    public $field_default;
    public $field_notnull;
    public $field_edit;
    public $field_show;
    public $field_config;
    public $field_options;
    public $step_id;

    public function __construct()
    {
        $this->initVar('field_id', \XOBJ_DTYPE_INT, null);
        $this->initVar('cat_id', \XOBJ_DTYPE_INT, null, true);
        $this->initVar('field_type', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_valuetype', \XOBJ_DTYPE_INT, null, true);
        $this->initVar('field_name', \XOBJ_DTYPE_TXTBOX, null, true);
        $this->initVar('field_title', \XOBJ_DTYPE_TXTBOX);
        $this->initVar('field_description', \XOBJ_DTYPE_TXTAREA);
        $this->initVar('field_required', \XOBJ_DTYPE_INT, 0); //0 = no, 1 = yes
        $this->initVar('field_maxlength', \XOBJ_DTYPE_INT, 0);
        $this->initVar('field_weight', \XOBJ_DTYPE_INT, 0);
        $this->initVar('field_default', \XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('field_notnull', \XOBJ_DTYPE_INT, 1);
        $this->initVar('field_edit', \XOBJ_DTYPE_INT, 0);
        $this->initVar('field_show', \XOBJ_DTYPE_INT, 0);
        $this->initVar('field_config', \XOBJ_DTYPE_INT, 0);
        $this->initVar('field_options', \XOBJ_DTYPE_ARRAY, []);
        $this->initVar('step_id', \XOBJ_DTYPE_INT, 0);
    }

    /**
     * Extra treatment dealing with non latin encoding
     * Tricky solution
     * @param string $key
     * @param mixed  $value
     * @param bool   $not_gpc
     */
    public function setVar($key, $value, $not_gpc = false): void
    {
        if ('field_options' === $key && \is_array($value)) {
            foreach (\array_keys($value) as $idx) {
                $value[$idx] = \base64_encode($value[$idx]);
            }
        }
        parent::setVar($key, $value, $not_gpc);
    }

    /**
     * @param string $key
     * @param string $format
     *
     * @return mixed
     */
    public function getVar($key, $format = 's')
    {
        $value = parent::getVar($key, $format);
        if ('field_options' === $key && !empty($value)) {
            foreach (\array_keys($value) as $idx) {
                $value[$idx] = \base64_decode($value[$idx], true);
            }
        }

        return $value;
    }

    /**
     * Returns a {@link XoopsFormElement} for editing the value of this field
     *
     * @param \XoopsUser $user    {@link \XoopsUser} object to edit the value of
     * @param Profile    $profile {@link Profile} object to edit the value of
     *
     * @return \XoopsFormCheckBox|\XoopsFormDatetime|\XoopsFormDhtmlTextArea|\XoopsFormLabel|\XoopsFormRadio|\XoopsFormRadioYN|\XoopsFormSelect|\XoopsFormSelectGroup|\XoopsFormSelectLang|\XoopsFormSelectTheme|\XoopsFormSelectTimezone|\XoopsFormText|\XoopsFormTextArea|\XoopsFormTextDateSelect
     */
    public function getEditElement($user, $profile)
    {
        $value   = \in_array($this->getVar('field_name'), $this->getUserVars(), true) ? $user->getVar($this->getVar('field_name'), 'e') : $profile->getVar($this->getVar('field_name'), 'e');
        $caption = $this->getVar('field_title');
        $caption = \defined($caption) ? \constant($caption) : $caption;
        $name    = $this->getVar('field_name', 'e');
        $options = $this->getVar('field_options');
        if (\is_array($options)) {
            //asort($options);
            foreach (\array_keys($options) as $key) {
                $optval = \defined($options[$key]) ? \constant($options[$key]) : $options[$key];
                $optkey = \defined((string)$key) ? \constant($key) : $key;
                unset($options[$key]);
                $options[$optkey] = $optval;
            }
        }
        require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        switch ($this->getVar('field_type')) {
            default:
            case 'autotext':
                //autotext is not for editing
                $element = new \XoopsFormLabel($caption, $this->getOutputValue($user, $profile));
                break;
            case 'textbox':
                $element = new \XoopsFormText($caption, $name, 35, $this->getVar('field_maxlength'), $value);
                break;
            case 'textarea':
                $element = new \XoopsFormTextArea($caption, $name, $value, 4, 30);
                break;
            case 'dhtml':
                $element = new \XoopsFormDhtmlTextArea($caption, $name, $value, 10, 30);
                break;
            case 'select':
                $element = new \XoopsFormSelect($caption, $name, $value);
                // If options do not include an empty element, then add a blank option to prevent any default selection
                //                if (!in_array('', array_keys($options))) {
                if (!\array_key_exists('', $options)) {
                    $element->addOption('', \_NONE);
                    $eltmsg                          = empty($caption) ? \sprintf(\_FORM_ENTER, $name) : \sprintf(\_FORM_ENTER, $caption);
                    $eltmsg                          = \str_replace('"', '\"', \stripslashes($eltmsg));
                    $element->customValidationCode[] = "\nvar hasSelected = false; var selectBox = myform.{$name};"
                                                       . "for (i = 0; i < selectBox.options.length; i++) { if (selectBox.options[i].selected === true && selectBox.options[i].value != '') { hasSelected = true; break; } }"
                                                       . "if (!hasSelected) { window.alert(\"{$eltmsg}\"); selectBox.focus(); return false; }";
                }
                $element->addOptionArray($options);
                break;
            case 'select_multi':
                $element = new \XoopsFormSelect($caption, $name, $value, 5, true);
                $element->addOptionArray($options);
                break;
            case 'radio':
                $element = new \XoopsFormRadio($caption, $name, $value);
                $element->addOptionArray($options);
                break;
            case 'checkbox':
                $element = new \XoopsFormCheckBox($caption, $name, $value);
                $element->addOptionArray($options);
                break;
            case 'yesno':
                $element = new \XoopsFormRadioYN($caption, $name, $value);
                break;
            case 'group':
                $element = new \XoopsFormSelectGroup($caption, $name, true, $value);
                break;
            case 'group_multi':
                $element = new \XoopsFormSelectGroup($caption, $name, true, $value, 5, true);
                break;
            case 'language':
                $element = new \XoopsFormSelectLang($caption, $name, $value);
                break;
            case 'date':
                $element = new \XoopsFormTextDateSelect($caption, $name, 15, $value);
                break;
            case 'longdate':
                $element = new \XoopsFormTextDateSelect($caption, $name, 15, \str_replace('-', '/', $value));
                break;
            case 'datetime':
                $element = new \XoopsFormDatetime($caption, $name, 15, $value);
                break;
            case 'timezone':
                $element = new \XoopsFormSelectTimezone($caption, $name, $value);
                $element->setExtra("style='width: 280px;'");
                break;
            case 'rank':
                $element = new \XoopsFormSelect($caption, $name, $value);
                require_once $GLOBALS['xoops']->path('class/xoopslists.php');
                $ranks = \XoopsLists::getUserRankList();
                $element->addOption(0, '--------------');
                $element->addOptionArray($ranks);
                break;
            case 'theme':
                $element = new \XoopsFormSelectTheme($caption, $name, $value, 1, true);
                break;
        }
        if ('' != $this->getVar('field_description')) {
            $element->setDescription($this->getVar('field_description'));
        }

        return $element;
    }

    /**
     * Returns a value for output of this field
     *
     * @param \XoopsUser $user    {@link XoopsUser} object to get the value of
     * @param Profile    $profile object to get the value of
     *
     * @return mixed
     **/
    public function getOutputValue($user, $profile)
    {
        \xoops_loadLanguage('modinfo', 'suico');

        $value = \in_array($this->getVar('field_name'), $this->getUserVars(), true) ? $user->getVar($this->getVar('field_name')) : $profile->getVar($this->getVar('field_name'));
        switch ($this->getVar('field_type')) {
            default:
            case 'textbox':
                $value = \is_array($value) ? $value[0] : $value;
                if ('url' === $this->getVar('field_name') && '' !== $value) {
                    return '<a href="' . \formatURL($value) . '" rel="external">' . $value . '</a>';
                }

                return $value;
                break;
            case 'textarea':
            case 'dhtml':
            case 'theme':
            case 'language':
                return $value;
                break;
            case 'select':
            case 'radio':
                $value   = \is_array($value) ? $value[0] : $value;
                $options = $this->getVar('field_options');
                if (isset($options[$value])) {
                    $value = \htmlspecialchars(\defined($options[$value]) ? \constant($options[$value]) : $options[$value], \ENT_QUOTES | \ENT_HTML5);
                } else {
                    $value = '';
                }

                return $value;
                break;
            case 'select_multi':
            case 'checkbox':
                $options = $this->getVar('field_options');
                $ret     = [];
                if (\count($options) > 0) {
                    foreach (\array_keys($options) as $key) {
                        if (\in_array($key, $value, true)) {
                            $ret[$key] = \htmlspecialchars(\defined($options[$key]) ? \constant($options[$key]) : $options[$key], \ENT_QUOTES | \ENT_HTML5);
                        }
                    }
                }

                return $ret;
                break;
            case 'group':
                /** @var \XoopsMemberHandler $memberHandler */ $memberHandler = \xoops_getHandler('member');
                $options                                                      = $memberHandler->getGroupList();
                $ret                                                          = $options[$value] ?? '';

                return $ret;
                break;
            case 'group_multi':
                /** @var \XoopsMemberHandler $memberHandler */ $memberHandler = \xoops_getHandler('member');
                $options                                                      = $memberHandler->getGroupList();
                $ret                                                          = [];
                foreach (\array_keys($options) as $key) {
                    if (\in_array($key, $value, true)) {
                        $ret[$key] = \htmlspecialchars($options[$key], \ENT_QUOTES | \ENT_HTML5);
                    }
                }

                return $ret;
                break;
            case 'longdate':
                //return YYYY/MM/DD format - not optimal as it is not using local date format, but how do we do that
                //when we cannot convert it to a UNIX timestamp?
                return \str_replace('-', '/', $value);
            case 'date':
                return \formatTimestamp($value, 's');
                break;
            case 'datetime':
                if (!empty($value)) {
                    return \formatTimestamp($value, 'm');
                }

                return $value = \_MI_SUICO_NEVER_LOGGED_IN;
                break;
            case 'autotext':
                $value = $user->getVar($this->getVar('field_name'), 'n'); //autotext can have HTML in it
                $value = \str_replace('{X_UID}', $user->getVar('uid'), $value);
                $value = \str_replace('{X_URL}', XOOPS_URL, $value);
                $value = \str_replace('{X_UNAME}', $user->getVar('uname'), $value);

                return $value;
                break;
            case 'rank':
                $userrank       = $user->rank();
                $user_rankimage = '';
                if (isset($userrank['image']) && '' !== $userrank['image']) {
                    $user_rankimage = '<img src="' . \XOOPS_UPLOAD_URL . '/' . $userrank['image'] . '" alt="' . $userrank['title'] . '"> ';
                }

                return $user_rankimage . $userrank['title'];
                break;
            case 'yesno':
                return $value ? \_YES : \_NO;
                break;
            case 'timezone':
                require_once $GLOBALS['xoops']->path('class/xoopslists.php');
                $timezones = \XoopsLists::getTimeZoneList();
                $value     = empty($value) ? '0' : (string)$value;

                return $timezones[\str_replace('.0', '', $value)];
                break;
        }
    }

    /**
     * Returns a value ready to be saved in the database
     *
     * @param mixed $value Value to format
     *
     * @return mixed
     */
    public function getValueForSave($value)
    {
        switch ($this->getVar('field_type')) {
            default:
            case 'textbox':
            case 'textarea':
            case 'dhtml':
            case 'yesno':
            case 'timezone':
            case 'theme':
            case 'language':
            case 'select':
            case 'radio':
            case 'select_multi':
            case 'group':
            case 'group_multi':
            case 'longdate':
                return $value;
            case 'checkbox':
                return (array)$value;
            case 'date':
                if ('' !== $value) {
                    return \strtotime($value);
                }

                return $value;
                break;
            case 'datetime':
                if (!empty($value)) {
                    return \strtotime($value['date']) + (int)$value['time'];
                }

                return $value;
                break;
        }
    }

    /**
     * Get names of user variables
     *
     * @return array
     */
    public function getUserVars()
    {
        /** @var Suico\ProfileHandler $profileHandler */
        $helper         = Helper::getInstance();
        $profileHandler = $helper->getHandler('Profile');

        return $profileHandler->getUserVars();
    }
}
