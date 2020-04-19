<?php declare(strict_types=1);

use Xmf\Request;
use XoopsModules\Yogurt;
use XoopsModules\Yogurt\Helper;

require __DIR__ . '/preloads/autoloader.php';

require dirname(__DIR__, 2) . '/mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

$moduleDirName = basename(__DIR__);

$helper = Helper::getInstance();

$modulePath = XOOPS_ROOT_PATH . '/modules/' . $moduleDirName;

$myts = MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
    require $GLOBALS['xoops']->path('class/theme.php');
    $GLOBALS['xoTheme'] = new xos_opal_Theme();
}

//Handlers
//$XXXHandler = xoops_getModuleHandler('XXX', $moduleDirName);

/** @var \XoopsPersistableObjectHandler $imageHandler */
$imageHandler = $helper->getHandler('Image');
/** @var \XoopsPersistableObjectHandler $friendshipHandler */
$friendshipHandler = $helper->getHandler('Friendship');
/** @var \XoopsPersistableObjectHandler $visitorsHandler */
$visitorsHandler = $helper->getHandler('Visitors');
/** @var \XoopsPersistableObjectHandler $videoHandler */
$videoHandler = $helper->getHandler('Video');
/** @var \XoopsPersistableObjectHandler $friendpetitionHandler */
$friendpetitionHandler = $helper->getHandler(
    'Friendpetition'
);
/** @var \XoopsPersistableObjectHandler $groupsHandler */
$groupsHandler = $helper->getHandler('Groups');
/** @var \XoopsPersistableObjectHandler $relgroupuserHandler */
$relgroupuserHandler = $helper->getHandler('Relgroupuser');
/** @var \XoopsPersistableObjectHandler $notesHandler */
$notesHandler = $helper->getHandler('Notes');
/** @var \XoopsPersistableObjectHandler $configsHandler */
$configsHandler = $helper->getHandler('Configs');
/** @var \XoopsPersistableObjectHandler $suspensionsHandler */
$suspensionsHandler = $helper->getHandler('Suspensions');
/** @var \XoopsPersistableObjectHandler $audioHandler */
$audioHandler = $helper->getHandler('Audio');

// Load language files
$helper->loadLanguage('blocks');
$helper->loadLanguage('common');
$helper->loadLanguage('main');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');
//$helper->loadLanguage('user');
xoops_loadLanguage('user');

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new XoopsTpl();
}

$imageFactory          = new Yogurt\ImageHandler($xoopsDB);
$visitorsFactory       = new Yogurt\VisitorsHandler($xoopsDB);
$videosFactory         = new Yogurt\VideoHandler($xoopsDB);
$friendpetitionFactory = new Yogurt\FriendpetitionHandler($xoopsDB);
$friendshipFactory     = new Yogurt\FriendshipHandler($xoopsDB);

$isOwner  = 0;
$isAnonym = 1;
$isFriend = 0;


if (1 === $helper->getConfig('enable_guestaccess')) {	

/**
 * Enable Guest Access
 * If anonym and uid not set then redirect to admins profile
 * Else redirects to own profile
 */
if (empty($xoopsUser)) {
    $isAnonym = 1;
    if (isset($_GET['uid'])) {
        $uid_owner = Request::getInt('uid', 0, 'GET');
    } else {
        $uid_owner = 1;
        $isOwner   = 0;
    }
} else {
    $isAnonym = 0;
    if (isset($_GET['uid'])) {
        $uid_owner = Request::getInt('uid', 0, 'GET');
        $isOwner   = $xoopsUser->getVar('uid') === $uid_owner ? 1 : 0;
    } else {
        $uid_owner = (int)$xoopsUser->getVar('uid');
        $isOwner   = 1;
    }
}


}

else {
	
/**
 * Disable Guest Access
 * If anonym redirect to landing guest page
 * Else redirects to own profile
 */

if (empty($xoopsUser)) {
    $isAnonym = 1;
	if (!stripos($_SERVER['REQUEST_URI'], 'user.php')){
     $xoopsUser || redirect_header('user.php', 3, _NOPERM);
	}
}
else {
	$isAnonym = 0;
		if (isset($_GET['uid'])) {
			$uid_owner = Request::getInt('uid', 0, 'GET');
			$isOwner   = $xoopsUser->getVar('uid') === $uid_owner ? 1 : 0;
		} else {
			$uid_owner = (int)$xoopsUser->getVar('uid');
			$isOwner   = 1;
		}
}
}
