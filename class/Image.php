<?php declare(strict_types=1);

namespace XoopsModules\Yogurt;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Module\Helper\Permission;
use XoopsDatabaseFactory;
use XoopsObject;

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author       Marcello Brandão aka  Suico
 * @author       XOOPS Development Team
 * @since
 */

/**
 * Protection against inclusion outside the site
 */
if (!\defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

/**
 * Includes of form objects and uploader
 */
require_once XOOPS_ROOT_PATH . '/class/uploader.php';
require_once XOOPS_ROOT_PATH . '/kernel/object.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * Image class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Image extends XoopsObject
{
    public $db;
    public $helper;
    public $permHelper;

    // constructor

    /**
     * Image constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        /** @var Helper $helper */
        $this->helper     = Helper::getInstance();
        $this->permHelper = new Permission();
        $this->db         = XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('cod_img', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('title', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('caption', \XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('date_created', \XOBJ_DTYPE_INT, 0, false);
        $this->initVar('date_updated', \XOBJ_DTYPE_INT, 0, false);
        $this->initVar('uid_owner', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('url', \XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('private', \XOBJ_DTYPE_TXTBOX, null, false);
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
        $sql   = 'SELECT * FROM ' . $this->db->prefix('yogurt_images') . ' WHERE cod_img=' . $id;
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
    public function getAllImages(
        $criteria = [],
        $asobject = false,
        $sort = 'cod_img',
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
            $sql    = 'SELECT cod_img FROM ' . $db->prefix('yogurt_images') . "${whereQuery} ORDER BY ${sort} ${order}";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = $myrow['yogurt_images_id'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $db->prefix('yogurt_images') . "${whereQuery} ORDER BY ${sort} ${order}";
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
     * @return \XoopsModules\Yogurt\Form\ImagesForm
     */
    public function getForm()
    {
        return new Form\ImagesForm($this);
    }

    /**
     * @return array|null
     */
    public function getGroupsRead()
    {
        //$permHelper = new \Xmf\Module\Helper\Permission();
        return $this->permHelper->getGroupsForItem(
            'sbcolumns_read',
            $this->getVar('cod_img')
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
            $this->getVar('cod_img')
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
            $this->getVar('cod_img')
        );
    }
}
