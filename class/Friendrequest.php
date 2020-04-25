<?php declare(strict_types=1);

namespace XoopsModules\Yogurt;

// Friendrequest.php,v 1
//  ---------------------------------------------------------------- //
// Author: Bruno Barthez                                               //
// ----------------------------------------------------------------- //

use Xmf\Module\Helper\Permission;
use XoopsDatabaseFactory;
use XoopsObject;

require_once XOOPS_ROOT_PATH . '/kernel/object.php';

/**
 * Friendrequest class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Friendrequest extends XoopsObject
{
    public $db;
    public $helper;
    public $permHelper;

    // constructor

    /**
     * Friendrequest constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        /** @var Helper $helper */
        $this->helper     = Helper::getInstance();
        $this->permHelper = new Permission();
        $this->db         = XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('friendpet_id', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('friendrequester_uid', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('friendrequestto_uid', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('date_created', \XOBJ_DTYPE_INT, 0, false);
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
    public function load($id)
    {
        $sql   = 'SELECT * FROM ' . $this->db->prefix('yogurt_friendrequest') . ' WHERE friendpet_id=' . $id;
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
    public function getAllFriendrequests(
        $criteria = [],
        $asobject = false,
        $sort = 'friendpet_id',
        $order = 'ASC',
        $limit = 0,
        $start = 0
    ) {
        $db          = XoopsDatabaseFactory::getDatabaseConnection();
        $ret         = [];
        $whereQuery = '';
        if (\is_array($criteria) && \count($criteria) > 0) {
            $whereQuery = ' WHERE';
            foreach ($criteria as $c) {
                $whereQuery .= " ${c} AND";
            }
            $whereQuery = mb_substr($whereQuery, 0, -4);
        } elseif (!\is_array($criteria) && $criteria) {
            $whereQuery = ' WHERE ' . $criteria;
        }
        if (!$asobject) {
            $sql    = 'SELECT friendpet_id FROM ' . $db->prefix(
                'yogurt_friendrequest'
            ) . "${whereQuery} ORDER BY ${sort} ${order}";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = $myrow['yogurt_friendrequest_id'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $db->prefix(
                'yogurt_friendrequest'
            ) . "${whereQuery} ORDER BY ${sort} ${order}";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = new self($myrow);
            }
        }

        return $ret;
    }

    /**
     * Get form
     *
     * @return \XoopsModules\Yogurt\Form\FriendrequestForm
     */
    public function getForm()
    {
        return new Form\FriendrequestForm($this);
    }

    /**
     * @return array|null
     */
    public function getGroupsRead()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem(
            'sbcolumns_read',
            $this->getVar('friendpet_id')
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
            $this->getVar('friendpet_id')
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
            $this->getVar('friendpet_id')
        );
    }
}
