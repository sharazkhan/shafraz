<?php

/**
 * Smarty function to display object tags.
 *
 * This function takes the modulename, objectid and areaid and returns an unordered list of associated tags
 *
 * Available parameters:
 *   - modname
 *   - objectid
 *   - areaname: hook bundle area name
 *   - assign: If set, the results are assigned to the corresponding variable instead of printed out
 *
 * Example
 * {displaytags modname='News' objectid=10012 areaname='subscriber.news.ui_hooks.articles'}
 *
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the unordered list
 */
function smarty_function_rightblock($params, &$smarty) {

    return ModUtil::apiFunc('Zvelo', 'plugin', 'rightBlock', $params);
    //$smarty->assign($assign, $banner['displaystring']);
}
