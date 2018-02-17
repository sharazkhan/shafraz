<?php
/**
 * Copyright socialise Team 2011
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package socialise
 * @link http://code.zikula.org/socialise
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Plugin api class.
 */
class ZSELEX_Api_Plugin extends Zikula_AbstractApi
{
    /**
     * Instance of Zikula_View.
     *
     * @var Zikula_View
     */
    protected $view;

    /**
     * Initialize.
     *
     * @return void
     */
    protected function initialize()
    {
        $this->setView();
    }

    /**
     * Set view property.
     *
     * @param Zikula_View $view
     *        	Default null means new Render instance for this module name.
     *
     * @return Zikula_AbstractController
     */
    protected function setView(Zikula_View $view = null)
    {
        if (is_null($view)) {
            $view = Zikula_View::getInstance($this->getName());
        }

        $this->view = $view;
        return $this;
    }

    public function postToWall($args)
    {
        $keys   = ModUtil::apiFunc('Socialise', 'user', 'getKeys',
                array(
                'service' => 'Facebook'
        ));
        // echo "<pre>"; print_r($keys); echo "</pre>";
        $appId  = $keys ['app_id'];
        $secret = '';
        $id     = $args ['id'];
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $appId  = '1610478949177311';
            $secret = '';
        }

        $current_theme = System::getVar('Default_Theme');

        // echo "<pre>"; print_r($encode); echo "</pre>";
        // echo "<pre>"; print_r($args); echo "</pre>";
        $file = str_replace(pnGetBaseURL(), "", $args ['image']);
        if (!file_exists($file)) {
            $args ['image'] = pnGetBaseURL()."themes/".$current_theme."/images/Logo.png";
        }
        $encode = json_encode($args);
        // $encode . $id = json_encode($args);
        // ${'encode' . $id} = json_encode($args);
        // echo $encode . $id .'<br>';
        // ${'encode' . $id} . '<br>';
        $this->view->assign('info', $args);
        $this->view->assign('appId', $appId);
        $this->view->assign('secret', $secret);
        $this->view->assign("id", $id);
        $this->view->assign("encode", $encode);
        return $this->view->fetch('plugin/post_wall.tpl');
    }

    public function searchBreadcrum($args)
    {

        // $this->view->assign("encode", $encode);
        $current_theme = System::getVar('Default_Theme');
        $this->view->assign("current_theme", $current_theme);
        return $this->view->fetch('plugin/search_breadcrum.tpl');
    }

    public function showRating($params)
    {
        if (UserUtil::isLoggedIn()) {

            //PageUtil::addVar("javascript", "modules/ZSELEX/javascript/rate.js");
        }
        // PageUtil::addVar('stylesheet', 'themes/CityPilot/style/rating.css');
        $shop_id = $params ['shop_id'];
        if (empty($shop_id) || !(int) $shop_id) {
            return;
        }
        // echo "<input type='hidden' id='shop_id' value=$shop_id>";
        $this->view->assign('shop_id', $shop_id);
        $x = '';

        /*
         * $ratingCount = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $count_args = array(
         * 'table' => 'zselex_shop_ratings',
         * 'where' => "shop_id=$shop_id"));
         */

        $countArgs = array(
            'entity' => 'ZSELEX_Entity_Rating',
            'field' => 'rating_id',
            'where' => array(
                'a.shop' => $shop_id
            )
        );

        $ratingCount = $this->entityManager->getRepository('ZSELEX_Entity_Rating')->getCount($countArgs);

        // echo $ratingCount;
        if ($ratingCount == 1) {
            $v = 'vote';
        } else {
            $v = 'votes';
        }

        /*
         * $ratings = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $rate_args = array(
         * 'table' => 'zselex_shop_ratings',
         * 'fields' => array('rating'),
         * 'where' => array(
         * "shop_id=$shop_id"
         * )
         * ));
         */

        $ratings = $this->entityManager->getRepository('ZSELEX_Entity_Rating')->getRatings(array(
            'shop_id' => $shop_id
        ));

        // echo "<pre>"; print_r($ratings); echo "</pre>";
        $this->view->assign('ratings', $ratings);
        $this->view->assign('v', $v);

        foreach ($ratings as $key => $val) {
            $rr = $val ["rating"]; // EACH RATING FOR THE CONTENT
            $x += $rr; // ADDS THEM ALL UP
        }
        // echo "X :" . $x;
        // IF THERE ARE RATINGS...
        if ($ratingCount) {
            $rating = $x / $ratingCount; // THE AVERAGE RATING (UNROUNDED)
        } else {
            $rating = 0; // SET TO PREVENT THE ERROR OF DIVISION BY 0, WHICH WOULD BE THE NUMBER OF RATINGS HERE
        }
        // echo $rating;
        // $rating = 3.4;
        // $dec_rating = round($rating, 1); //ROUNDED RATING TO THE NEAREST TENTH
        $dec_rating = round($rating);
        $stars      = '';
        $y          = '';
        $this->view->assign('isLoggedIn', UserUtil::isLoggedIn());
        $this->view->assign('rating', $rating);
        $this->view->assign('dec_rating', $dec_rating);
        $this->view->assign('ratingCount', $ratingCount);
        $this->view->assign('v', $v);
        /*
         * //SHOWS THE FULL NUMBER OF STARS (Ex: 3.5 stars = 3 full stars)
         * //for ($i = 1; $i <= floor($rating); $i++) {
         * for ($i = 1; $i <= $dec_rating; $i++) {
         * //echo $i . '<br>';
         * $stars .= '<div class="star" id="' . $i . '"></div>';
         * }
         */
        if (UserUtil::isLoggedIn()) {
            $user_id    = $params ['user_id'];
            /*
             * $userRating = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow', $user_rate_args = array(
             * 'table' => 'zselex_shop_ratings',
             * 'where' => array(
             * "shop_id=$shop_id", "user_id=$user_id"
             * )
             * ));
             */
            $userRating = $this->entityManager->getRepository('ZSELEX_Entity_Rating')->getUserRating(array(
                'shop_id' => $shop_id,
                'user_id' => $user_id
            ));
            // echo "<pre>"; print_r($userRating); echo "</pre>";
            if ($userRating ['rating']) {
                // $user_rated = '<div>You rated this a <b>' . $userRating[rating] . '</b></div>';
                $this->view->assign('userRating', $userRating ['rating']);
            }
        }

        return $this->view->fetch('plugin/showRating.tpl');
    }

    public function fbshare($args)
    {
        $keys   = ModUtil::apiFunc('Socialise', 'user', 'getKeys',
                array(
                'service' => 'Facebook'
        ));
        // echo "<pre>"; print_r($keys); echo "</pre>";
        // echo "<pre>"; print_r($args); echo "</pre>";
        $appId  = $keys ['app_id'];
        $secret = '';
        $id     = $args ['id'];
        $url    = $args ['url'];
        if ($_SERVER ['SERVER_NAME'] == 'localhost') {
            $appId  = '1610478949177311';
            $secret = '';
        }

        $current_theme = System::getVar('Default_Theme');

        // echo "<pre>"; print_r($encode); echo "</pre>";
        // echo "<pre>"; print_r($args); echo "</pre>";
        $file = str_replace(pnGetBaseURL(), "", $args ['image']);
        if (!file_exists($file)) {
            $args ['image'] = pnGetBaseURL()."themes/".$current_theme."/images/Logo.png";
        }
        $encode = json_encode($args);
        // $encode . $id = json_encode($args);
        // ${'encode' . $id} = json_encode($args);
        // echo $encode . $id .'<br>';
        // ${'encode' . $id} . '<br>';
        $this->view->assign('info', $args);
        $this->view->assign('appId', $appId);
        $this->view->assign('secret', $secret);
        $this->view->assign("id", $id);
        $this->view->assign("encode", $encode);
        $this->view->assign("url", $url);
        return $this->view->fetch('plugin/fb_share.tpl');
    }

    public function fileVersion()
    {
        return '';
        return '?v=1.1';
    }
}