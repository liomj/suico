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
 * Class ProfileVisibilityHandler
 */
class VisibilityHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @param \XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'suico_profile_visibility', Visibility::class, 'field_id');
    }

    /**
     * Get fields visible to the $user_groups on a $profile_groups profile
     *
     * @param array      $profile_groups groups of the user to be accessed
     * @param array|null $user_groups    groups of the visitor, default as $GLOBALS['xoopsUser']
     *
     * @return array
     */
    public function getVisibleFields($profile_groups, $user_groups = null)
    {
        $user_groups[]    = 0;
        $profile_groups[] = 0;
        $sql              = "SELECT field_id FROM {$this->table} WHERE profile_group IN (" . \implode(',', $profile_groups) . ')';
        $sql              .= ' AND user_group IN (' . \implode(',', $user_groups) . ')';
        $field_ids        = [];
        if (false !== ($result = $this->db->query($sql))) {
            while (false !== ([$field_id] = $this->db->fetchRow($result))) {
                $field_ids[] = $field_id;
            }
        }

        return $field_ids;
    }

    /**
     * get all rows matching a condition
     *
     * @param \CriteriaElement|null $criteria {@link \CriteriaElement} to match
     *
     * @return array of row arrays, indexed by field_id
     */
    public function getAllByFieldId(\CriteriaElement $criteria = null)
    {
        $rawRows = parent::getAll($criteria, null, false, false);
        \usort($rawRows, [$this, 'visibilitySort']);
        $rows = [];
        foreach ($rawRows as $rawRow) {
            $rows[$rawRow['field_id']][] = $rawRow;
        }

        return $rows;
    }

    /**
     * compare two arrays, each a row from profile_visibility
     * The comparison is on three columns, 'field_id', 'user_group', 'profile_group' considered in that
     * order for comparison
     *
     * @param array $a associative array with 3 numeric entries 'field_id', 'user_group', 'profile_group'
     * @param array $b associative array with 3 numeric entries 'field_id', 'user_group', 'profile_group'
     *
     * @return int integer less that zero if $a is less than $b
     *              integer zero if $a and $b are equal
     *              integer greater than zero if $a is greater than $b
     */
    protected function visibilitySort($a, $b)
    {
        $fieldDiff = $a['field_id'] - $b['field_id'];
        $userDiff  = $a['user_group'] - $b['user_group'];
        $profDiff  = $a['profile_group'] - $b['profile_group'];
        if (0 != $fieldDiff) {
            return $fieldDiff;
        }
        if (0 !== $userDiff) {
            return $userDiff;
        }

        return $profDiff;
    }
}
