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

use Xmf\Module\Helper\Permission;
use XoopsDatabaseFactory;
use XoopsObject;

require_once XOOPS_ROOT_PATH . '/kernel/object.php';

/**
 * Configs class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Configs extends XoopsObject
{
    public \XoopsDatabase $db;
    public Helper         $helper;
    public Permission     $permHelper;
    public                $config_id;
    public $config_uid;
    public $pictures;
    public $audio;
    public $videos;
    public $groups;
    public $notes;
    public $friends;
    public $profile_contact;
    public $profile_general;
    public $profile_stats;
    public $suspension;
    public $backup_password;
    public $backup_email;
    public $end_suspension;
    // constructor

    /**
     * Configs constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        /** @var Helper $helper */
        $this->helper     = Helper::getInstance();
        $this->permHelper = new Permission();
        $this->db         = XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('config_id', \XOBJ_DTYPE_INT);
        $this->initVar('config_uid', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('pictures', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('audio', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('videos', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('groups', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('notes', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('friends', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('profile_contact', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('profile_general', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('profile_stats', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('suspension', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('backup_password', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('backup_email', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('end_suspension', \XOBJ_DTYPE_INT, 0, false);
        if (!empty($id)) {
            if (\is_array($id)) {
                $this->assignVars($id);
            } else {
                $this->load((int)$id);
            }
        } else {
            $this->setNew();
        }
    }

    /**
     * @param $id
     */
    public function load($id): void
    {
        $sql   = 'SELECT * FROM ' . $this->db->prefix('suico_configs') . ' WHERE config_id=' . $id;
        $myrow = $this->db->fetchArray($this->db->query($sql));
        $this->assignVars($myrow);
        if (!$myrow) {
            $this->setNew();
        }
    }

    /**
     * @param array  $criteria
     * @param bool   $asobject
     * @param string $sort
     * @param string $order
     * @param int    $limit
     * @param int    $start
     * @return array
     */
    public function getAllConfigs(
        $criteria = [],
        $asobject = false,
        $sort = 'config_id',
        $order = 'ASC',
        $limit = 0,
        $start = 0
    ) {
        $db         = XoopsDatabaseFactory::getDatabaseConnection();
        $ret        = [];
        $whereQuery = '';
        if (\is_array($criteria) && \count($criteria) > 0) {
            $whereQuery = ' WHERE';
            foreach ($criteria as $c) {
                $whereQuery .= " {$c} AND";
            }
            $whereQuery = mb_substr($whereQuery, 0, -4);
        } elseif (!\is_array($criteria) && $criteria) {
            $whereQuery = ' WHERE ' . $criteria;
        }
        if ($asobject) {
            $sql    = 'SELECT * FROM ' . $db->prefix('suico_configs') . "{$whereQuery} ORDER BY {$sort} {$order}";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = new self($myrow);
            }
        } else {
            $sql    = 'SELECT config_id FROM ' . $db->prefix(
                    'suico_configs'
                ) . "{$whereQuery} ORDER BY {$sort} {$order}";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = $myrow['suico_configs_id'];
            }
        }

        return $ret;
    }

    /**
     * Get form
     *
     * @return \XoopsModules\Suico\Form\ConfigsForm
     */
    public function getForm()
    {
        return new Form\ConfigsForm($this);
    }

    /**
     * @return array|null
     */
    public function getGroupsRead()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem(
            'sbcolumns_read',
            $this->getVar('config_id')
        );
    }

    /**
     * @return array|null
     */
    public function getGroupsSubmit()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem(
            'sbcolumns_submit',
            $this->getVar('config_id')
        );
    }

    /**
     * @return array|null
     */
    public function getGroupsModeration()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem(
            'sbcolumns_moderation',
            $this->getVar('config_id')
        );
    }
}
