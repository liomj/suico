<?php

namespace XoopsModules\Yogurt;

// Configs.php,v 1
//  ---------------------------------------------------------------- //
// Author: Bruno Barthez	                                           //
// ----------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH.'/kernel/object.php';

// -------------------------------------------------------------------------
// ------------------Configs user handler class -------------------
// -------------------------------------------------------------------------

/**
 * yogurt_configshandler class.
 * This class provides simple mecanisme for Configs object
 */
class ConfigsHandler extends \XoopsObjectHandler
{

	/**
	 * create a new Configs
	 *
	 * @param bool $isNew flag the new objects as "new"?
	 * @return \XoopsObject Configs
	 */
	public function create($isNew = true)
	{
		$yogurt_configs = new Configs();
		if ($isNew) {
			$yogurt_configs->setNew();
		} else {
			$yogurt_configs->unsetNew();
		}

		return $yogurt_configs;
	}

	/**
	 * retrieve a Configs
	 *
	 * @param int $id of the Configs
	 * @return mixed reference to the {@link Configs} object, FALSE if failed
	 */
	public function get($id)
	{
		$sql = 'SELECT * FROM ' . $this->db->prefix('yogurt_configs') . ' WHERE config_id=' . $id;
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		$numrows = $this->db->getRowsNum($result);
		if (1 == $numrows) {
			$yogurt_configs = new Configs();
			$yogurt_configs->assignVars($this->db->fetchArray($result));
			return $yogurt_configs;
		}
		return false;
	}

	/**
	 * insert a new Configs in the database
	 *
	 * @param \XoopsObject $yogurt_configs reference to the {@link Configs}
	 *                                     object
	 * @param bool         $force
	 * @return bool FALSE if failed, TRUE if already present and unchanged or successful
	 */
	public function insert(\XoopsObject $yogurt_configs, $force = false)
	{
		global $xoopsConfig;
		if (!$yogurt_configs instanceof \Configs) {
			return false;
		}
		if (!$yogurt_configs->isDirty()) {
			return true;
		}
		if (!$yogurt_configs->cleanVars()) {
			return false;
		}
		foreach ($yogurt_configs->cleanVars as $k => $v) {
			${$k} = $v;
		}
		$now = 'date_add(now(), interval ' . $xoopsConfig['server_TZ'] . ' hour)';
		if ($yogurt_configs->isNew()) {
			// ajout/modification d'un Configs
			$yogurt_configs = new Configs();
			$format         = 'INSERT INTO %s (config_id, config_uid, pictures, audio, videos, tribes, Notes, friends, profile_contact, profile_general, profile_stats, suspension, backup_password, backup_email, end_suspension)';
			$format         .= 'VALUES (%u, %u, %u, %u, %u, %u, %u, %u, %u, %u, %u, %u, %s, %s, %s)';
			$sql            = sprintf(
				$format,
				$this->db->prefix('yogurt_configs'),
				$config_id,
				$config_uid,
				$pictures,
				$audio,
				$videos,
				$tribes,
				$Notes,
				$friends,
				$profile_contact,
				$profile_general,
				$profile_stats,
				$suspension,
				$this->db->quoteString($backup_password),
				$this->db->quoteString($backup_email),
				$this->db->quoteString($end_suspension)
			);
			$force          = true;
		} else {
			$format = 'UPDATE %s SET ';
			$format .= 'config_id=%u, config_uid=%u, pictures=%u, audio=%u, videos=%u, tribes=%u, Notes=%u, friends=%u, profile_contact=%u, profile_general=%u, profile_stats=%u, suspension=%u, backup_password=%s, backup_email=%s, end_suspension=%s';
			$format .= ' WHERE config_id = %u';
			$sql    = sprintf(
				$format,
				$this->db->prefix('yogurt_configs'),
				$config_id,
				$config_uid,
				$pictures,
				$audio,
				$videos,
				$tribes,
				$Notes,
				$friends,
				$profile_contact,
				$profile_general,
				$profile_stats,
				$suspension,
				$this->db->quoteString($backup_password),
				$this->db->quoteString($backup_email),
				$this->db->quoteString($end_suspension),
				$config_id
			);
		}
		if (false !== $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($config_id)) {
			$config_id = $this->db->getInsertId();
		}
		$yogurt_configs->assignVar('config_id', $config_id);
		return true;
	}

	/**
	 * delete a Configs from the database
	 *
	 * @param \XoopsObject $yogurt_configs reference to the Configs to delete
	 * @param bool         $force
	 * @return bool FALSE if failed.
	 */
	public function delete(\XoopsObject $yogurt_configs, $force = false)
	{
		if (!$yogurt_configs instanceof \Configs) {
			return false;
		}
		$sql = sprintf('DELETE FROM %s WHERE config_id = %u', $this->db->prefix('yogurt_configs'), $yogurt_configs->getVar('config_id'));
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
	 * retrieve yogurt_configss from the database
	 *
	 * @param CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
	 * @param bool            $id_as_key use the UID as key for the array?
	 * @return array array of {@link Configs} objects
	 */
	public function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret   = [];
		$limit = $start = 0;
		$sql   = 'SELECT * FROM ' . $this->db->prefix('yogurt_configs');
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
			$yogurt_configs = new Configs();
			$yogurt_configs->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $yogurt_configs;
			} else {
				$ret[$myrow['config_id']] =& $yogurt_configs;
			}
			unset($yogurt_configs);
		}
		return $ret;
	}

	/**
	 * count yogurt_configss matching a condition
	 *
	 * @param CriteriaElement $criteria {@link CriteriaElement} to match
	 * @return int count of yogurt_configss
	 */
	public function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('yogurt_configs');
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
	 * delete yogurt_configss matching a set of conditions
	 *
	 * @param CriteriaElement $criteria {@link CriteriaElement}
	 * @return bool FALSE if deletion failed
	 */
	public function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM ' . $this->db->prefix('yogurt_configs');
		if (isset($criteria) && $criteria instanceof \criteriaelement) {
			$sql .= ' ' . $criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
}