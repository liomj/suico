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
 * Class RegstepHandler
 */
class RegstepHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @param \XoopsDatabase|null $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'suico_profile_regstep', Regstep::class, 'step_id', 'step_name');
    }

    /**
     * Delete an object from the database
     * @param \XoopsObject $object
     * @param bool $force
     *
     * @return bool
     * @see XoopsPersistableObjectHandler
     */
    public function delete(\XoopsObject $object, $force = false)
    {
        if (parent::delete($object, $force)) {
            $fieldHandler = Helper::getInstance()->getHandler('Field');

            return $fieldHandler->updateAll('step_id', 0, new \Criteria('step_id', $object->getVar('step_id')), $force);
        }

        return false;
    }
}
