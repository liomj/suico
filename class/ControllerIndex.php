<?php

namespace XoopsModules\Yogurt;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author       Marcello Brandão aka  Suico
 * @author       XOOPS Development Team
 * @since
 */
require_once XOOPS_ROOT_PATH . '/kernel/object.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/criteria.php';
require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
/**
 * Module classes
 */
//require_once __DIR__ . '/Image.php';
//require_once __DIR__ . '/yogurt_visitors.php';
//require_once __DIR__ . '/Video.php';
//require_once __DIR__ . '/yogurt_audio.php';
//require_once __DIR__ . '/Friendpetition.php';
//require_once __DIR__ . '/Friendship.php';
//require_once __DIR__ . '/Reltribeuser.php';
//require_once __DIR__ . '/Tribes.php';
//require_once __DIR__ . '/Notes.php';
//require_once __DIR__ . '/Configs.php';
//require_once __DIR__ . '/Suspensions.php';
if (str_replace('.', '', PHP_VERSION) > 499) {
    require_once __DIR__ . '/class.Id3v1.php';
}

/**
 * Class ControllerIndex
 */
class ControllerIndex extends YogurtController
{
    /**
     * @param null|string $section
     * @return int|void
     */
    public function checkPrivilege($section = null)
    {
        global $xoopsModuleConfig;
        if ('' == trim($section)) {
            return -1;
        }
        $configsectionname = 'enable_' . $section;
        if (array_key_exists($configsectionname, $xoopsModuleConfig)) {
            if (0 == $xoopsModuleConfig[$configsectionname]) {
                return -1;
            }
        }

        //  if ($section=="Notes" && $xoopsModuleConfig['enable_notes']==0){
        //          return false;
        //      }
        //      if ($section=="pictures" && $xoopsModuleConfig['enable_pictures']==0){
        //          return false;
        //      }
        //
        //      if ($section=="pictures" && $xoopsModuleConfig['enable_pictures']==0){
        //          return false;
        //      }
        $criteria = new \Criteria('config_uid', $this->owner->getVar('uid'));
        if (1 == $this->configsFactory->getCount($criteria)) {
            $configs = $this->configsFactory->getObjects($criteria);

            $config = $configs[0]->getVar($section);

            if (!$this->checkPrivilegeLevel($config)) {
                return 0;
            }
        }

        return 1;
    }
}
