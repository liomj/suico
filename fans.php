<?php
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

use XoopsModules\Yogurt;

$GLOBALS['xoopsOption']['template_main'] = 'yogurt_fans.tpl';
require __DIR__ . '/header.php';
$controler = new Yogurt\ControlerFriends($xoopsDB, $xoopsUser);

/**
 * Fecthing numbers of tribes friends videos pictures etc...
 */
$nbSections = $controler->getNumbersSections();

$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;

/**
 * Friends
 */
$criteria_friends    = new \Criteria('friend2_uid', $controler->uidOwner);
$criteria_fans       = new \Criteria('fan', 1);
$criteria_compo_fans = new \CriteriaCompo($criteria_friends);
$criteria_compo_fans->add($criteria_fans);
$nb_friends = $controler->friendshipsFactory->getCount($criteria_compo_fans);
$criteria_compo_fans->setLimit($xoopsModuleConfig['friendsperpage']);
$criteria_compo_fans->setStart($start);
$vetor = $controler->friendshipsFactory->getFans('', $criteria_compo_fans, 0);
if (0 == $nb_friends) {
    $xoopsTpl->assign('lang_nofansyet', _MD_YOGURT_NOFANSYET);
}

/**
 * Let's get the user name of the owner of the album
 */
$owner      = new \XoopsUser();
$identifier = $owner->getUnameFromId($controler->uidOwner);

/**
 * Adding to the module js and css of the lightbox and new ones
 */
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/include/yogurt.css');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/css/jquery.tabs.css');
// what browser they use if IE then add corrective script.
if (preg_match('/msie/', strtolower($_SERVER['HTTP_USER_AGENT']))) {
    $xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/css/jquery.tabs-ie.css');
}
//$xoTheme->addStylesheet(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/css/lightbox.css');
//$xoTheme->addScript(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/js/prototype.js');
//$xoTheme->addScript(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/js/scriptaculous.js?load=effects');
//$xoTheme->addScript(XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/lightbox/js/lightbox.js');
$xoTheme->addStylesheet(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/include/jquery.lightbox-0.3.css');
$xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/include/jquery.js');
$xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/include/jquery.lightbox-0.3.js');
$xoTheme->addScript(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/include/yogurt.js');

/**
 * Criando a barra de navegao caso tenha muitos amigos
 */
$barra_navegacao = new \XoopsPageNav($nb_friends, $xoopsModuleConfig['friendsperpage'], $start, 'start', 'uid=' . (int)$controler->uidOwner);
$navegacao       = $barra_navegacao->renderImageNav(2);

//permissions
$xoopsTpl->assign('allow_Notes', $controler->checkPrivilegeBySection('Notes'));
$xoopsTpl->assign('allow_friends', $controler->checkPrivilegeBySection('friends'));
$xoopsTpl->assign('allow_tribes', $controler->checkPrivilegeBySection('tribes'));
$xoopsTpl->assign('allow_pictures', $controler->checkPrivilegeBySection('pictures'));
$xoopsTpl->assign('allow_videos', $controler->checkPrivilegeBySection('videos'));
$xoopsTpl->assign('allow_audios', $controler->checkPrivilegeBySection('audio'));

//Owner data
$xoopsTpl->assign('uid_owner', $controler->uidOwner);
$xoopsTpl->assign('owner_uname', $controler->nameOwner);
$xoopsTpl->assign('isOwner', $controler->isOwner);
$xoopsTpl->assign('isanonym', $controler->isAnonym);

//numbers
$xoopsTpl->assign('nb_tribes', $nbSections['nbTribes']);
$xoopsTpl->assign('nb_photos', $nbSections['nbPhotos']);
$xoopsTpl->assign('nb_videos', $nbSections['nbVideos']);
$xoopsTpl->assign('nb_Notes', $nbSections['nbNotes']);
$xoopsTpl->assign('nb_friends', $nbSections['nbFriends']);
$xoopsTpl->assign('nb_audio', $nbSections['nbAudio']);

//navbar
$xoopsTpl->assign('module_name', $xoopsModule->getVar('name'));
$xoopsTpl->assign('lang_mysection', _MD_YOGURT_MYFRIENDS);
$xoopsTpl->assign('section_name', _MD_YOGURT_FRIENDS);
$xoopsTpl->assign('lang_home', _MD_YOGURT_HOME);
$xoopsTpl->assign('lang_photos', _MD_YOGURT_PHOTOS);
$xoopsTpl->assign('lang_friends', _MD_YOGURT_FRIENDS);
$xoopsTpl->assign('lang_videos', _MD_YOGURT_VIDEOS);
$xoopsTpl->assign('lang_Notebook', _MD_YOGURT_NOTEBOOK);
$xoopsTpl->assign('lang_profile', _MD_YOGURT_PROFILE);
$xoopsTpl->assign('lang_tribes', _MD_YOGURT_TRIBES);
$xoopsTpl->assign('lang_configs', _MD_YOGURT_CONFIGSTITLE);
$xoopsTpl->assign('lang_audio', _MD_YOGURT_AUDIOS);

//barra de navega��o
$xoopsTpl->assign('navegacao', $navegacao);

//xoopsToken
$xoopsTpl->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML());

//page atributes
$xoopsTpl->assign('xoops_pagetitle', sprintf(_MD_YOGURT_PAGETITLE, $xoopsModule->getVar('name'), $controler->nameOwner));

$xoopsTpl->assign('lang_fanstitle', sprintf(_MD_YOGURT_FANSTITLE, $identifier));
//$xoopsTpl->assign('path_yogurt_uploads',$xoopsModuleConfig['link_path_upload']);

$xoopsTpl->assign('friends', $vetor);

$xoopsTpl->assign('lang_delete', _MD_YOGURT_DELETE);
$xoopsTpl->assign('lang_evaluate', _MD_YOGURT_FRIENDSHIPCONFIGS);

include __DIR__ . '/../../footer.php';
