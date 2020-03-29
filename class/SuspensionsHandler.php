<?php

namespace XoopsModules\Yogurt;

// Suspensions.php,v 1
//  ---------------------------------------------------------------- //
// Author: Bruno Barthez	                                           //
// ----------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH.'/kernel/object.php';

// -------------------------------------------------------------------------
// ------------------Suspensions user handler class -------------------
// -------------------------------------------------------------------------

/**
 * Suspensionshandler class.
 * This class provides simple mecanisme for Suspensions object
 */
class SuspensionsHandler extends \XoopsObjectHandler
{

	/**
	 * create a new Suspensions
	 *
	 * @param bool $isNew flag the new objects as "new"?
	 * @return \XoopsObject Suspensions
	 */
	public function create($isNew = true)
	{
		$suspensions = new Suspensions();
		if ($isNew) {
			$suspensions->setNew();
		} else {
			$suspensions->unsetNew();
		}

		return $suspensions;
	}

	/**
	 * retrieve a Suspensions
	 *
	 * @param int $id of the Suspensions
	 * @return mixed reference to the {@link Suspensions} object, FALSE if failed
	 */
	public function get($id)
	{
		$sql = 'SELECT * FROM ' . $this->db->prefix('yogurt_suspensions') . ' WHERE uid=' . $id;
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		$numrows = $this->db->getRowsNum($result);
		if (1 == $numrows) {
			$suspensions = new Suspensions();
			$suspensions->assignVars($this->db->fetchArray($result));
			return $suspensions;
		}
		return false;
	}

	/**
	 * insert a new Suspensions in the database
	 *
	 * @param \XoopsObject $suspensions        reference to the {@link Suspensions}
	 *                                         object
	 * @param bool         $force
	 * @return bool FALSE if failed, TRUE if already present and unchanged or successful
	 */
	public function insert(\XoopsObject $suspensions, $force = false)
	{
		global $xoopsConfig;
		if (!$suspensions instanceof \Suspensions) {
			return false;
		}
		if (!$suspensions->isDirty()) {
			return true;
		}
		if (!$suspensions->cleanVars()) {
			return false;
		}
		foreach ($suspensions->cleanVars as $k => $v) {
			${$k} = $v;
		}
		$now = 'date_add(now(), interval ' . $xoopsConfig['server_TZ'] . ' hour)';
		if ($suspensions->isNew()) {
			// ajout/modification d'un Suspensions
			$suspensions = new Suspensions();
			$format      = 'INSERT INTO %s (uid, old_pass, old_email, old_signature, suspension_time)';
			$format      .= 'VALUES (%u, %s, %s, %s, %u)';
			$sql         = sprintf($format, $this->db->prefix('yogurt_suspensions'), $uid, $this->db->quoteString($old_pass), $this->db->quoteString($old_email), $this->db->quoteString($old_signature), $suspension_time);
			$force       = true;
		} else {
			$format = 'UPDATE %s SET ';
			$format .= 'uid=%u, old_pass=%s, old_email=%s, old_signature=%s, suspension_time=%u';
			$format .= ' WHERE uid = %u';
			$sql    = sprintf($format, $this->db->prefix('yogurt_suspensions'), $uid, $this->db->quoteString($old_pass), $this->db->quoteString($old_email), $this->db->quoteString($old_signature), $suspension_time, $uid);
		}
		if (false !== $force) {
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
		$suspensions->assignVar('uid', $uid);
		return true;
	}

	/**
	 * delete a Suspensions from the database
	 *
	 * @param \XoopsObject $suspensions reference to the Suspensions to delete
	 * @param bool         $force
	 * @return bool FALSE if failed.
	 */
	public function delete(\XoopsObject $suspensions, $force = false)
	{
		if (!$suspensions instanceof \Suspensions) {
			return false;
		}
		$sql = sprintf('DELETE FROM %s WHERE uid = %u', $this->db->prefix('yogurt_suspensions'), $suspensions->getVar('uid'));
		if (false !== $force) {
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
	 * retrieve yogurt_suspensionss from the database
	 *
	 * @param CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
	 * @param bool            $id_as_key use the UID as key for the array?
	 * @return array array of {@link Suspensions} objects
	 */
	public function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret   = [];
		$limit = $start = 0;
		$sql   = 'SELECT * FROM ' . $this->db->prefix('yogurt_suspensions');
		if (isset($criteria) && $criteria instanceof \criteriaelement) {
			$sql .= ' ' . $criteria->renderWhere();
			if ('' != $criteria->getSort()) {
				$sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$suspensions = new Suspensions();
			$suspensions->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $suspensions;
			} else {
				$ret[$myrow['uid']] =& $suspensions;
			}
			unset($suspensions);
		}
		return $ret;
	}

	/**
	 * count yogurt_suspensionss matching a condition
	 *
	 * @param CriteriaElement $criteria {@link CriteriaElement} to match
	 * @return int count of yogurt_suspensionss
	 */
	public function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('yogurt_suspensions');
		if (isset($criteria) && $criteria instanceof \criteriaelement) {
			$sql .= ' ' . $criteria->renderWhere();
		}
		$result = $this->db->query($sql);
		if (!$result) {
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}

	/**
	 * delete yogurt_suspensionss matching a set of conditions
	 *
	 * @param CriteriaElement $criteria {@link CriteriaElement}
	 * @return bool FALSE if deletion failed
	 */
	public function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM ' . $this->db->prefix('yogurt_suspensions');
		if (isset($criteria) && $criteria instanceof \criteriaelement) {
			$sql .= ' ' . $criteria->renderWhere();
		}
		if (!$result = $this->db->queryF($sql)) {
			return false;
		}
		return true;
	}
}