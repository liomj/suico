<?php declare(strict_types=1);

namespace XoopsModules\Suico;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * @category        Module
 * @copyright       {@link https://xoops.org/ XOOPS Project}
 * @license         GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Bruno Barthez, Marcello Brandão aka  Suico, Mamba, LioMJ  <https://xoops.org>
 */

use CriteriaElement;
use XoopsDatabase;
use XoopsObject;
use XoopsPersistableObjectHandler;

require_once XOOPS_ROOT_PATH . '/kernel/object.php';

/**
 * Suspensionshandler class.
 * This class provides simple mechanism for Suspensions object
 */
class SuspensionsHandler extends XoopsPersistableObjectHandler
{
    public Helper $helper;
    public        $isAdmin;

    /**
     * Constructor
     * @param \XoopsDatabase|null             $xoopsDatabase
     * @param \XoopsModules\Suico\Helper|null $helper
     */
    public function __construct(
        ?XoopsDatabase $xoopsDatabase = null,
        $helper = null
    ) {
        /** @var \XoopsModules\Suico\Helper $this- >helper */
        if (null === $helper) {
            $this->helper = Helper::getInstance();
        } else {
            $this->helper = $helper;
        }
        $this->isAdmin = $this->helper->isUserAdmin();
        parent::__construct($xoopsDatabase, 'suico_suspensions', Suspensions::class, 'uid', 'uid');
    }

    /**
     * create a new Groups
     *
     * @param bool $isNew flag the new objects as "new"?
     * @return \XoopsObject Groups
     */
    public function create(
        $isNew = true
    ) {
        $obj = parent::create($isNew);
        if ($isNew) {
            $obj->setNew();
        } else {
            $obj->unsetNew();
        }
        $obj->helper = $this->helper;

        return $obj;
    }

    /**
     * retrieve a Suspensions
     *
     * @param int|null $id of the Suspensions
     * @param null     $fields
     * @return false|\XoopsModules\Suico\Suspensions reference to the {@link Suspensions} object, FALSE if failed
     */
    public function get2(
        $id = null,
        $fields = null
    ) {
        $sql = 'SELECT * FROM ' . $this->db->prefix('suico_suspensions') . ' WHERE uid=' . $id;
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $numrows = $this->db->getRowsNum($result);
        if (1 === $numrows) {
            $suspensions = new Suspensions();
            $suspensions->assignVars($this->db->fetchArray($result));

            return $suspensions;
        }

        return false;
    }

    /**
     * insert a new Suspensions in the database
     *
     * @param \XoopsObject $object        reference to the {@link Suspensions}
     *                                         object
     * @param bool         $force
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert2(
        XoopsObject $object,
        $force = false
    ) {
        global $xoopsConfig;
        if (!$object instanceof Suspensions) {
            return false;
        }
        if (!$object->isDirty()) {
            return true;
        }
        if (!$object->cleanVars()) {
            return false;
        }
        $suspension_time = '';
        foreach ($object->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        //        $now = 'date_add(now(), interval ' . $xoopsConfig['server_TZ'] . ' hour)';
        $uid = 0;
        if ($object->isNew()) {
            // ajout/modification d'un Suspensions
            $object = new Suspensions();
            $format      = 'INSERT INTO %s (uid, old_pass, old_email, old_signature, suspension_time)';
            $format      .= 'VALUES (%u, %s, %s, %s, %u)';
            $sql         = \sprintf(
                $format,
                $this->db->prefix('suico_suspensions'),
                $uid,
                $this->db->quoteString($old_pass),
                $this->db->quoteString($old_email),
                $this->db->quoteString($old_signature),
                $suspension_time
            );
            $force       = true;
        } else {
            $format = 'UPDATE %s SET ';
            $format .= 'uid=%u, old_pass=%s, old_email=%s, old_signature=%s, suspension_time=%u';
            $format .= ' WHERE uid = %u';
            $sql    = \sprintf(
                $format,
                $this->db->prefix('suico_suspensions'),
                $uid,
                $this->db->quoteString($old_pass),
                $this->db->quoteString($old_email),
                $this->db->quoteString($old_signature),
                $suspension_time,
                $uid
            );
        }
        if ($force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        if (empty($uid)) {
            $uid = $this->db->getInsertId();
        }
        $object->assignVar('uid', $uid);

        return true;
    }

    /**
     * delete a Suspensions from the database
     *
     * @param \XoopsObject $object reference to the Suspensions to delete
     * @param bool         $force
     * @return bool FALSE if failed.
     */
    public function delete(
        XoopsObject $object,
        $force = false
    ) {
        if (!$object instanceof Suspensions) {
            return false;
        }
        $sql = \sprintf(
            'DELETE FROM %s WHERE uid = %u',
            $this->db->prefix('suico_suspensions'),
            (int)$object->getVar('uid')
        );
        if ($force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * retrieve suico_suspensionss from the database
     *
     * @param \CriteriaElement|\CriteriaCompo|null $criteria {@link \CriteriaElement} conditions to be met
     * @param bool                                 $id_as_key       use the UID as key for the array?
     * @param bool                                 $as_object
     * @return array array of {@link Suspensions} objects
     */
    public function &getObjects(
        ?CriteriaElement $criteria = null,
        $id_as_key = false,
        $as_object = true
    ) {
        $ret   = [];
        $start = 0;
        $limit = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('suico_suspensions');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' !== $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $suspensions = new Suspensions();
            $suspensions->assignVars($myrow);
            if ($id_as_key) {
                $ret[$myrow['uid']] = &$suspensions;
            } else {
                $ret[] = &$suspensions;
            }
            unset($suspensions);
        }

        return $ret;
    }

    /**
     * count suico_suspensionss matching a condition
     *
     * @param \CriteriaElement|\CriteriaCompo|null $criteria {@link \CriteriaElement} to match
     * @return int count of suico_suspensionss
     */
    public function getCount(
        ?CriteriaElement $criteria = null
    ) {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('suico_suspensions');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        [$count] = $this->db->fetchRow($result);

        return (int)$count;
    }

    /**
     * delete suico_suspensionss matching a set of conditions
     *
     * @param \CriteriaElement|\CriteriaCompo|null $criteria {@link \CriteriaElement}
     * @param bool                                 $force
     * @param bool                                 $asObject
     * @return bool FALSE if deletion failed
     */
    public function deleteAll(
        ?CriteriaElement $criteria = null,
        $force = true,
        $asObject = false
    ) {
        $sql = 'DELETE FROM ' . $this->db->prefix('suico_suspensions');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return true;
    }
}
