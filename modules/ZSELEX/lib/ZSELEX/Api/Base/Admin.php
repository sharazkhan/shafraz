<?php
/**
 * Copyright  2013
 *
 * ZSELEX
 * Demonstration of Zikula Module
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */

/**
 * Class to control Admin interface
 */
class ZSELEX_Api_Base_Admin extends Zikula_AbstractApi
{
    const DEMO = 1;
    const PAID = 2;

    public function initialize()
    {
        
    }

    /**
     * Get available admin panel links
     *
     * @return array array of admin links
     */
    public function getlinks()
    {
        // Define an empty array to hold the list of admin links
        $links = array();
        // return;
        // Check the users permissions to each avaiable action within the admin panel
        // and populate the links array if the user has permission
        if ($_REQUEST ['module'] != 'news') {
            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $approval      = ModUtil::apiFunc('ZSELEX', 'user',
                        'selectArray',
                        $args          = array(
                        'table' => 'zselex_serviceapproval',
                        'where' => array(
                            "status=0"
                        )
                ));
                $approvalCount = count($approval);
                $counts        = '';
                if ($approvalCount > 0) {
                    $counts = "(".$approvalCount.")";
                }

                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin',
                        'viewserviceapproval'),
                    'text' => $this->__('Approval').$counts,
                    'title' => $this->__('Service Purchase Approval')." ".$counts,
                    'class' => 'z-icon-es-new'
                );
            }
            // if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
            // $links[] = array(
            // 'url' => ModUtil::url('ZSELEX', 'admin', 'viewadvertise'),
            // 'text' => $this->__('Advertise'),
            // 'class' => 'z-icon-es-new');
            // }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
                // viewbasket
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'serviceCart'),
                    'text' => $this->__('Cart'),
                    'title' => $this->__('Shop Service Cart'),
                    'class' => 'z-icon-es-new'
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewzselextheme'),
                    'text' => $this->__('Theme (Global)'),
                    'title' => $this->__('Configure the global ZSELEX Theme'),
                    'class' => 'z-icon-es-new'
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewownertheme'),
                    'text' => $this->__('Theme (Owner)'),
                    'title' => $this->__('Configure Owners Minisite/-shop Theme'),
                    'class' => 'z-icon-es-new'
                );
            }

            // if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
            // $links[] = array(
            // 'url' => ModUtil::url('ZSELEX', 'admin', 'viewconfiguredservices'),
            // 'text' => $this->__('View Your Service'),
            // 'class' => 'z-icon-es-new');
            // }

            $sublinks = array(
                array(
                    'url' => ModUtil::url('ZSELEX', 'admin',
                        'viewserviceidentifiers'),
                    'text' => $this->__('Identifieres'),
                    'title' => $this->__('Service Identifiers')
                ),
                array(
                    'url' => ModUtil::url('ZSELEX', 'admin',
                        'viewservicebundles'),
                    'text' => $this->__('Bundles'),
                    'title' => $this->__('Service Bundles')
                )
            );

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewplugin'),
                    'text' => $this->__('Services'),
                    'title' => $this->__('Shop Services'),
                    'class' => 'z-icon-es-new',
                    'links' => $sublinks
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin',
                        'viewserviceidentifiers'),
                    'text' => $this->__('Identifiers'),
                    'title' => $this->__('Service Identifiers'),
                    'class' => 'z-icon-es-new '
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin',
                        'viewservicebundles'),
                    'text' => $this->__('Bundles'),
                    'title' => $this->__('Service Bundles'),
                    'class' => 'z-icon-es-new '
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewcategory'),
                    'text' => $this->__('Category'),
                    'title' => $this->__('Defined Categories'),
                    'class' => 'z-icon-es-new '
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewbranch'),
                    'text' => $this->__('Branch'),
                    'title' => $this->__('Defined Branches'),
                    'class' => 'z-icon-es-new '
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewcountry'),
                    'text' => $this->__('Country'),
                    'title' => $this->__('Defined Countries'),
                    'class' => 'z-icon-es-new '
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewregion'),
                    'text' => $this->__('Region'),
                    'title' => $this->__('Defined Regions'),
                    'class' => 'z-icon-es-new '
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewcity'),
                    'text' => $this->__('City'),
                    'title' => $this->__('Defined Cities'),
                    'class' => 'z-icon-es-new'
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewarea'),
                    'text' => $this->__('Area'),
                    'title' => $this->__('Defined Areas'),
                    'class' => 'z-icon-es-new'
                );
            }

            /*
             * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
             * $links[] = array(
             * 'url' => ModUtil::url('ZSELEX', 'admin', 'viewtype'),
             * 'text' => $this->__('Type'),
             * 'title' => $this->__('Types'),
             * 'class' => 'z-icon-es-new'
             * );
             * }
             */

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewadprice'),
                    'text' => $this->__('AD prices'),
                    'title' => $this->__('Create pricelevel for ADs'),
                    'class' => 'z-icon-es-new'
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewsociallinks'),
                    'text' => $this->__('Social Links'),
                    'title' => $this->__('Social Links'),
                    'class' => 'z-icon-es-new'
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                array_unshift($links,
                    array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewshoptypes'),
                    'text' => $this->__('Types'),
                    'title' => $this->__('View the different types of shops'),
                    'class' => 'z-icon-es-new'
                ));
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                array_unshift($links,
                    array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewaffiliate'),
                    'text' => $this->__('Affiliate'),
                    'title' => $this->__('View the different types of shops'),
                    'class' => 'z-icon-es-new'
                ));
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                array_unshift($links,
                    array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'denmarkMap'),
                    'text' => $this->__('Map'),
                    'title' => $this->__('Set regions in map'),
                    'class' => 'z-icon-es-new'
                ));
            }

            /*
             * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
             * $links[] = array(
             * 'url' => ModUtil::url('ZSELEX', 'admin', 'viewmodulearticles'),
             * 'text' => $this->__('Articles Module'),
             * 'class' => 'z-icon-es-new');
             * }
             */

            // if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
            // $links[] = array(
            // 'url' => ModUtil::url('ZSELEX', 'admin', 'viewdotd' , array('shop_id'=>$_REQUEST['id'])),
            // 'text' => $this->__('DEAL OF THE DAY'),
            // 'class' => 'z-icon-es-new');
            // }
            // KIMENEMARK BEGIN
            // sort all but the first menuitems (inserted below afterwards)
            foreach ($links as $key => $row) {
                $urls [$key]    = $row ['url'];
                $texts [$key]   = $row ['text'];
                $classes [$key] = $row ['class'];
            }
            // Sort text first then url and then class
            if (!empty($texts)) {
                array_multisort($texts, SORT_ASC, $urls, SORT_ASC, $classes,
                    SORT_ASC, $links);
            }

            // NOTE... menuitems inserted hereafter will not be sorted!!
            // Insert the menuitems that will be in front (the last first and so forth)

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                array_unshift($links,
                    array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'mailtext'),
                    'text' => $this->__('Mail Text'),
                    'title' => $this->__('Mail Text'),
                    'class' => 'z-icon-es-config'
                ));
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                array_unshift($links,
                    array(
                    'url' => ModUtil::url('ZSELEX', 'admin',
                        'paymentgatewaysettings'),
                    'text' => $this->__('Payment Gateway Settings'),
                    'title' => $this->__('Configure General Payment Gateways'),
                    'class' => 'z-icon-es-config'
                ));
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                array_unshift($links,
                    array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'modifyconfig'),
                    'text' => $this->__('General Settings'),
                    'title' => $this->__('General settings of ZSELEX'),
                    'class' => 'z-icon-es-config'
                ));
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                array_unshift($links,
                    array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewshop'),
                    'text' => $this->__('Shoplist'),
                    'title' => $this->__('List of Shops you administrate'),
                    'class' => 'z-icon-es-home'
                ));
            }

            // To help break the menuline nice
            // ZSELEX/style/style.css:
            // .z-iconlink span {white-space:nowrap;}
            if (!empty($links)) {
                for ($i = 0; $i < count($links); $i ++) {
                    $links [$i] ['text'] = "<span>".$links [$i] ['text']."</span> ";
                }
                if (count($links) > 3) {
                    $links [3] ['text'] = $links [3] ['text']."<br />";
                }
            }
            // KIMENEMARK END
        }

        // Return the links array back to the calling function
        return $links;
    }

    public function getshoplinks()
    {
        // Define an empty array to hold the list of admin links
        $links        = array();
        $service_grey = " ServiceGrey";
        // return;
        $shop_id      = FormUtil::getPassedValue('shop_id', null, 'REQUEST');

        // Check the users permissions to each avaiable action within the admin panel
        // and populate the links array if the user has permission
        if ($_REQUEST ['module'] != 'news') {

            // if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
            // $links[] = array(
            // 'url' => ModUtil::url('ZSELEX', 'admin', 'viewproducts' , array('shop_id'=>$_REQUEST['shop_id'])),
            // 'text' => $this->__('View Products'),
            // 'class' => 'z-icon-es-new');
            // }

            $menu_args = array(
                'service_type' => 'createad',
                'table' => 'zselex_advertise',
                'shop_id' => $shop_id
            );

            // if (($this->serviceDisabled('createad') >= 1) || (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN))) {

            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                    $links [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'productAd',
                            array(
                            'shop_id' => $_REQUEST ['shop_id']
                        )),
                        'text' => $this->__('Product AD'),
                        'title' => $this->__('Advertise your products'),
                        'class' => 'z-icon-es-new'.$this->serviceGrey($args   = array(
                            'shop_id' => $shop_id,
                            'service_type' => 'createad'
                        ))
                    );
                }
            }
            // }
            $menu_args = array(
                'service_type' => 'createarticle',
                'table' => 'zselex_shop_news',
                'shop_id' => $shop_id
            );
            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                    $links [] = array(
                        'url' => ModUtil::url('ZSELEX', 'user', 'newitem',
                            array(
                            'shop_id' => $_REQUEST ['shop_id']
                        )),
                        'text' => $this->__('Articles'),
                        'title' => $this->__('Minisite Articles'),
                        'class' => 'z-icon-es-new'.$this->serviceGrey($args   = array(
                            'shop_id' => $shop_id,
                            'service_type' => 'createarticle'
                        ))
                    );
                }
            }

            $menu_args = array(
                'service_type' => 'createarticlead',
                'table' => 'zselex_article_ads',
                'shop_id' => $shop_id
            );
            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                    $links [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'viewarticleads',
                            array(
                            'shop_id' => $_REQUEST ['shop_id']
                        )),
                        'text' => $this->__('Article AD'),
                        'title' => $this->__('Advertise your articles'),
                        'class' => 'z-icon-es-new'.$this->serviceGrey($args   = array(
                            'shop_id' => $shop_id,
                            'service_type' => 'createarticlead'
                        ))
                    );
                }
            }

            //
            // if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
            // $links[] = array(
            // 'url' => ModUtil::url('ZSELEX', 'admin', 'viewconfiguredservices' , array('shop_id'=>$_REQUEST['shop_id'])),
            // 'text' => $this->__('View Your Service'),
            // 'class' => 'z-icon-es-new');
            // }

            $menu_args = array(
                'service_type' => 'dealoftheday',
                'table' => 'zselex_dotd',
                'shop_id' => $shop_id
            );
            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                    $links [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'viewdotd',
                            array(
                            'shop_id' => $_REQUEST ['shop_id']
                        )),
                        'text' => $this->__('DOTD'),
                        'title' => $this->__('Deal Of The Day'),
                        'class' => 'z-icon-es-new'.$this->serviceGrey($args   = array(
                            'shop_id' => $shop_id,
                            'service_type' => 'dealoftheday'
                        ))
                    );
                }
            }

            /*
             * $shop_id = $_REQUEST['shop_id'];
             * $shopType = ModUtil::apiFunc('ZSELEX', 'admin', 'getShopType', $args = array('shop_id' => $shop_id));
             *
             * if ($shopType == 'iSHOP') {
             * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
             * $links[] = array(
             * 'url' => ModUtil::url('ZSELEX', 'admin', 'viewproducts', array('shop_id' => $_REQUEST['shop_id'])),
             * 'text' => $this->__('Products'),
             * 'class' => 'z-icon-es-new');
             * }
             * }
             */

            /*
             * $menu_args = array('service_type' => 'minisiteimages', 'table' => 'zselex_files', 'shop_id' => $shop_id);
             * if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN))) {
             * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
             * $links[] = array(
             * 'url' => ModUtil::url('ZSELEX', 'admin', 'viewshopimages', array(
             * 'shop_id' => $_REQUEST['shop_id']
             * )),
             * 'text' => $this->__('Images'),
             * 'title' => $this->__('Minisite Images'),
             * 'class' => 'z-icon-es-new' . $this->serviceGrey($args = array('shop_id' => $shop_id, 'service_type' => 'minisiteimages'))
             * );
             * }
             * }
             */

            $menu_args = array(
                'service_type' => 'minisitegallery',
                'table' => 'zselex_shop_gallery',
                'shop_id' => $shop_id
            );
            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                    $links [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin',
                            'viewshopgalleryimages',
                            array(
                            'shop_id' => $_REQUEST ['shop_id']
                        )),
                        'text' => $this->__('Gallery'),
                        'title' => $this->__('Minisite Image Gallery'),
                        'class' => 'z-icon-es-new'.$this->serviceGrey($args   = array(
                            'shop_id' => $shop_id,
                            'service_type' => 'minisitegallery'
                        ))
                    );
                }
            }

            $service_grey;

            $menu_args = array(
                'service_type' => 'pdfupload',
                'table' => 'zselex_shop_pdf',
                'shop_id' => $shop_id
            );
            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                    $links [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'viewshoppdf',
                            array(
                            'shop_id' => $_REQUEST ['shop_id']
                        )),
                        'text' => $this->__('PDF'),
                        'title' => $this->__('Minisite PDF files'),
                        'class' => 'z-icon-es-new '.$this->serviceGrey($args   = array(
                            'shop_id' => $shop_id,
                            'service_type' => 'pdfupload'
                        ))
                    );
                }
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
                $links [] = array(
                    'url' => ModUtil::url('zselex', 'adminusers', 'viewOwner',
                        array(
                        'shop_id' => $_REQUEST ['shop_id']
                    )),
                    'text' => $this->__('Owner'),
                    'title' => $this->__('Shop Owner'),
                    'class' => 'z-icon-es-new'
                );
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
                $links [] = array(
                    'url' => ModUtil::url('zselex', 'adminusers', 'view',
                        array(
                        'shop_id' => $_REQUEST ['shop_id']
                    )),
                    'text' => $this->__('Admins'),
                    'title' => $this->__('Shop Administrators'),
                    'class' => 'z-icon-es-new'
                );
            }

            $menu_args = array(
                'service_type' => 'stdtheme',
                'table' => 'zselex_shop',
                'shop_id' => $shop_id,
                'extra_sql' => "AND theme!=''"
            );
            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                    $links [] = array(
                        'url' => ModUtil::url('zselex', 'admin',
                            'configureshoptheme',
                            array(
                            'shop_id' => $_REQUEST ['shop_id']
                        )),
                        'text' => $this->__('Theme'),
                        'title' => $this->__('Configure Theme for Minisite/-shop'),
                        'class' => 'z-icon-es-new'.$this->serviceGrey($args   = array(
                            'shop_id' => $shop_id,
                            'service_type' => 'stdtheme'
                        ))
                    );
                }
            }

            $menu_args = array(
                'service_type' => 'minishop',
                'table' => 'zselex_minishop',
                'shop_id' => $shop_id
            );
            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                    $links [] = array(
                        'url' => ModUtil::url('zselex', 'admin', 'minishop',
                            array(
                            'shop_id' => $_REQUEST ['shop_id']
                        )),
                        'text' => $this->__('Shop'),
                        'title' => $this->__('Configure Minishop'),
                        'class' => 'z-icon-es-new'.$this->serviceGrey($args   = array(
                            'shop_id' => $shop_id,
                            'service_type' => 'minishop'
                        ))
                    );
                }
            }

            $menu_args = array(
                'service_type' => 'addproducts',
                'table' => 'zselex_products',
                'shop_id' => $shop_id
            );
            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                    $minShopFunction = '';
                    $viewShoptitle   = '';
                    $minishop        = DBUtil::selectObjectByID('zselex_minishop',
                            $shop_id, 'shop_id');
                    // echo "<pre>"; print_r($minishop); echo "</pre>";

                    if ($minishop > 0) {
                        $miniShopType = $minishop ['shoptype'];
                        if ($miniShopType == 'zSHOP') {
                            $viewShoptitle     = $this->__('ZenCart');
                            $minShopFunction   = 'viewZenShop';
                            $minishoppopuptext = 'Configure your external ZenCart Shop';
                        } elseif ($miniShopType == 'iSHOP') {
                            $viewShoptitle     = $this->__('Products');
                            // $minShopFunction = 'viewproducts';
                            $minShopFunction   = 'products';
                            $minishoppopuptext = 'Add Products to your Minishop';
                        }

                        $product_sublinks = array(
                            array(
                                'url' => ModUtil::url('ZSELEX', 'admin',
                                    'productOption',
                                    array(
                                    'shop_id' => $shop_id
                                )),
                                'text' => $this->__('Product Option'),
                                'title' => $this->__('Product Option')
                            )
                        );

                        $links [] = array(
                            'url' => ModUtil::url('zselex', 'admin',
                                $minShopFunction,
                                array(
                                'shop_id' => $_REQUEST ['shop_id']
                            )),
                            'text' => $viewShoptitle,
                            'title' => $minishoppopuptext,
                            'class' => 'z-icon-es-new'.$this->serviceGrey($args   = array(
                                'shop_id' => $shop_id,
                                'service_type' => 'addproducts'
                            ))
                            )
                        // 'links' => $product_sublinks,
                        ;

                        /*
                         * $links[] = array(
                         * 'url' => ModUtil::url('zselex', 'admin', 'productOption', array(
                         * 'shop_id' => $_REQUEST['shop_id']
                         * )),
                         * 'text' => $this->__('Product Option'),
                         * 'title' => $this->__('Product Option'),
                         * 'class' => 'z-icon-es-new' . $this->serviceGrey($args = array('shop_id' => $shop_id, 'service_type' => 'addproducts')),
                         * // 'links' => $product_sublinks,
                         * );
                         */
                    }
                }
            }

            /*
             * $menu_args = array('service_type' => 'paybutton', 'table' => 'zselex_paypal', 'shop_id' => $shop_id);
             * if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD))) {
             * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
             * $links[] = array(
             * 'url' => ModUtil::url('ZSELEX', 'admin', 'paymentgateway', array(
             * 'shop_id' => $_REQUEST['shop_id']
             * )),
             * 'text' => $this->__('Gateway'),
             * 'title' => $this->__('Configure your Payment Gateway'),
             * 'class' => 'z-icon-es-new' . $this->serviceGrey($args = array('shop_id' => $shop_id, 'service_type' => 'paybutton'))
             * );
             * }
             * }
             */

            $menu_args = array(
                'service_type' => 'eventservice',
                'table' => 'zselex_shop_events',
                'shop_id' => $shop_id
            );
            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                    $links [] = array(
                        /*
                          'url' => ModUtil::url('zselex', 'admin', 'viewshopevent', array(
                          'shop_id' => $_REQUEST['shop_id']
                          )),
                         */
                        'url' => ModUtil::url('zselex', 'admin', 'events',
                            array(
                            'shop_id' => $_REQUEST ['shop_id']
                        )),
                        'text' => $this->__('Events'),
                        'title' => $this->__('Create Events in your shop or area'),
                        'class' => 'z-icon-es-new'.$this->serviceGrey($args   = array(
                            'shop_id' => $shop_id,
                            'service_type' => 'eventservice'
                        ))
                    );
                }
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {

                /*
                 * $links[] = array(
                 * 'url' => ModUtil::url('ZSELEX', 'admin', 'paymentgatewaysettings'),
                 * 'text' => $this->__('Payment Gateway Settings'),
                 * 'title' => $this->__('Configure General Payment Gateways'),
                 * 'class' => 'z-icon-es-new'
                 * );
                 */
            }

            /*
             * $menu_args = array('service_type' => 'minisitebanner', 'table' => 'zselex_shop_banner', 'shop_id' => $shop_id);
             * if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN))) {
             * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
             * $links[] = array(
             * 'url' => ModUtil::url('zselex', 'admin', 'viewminisitebanner', array(
             * 'shop_id' => $_REQUEST['shop_id']
             * )),
             * 'text' => $this->__('Banner'),
             * 'title' => $this->__('Minisite Top Banner'),
             * 'class' => 'z-icon-es-new' . $this->serviceGrey($args = array('shop_id' => $shop_id, 'service_type' => 'minisitebanner'))
             * );
             * }
             * }
             *
             * $menu_args = array('service_type' => 'minisiteannouncement', 'table' => 'zselex_shop_announcement', 'shop_id' => $shop_id);
             * if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN))) {
             * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
             * $links[] = array(
             * 'url' => ModUtil::url('ZSELEX', 'admin', 'announcement', array(
             * 'shop_id' => $_REQUEST['shop_id']
             * )),
             * 'text' => $this->__('Announcement'),
             * 'title' => $this->__('Minisite Announcement (on Banner)'),
             * 'class' => 'z-icon-es-new' . $this->serviceGrey($args = array('shop_id' => $shop_id, 'service_type' => 'minisiteannouncement'))
             * );
             * }
             * }
             *
             * $menu_args = array('service_type' => 'employees', 'table' => 'zselex_shop_employees', 'shop_id' => $shop_id);
             * if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN))) {
             * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
             * $links[] = array(
             * 'url' => ModUtil::url('ZSELEX', 'admin', 'viewemployees', array(
             * 'shop_id' => $_REQUEST['shop_id']
             * )),
             * 'text' => $this->__('Employees'),
             * 'title' => $this->__('View shop Employees'),
             * 'class' => 'z-icon-es-new' . $this->serviceGrey($args = array('shop_id' => $shop_id, 'service_type' => 'employees'))
             * );
             * }
             * }
             */

            $menu_args = array(
                'service_type' => 'pages',
                'table' => 'ztext_pages',
                'shop_id' => $shop_id
            );
            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                    $links [] = array(
                        'url' => ModUtil::url('ztext', 'admin', 'pages',
                            array(
                            'shop_id' => $_REQUEST ['shop_id']
                        )),
                        'text' => $this->__('Pages'),
                        'title' => $this->__('Create pages'),
                        'class' => 'z-icon-es-new'.$this->serviceGrey($args   = array(
                            'shop_id' => $shop_id,
                            'service_type' => 'pages'
                        ))
                    );
                }
            }

            $menu_args = array(
                'service_type' => 'sociallinks',
                'table' => 'zselex_social_links_shop',
                'shop_id' => $shop_id
            );
            if (($this->showMenu($menu_args)) || (SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADMIN))) {
                if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
                    $links [] = array(
                        'url' => ModUtil::url('ZSELEX', 'admin', 'sociallinks',
                            array(
                            'shop_id' => $_REQUEST ['shop_id']
                        )),
                        'text' => $this->__('Social Links'),
                        'title' => $this->__('Social Links'),
                        'class' => 'z-icon-es-new'.$this->serviceGrey($args   = array(
                            'shop_id' => $shop_id,
                            'service_type' => 'sociallinks'
                        ))
                    );
                }
            }

            // KIMENEMARK BEGIN
            // sort all but the first menuitems (inserted below afterwards)
            foreach ($links as $key => $row) {
                $urls [$key]    = $row ['url'];
                $texts [$key]   = $row ['text'];
                $classes [$key] = $row ['class'];
            }
            // Sort text first then url and then class
            array_multisort($texts, SORT_ASC, $urls, SORT_ASC, $classes,
                SORT_ASC, $links);

            // NOTE... menuitems inserted hereafter will not be sorted!!
            // Insert the menuitems that will be in front (the last first and so forth)
            // if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
            // array_unshift($links, array(
            // 'url' => ModUtil::url('ZSELEX', 'admin', 'shopconfig', array(
            // 'shop_id' => $_REQUEST['shop_id']
            // )),
            // 'text' => $this->__('Settings'),
            // 'title' => $this->__('Minisite settings'),
            // 'class' => 'z-icon-es-config'
            // ));
            // }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                array_unshift($links,
                    array(
                    'url' => ModUtil::url('zselex', 'admin',
                        'configuredServices',
                        array(
                        'shop_id' => $_REQUEST ['shop_id']
                    )),
                    'text' => $this->__('Services'),
                    'title' => $this->__('List of configured services for Minisite/-shop'),
                    'class' => 'z-icon-es-services-cp'
                ));
            }

            /*
             * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
             * array_unshift($links, array(
             * 'url' => ModUtil::url('zselex', 'admin', 'purchaseservices', array(
             * 'shop_id' => $_REQUEST['shop_id']
             * )),
             * 'text' => $this->__('Purchase'),
             * 'title' => $this->__('Purchase services to extend your Minisite/-shop'),
             * // 'class' => 'z-icon-es-new'
             * 'class' => 'z-icon-es-purchase-cp'
             * ));
             * }
             */
            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
                array_unshift($links,
                    array(
                    'url' => ModUtil::url('zselex', 'admin', 'shopservices',
                        array(
                        'shop_id' => $_REQUEST ['shop_id']
                    )),
                    'text' => $this->__('Purchase'),
                    'title' => $this->__('Purchase services to extend your Minisite/-shop'),
                    // 'class' => 'z-icon-es-new'
                    'class' => 'z-icon-es-purchase-cp'
                ));
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                array_unshift($links,
                    array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'shopsettings',
                        array(
                        'shop_id' => $_REQUEST ['shop_id']
                    )),
                    'text' => $this->__('Shop Settings'),
                    'title' => $this->__('General settings in you Shop'),
                    'class' => 'z-icon-es-config'
                ));
            }

            if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
                array_unshift($links,
                    array(
                    'url' => ModUtil::url('ZSELEX', 'admin', 'viewshop'),
                    'text' => $this->__('Back to Shoplist'),
                    'title' => $this->__('Go back to list of your Shops'),
                    'class' => 'z-icon-es-home'
                ));
            }

            // To help break the menuline nice
            // ZSELEX/style/style.css:
            // .z-iconlink span {white-space:nowrap;}
            if (!empty($links)) {
                for ($i = 0; $i < count($links); $i ++) {
                    $links [$i] ['text'] = "<span>".$links [$i] ['text']."</span> ";
                }
                if (count($links) > 1) {
                    $links [1] ['text'] = $links [1] ['text']."<br />";
                }
                if (count($links) > 3) {
                    $links [3] ['text'] = $links [3] ['text']."<br />";
                }
            }
            // KIMENEMARK END
        }

        // Return the links array back to the calling function
        return $links;
    }

    public function showMenu($args)
    {
        $service_table = $args ['table'];
        $service_type  = $args ['service_type'];
        $shop_id       = $args ['shop_id'];
        $extra_sql     = '';
        if (isset($args ['extra_sql']) && !empty($args ['extra_sql'])) {
            $extra_sql = $args ['extra_sql'];
        }

        // $where = "shop_id=$shop_id" . " " . $extra_sql;
        // $service_record_exist = DBUtil::selectObjectCount($service_table, $where);
        /*
         * $service_record_exist = ModUtil::apiFunc('ZSELEX', 'admin', 'getCount', $args = array(
         * 'table' => $service_table,
         * "where" => $where
         * ));
         */
        $where = "WHERE shop_id=$shop_id"." ".$extra_sql;

        $query              = "SELECT COUNT(*) as count FROM ".$service_table." ".$where;
        $statement          = Doctrine_Manager::getInstance()->connection();
        $results            = $statement->execute($query);
        $result             = $results->fetch();
        $serviceRecordExist = $result ['count'];
        // echo $service_type . "-" . $serviceRecordExist . '<br>';

        if (($this->serviceDisabled($service_type) < 1) && !$serviceRecordExist) {
            return 0;
        } else {
            return 1;
        }
    }

    public function serviceGrey($args)
    {
        $service_grey  = '';
        $service_type  = $args ['service_type'];
        $shop_id       = $args ['shop_id'];
        $service_exist = DBUtil::selectObjectCount('zselex_serviceshop',
                $where         = "shop_id=$shop_id AND type='".$service_type."'");
        if (!$service_exist) {
            $service_grey = ' ServiceGrey ';
        }
        return $service_grey;
    }

    /**
     * api function to insert the items held by this module
     *
     * @author
     *
     * @return insert id held by this module
     */
    public function createElement($args)
    {

        // evaluates the input action
        $args ['element'] ['action'] = isset($args ['element'] ['action']) ? $args ['element'] ['action']
                : null;

        // Security check
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_COMMENT)) {
            return LogUtil::registerPermissionError();
        }
        // echo "comes here"; exit;
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        if (!($obj = DBUtil::insertObject($args ['element'], $args ['table'],
                $args ['Id']))) {
            return LogUtil::registerError($this->__('Error! Unable to create new element.'));
        }

        return $obj;
    }

    /**
     * api function to getElements of the items held by this module
     *
     * @author
     *
     * @return insert id held by this module
     */
    public function getElements($args)
    {

        // Optional arguments.
        $where    = isset($args ['where']) ? $args ['where'] : '';
        $orderBy  = isset($args ['orderBy']) ? $args ['orderBy'] : '';
        $useJoins = isset($args ['useJoins']) ? ((bool) $args ['useJoins']) : true;
        $fields   = isset($args ['fields']) ? $args ['fields'] : null;
        // create a empty result set
        $items    = array();
        // Security check
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_OVERVIEW)) {
            return $items;
        }

        $objArray = DBUtil::selectObjectArray($args ['table'], $where, $orderBy,
                $limitOffset, $limitNumRows, '', null, '', $fields);

        // $objArray = DBUtil::selectObjectArray($table, $where, $orderby, $limitOffset, $limitNumRows, $assocKey, $permissionFilter);
        // DBUtil::selectObjectArrayFilter($table, $where, $orderby, $limitOffset, $limitNumRows, $assocKey, $filterCallback, $categoryFilter, $columnArray);
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any results.'));
        }

        // Return the items
        return $objArray;
    }

    public function updateElement($args)
    {

        // print_r($args); exit;
        // Argument check
        if (!isset($args ['IdValue'])) {
            return LogUtil::registerArgsError();
        }
        // Get the news item
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        // print_r($item); exit;

        if ($item == false) {
            return LogUtil::registerError($this->__('Error! Item not found.'));
        }

        $this->throwForbiddenUnless($this->_isSubmittor($item) || SecurityUtil::checkPermission('ZSELEX::',
                $item ['cr_uid'].'::'.$args ['IdValue'], ACCESS_EDIT),
            LogUtil::getErrorMsgPermission());

        if (!DBUtil::updateObject($args ['element'], $args ['table'],
                $args ['where'], $args ['IdName'])) {
            return LogUtil::registerError($this->__('Error! Unable to save your changes.'));
        }
        // echo "hiiiii"; exit;
        // Let the calling process know that we have finished successfully
        return true;
    }

    public function updateElementWhere($args)
    {
        $table    = $args ['table'];
        $pntables = pnDBGetTables();
        $column   = $pntables [$table."_column"];
        $obj      = $args ['items'];
        $whereArr = $args ['where'];
        $where    = "WHERE ";

        foreach ($whereArr as $key => $val) {
            $where .= $column [$key]."='".$val."' AND ";
        }

        $where = substr($where, 0, - 4);

        if (!DBUtil::updateObject($obj, $table, $where)) {
            return LogUtil::registerError($this->__('Error! Unable to save your changes.'));
        }
        // echo "hiiiii"; exit;
        // Let the calling process know that we have finished successfully
        return true;
    }

    public function getAllElements($args)
    {
        // Optional arguments.
        if (!isset($args ['status']) || (empty($args ['status']) && $args ['status']
            !== 0)) {
            $args ['status'] = null;
        }
        if (!isset($args ['startnum']) || empty($args ['startnum'])) {
            $args ['startnum'] = 1;
        }
        if (!isset($args ['numitems']) || empty($args ['numitems'])) {
            $args ['numitems'] = - 1;
        }

        if ((!empty($args ['status']) && !is_numeric($args ['status'])) || !is_numeric($args ['startnum'])
            || !is_numeric($args ['numitems'])) {
            return LogUtil::registerArgsError();
        }

        // create a empty result set
        $items = array();

        // Security check
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_OVERVIEW)) {
            return $items;
        }

        $where         = $args ['where'];
        $tables        = DBUtil::getTables();
        $zselex_column = $tables [$args ['table'].'_column'];
        $orderby       = '';
        // Handle the sort order, if nothing requested use admin setting
        if (isset($zselex_column [$args ['order']])) {
            $order = $args ['order'];
        }

        // if ordering is used also set the order direction, ascending/descending
        if (!empty($order)) {
            if (isset($args ['orderdir']) && in_array(strtoupper($args ['orderdir']),
                    array(
                    'ASC',
                    'DESC'
                ))) {
                $orderby = $zselex_column [$order].' '.strtoupper($args ['orderdir']);
            } else {
                $orderby = $zselex_column [$order].' DESC';
            }
        } elseif ($args ['order'] == 'random') {
            $orderby = 'RAND()';
        }
        // if sorted by weight add second ordering "from", since weight is not unique
        if ($order == 'cr_date') {
            $orderby .= ', '.$zselex_column ['cr_date'].' DESC';
        }

        $permChecker = new ZSELEX_ResultChecker($this->getVar('enablecategorization'),
            $this->getVar('enablecategorybasedpermissions'));
        $objArray    = DBUtil::selectObjectArrayFilter($args ['table'], $where,
                $orderby, $args ['startnum'] - 1, $args ['numitems'], '',
                $permChecker, $this->getCatFilter($args));

        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any types.'));
        }

        // echo "<pre>"; print_r($objArray); echo "</pre>";
        // Return the items
        return $objArray;
    }

    /**
     * utility function to count the number of items held by this module
     *
     * @author Mark West
     * @return int number of items held by this module
     */
    public function countitems($args)
    {
        // Optional arguments.
        if (!isset($args ['status']) || (empty($args ['status']) && $args ['status']
            !== 0)) {
            $args ['status'] = null;
        }
        if (!isset($args ['ignoreml']) || !is_bool($args ['ignoreml'])) {
            $args ['ignoreml'] = false;
        }
        if (!isset($args ['language'])) {
            $args ['language'] = '';
        }

        $where = $this->generateWhere($args);
        $where = !empty($where) ? ' WHERE '.$where : '';

        return DBUtil::selectObjectCount('news', $where, 'sid', false,
                $this->getCatFilter($args));
    }

    /**
     * populate an array with each part of the where clause and then implode the array if there is a need.
     *
     * @param array $args        	
     * @param boolean $prependWhere        	
     * @return string
     */
    protected function generateWhere($args)
    {

        // echo "News";
        $tables      = DBUtil::getTables();
        $news_column = $tables ['news_column'];
        $queryargs   = array();

        if (System::getVar('multilingual') == 1 && !$args ['ignoreml'] && empty($args ['language'])) {
            $queryargs [] = "({$news_column['language']} = '".DataUtil::formatForStore(ZLanguage::getLanguageCode())."' OR {$news_column['language']} = '')";
        } elseif (!empty($args ['language'])) {
            $queryargs [] = "{$news_column['language']} = '".DataUtil::formatForStore($args ['language'])."'";
        }

        if (isset($args ['status'])) {
            $queryargs [] = "{$news_column['published_status']} = '".DataUtil::formatForStore($args ['status'])."'";
        }

        if (isset($args ['displayonindex'])) {
            $queryargs [] = "{$news_column['displayonindex']} = '".DataUtil::formatForStore($args ['displayonindex'])."'";
        }

        // Check for specific date interval
        if (isset($args ['from']) || isset($args ['to'])) {
            // Both defined
            if (isset($args ['from']) && isset($args ['to'])) {
                $from         = DataUtil::formatForStore($args ['from']);
                $to           = DataUtil::formatForStore($args ['to']);
                $queryargs [] = "({$news_column['from']} >= '$from' AND {$news_column['from']} < '$to')";
                // Only 'from' is defined
            } elseif (isset($args ['from'])) {
                $date         = DataUtil::formatForStore($args ['from']);
                $queryargs [] = "({$news_column['from']} >= '$date' AND ({$news_column['to']} IS NULL OR {$news_column['to']} >= '$date'))";
                // Only 'to' is defined
            } elseif (isset($args ['to'])) {
                $date         = DataUtil::formatForStore($args ['to']);
                $queryargs [] = "({$news_column['from']} < '$date')";
            }
            // or can filter with the current date
        } elseif ((isset($args ['filterbydate'])) && ($args ['filterbydate'])) {
            $date         = DateUtil::getDatetime();
            $queryargs [] = "('$date' >= {$news_column['from']} AND ({$news_column['to']} IS NULL OR '$date' <= {$news_column['to']}))";
        }

        if (isset($args ['tdate'])) {
            $queryargs [] = "{$news_column['from']} LIKE '%{$args['tdate']}%'";
        }

        if (isset($args ['query']) && is_array($args ['query'])) {
            // query array with rows like {'field', 'op', 'value'}
            $allowedoperators = array(
                '>',
                '>=',
                '=',
                '<',
                '<=',
                'LIKE',
                '!=',
                '<>'
            );
            foreach ($args ['query'] as $row) {
                if (is_array($row) && count($row) == 3) {
                    // validate fields and operators
                    list($field, $op, $value) = $row;
                    if (isset($news_column [$field]) && in_array($op,
                            $allowedoperators)) {
                        $queryargs [] = "$news_column[$field] $op '".DataUtil::formatForStore($value)."'";
                    }
                }
            }
        }

        // check for a specific author
        if (isset($args ['uid']) && is_numeric($args ['uid'])) {
            $queryargs [] = "{$news_column['cr_uid']} = '".DataUtil::formatForStore($args ['uid'])."'";
        }

        $where = '';

        if (count($queryargs) > 0) {
            $where = implode(' AND ', $queryargs);
        }

        return $where;
    }

    /**
     * get a specific item
     * 
     * @author
     *
     * @param $args['type_id'] id
     *        	of ZSELEX item to get
     * @return mixed item array, or false on failure
     */
    public function getElement($args)
    {

        // echo "hiii"; exit;
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        // optional arguments
        if (isset($args ['objectid'])) {
            $args ['type_id'] = $args ['objectid'];
        }

        // Argument check
        if ((!isset($args ['IdValue']) || !is_numeric($args ['IdValue']))) {
            return LogUtil::registerArgsError();
        }

        // Check for caching of the DBUtil calls (needed for AJAX editing)
        if (!isset($args ['SQLcache'])) {
            $args ['SQLcache'] = true;
        }

        $permFilter    = array();
        $permFilter [] = array(
            'realm' => 0,
            'component_left' => 'ZSELEX',
            'component_middle' => '',
            'component_right' => '',
            'instance_left' => 'cr_uid',
            'instance_middle' => '',
            'instance_right' => $args ['IdName'],
            'level' => ACCESS_READ
        );

        if (isset($args ['IdValue']) && is_numeric($args ['IdValue'])) {
            $item = DBUtil::selectObjectByID($args ['table'], $args ['IdValue'],
                    $args ['IdName'], null, $permFilter, null,
                    $args ['SQLcache']);
        }
        // echo "comes here";
        if (empty($item)) {
            return false;
        }

        return $item;
    }

    public function getAdvertise($args)
    {
        $query = "SELECT a.advertise_id , a.name , a.description ,b.parentId as city_id , c.parentId as shop_id FROM zselex_advertise a LEFT JOIN zselex_parent b ON a.advertise_id=b.childId 
                AND b.childType='AD' and b.parentType='CITY' 
                LEFT JOIN zselex_parent c ON a.advertise_id=c.childId  AND c.childType='AD' and c.parentType='SHOP' 
                WHERE a.advertise_id='".$args ['IdValue']."'";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $values    = $results->fetch();

        return $values;

        // return $sql;
    }

    public function getAll($args)
    {
        $result = DBUtil::executeSQL($args ['sql'], $args ['startnum'] - 1,
                $args ['numitems']);

        $objArray = DBUtil::marshallObjects($result);
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any results.'));
        }
        // Return the items
        return $objArray;
    }

    /**
     *
     * @author
     *
     * @param $args['sql'] query
     *        	to ecute sql
     * @return mixed item array, or false on failure
     */
    public function execteQuery($sql)
    {
        $result = DBUtil::executeSQL($sql);

        $objArray = DBUtil::marshallObjects($result);
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any results.'));
        }
        // Return the items
        return $objArray;
    }

    /**
     * get a parent cities
     * 
     * @author
     *
     * @param $args['childType'] type
     *        	which child type to get parent city
     * @param $args['childId'] id
     *        	which child id to get parent city
     * @return mixed item array, or false on failure
     */
    public function getParentCity($args)
    {
        $sClumon = "p.*,c.city_name AS parentname";
        $sql     = "SELECT ".$sClumon." FROM zselex_parent AS p
                LEFT JOIN zselex_city AS c ON(p.parentId = c.city_id)
                WHERE p.parentType = 'CITY' AND childType  ='".$args ['childType']."' AND childId = ".$args ['childId'];

        $result   = DBUtil::executeSQL($sql);
        $objArray = DBUtil::marshallObjects($result);
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any results.'));
        }
        // Return the items
        return $objArray;
    }

    /**
     * get a parent shops
     * 
     * @author
     *
     * @param $args['childType'] type
     *        	which child type to get parent shop
     * @param $args['childId'] id
     *        	which child id to get parent shop
     * @return mixed item array, or false on failure
     */
    public function getParentShop($args)
    {
        $sClumon = "p.*,s.shop_name AS parentname";
        $sql     = "SELECT ".$sClumon." FROM zselex_parent AS p
                LEFT JOIN zselex_shop AS s ON(p.parentId = s.shop_id)
                WHERE p.parentType = 'SHOP' AND childType  ='".$args ['childType']."' AND childId = ".$args ['childId'];

        $result = DBUtil::executeSQL($sql);

        $objArray = DBUtil::marshallObjects($result);
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any results.'));
        }
        // Return the items
        return $objArray;
    }

    /**
     * get a parent AD
     * 
     * @author
     *
     * @param $args['childType'] type
     *        	which child type to get parent AD
     * @param $args['childId'] id
     *        	which child id to get parent AD
     * @return mixed item array, or false on failure
     */
    public function getParentAd($args)
    {
        $sClumon = "p.*,a.name AS parentname";
        $sql     = "SELECT ".$sClumon." FROM zselex_parent AS p
                LEFT JOIN zselex_advertise AS a ON(p.parentId = a.advertise_id)
                WHERE p.parentType = 'AD' AND childType  ='".$args ['childType']."' AND childId = ".$args ['childId'];

        $result = DBUtil::executeSQL($sql);

        $objArray = DBUtil::marshallObjects($result);
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any results.'));
        }
        // Return the items
        return $objArray;
    }

    /**
     * get a parent plugin
     * 
     * @author
     *
     * @param $args['childType'] type
     *        	which child type to get parent plugin
     * @param $args['childId'] id
     *        	which child id to get parent plugin
     * @return mixed item array, or false on failure
     */
    public function getParentPlugin($args)
    {
        $sClumon = "p.*,pl.plugin_name AS parentname";
        $sql     = "SELECT ".$sClumon." FROM zselex_parent AS p
                LEFT JOIN zselex_plugin AS pl ON(p.parentId = pl.plugin_id )
                WHERE  p.parentType = 'PLUGIN' AND childType  ='".$args ['childType']."' AND childId = ".$args ['childId'];

        $result = DBUtil::executeSQL($sql);

        $objArray = DBUtil::marshallObjects($result);
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any results.'));
        }
        // Return the items
        return $objArray;
    }

    /**
     * get a parent
     * 
     * @author
     *
     * @param $args['childType'] type
     *        	which child type to get parent
     * @param $args['childId'] id
     *        	which child id to get parent
     * @return mixed item array, or false on failure
     */
    public function getParents($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>";
        $sClumon = "p.tableId,p.childType,p.childId,p.parentId,p.parentType,pt.*";
        $sql     = "SELECT ".$sClumon." FROM zselex_parent AS p
                LEFT JOIN ".$args ['parentTable']." AS pt ON(p.parentId = pt.".$args ['parentId']." )
                WHERE  p.parentType = '".$args ['parentType']."' AND p.childType  ='".$args ['childType']."' AND p.childId = ".$args ['childId'];

        // echo $sql . '<br>'; exit;
        $result = DBUtil::executeSQL($sql);

        $objArray = DBUtil::marshallObjects($result);
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any results.'));
        }
        // Return the items
        return $objArray;
    }

    public function getOwnerInShopList($args)
    {
        $shop_id = $args ['shop_id'];
        $sql     = "SELECT u.uname FROM zselex_shop_owners ownr , users u
                WHERE ownr.user_id=u.uid AND ownr.shop_id=$shop_id";
        $result  = DBUtil::executeSQL($sql);

        $objArray = DBUtil::marshallObjects($result);
        return $objArray;
    }

    /**
     * get a parent
     * 
     * @author
     *
     * @param $args['childType'] type
     *        	which child type to get parent
     * @param $args['childId'] id
     *        	which child id to get parent
     * @return mixed item array, or false on failure
     */
    public function getParentsId($args)
    {
        $sClumon = "p.tableId,p.childType,p.childId,p.parentId,p.parentType";
        $sql     = "SELECT ".$sClumon." FROM zselex_parent AS p
                WHERE  p.parentType = '".$args ['parentType']."' AND p.childType  ='".$args ['childType']."' AND p.childId = ".$args ['childId'];

        $result = DBUtil::executeSQL($sql);

        $objArray = DBUtil::marshallObjects($result);
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return LogUtil::registerError($this->__('Error! Could not load any results.'));
        }
        // Return the items
        return $objArray;
    }

    /**
     * delete a Type item
     *
     * @author
     *
     * @param $args['type_id'] ID
     *        	of the item
     * @return bool true on success, false on failure
     */
    public function deleteElement($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        // Argument check
        if (!isset($args ['IdValue']) || !is_numeric($args ['IdValue'])) {
            return LogUtil::registerArgsError();
        }

        // Get the ZSELEX story
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        if ($item == false) {
            return LogUtil::registerError($this->__('Error! Item not found.'));
        }

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('ZSELEX::',
                $item ['cr_uid'].'::'.$item ['IdValue'], ACCESS_DELETE),
            LogUtil::getErrorMsgPermission());

        if (!DBUtil::deleteObjectByID($args ['table'], $args ['IdValue'],
                $args ['IdName'])) {
            return LogUtil::registerError($this->__('Error! Could not delete type.'));
        }

        // Let the calling process know that we have finished successfully
        return true;
    }

    public function deleteAdDetails($args)
    {
        $sql       = "DELETE FROM zselex_parent WHERE parentType = 'CITY' AND childType = 'AD' AND childId='".$args ['IdValue']."'";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);

        $sql       = "DELETE FROM zselex_parent WHERE parentType = 'SHOP' AND childType = 'AD' AND childId='".$args ['IdValue']."'";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
    }

    /**
     * utility function to count the number of items held by this module
     *
     * @author
     *
     * @return int number of items held by this module
     */
    public function countElements($args)
    {
        // Optional arguments.
        if (!isset($args ['status']) || (empty($args ['status']) && $args ['status']
            !== 0)) {
            $args ['status'] = null;
        }
        $where = $args ['where'];
        $where = !empty($where) ? ' WHERE '.$where : '';

        return DBUtil::selectObjectCount($args ['table'], $where, $args ['Id'],
                false, $this->getCatFilter($args));
    }

    public function scount($args)
    {
        $sql       = $args ['sql'];
        $query     = $sql;
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $count     = $results->rowCount();

        return $count;
    }

    /**
     * create Category Filter
     *
     * @param array $args        	
     * @return array
     */
    protected function getCatFilter($args)
    {
        $catFilter = array();
        if (isset($args ['category']) && !empty($args ['category'])) {
            if (is_array($args ['category'])) {
                $catFilter = $args ['category'];
            } elseif (isset($args ['property'])) {
                $property              = $args ['property'];
                $catFilter [$property] = $args ['category'];
            }
            $catFilter ['__META__'] = array(
                'module' => 'ZSELEX'
            );
        } elseif (isset($args ['catfilter'])) {
            $catFilter = $args ['catfilter'];
        }
        return $catFilter;
    }

    public function getType()
    {
        $data      = array();
        $query     = "SELECT * FROM zselex_type ORDER BY typeid";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $values    = $results->fetchAll();
        return $values;
    }

    public function getElementsSelectArray()
    {
        $aSelectArray = array();

        $args = array(
            'table' => 'zselex_type',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );

        $aSelectArray ['types'] = $this->getElements($args);

        $cityargs                = array(
            'table' => 'zselex_city',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );
        $aSelectArray ['cities'] = $this->getElements($cityargs);

        $shopargs               = array(
            'table' => 'zselex_shop',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );
        $aSelectArray ['shops'] = $this->getElements($shopargs);

        $pluginargs = array(
            'table' => 'zselex_plugin',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );

        $aSelectArray ['plugins'] = $this->getElements($pluginargs);

        $args = array(
            'table' => 'zselex_shop_types',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );

        $aSelectArray ['ecommerce'] = $this->getElements($args);

        $countryargs              = array(
            'table' => 'zselex_country',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );
        $aSelectArray ['country'] = $this->getElements($countryargs);

        $regionargs              = array(
            'table' => 'zselex_region',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );
        $aSelectArray ['region'] = $this->getElements($regionargs);

        $branchargs              = array(
            'table' => 'zselex_branch',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );
        $aSelectArray ['branch'] = $this->getElements($branchargs);

        $adargs              = array(
            'table' => 'zselex_advertise',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );
        $aSelectArray ['ad'] = $this->getElements($adargs);

        $cateargs                  = array(
            'table' => 'zselex_category',
            'where' => '',
            'orderBy' => '',
            'useJoins' => ''
        );
        $aSelectArray ['category'] = $this->getElements($cateargs);

        return $aSelectArray;
    }

    public function getCity($city_id)
    {

        // echo "city_id: " . $city_id;
        $data = array();

        if (!empty($city_id)) {
            $query = "SELECT * FROM zselex_city a  LEFT JOIN zselex_parent b 
                      ON b.childId=a.city_id AND b.childType='CITY' WHERE a.city_id='".$city_id."'";
        } else {
            $query = "SELECT * FROM zselex_city";
        }

        // echo $query;
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $values    = $results->fetchAll();

        return $values;
    }

    public function getShop($items)
    {

        // echo "shop_id: ". $items['id'];
        if (empty($items ['id'])) {
            // echo "here";
            $query     = "SELECT a.shop_id,a.shop_name,a.description,a.address,a.telephone,a.fax,a.email,a.status,a.cr_date,a.cr_uid,a.lu_date,
                    a.lu_uid,b.shoptype_id, b.domain,b.hostname,b.dbname,b.username,b.password,b.table_prefix
                    FROM zselex_shop a 
                    LEFT JOIN zselex_shop_config b ON b.shop_id=a.shop_id
                    LEFT JOIN zselex_parent c ON c.childId=a.shop_id AND c.childType='SHOP' AND c.parentType='CITY'";
            // echo $query;
            $statement = Doctrine_Manager::getInstance()->connection();
            $results   = $statement->execute($query);
            $values    = $results->fetchAll();
            return $values;
        } else {
            // echo "here 1";
            $query = "SELECT a.shop_id,a.shop_name,a.description,a.address,a.telephone,a.fax,a.email,a.status,a.cr_date,a.cr_uid,a.lu_date,
                    a.lu_uid,c.shoptype_id,b.parentId, c.domain,c.hostname,c.dbname,c.username,c.password,c.table_prefix,d.city_name
                    FROM zselex_shop a
                  LEFT JOIN zselex_parent b ON b.childId=a.shop_id AND b.childType='SHOP'
                 
                  LEFT JOIN zselex_shop_config c ON c.shop_id=a.shop_id
                  LEFT JOIN zselex_city d ON b.parentId=d.city_id
                  WHERE a.shop_id='".$items ['id']."' ";

            // echo $query;
            $statement = Doctrine_Manager::getInstance()->connection();
            $results   = $statement->execute($query);
            $values    = $results->fetch();

            $plgn      = "SELECT * FROM zselex_plugin a LEFT JOIN zselex_parent b
                     ON b.childId=a.plugin_id and b.parentId='".$items ['id']."' and b.parentType='SHOP' and b.childType='PLUGIN'";
            // echo $plgn; exit;
            $statement = Doctrine_Manager::getInstance()->connection();
            $result    = $statement->execute($plgn);
            $value     = $result->fetchALL();
            $plugins   = array(
                'PLUGINS' => $value
            );
            // echo "<pre>"; print_r($plugins); echo "</pre>"; exit;
            $values    = $values + $plugins;
            // echo "<pre>"; print_r($values); echo "</pre>"; exit;
            // echo $plgn;
            return $values;
        }
    }

    public function getPlugin()
    {
        $data      = array();
        $query     = "SELECT * FROM zselex_plugin";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $values    = $results->fetchAll();

        return $values;
    }

    public function getShopTypes()
    {
        $query     = "SELECT * FROM zselex_shop_types";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $values    = $results->fetchAll();

        return $values;
    }

    public function getShopConfig($vars)
    {
        $query     = "SELECT * FROM zselex_shop a LEFT JOIN zselex_shop_config b
                 ON a.shop_id=b.shop_id WHERE a.shop_id='".$vars ['shop']."'";
        // echo $query; exit;
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $config    = $results->fetch();

        return $config;
    }

    public function getShopConfigs($vars)
    {
        $query     = "SELECT * FROM zselex_shop WHERE shop_id='".$vars ['shop']."'";
        // echo $query; exit;
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $config    = $results->fetch();

        return $config;
    }

    public function getAdTypes()
    {
        $query     = "SELECT * FROM zselex_advertise_price";
        // echo $query; exit;
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $values    = $results->fetchAll();

        return $values;
    }

    public function getSignleElement($args)
    {
        // Argument check
        if ((!isset($args ['table']) || $args ['table'] == '')) {
            return LogUtil::registerArgsError();
        }

        if (isset($args ['where']) && $args ['where'] != '') {
            $sql            = "SELECT * FROM ".$args ['table']." ";
            $args ['where'] = " WHERE ".$args ['where'];
            $sql .= $args ['where'];
        }

        $result = DBUtil::executeSQL($sql);

        $objArray = DBUtil::marshallObjects($result);
        // Check for an error with the database code, and if so set an appropriate
        // error message and return
        if ($objArray === false) {
            return $objArray;
        }
        // Return the items
        return $objArray;
    }

    public function get_cat_selectlist($current_cat_id = 0, $count = 0)
    {
        static $option_results;
        // if there is no current category id set, start off at the top level (zero)
        if (!isset($current_cat_id)) {
            $current_cat_id = 0;
        }
        // increment the counter by 1
        $count = $count + 1;

        // query the database for the sub-categories of whatever the parent category is
        $sql = "SELECT a.city_id , a.city_name , b.parentId from zselex_city a left join zselex_parent b on
	         a.city_id=b.childId and b.childType='CITY' and b.parentType='CITY'
	         where b.parentId =  '".$current_cat_id."'";
        $sql .= " order by a.city_name asc ";

        // echo $sql; exit;

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);

        $get_options = $statement->execute($sql);
        $num_options = $get_options->rowCount();

        // echo $num_options; exit;
        // our category is apparently valid, so go ahead â‚¬Â¦
        if ($num_options > 0) {
            while (list($cat_id, $cat_name, $parentId) = $get_options->fetch()) {
                // if its not a top-level category, indent it to
                // show that its a child category
                $indent_flag = '';

                if ($current_cat_id != 0) {
                    $indent_flag = '&nbsp';
                    for ($x = 2; $x <= $count; $x ++) {
                        $indent_flag .= '&nbsp&nbsp&nbsp';
                    }
                }
                $cat_name                 = $indent_flag.$cat_name;
                $option_results [$cat_id] = $cat_name;
                // now call the function again, to recurse through the child categories
                $this->get_cat_selectlist($cat_id, $count);
            }
        }
        return $option_results;
    }

    public function getShoplistFrontEnd($vars)
    {

        // print_r($vars);
        // $limit = '5';
        $limit = $vars ['amount'];
        if (isset($vars ['amount'])) {
            if (!empty($vars ['amount'])) {
                $limit = $vars ['amount'];
            }
        }
        $orderby = '';

        if (isset($vars ['orderby'])) {
            if ($vars ['orderby'] == 'random') {
                $orderby = ' ORDER BY RAND()';
            } elseif ($vars ['orderby'] == 'new') {
                $orderby = ' ORDER BY shop_id desc';
            }
        }

        // $sql = "SELECT * , a.shop_id as SID FROM zselex_shop a
        // LEFT JOIN zselex_serviceshop sv ON sv.shop_id=a.shop_id AND (type='minishop')
        // LEFT JOIN zselex_files b ON a.shop_id=b.shop_id AND b.defaultImg=1 LIMIT 0 , $limit";
        // $obj = DBUtil::selectObjectByID('zselex_shop', $shop_id, 'shop_id');

        $sql = "SELECT * , ms.configured as minishop_configured , g.image_name, a.shop_id as SID
                 FROM zselex_shop a
                 LEFT JOIN zselex_serviceshop sv ON sv.shop_id=a.shop_id AND (sv.type='linktoshop')
                 LEFT JOIN zselex_minishop ms ON ms.shop_id=a.shop_id
                 LEFT JOIN zselex_shop_owners ow ON ow.shop_id=a.shop_id
                 LEFT JOIN users u ON u.uid=ow.user_id
                 LEFT JOIN zselex_shop_gallery g ON a.shop_id=g.shop_id AND g.defaultImg=1
                 LEFT JOIN zselex_files b ON a.shop_id=b.shop_id AND b.defaultImg=1
                 WHERE a.shop_id IS NOT NULL  AND a.status='1'
                 LIMIT 0 , $limit";

        // echo $sql;

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $allshops  = $results->fetchAll();

        // echo "<pre>"; print_r($allshops); echo "</pre>";

        return $allshops;
    }

    public function getShoplistCount()
    {
        $sql = "SELECT * FROM zselex_shop";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $count     = $results->rowCount();

        return $count;
    }

    public function getShopUser($usereargs)
    {
        $shop_id = $usereargs ['shop_id'];
        $sql     = "SELECT * FROM zselex_parent WHERE childType='SHOP' AND childId=$shop_id AND parentType='USER'";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->fetchAll();

        return $value;
    }

    public function getUser($usereargs)
    {
        $loguser = UserUtil::getVar('uid');
        if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            $sql = "SELECT * FROM users WHERE uid!=1 AND uid!=2";
        } else {
            $sql = "SELECT * FROM users a , zselex_parent b WHERE a.uid!=1 AND a.uid!=2 
                AND a.uid=b.parentId AND b.cr_uid=$loguser AND b.childType='SHOP' AND b.parentType='SHOPADMIN'";
        }

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->fetchAll();

        return $value;
    }

    public function getserviceBasket($args)
    {
        $sql = "SELECT * , a.price newprice FROM zselex_basket a LEFT JOIN zselex_plugin b ON a.plugin_id=b.plugin_id
                LEFT JOIN zselex_shop s ON a.shop_id=s.shop_id
                    WHERE a.user_id='".$args ['user_id']."' AND a.status=0";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->fetchAll();

        return $value;
    }

    public function getServiceAmount($args)
    {
        $sql = "SELECT sum(price) as total FROM zselex_basket WHERE user_id='".$args ['user_id']."' AND status=0";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->fetch();
        $total     = $value ['total'];

        return $total;
    }

    public function getserviceFromBasket($args)
    {
        $sql = "SELECT * FROM zselex_basket 
                    WHERE user_id='".$args ['user_id']."' AND status=0";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->fetchAll();

        // echo "<pre>"; print_r($value); echo "</pre>"; exit;
        return $value;
    }

    public function getBasket($msge)
    {
        if (empty($_SESSION ['admincart'])) {
            if (!empty($_COOKIE ['admincart'])) {
                foreach ($_COOKIE ['admincart'] as $key => $val) {
                    $finalCookie [$key] = json_decode($val, true);
                }

                foreach ($finalCookie as $key => $val) {
                    $_SESSION ['admincart'] [$key] = array(
                        'plugin_id' => $val ['plugin_id'],
                        'plugin_name' => $val ['plugin_name'],
                        'type' => $val ['type'],
                        'shop_id' => $val ['shop_id'],
                        'user_id' => $val ['user_id'],
                        'quantity' => $val ['quantity'],
                        'qty_based' => $val ['qty_based'],
                        'price' => $val ['price'],
                        'originalPrice' => $val ['originalPrice']
                    );
                }
            }
        }

        $output       = '';
        $disabled     = '';
        // $loguser = UserUtil::getVar('uid');
        // if (!empty($_SESSION['admincart'])) {
        // $sessionCount = count($_SESSION['admincart']);
        $sessionCount = !empty($_SESSION ['admincart']) ? count($_SESSION ['admincart'])
                : 0;
        // }
        // if (!empty($_COOKIE['admincart'])) {
        // $cookieCount = count($_COOKIE['admincart']);
        // }

        $cookieCount = !empty($_COOKIE ['admincart']) ? count($_COOKIE ['admincart'])
                : 0;

        if (count($_COOKIE ['admincart']) > 0) {
            foreach ($_COOKIE ['admincart'] as $key => $val) {
                $finalCookie [$key] = json_decode($val, true);
            }
        }

        $count = (empty($cookieCount)) ? $sessionCount : $cookieCount;

        $GRANDTOTAL = '';
        if ($sessionCount > $cookieCount) {
            if (count($_SESSION ['admincart']) > 0) {
                foreach ($_SESSION ['admincart'] as $val) {
                    $grandPrice [] = $val ['price'];
                }
                $GRANDTOTAL = array_sum($grandPrice);
                $grandtotal = $GRANDTOTAL;
            }
        } else {
            if (count($_COOKIE ['admincart']) > 0) {
                foreach ($finalCookie as $val) {
                    $grandPrice [] = $val ['price'];
                }
                $GRANDTOTAL = array_sum($grandPrice);
                $grandtotal = $GRANDTOTAL;
            }
        }

        /*
         * if ($sessionCount > $cookieCount) { // if session is set first then render session products to cart page otherwise render cookie products
         * $values = $_SESSION['admincart'];
         * } elseif (($sessionCount < $cookieCount) && ($cookieCount - $sessionCount == 1)) {
         * $values = $_SESSION['admincart'];
         * } elseif ($sessionCount == $cookieCount) {
         * $values = $_SESSION['admincart'];
         * } else {
         *
         * $values = $finalCookie;
         * }
         */

        if ($sessionCount > $cookieCount) { // if session is set first then render session products to cart page otherwise render cookie products
            $values = $_SESSION ['admincart'];
        } elseif ($sessionCount == $cookieCount) {
            $values = $_SESSION ['admincart'];
        } elseif (($sessionCount < $cookieCount) && ($cookieCount - $sessionCount
            == 1)) {
            $values = $_SESSION ['admincart'];
        } else {
            // $test1 = "comes here";
            $values = $finalCookie;
        }

        // $test = "session :" . $sessionCount . " " . "cookie : " . $cookieCount;
        // return $test;

        $data ['values']     = $values;
        $data ['grandtotal'] = $grandtotal;

        return $values;
    }

    public function insertServicesShopApprovals($args)
    {

        // echo "<pre>"; print_r($args['data']); echo "</pre>"; exit;
        foreach ($args ['data'] as $key => $srvc) {
            $chk        = "SELECT * FROM zselex_serviceapproval WHERE shop_id='".$srvc ['shop_id']."' AND owner_id='".$args ['owner_id']."'  AND type='".$srvc ['type']."'  AND status=0";
            $statement  = Doctrine_Manager::getInstance()->connection();
            $results    = $statement->execute($chk);
            $totalCount = $results->rowCount();

            if ($totalCount < 1) {
                $sql           = "INSERT INTO zselex_serviceapproval(shop_id , user_id , owner_id ,plugin_id , type , quantity , status)VALUES('".$srvc ['shop_id']."','".$args ['user_id']."', '".$args ['owner_id']."'   ,'".$srvc ['plugin_id']."','".$srvc ['type']."','".$srvc ['quantity']."' , 0)";
                // echo $sql; exit;
                $statement     = Doctrine_Manager::getInstance()->connection();
                $resultsInsert = $statement->execute($sql);
                unset($_SESSION ['admincart'] [$key]);
                setcookie("admincart[$key]", "", time() - 604800);
            } else {
                $sql = "UPDATE zselex_serviceapproval SET quantity=quantity+$srvc[quantity] WHERE shop_id='".$srvc ['shop_id']."' AND owner_id='".$args ['user_id']."' AND plugin_id='".$srvc ['plugin_id']."' AND type='".$srvc ['type']."'  AND status=0";

                // echo $sql; exit;
                $statement = Doctrine_Manager::getInstance()->connection();
                $results   = $statement->execute($sql);
                unset($_SESSION ['admincart'] [$key]);
                setcookie("admincart[$key]", "", time() - 604800);
            }
        }

        // $sql = "UPDATE zselex_basket SET status=1 WHERE user_id='" . $args['user_id'] . "' AND status=0";
        // $statement = Doctrine_Manager::getInstance()->connection();
        // $results = $statement->execute($sql);
    }

    public function submitServiceToApprove($args)
    {

        // echo "<pre>"; print_r($args['data']); echo "</pre>"; exit;
        foreach ($args ['data'] as $key => $srvc) {
            $chk        = "SELECT * FROM zselex_serviceapproval WHERE shop_id='".$srvc ['shop_id']."' AND owner_id='".$srvc ['owner_id']."'  AND type='".$srvc ['type']."'  AND status=0";
            $statement  = Doctrine_Manager::getInstance()->connection();
            $results    = $statement->execute($chk);
            $totalCount = $results->rowCount();

            if ($totalCount < 1) {
                $date          = date("Y-m-d");
                $sql           = "INSERT INTO zselex_serviceapproval(shop_id , user_id , owner_id ,plugin_id , type , bundle , bundle_id , top_bundle , quantity , status , service_status , qty_based , timer_days ,cr_date )VALUES('".$srvc ['shop_id']."','".$args ['user_id']."', '".$srvc ['owner_id']."'   ,'".$srvc ['plugin_id']."','".$srvc ['type']."', '".$srvc ['bundle']."'   ,  '".$srvc ['bundle_id']."'   ,   '".$srvc ['top_bundle']."'   ,'".$srvc ['quantity']."' , 0 , '".$srvc ['service_status']."' , '".$srvc ['qty_based']."' ,  '".$srvc ['timer_days']."'    ,'".$date."')";
                // echo $sql; exit;
                $statement     = Doctrine_Manager::getInstance()->connection();
                $resultsInsert = $statement->execute($sql);
            } else {
                $sql       = "UPDATE zselex_serviceapproval SET quantity=quantity+$srvc[quantity] , service_status=$srvc[service_status] , timer_days=$srvc[timer_days]
                        WHERE shop_id='".$srvc ['shop_id']."' AND owner_id='".$args ['user_id']."' AND plugin_id='".$srvc ['plugin_id']."' AND type='".$srvc ['type']."'  AND status=0";
                $statement = Doctrine_Manager::getInstance()->connection();
                $results   = $statement->execute($sql);
            }
        }
    }

    public function insertServicesShopApproval($args)
    {
        // echo "<pre>"; print_r($args['data']); echo "</pre>"; exit;
        foreach ($args ['data'] as $srvc) {
            $chk        = "SELECT * FROM zselex_serviceapproval WHERE shop_id='".$srvc ['shop_id']."' AND user_id='".$srvc ['user_id']."' AND plugin_id='".$srvc ['plugin_id']."' AND type='".$srvc ['type']."'  AND status=0";
            $statement  = Doctrine_Manager::getInstance()->connection();
            $results    = $statement->execute($chk);
            $totalCount = $results->rowCount();

            if ($totalCount < 1) {
                $sql           = "INSERT INTO zselex_serviceapproval(shop_id , user_id , plugin_id , type , quantity , status)VALUES('".$srvc ['shop_id']."','".$srvc ['user_id']."','".$srvc ['plugin_id']."','".$srvc ['type']."','".$srvc ['quantity']."' , 0)";
                // echo $sql; exit;
                $statement     = Doctrine_Manager::getInstance()->connection();
                $resultsInsert = $statement->execute($sql);
            } else {
                $sql       = "UPDATE zselex_serviceapproval SET quantity=quantity+$srvc[quantity] WHERE shop_id='".$srvc ['shop_id']."' AND user_id='".$srvc ['user_id']."' AND plugin_id='".$srvc ['plugin_id']."' AND type='".$srvc ['type']."'  AND status=0";
                $statement = Doctrine_Manager::getInstance()->connection();
                $results   = $statement->execute($sql);
            }
        }

        $sql       = "UPDATE zselex_basket SET status=1 WHERE user_id='".$args ['user_id']."' AND status=0";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
    }

    /**
     * This function will aprove the service from service approval table.
     * It will update the service table with demo or paid.
     * if the service was already existing as demo it will update it to paid by setting the new date
     * if the service was existing as paid it will just update the quantity
     * if service doesnt exist in the table it will insert as new
     */
    public function approveServices($args)
    { // approve service
        // doesnt needs to know whether its a paid or demo. only update quantity here.
        $sql       = "SELECT * FROM zselex_serviceapproval WHERE id='".$args ['id']."'";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->fetch();

        // echo "<pre>"; print_r($value); exit;

        if ($value ['top_bundle']) {
            // echo "hellooooo"; exit;
            $bundle_details = ModUtil::apiFunc('ZSELEX', 'user', 'get',
                    $getargs        = array(
                    'table' => 'zselex_service_bundles',
                    'where' => "bundle_id=$value[bundle_id]"
            ));
            // echo "<pre>"; print_r($bundle_details); exit;
            if ($bundle_details ['bundle_type'] != 'additional') {
                DBUtil::deleteWhere('zselex_serviceshop',
                    $where = "shop_id=$value[shop_id] AND top_bundle=1");
                DBUtil::deleteWhere('zselex_service_demo',
                    $where = "shop_id=$value[shop_id] AND top_bundle=1");
                DBUtil::deleteWhere('zselex_service_config',
                    $where = "shop_id=$value[shop_id] AND top_bundle=1");
            }
        }

        $chk        = "SELECT * FROM zselex_serviceshop
                WHERE shop_id='".$value ['shop_id']."' AND owner_id='".$value ['owner_id']."' AND plugin_id='".$value ['plugin_id']."' AND type='".$value ['type']."'";
        // echo $chk; exit;
        $statement  = Doctrine_Manager::getInstance()->connection();
        $results    = $statement->execute($chk);
        $item       = $results->fetch();
        $totalCount = $results->rowCount();
        $date       = date("Y-m-d");

        // echo $totalCount; exit;

        if ($totalCount < 1) { // doesnt exist as paid/demo
            $date           = date("Y-m-d");
            $remaining_days = date('t') - date('j');
            if (($value ['top_bundle'] == 1 && $bundle_details ['bundle_type'] != 'additional')
                || $value ['top_bundle'] != 1) {
                $sql           = "INSERT INTO zselex_serviceshop(shop_id , user_id , owner_id , plugin_id , type , original_quantity , quantity , status , service_status , qty_based , bundle, bundle_id, top_bundle ,timer_date,timer_days)
                    VALUES('".$value ['shop_id']."','".$value ['user_id']."',  '".$value ['owner_id']."'  ,'".$value ['plugin_id']."','".$value ['type']."', '".$value ['quantity']."'  ,'".$value ['quantity']."' ,  '1'  ,'".$value ['service_status']."' ,   '".$value ['qty_based']."'  ,   '".$value ['bundle']."'  ,  '".$value ['bundle_id']."',  '".$value ['top_bundle']."'   ,'".$date."','".$value ['timer_days']."')";
                // echo $sql; exit;
                $statement     = Doctrine_Manager::getInstance()->connection();
                $resultsInsert = $statement->execute($sql);
            }
            if ($value ['type'] == 'minishop') {
                $m_arg         = array(
                    'table' => 'zselex_minishop',
                    'where' => "shop_id=$value[shop_id]",
                    'Id' => 'id'
                );
                $minishopCount = $this->countElements($m_arg);
                if ($resultsInsert) { // configure as ishop as default if its a 'minishop' service
                    if ($minishopCount < 1) {
                        $item_minishop = array(
                            'shop_id' => $value ['shop_id'],
                            'shoptype_id' => 2,
                            'shoptype' => 'iSHOP',
                            'minishop_name' => 'My Ishop',
                            'description' => '',
                            'configured' => 1
                        );
                        $args_minishop = array(
                            'table' => 'zselex_minishop',
                            'element' => $item_minishop,
                            'Id' => 'id'
                        );

                        $insert_minishop = ModUtil::apiFunc('ZSELEX', 'admin',
                                'createElement', $args_minishop);
                    }
                }
            }

            if ($value ['service_status'] == 2) { // only applicable for paid service
                $obj    = array(
                    'shop_id' => $value ['shop_id'],
                    'plugin_id' => $value ['plugin_id'],
                    'type' => $value ['type'],
                    'user_id' => $value ['user_id'],
                    'owner_id' => $value ['owner_id'],
                    'service_status' => $value ['service_status'],
                    'qty_based' => $value ['qty_based'],
                    'bundle' => $value ['bundle'],
                    'bundle_id' => $value ['bundle_id'],
                    'top_bundle' => $value ['top_bundle'],
                    'start_date' => $date
                );
                $insert = DBUtil::insertObject($obj, 'zselex_service_config');
            }
            if ($value ['bundle'] == 1) {
                $bundleitems = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray',
                        $args1       = array(
                        'table' => 'zselex_service_bundle_items',
                        'where' => array(
                            "bundle_id=".$value ['bundle_id']
                        )
                ));

                $value ['bundleitems'] = $bundleitems;
                $this->approveBundleItems($value);
            }
        } else {
            // echo "comes here...."; exit;
            // echo "<pre>"; print_r($item); exit;
            if ($item ['service_status'] == 1 & $value ['service_status'] == 2) { // if already it was existed as demo and its chnaging to paid then update the date as new date for paid
                $remaining_days = date('t') - date('j');
                // $timerdate = " AND timer_date='" . $date . "'";
                $set_timerdate  = " , timer_date='".$date."' , timer_days ='".$value [timer_days]."' "; // set the date to latest date if the service is changing from demo to paid
                $set_quantity   = " original_quantity=$value[quantity] , quantity=$value[quantity] , availed=0 ";
            } else {
                // $timerdate = '';
                $set_timerdate = '';
                $set_timerdate = " , timer_date='".$date."' , timer_days ='".$value [timer_days]."' ";
                $set_quantity  = " original_quantity=quantity+$value[quantity] , quantity=quantity+$value[quantity] "; // if its in demo or paid and just upgrading its quantity
            }

            $timerdate = '';

            // $sql = "UPDATE zselex_serviceshop SET quantity=quantity+$value[quantity] , status='1' WHERE shop_id='" . $value['shop_id'] . "' AND owner_id='" . $value['owner_id'] . "' AND plugin_id='" . $value['plugin_id'] . "' AND type='" . $value['type'] . "' AND service_status='" . $value['service_status'] . "'" . " " . $timerdate;
            $sql = "UPDATE zselex_serviceshop
                    SET $set_quantity , status='1' , service_status='".$value ['service_status']."' "." ".$set_timerdate." 
                    WHERE shop_id='".$value ['shop_id']."' AND owner_id='".$value ['owner_id']."' AND plugin_id='".$value ['plugin_id']."' AND type='".$value ['type']."'";
            // echo $sql; exit;

            $statement = Doctrine_Manager::getInstance()->connection();
            $results   = $statement->execute($sql);
        }

        $sql       = "UPDATE zselex_serviceapproval SET status=1 WHERE id='".$args ['id']."'";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
    }

    public function approveBundleItems($args)
    {
        // $msg = "bundle items";
        // echo $msg; exit;
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $date           = date("Y-m-d");
        $remaining_days = date('t') - date('j');
        $bundleitems    = $args ['bundleitems'];

        foreach ($bundleitems as $key => $val) {
            $chk        = "SELECT * FROM zselex_serviceshop
                    WHERE shop_id='".$args ['shop_id']."' 
                    AND owner_id='".$args ['owner_id']."' AND plugin_id='".$val ['plugin_id']."' AND type='".$val ['servicetype']."'";
            // echo $chk; exit;
            $statement  = Doctrine_Manager::getInstance()->connection();
            $results    = $statement->execute($chk);
            $item       = $results->fetch();
            $totalCount = $results->rowCount();
            $date       = date("Y-m-d");

            // echo $totalCount; exit;

            if ($totalCount < 1) { // doesnt exist as paid/demo
                $date = date("Y-m-d");

                $sql           = "INSERT INTO zselex_serviceshop(shop_id , user_id , owner_id , plugin_id , type , original_quantity , quantity , status ,service_status , qty_based , bundle , bundle_id , timer_date , timer_days)
                    VALUES('".$args ['shop_id']."','".$args ['user_id']."',  '".$args ['owner_id']."'  ,'".$val ['plugin_id']."','".$val ['servicetype']."', '".$val ['qty']."'  ,'".$val ['qty']."' ,  '1'  , 2 ,   '".$val ['qty_based']."'  , '".$args ['bundle']."' , '".$val ['bundle_id']."'  ,'".$date."','".$args ['timer_days']."')";
                // echo $sql; exit;
                $statement     = Doctrine_Manager::getInstance()->connection();
                $resultsInsert = $statement->execute($sql);

                if ($val ['servicetype'] == 'minishop') {
                    $m_arg         = array(
                        'table' => 'zselex_minishop',
                        'where' => "shop_id=$args[shop_id]",
                        'Id' => 'id'
                    );
                    $minishopCount = $this->countElements($m_arg);
                    if ($resultsInsert) { // configure as ishop as default if its a 'minishop' service
                        if ($minishopCount < 1) {
                            $item_minishop = array(
                                'shop_id' => $args ['shop_id'],
                                'shoptype_id' => 2,
                                'shoptype' => 'iSHOP',
                                'minishop_name' => 'My Ishop',
                                'description' => '',
                                'configured' => 1
                            );
                            $args_minishop = array(
                                'table' => 'zselex_minishop',
                                'element' => $item_minishop,
                                'Id' => 'id'
                            );

                            $insert_minishop = ModUtil::apiFunc('ZSELEX',
                                    'admin', 'createElement', $args_minishop);
                        }
                    }
                }

                if ($args ['service_status'] == 2) { // only applicable for paid service
                    $obj    = array(
                        'shop_id' => $args ['shop_id'],
                        'plugin_id' => $val ['plugin_id'],
                        'type' => $val ['servicetype'],
                        'user_id' => $args ['user_id'],
                        'owner_id' => $args ['owner_id'],
                        'service_status' => $args ['service_status'],
                        'qty_based' => $val ['qty_based'],
                        'bundle' => $args ['bundle'],
                        'bundle_id' => $args ['bundle_id'],
                        'start_date' => $date
                    );
                    $insert = DBUtil::insertObject($obj, 'zselex_service_config');
                }
            } else {
                // echo "comes here...."; exit;
                // echo "<pre>"; print_r($item); exit;
                if ($item ['service_status'] == 1 & $args ['service_status'] == 2) { // if already it was existed as demo and its chnaging to paid then update the date as new date for paid
                    // $timerdate = " AND timer_date='" . $date . "'";
                    // $set_timerdate = " , timer_date='" . $date . "' "; // set the date to latest date if the service is changing from demo to paid
                    // $set_quantity = " quantity=$val[qty] , availed=0 ";
                    $set_timerdate = " , timer_date='".$date."' , timer_days ='".$args ['timer_days']."' "; // set the date to latest date if the service is changing from demo to paid
                    $set_quantity  = " quantity=$args[quantity] , availed=0 ";
                    // echo $set_quantity; exit;
                } else {
                    // $timerdate = '';
                    // $set_timerdate = '';
                    // $set_quantity = " quantity=quantity+$val[qty] "; // if its in demo or paid and just upgrading its quantity
                    $set_timerdate = '';
                    $set_timerdate = " , timer_date='".$date."' , timer_days ='".$args ['timer_days']."' ";
                    $set_quantity  = " quantity=quantity+$args[quantity] "; // if its in demo or paid and just upgrading its quantity
                    // echo $set_quantity; exit;
                }
                $timerdate = '';

                // $sql = "UPDATE zselex_serviceshop SET quantity=quantity+$value[quantity] , status='1' WHERE shop_id='" . $value['shop_id'] . "' AND owner_id='" . $value['owner_id'] . "' AND plugin_id='" . $value['plugin_id'] . "' AND type='" . $value['type'] . "' AND service_status='" . $value['service_status'] . "'" . " " . $timerdate;
                $sql = "UPDATE zselex_serviceshop
                    SET $set_quantity , status='1' , service_status='2' "." ".$set_timerdate." 
                    WHERE shop_id='".$args ['shop_id']."' AND owner_id='".$args ['owner_id']."' AND plugin_id='".$val ['plugin_id']."' AND type='".$val ['servicetype']."'";
                // echo $sql; exit;

                $statement = Doctrine_Manager::getInstance()->connection();
                $results   = $statement->execute($sql);
            }
        }
        // echo "comes here"; exit;
        return true;
    }

    public function insertServicesShop($args)
    {
        foreach ($args ['data'] as $srvc) {
            $chk        = "SELECT * FROM zselex_serviceshop WHERE shop_id='".$srvc ['shop_id']."' AND user_id='".$srvc ['user_id']."' AND plugin_id='".$srvc ['plugin_id']."' AND type='".$srvc ['type']."'";
            $statement  = Doctrine_Manager::getInstance()->connection();
            $results    = $statement->execute($chk);
            $totalCount = $results->rowCount();

            if ($totalCount < 1) {
                $sql           = "INSERT INTO zselex_serviceshop(shop_id , user_id , plugin_id , type, quantity)VALUES('".$srvc ['shop_id']."','".$srvc ['user_id']."','".$srvc ['plugin_id']."','".$srvc ['type']."','".$srvc ['quantity']."')";
                echo $sql;
                exit();
                $statement     = Doctrine_Manager::getInstance()->connection();
                $resultsInsert = $statement->execute($sql);
            } else {
                $sql       = "UPDATE zselex_serviceshop SET quantity=quantity+$srvc[quantity] WHERE shop_id='".$srvc ['shop_id']."' AND user_id='".$srvc ['user_id']."' AND plugin_id='".$srvc ['plugin_id']."' AND type='".$srvc ['type']."'";
                $statement = Doctrine_Manager::getInstance()->connection();
                $results   = $statement->execute($sql);
            }
        }

        $sql       = "UPDATE zselex_basket SET status=1 WHERE user_id='".$args ['user_id']."' AND status=0";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
    }

    public function getServicePermission($args)
    {
        $sql = "SELECT * FROM zselex_serviceshop 
                WHERE user_id='".$args ['user_id']."' AND type='".$args ['type']."' AND quantity > availed";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->fetchAll();

        // echo "<pre>"; print_r($value); echo "</pre>"; exit;
        return $value;
    }

    public function updateServiceAvailed($args)
    {
        $sql       = "UPDATE zselex_serviceshop SET availed=availed+1 WHERE user_id='".$args ['user_id']."' AND type='".$args ['type']."' AND shop_id='".$args ['shop_id']."'";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
    }

    public function lessServiceAvailed($args)
    {
        if (!empty($args ['shop_id'])) {
            $shopSql = " AND shop_id=$args[shop_id] ";
        }

        $sql       = "UPDATE zselex_serviceshop SET availed=availed-1 WHERE user_id='".$args ['user_id']."' AND type='".$args ['type']."'";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
    }

    public function getServicePurchased($args)
    {

        // $sql = "SELECT * FROM zselex_serviceshop
        // WHERE user_id='" . $args['user_id'] . "' AND shop_id='" . $args['shop_id'] . "'";
        $sql = "SELECT a.* , b.plugin_name , b.func_name , b.price , b.is_editable
                FROM zselex_serviceshop a
                LEFT JOIN zselex_plugin b ON b.plugin_id=a.plugin_id
                WHERE  a.shop_id='".$args ['shop_id']."' AND a.status=1
                ORDER BY IF(b.sort_order = 0, 999999999, b.sort_order) ASC ";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->fetchAll();

        return $value;
    }

    public function getServicePurchasedCount($args)
    {

        // $sql = "SELECT * FROM zselex_serviceshop
        // WHERE user_id='" . $args['user_id'] . "' AND shop_id='" . $args['shop_id'] . "'";
        $sql = "SELECT * FROM zselex_serviceshop 
                WHERE shop_id='".$args ['shop_id']."'";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->rowCount();

        return $value;
    }

    public function getServicePurchasedQuantity($args)
    {
        $sql = "SELECT quantity FROM zselex_serviceshop 
                WHERE type='".$args ['type']."' AND shop_id=".$args ['shop_id'];

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->fetch();
        $value     = $value ['quantity'];

        return $value;
    }

    public function getServicePurchasedCounts($args)
    {
        if (!empty($args ['shop_id'])) {
            $shopSql = " AND shop_id=$args[shop_id] ";
        }

        $sql = "SELECT * FROM zselex_serviceshop
                WHERE user_id='".$args ['user_id']."' $shopSql AND type='".$args ['type']."' AND quantity > availed";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->rowCount();

        return $value;
    }

    public function getServicePurchasedCountProducts($args)
    {
        if (!empty($args ['shop_id'])) {
            $shopSql = " AND a.shop_id=$args[shop_id] ";
        }

        $sql = "SELECT * FROM zselex_serviceshop a , zselex_shop b
                WHERE a.user_id='".$args ['user_id']."' $shopSql AND a.type='".$args ['type']."' AND a.quantity > a.availed 
                    AND b.shoptype_id='2'";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->rowCount();

        return $value;
    }

    public function deleteItem($args)
    {
        $sql    = "DELETE FROM ".$args ['table']." WHERE  ".$args ['IdName']."='".$args ['IdValue']."'";
        // echo $sql; exit;
        // $statement = Doctrine_Manager::getInstance()->connection();
        // $results = $statement->execute($sql);
        $result = DBUtil::executeSQL($sql);

        return true;
    }

    public function updateSingleItem($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $table   = $args ['table'];
        $idName  = $args ['IdName'];
        $idValue = $args ['IdValue'];

        $values = '';
        foreach ($args ['element'] as $key => $val) {
            $values .= $key."="."'$val'".',';
        }
        $values = substr($values, 0, - 1);

        $sql       = "UPDATE $table SET $values WHERE $idName='".$idValue."'";
        // echo $sql; exit;
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->exec($sql);
    }

    public function updateMultipleItem($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $table   = $args ['table'];
        $idName  = $args ['IdName'];
        $idValue = $args ['IdValue'];

        $values = '';
        foreach ($args ['values'] as $key => $val) {
            $values .= $key."="."'$val'".',';
        }
        $values = substr($values, 0, - 1);

        $where = '';
        if (!empty($args ['where'])) {
            $where = ' WHERE ';
            foreach ($args ['where'] as $key => $val) {
                $where .= " ".$key."="."'$val'".' AND';
            }
        }
        $where = substr($where, 0, - 4);
        $sql   = "UPDATE $table SET $values $where";
        // echo $sql; exit;

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->exec($sql);
    }

    public function selectItems($args)
    {
        $table   = $args ['table'];
        $idName  = $args ['IdName'];
        $idValue = $args ['IdValue'];

        $fields = '';
        if ($fields != '') {
            foreach ($args ['fields'] as $val) {
                $fields .= $val.',';
            }
        } else {
            $fields = '*';
        }

        $where = '';
        if (!empty($args ['where'])) {
            $where = 'WHERE ';
            foreach ($args ['where'] as $key => $val) {
                $where .= " ".$key."="."'$val'".' AND';
            }
        }

        $where = substr($where, 0, - 4);

        $sql = "SELECT  $fields FROM $table $where";
        // echo $sql;

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $values    = $results->fetchAll();

        return $values;
    }

    public function trackUpdates($args)
    {
        $args = array(
            'type' => $type,
            'type_id' => $item_id,
            'sDate' => $date,
            'user_id' => $user_id,
            'action' => $action
        );

        $sql       = "INSERT INTO zselex_history(type , type_id , sDate , user_id , action)VALUES('".$args ['type']."' , '".$args ['type_id']."' , '".$args ['sDate']."' ,'".$args ['user_id']."' , '".$args ['action']."')";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
    }

    public function getServiceImage($args)
    {
        $sql       = "SELECT quantity , availed  FROM zselex_serviceshop WHERE user_id='".$args ['user_id']."' AND shop_id='".$args ['shop_id']."' AND type='".$args ['type']."' AND quantity>availed";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $value     = $results->fetch();

        $quantity = $value ['quantity'];
        return $value;
    }

    public function getShopImages($args)
    {

        // print_r($args);
        $sql       = "SELECT file_id , dispname, name , defaultImg FROM zselex_files WHERE shop_id='".$args ['shop_id']."' ORDER BY file_id asc";
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);
        $values    = $results->fetchAll();

        return $values;
    }

    public function test($args)
    {

        // print_r($args);
        $query = "select * from zselex_order";
        $stmt  = $this->prepare($query);
        $stmt->execute($stmt);

        // return $values;
    }

    public function uploadFile($args)
    {

        // print_r($args); exit;
        // Security check
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
            return LogUtil::registerPermissionError();
        }
        // extract($_FILES['file']);
        // print_r($args['fname']); exit;

        $name              = $args ['fname'];
        // Check file extension
        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg',
            'JPEG'
        );
        $ex                = end(explode(".", $name));
        if (!in_array($ex, $allowedExtensions)) {
            return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s',
                        $ex));
        }

        // Check file size
        if ($size >= 16000000) {
            return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
        }

        // $destination = $this->getVar('upload_path');
        $destination = $args ['destination'];
        $code        = FileUtil::uploadFile($name, $destination);
        LogUtil::registerError(FileUtil::uploadErrorMsg($code));

        // create thumbnail
        $imagine = new Imagine\Gd\Imagine();
        $size    = new Imagine\Image\Box(120, 120);
        $mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $imagine->open($destination.'/'.$name)->thumbnail($size, $mode)->save($destination.'/thumbs/'.$name);
    }

    public function uploadSingleFile($file, $destination)
    {
        $name              = $file ['name'];
        // exit;
        // Check file extension
        $allowedExtensions = array(
            'png',
            'jpg',
            'gif',
            'jpeg'
        );
        $ex                = end(explode(".", $name));
        if (!in_array($ex, $allowedExtensions)) {
            return LogUtil::registerError($this->__f('Error! Invalid file type: %1$s',
                        $ex));
        }

        // Check file size
        if ($size >= 16000000) {
            return LogUtil::registerError($this->__('Error! Your file is too big. The limit is 14 MB.'));
        }

        $destination = $destination;
        $code        = FileUtil::uploadFile('files', $destination);
        LogUtil::registerError(FileUtil::uploadErrorMsg($code));

        // create thumbnail
        $imagine = new Imagine\Gd\Imagine();
        $size    = new Imagine\Image\Box(120, 120);
        $mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $imagine->open($destination.'/'.$name)->thumbnail($size, $mode)->save($destination.'/thumbs/'.$name);
    }

    public function getProducts($vars)
    {

        // echo "text.....";
        try {
            // echo "<pre>"; print_r($vars); echo "</pre>"; exit;

            $query     = "SELECT * FROM zselex_shop s , zselex_minishop m
                 WHERE s.shop_id='".$vars ['shop']."' AND s.shop_id=m.shop_id";
            // echo $query; exit;
            $statement = Doctrine_Manager::getInstance()->connection();
            $results   = $statement->execute($query);
            $config    = $results->fetch();
            // echo "<pre>"; print_r($values); echo "</pre>";
            // echo "<pre>"; print_r($config); echo "</pre>";
            // echo $config['dbname'];
            $limit     = '3';
            $orderby   = " ORDER BY a.products_id";

            if ($vars ['amount'] != '') {
                $limit = $vars ['amount'];
            }

            // echo $limit;

            if ($config ['shoptype'] == 'zSHOP') {
                $zshop = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                        $args  = array(
                        'table' => 'zselex_zenshop',
                        'where' => array(
                            "shop_id=$vars[shop]"
                        )
                ));

                $dnName  = (!empty($zshop ['dbname']) ? $zshop ['dbname'] : 'nodb');
                $dnUser  = (!empty($zshop ['username']) ? $zshop ['username'] : 'root');
                $dbPswrd = (!empty($zshop ['password']) ? $zshop ['password'] : '');
                $dbHost  = (!empty($zshop ['hostname']) ? $zshop ['hostname'] : 'localhost');

                $dsn = "mysql:dbname='".$dnName."';host='".$dbHost."'";
                // echo $dsn; exit;

                $dsn         = "mysql:dbname=$dnName;host=$dbHost";
                $user        = $dnUser;
                $password    = $dbPswrd;
                $tableprefix = (!empty($zshop ['table_prefix']) ? $zshop ['table_prefix']
                            : '');
                // $sValues = '';
                // $statement = Doctrine_Manager::getInstance()->closeConnection($statement);
                // echo $vars['amount'];

                $prdwhere   = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
                $prdwhere   = "a.products_status=1";
                $prdquery   = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  ".$tableprefix."products a 
                         LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                         LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE ".$prdwhere."
                        
                         ORDER BY RAND()  LIMIT  0,$limit";
                // echo $prdquery; exit;
                $dbh        = new PDO($dsn, $user, $password);
                $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
                $results    = $statement1->execute($prdquery);
                $sValues    = $results->fetchAll();

                // echo $config['domain'];
                // $imagearr = array('imageval'=>'lower');
                $list = array();
                for ($i = 0; $i < count($sValues); $i ++) {

                    // echo number_format($sValues[$i]['products_price'], 2) . '<br>';
                    $priceexplode = explode('.',
                        $sValues [$i] ['products_price']);

                    // echo $priceexplode[1] . '<br>';

                    if (strlen($priceexplode [0]) >= 4) {
                        $p1 = substr_replace($priceexplode [0], ".", 1, 0);

                        $p2 = substr_replace($priceexplode [1], ",", 2);
                        $p2 = substr($p2, 0, - 1);

                        $sValues [$i] ['PRICE'] = $p1.','.$p2;
                    } else {

                        // echo $priceexplode[1] . '<br>';
                        $newstring = substr_replace($priceexplode [1], '', '2');

                        // echo $newstring . '<br>';
                        // echo $priceexplode[0] . ',' . $newstring . '<br>';

                        $sValues [$i] ['PRICE'] = $priceexplode [0].','.$newstring;
                    }

                    // echo $sValues[$i]['PRICE'] . '<br>';
                    // echo $zshop['domain'];
                    $sValues [$i] ['domain'] = $zshop ['domain'];
                    if ($sValues [$i] ['products_image'] != '') {
                        list($width, $height, $type, $attr) = getimagesize('http://'.$zshop ['domain'].'/images/'.str_replace(" ",
                                "%20", $sValues [$i] ['products_image']));
                        $AW = $width;
                        $AH = $height;

                        $H = '';
                        $W = '';

                        if ($AH < 210 && $AW < 170) {
                            
                        }

                        if ($AH > 210 && $AW < 170) {
                            $H = 210;
                            $W = $AW * ((210 * 100) / $AH) / 100;

                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }

                        if ($AH < 210 && $AW > 170) {
                            $W                  = 170;
                            $H                  = $AH * ((170 * 100) / $AW) / 100;
                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }

                        if ($AH > 210 && $AW > 170) {
                            $H = 210;
                            $W = $AW * ((210 * 100) / $AH) / 100;

                            $WTmp = $W;
                            if ($W > 170) {
                                $W = 170;
                                $H = $H * ((170 * 100) / $WTmp) / 100;
                            }

                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }
                    }
                }

                // return $sValues;
                // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
                $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
            }
        } catch (PDOException $e) {
            // echo 'Caught exception: ', $e->getMessage(), "\n";
            // die;
        }
        // echo "<pre>"; print_r($sValues); echo "</pre>";
        // echo "<pre>"; print_r($productvalue); echo "</pre>";

        return $sValues;
    }

    public function getBlockResult($config)
    {
        try {
            $dnName  = (!empty($config ['dbname']) ? $config ['dbname'] : 'nodb');
            $dnUser  = (!empty($config ['username']) ? $config ['username'] : 'root');
            $dbPswrd = (!empty($config ['password']) ? $config ['password'] : '');
            $dbHost  = (!empty($config ['hostname']) ? $config ['hostname'] : 'localhost');

            $dsn = "mysql:dbname='".$dnName."';host='".$dbHost."'";
            // echo $dsn; exit;

            $dsn         = "mysql:dbname=$dnName;host=$dbHost";
            $user        = $dnUser;
            $password    = $dbPswrd;
            $tableprefix = (!empty($config ['table_prefix']) ? $config ['table_prefix']
                        : '');
            // $sValues = '';
            // $statement = Doctrine_Manager::getInstance()->closeConnection($statement);
            // echo $vars['amount'];

            $prdwhere   = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
            $prdwhere   = "a.products_status=1";
            $prdquery   = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  ".$tableprefix."products a 
                         LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                         LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE ".$prdwhere."
                        
                         ORDER BY RAND()  LIMIT  0,2";
            // echo $prdquery; exit;
            $dbh        = new PDO($dsn, $user, $password);
            $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
            $results    = $statement1->execute($prdquery);
            $sValues    = $results->fetchAll();

            // echo $config['domain'];
            // $imagearr = array('imageval'=>'lower');
            $list = array();
            for ($i = 0; $i < count($sValues); $i ++) {
                if ($sValues [$i] ['products_image'] != '') {
                    list($width, $height, $type, $attr) = getimagesize('http://'.$config ['domain'].'/images/'.str_replace(" ",
                            "%20", $sValues [$i] ['products_image']));
                    $AW = $width;
                    $AH = $height;

                    $H = '';
                    $W = '';

                    if ($AH < 210 && $AW < 170) {
                        
                    }

                    if ($AH > 210 && $AW < 170) {
                        $H = 210;
                        $W = $AW * ((210 * 100) / $AH) / 100;

                        $sValues [$i] ['H'] = round($H);
                        $sValues [$i] ['W'] = round($W);
                    }

                    if ($AH < 210 && $AW > 170) {
                        $W                  = 170;
                        $H                  = $AH * ((170 * 100) / $AW) / 100;
                        $sValues [$i] ['H'] = round($H);
                        $sValues [$i] ['W'] = round($W);
                    }

                    if ($AH > 210 && $AW > 170) {
                        $H = 210;
                        $W = $AW * ((210 * 100) / $AH) / 100;

                        $WTmp = $W;
                        if ($W > 170) {
                            $W = 170;
                            $H = $H * ((170 * 100) / $WTmp) / 100;
                        }

                        $sValues [$i] ['H'] = round($H);
                        $sValues [$i] ['W'] = round($W);
                    }
                }
            }

            // return $sValues;
            // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
            $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
        } catch (PDOException $e) {
            // echo 'Caught exception: ', $e->getMessage(), "\n";
            // die;
        }
        // echo "<pre>"; print_r($sValues); echo "</pre>";
        // echo "<pre>"; print_r($productvalue); echo "</pre>";

        return $sValues;
    }

    public function getAdRandomProducts($vars)
    {
        $sqlextra = '';

        if ($vars ['adtype'] == 'Low') {
            $sqlextra = " AND (shop_id IN (SELECT childId FROM zselex_parent a, zselex_advertise b,zselex_advertise_price c
                WHERE a.childType='SHOP' AND a.parentType='AD' AND a.parentId=b.advertise_id AND b.adprice_id=c.adprice_id 
                AND c.adprice_id=2)
                
                OR

                shop_id IN (SELECT parentId FROM zselex_parent a, zselex_advertise b,zselex_advertise_price c
                WHERE a.childType='AD' AND a.parentType='SHOP' AND a.childId=b.advertise_id AND b.adprice_id=c.adprice_id 
                AND c.adprice_id=2))
                

                ";
        }

        // echo "<pre>"; print_r($vars); echo "</pre>";

        $query     = "SELECT * FROM zselex_shop
                      WHERE shoptype_id = 1  $sqlextra";
        // echo $query; exit;
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $configs   = $results->fetchAll();
        $counts    = $results->rowCount();

        // echo "<pre>"; print_r($values); echo "</pre>";
        // echo $config['dbname'];
        $limit = 2;

        $allValues = array();
        if ($counts > 0) {
            try {
                foreach ($configs as $config) {
                    $dnName  = (!empty($config ['dbname']) ? $config ['dbname'] : 'nodb');
                    $dnUser  = (!empty($config ['username']) ? $config ['username']
                                : 'root');
                    $dbPswrd = (!empty($config ['password']) ? $config ['password']
                                : '');
                    $dbHost  = (!empty($config ['hostname']) ? $config ['hostname']
                                : 'localhost');

                    $dsn         = "mysql:dbname=$dnName;host=$dbHost";
                    $user        = $dnUser;
                    $password    = $dbPswrd;
                    $tableprefix = (!empty($config ['table_prefix']) ? $config ['table_prefix']
                                : '');

                    $prdwhere = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
                    $prdwhere = "a.products_status=1";
                    $prdquery = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  ".$tableprefix."products a 
                         LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                         LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE ".$prdwhere."
                         ORDER BY RAND()  LIMIT  0,$limit";

                    $dbh        = new PDO($dsn, $user, $password);
                    $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
                    $results    = $statement1->execute($prdquery);
                    $sValues    = $results->fetchAll();
                    // echo "<pre>"; print_r($sValues); echo "</pre>";
                    // $imagearr = array('imageval'=>'lower');
                    $list       = array();
                    for ($i = 0; $i < count($sValues); $i ++) {
                        $sValues [$i] ['domainname'] = $config ['domain'];

                        $priceexplode = explode('.',
                            $sValues [$i] ['products_price']);

                        if (strlen($priceexplode [0]) >= 4) {
                            $p1 = substr_replace($priceexplode [0], ".", 1, 0);

                            $p2 = substr_replace($priceexplode [1], ",", 2);
                            $p2 = substr($p2, 0, - 1);

                            $sValues [$i] ['PRICE'] = $p1.','.$p2;
                        } else {
                            $newstring              = substr_replace($priceexplode [1],
                                '', '2');
                            $sValues [$i] ['PRICE'] = $priceexplode [0].','.$newstring;
                        }

                        // echo $sValues[$i]['PRICE'] . '<br>';

                        if ($sValues [$i] ['products_image'] != '') {
                            list($width, $height, $type, $attr) = getimagesize('http://'.$config ['domain'].'/images/'.str_replace(" ",
                                    "%20", $sValues [$i] ['products_image']));
                            $AW = $width;
                            $AH = $height;

                            $H = '';
                            $W = '';

                            if ($AH < 210 && $AW < 170) {
                                
                            }

                            if ($AH > 210 && $AW < 170) {
                                $H = 210;
                                $W = $AW * ((210 * 100) / $AH) / 100;

                                $sValues [$i] ['H'] = round($H);
                                $sValues [$i] ['W'] = round($W);
                            }

                            if ($AH < 210 && $AW > 170) {
                                $W                  = 170;
                                $H                  = $AH * ((170 * 100) / $AW) / 100;
                                $sValues [$i] ['H'] = round($H);
                                $sValues [$i] ['W'] = round($W);
                            }

                            if ($AH > 210 && $AW > 170) {
                                $H = 210;
                                $W = $AW * ((210 * 100) / $AH) / 100;

                                $WTmp = $W;
                                if ($W > 170) {
                                    $W = 170;
                                    $H = $H * ((170 * 100) / $WTmp) / 100;
                                }

                                $sValues [$i] ['H'] = round($H);
                                $sValues [$i] ['W'] = round($W);
                            }
                        }
                    }

                    $allValues [] = $sValues;
                    // return $sValues;
                    // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
                    $statement1   = Doctrine_Manager::getInstance()->closeConnection($statement1);
                }
            } catch (PDOException $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
            }

            // return $allValues;
        }
    }

    public function getShopImage($args)
    {
        $sql    = "SELECT * FROM  zselex_files a
            LEFT JOIN zselex_shop b ON a.shop_id=b.shop_id
            WHERE a.shop_id='".$args [shop_id]."'";
        $res    = DBUtil::executeSQL($sql, $args ['start'] - 1,
                $args ['itemsperpage']);
        $result = DBUtil::marshallObjects($res);

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getShopGalleryImage($args)
    { // gallery images listing
        $sql    = "SELECT * FROM  zselex_shop_gallery a
            LEFT JOIN zselex_shop b ON a.shop_id=b.shop_id
            WHERE a.shop_id='".$args [shop_id]."'";
        $res    = DBUtil::executeSQL($sql, $args ['start'] - 1,
                $args ['itemsperpage']);
        $result = DBUtil::marshallObjects($res);

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getZselexThemes($args)
    { // gallery images listing
        $extra = $args ['sql'];

        // $sql = "SELECT * , t.id as id FROM themes t
        // LEFT JOIN zselex_shop_owners_theme ot ON ot.theme_id=t.id
        // WHERE t.id IS NOT NULL" . $extra;

        /*
         * $sql = "SELECT *,t.theme_name FROM zselex_themes t
         * LEFT JOIN zselex_shop_owners_theme ot ON ot.theme_id=t.theme_id
         * LEFT JOIN themes ON themes.id=t.theme_id
         * WHERE t.zt_id IS NOT NULL " . $extra;
         *
         * //echo $sql;
         *
         * $res = DBUtil::executeSQL($sql, $args['start'] - 1, $args['itemsperpage']);
         * $result = DBUtil::marshallObjects($res);
         */

        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_ZselexTheme');
        $result = $repo->getZselexThemes($args);

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getZselexThemesCount($args)
    {
        $extra = $args ['sql'];
        // $sql = "SELECT * FROM themes t
        // WHERE t.id IS NOT NULL" . $extra;

        $sql = "SELECT * FROM zselex_themes t  
                LEFT JOIN  zselex_shop_owners_theme ot ON ot.theme_id=t.theme_id 
                WHERE t.zt_id IS NOT NULL ".$extra;

        $query     = $sql;
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $count     = $results->rowCount();

        return $count;
    }

    public function getThemesToConfigureToZselex($args)
    {
        $extra  = $args ['sql'];
        $sql    = "SELECT * , t.id as id FROM themes t
                LEFT JOIN zselex_shop_owners_theme ot ON ot.theme_id=t.id 
                WHERE t.id NOT IN(SELECT theme_id FROM zselex_themes)".$extra;
        $res    = DBUtil::executeSQL($sql, $args ['start'] - 1,
                $args ['itemsperpage']);
        $result = DBUtil::marshallObjects($res);

        // $repo = $this->entityManager->getRepository('ZSELEX_Entity_ZselexTheme');
        // $result2 = $repo->getThemesToConfigureToZselex(array());
        // echo "<pre>"; print_r($result); echo "</pre>";
        // echo "<pre>"; print_r($result2); echo "</pre>";
        return $result;
    }

    public function getThemesToConfigureToZselexCount($args)
    {
        $extra  = $args ['sql'];
        $sql    = "SELECT * FROM themes t
                WHERE t.id NOT IN(SELECT theme_id FROM zselex_themes)".$extra;
        $res    = DBUtil::executeSQL($sql);
        $result = $res->rowCount();

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getArticleAds($args)
    { // articles ad listings
        $extra   = $args ['sql'];
        $shop_id = $args ['shop_id'];

        $sql = "SELECT * , ad.status as status FROM zselex_article_ads ad
                LEFT JOIN  zselex_country country ON country.country_id=ad.country_id
                LEFT JOIN  zselex_region region ON region.region_id=ad.region_id
                LEFT JOIN  zselex_city city ON city.city_id=ad.city_id
                LEFT JOIN  zselex_shop shop ON shop.shop_id=ad.shop_id
                WHERE ad.articlead_id IS NOT NULL AND ad.shop_id=$shop_id".$extra;

        // echo $sql;

        $res    = DBUtil::executeSQL($sql, $args ['start'] - 1,
                $args ['itemsperpage']);
        $result = DBUtil::marshallObjects($res);

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getArticleAdsCount($args)
    { // articles ad listings
        $extra   = $args ['sql'];
        $shop_id = $args ['shop_id'];
        $sql     = "SELECT * FROM zselex_article_ads ad
                LEFT JOIN  zselex_country country ON country.country_id=ad.country_id
                LEFT JOIN  zselex_region region ON region.region_id=ad.region_id
                LEFT JOIN  zselex_city city ON city.city_id=ad.city_id
                LEFT JOIN  zselex_shop shop ON shop.shop_id=ad.shop_id
                WHERE ad.articlead_id IS NOT NULL AND ad.shop_id=$shop_id".$extra;

        $query     = $sql;
        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($query);
        $count     = $results->rowCount();

        return $count;
    }

    public function getArticleAdsEdit($args)
    { // articles ad listings
        $adId = $args ['adId'];

        $sql = "SELECT * , ad.status as adstatus  FROM zselex_article_ads ad
                LEFT JOIN  zselex_country country ON country.country_id=ad.country_id
                LEFT JOIN  zselex_region region ON region.region_id=ad.region_id
                LEFT JOIN  zselex_city city ON city.city_id=ad.city_id
                LEFT JOIN  zselex_shop shop ON shop.shop_id=ad.shop_id
                WHERE ad.articlead_id IS NOT NULL AND ad.articlead_id='".$adId."'";

        // echo $sql;

        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getShopThemes($args)
    { // get zselex themes for shops
        $extra = $args ['sql'];

        $sql = "SELECT * FROM zselex_themes".$extra;

        // echo $sql;

        $res    = DBUtil::executeSQL($sql, $args ['start'] - 1,
                $args ['itemsperpage']);
        $result = DBUtil::marshallObjects($res);

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getShopThemesCount($args)
    { // get zselex themes count
        $extra = $args ['sql'];

        $sql = "SELECT * FROM zselex_themes".$extra;

        $res   = DBUtil::executeSQL($sql);
        $count = $res->rowCount();
        // echo "<pre>"; print_r($result); echo "</pre>";
        return $count;
    }

    public function getOwnerThemes($args)
    {
        $user_id = $args ['user_id'];
        $shop_id = $args ['shop_id'];
        // $user_id = 4;

        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_ShopOwner');
        /*
         * if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
         * //$ownerThemes = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $args = array('table' => 'zselex_shop_owners_theme', 'where' => array("user_id=$loguser")));
         *
         * $sql = "SELECT t.*
         * FROM themes t , zselex_shop_owners_theme ot
         * WHERE ot.user_id='" . $user_id . "' AND ot.theme_id=t.id";
         * }
         *
         * if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
         * $sql = "SELECT t.*
         * FROM themes t , zselex_shop_owners_theme ot
         * WHERE ot.theme_id=t.id AND ot.user_id IN(SELECT user_id FROM zselex_shop_admins WHERE shop_id=$shop_id AND owner_id=$user_id)";
         * } elseif (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
         *
         * $sql = "SELECT t.*
         * FROM themes t , zselex_shop_owners_theme ot
         * WHERE ot.theme_id=t.id AND ot.user_id IN(SELECT user_id FROM zselex_shop_owners WHERE shop_id=$shop_id)";
         * }
         *
         * $res = DBUtil::executeSQL($sql);
         * $result = DBUtil::marshallObjects($res);
         */
        $result = $repo->getOwnerThemes($args);
        // echo "<pre>"; print_r($result); echo "</pre>";

        return $result;
    }

    public function getShopThemes1($args)
    { // get zselex themes for shops
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        /*
         * $extra = $args['sql'];
         * $user_id = $args['user_id'];
         *
         * // $sql = "SELECT t.* FROM zselex_themes zt , themes t
         * // WHERE t.id=zt.theme_id";
         *
         *
         * $sql = "SELECT t.* FROM zselex_themes zt , themes t
         * WHERE t.id=zt.theme_id";
         *
         * // echo $sql;
         * //$res = DBUtil::executeSQL($sql, $args['start'] - 1, $args['itemsperpage']);
         * $res = DBUtil::executeSQL($sql);
         * $result = DBUtil::marshallObjects($res);
         */

        $result = $repo->getShopThemes();
        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function setasdefaultshoptheme($args)
    {

        // echo "comes here"; exit;
        $shop_id   = $args ['shop_id'];
        $themename = $args ['themename'];
        /*
         * $sql = "UPDATE zselex_shop set theme='" . $themename . "' WHERE shop_id='" . $shop_id . "'";
         * DBUtil::executeSQL($sql);
         */

        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $update = $repo->updateEntity(null, 'ZSELEX_Entity_Shop',
            array(
            'theme' => $themename
            ), array(
            'a.shop_id' => $shop_id
        ));
        return $update;
    }

    public function getShopPdfImages($args)
    { // gallery images listing
        $sql    = "SELECT * FROM  zselex_shop_pdf a
            LEFT JOIN zselex_shop b ON a.shop_id=b.shop_id
            WHERE a.shop_id='".$args [shop_id]."'";
        $res    = DBUtil::executeSQL($sql, $args ['start'] - 1,
                $args ['itemsperpage']);
        $result = DBUtil::marshallObjects($res);

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getCatsCat($args)
    { // gallery images listing
        static $option_results;
        // if there is no current category id set, start off at the top level (zero)
        if (!isset($current_cat_id)) {
            $current_cat_id = 0;
        }
        // increment the counter by 1
        $count = $count + 1;

        // query the database for the sub-categories of whatever the parent category is
        $sql = "SELECT id , name , parent_id from  categories_category 
	         where parent_id=  '".$current_cat_id."' order by id desc";

        $statement = Doctrine_Manager::getInstance()->connection();
        $results   = $statement->execute($sql);

        $get_options = $statement->execute($sql);
        $num_options = $get_options->rowCount();

        // echo $num_options; exit;
        // our category is apparently valid, so go ahead â‚¬Â¦
        if ($num_options > 0) {
            while (list($cat_id, $cat_name, $parentId) = $get_options->fetch()) {
                // if its not a top-level category, indent it to
                // show that its a child category
                $indent_flag = '';

                if ($current_cat_id != 0) {
                    $indent_flag = '&nbsp';
                    for ($x = 2; $x <= $count; $x ++) {
                        $indent_flag .= '&nbsp&nbsp&nbsp';
                    }
                }
                $cat_name                 = $indent_flag.$cat_name;
                $option_results [$cat_id] = $cat_name;
                // now call the function again, to recurse through the child categories
                $this->get_cat_selectlist($cat_id, $count);
            }
        }
        return $option_results;
    }

    public function getGalleryBlockImages($args)
    { // get gallery images for gallery block
        $sql    = "SELECT * FROM  zselex_shop_gallery a
                LEFT JOIN zselex_shop b ON a.shop_id=b.shop_id
                WHERE a.shop_id='".$args [shop_id]."'";
        $res    = DBUtil::executeSQL($sql);
        $result = DBUtil::marshallObjects($res);

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getShopType($args)
    { // get shop type name
        $sql      = "SELECT a.shoptype FROM  zselex_shop_types a , zselex_shop b
                WHERE b.shop_id='".$args [shop_id]."' AND a.shoptype_id=b.shoptype_id";
        $query    = DBUtil::executeSQL($sql);
        $result   = $query->fetch();
        $shopType = $result ['shoptype'];
        // echo "<pre>"; print_r($result); echo "</pre>";
        return $shopType;
    }

    public function shopType($args)
    { // get shop type details
        /*
         * $sql = "SELECT shoptype_id,shoptype FROM zselex_minishop
         * WHERE shop_id='" . $args[shop_id] . "'";
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetch();
         */
        $result = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getShopType($args);
        // echo "<pre>"; print_r($result); echo "</pre>";

        return $result;
    }

    public function getShopType1($args)
    { // get shop type name
        $sql   = "SELECT shoptype FROM zselex_minishop
                WHERE shop_id='".$args [shop_id]."'";
        $query = DBUtil::executeSQL($sql);

        $result   = $query->fetch();
        $shopType = $result ['shoptype'];
        // echo "<pre>"; print_r($result); echo "</pre>";
        return $shopType;
    }

    public function getIshopProductsAutocomplete($args)
    { // get gallery images for gallery block
        $sql   = "SELECT product_id , product_name FROM  zselex_products
                WHERE shop_id='".$args [shop_id]."'";
        $query = DBUtil::executeSQL($sql);

        $result = $query->fetchAll();

        return $result;
    }

    public function chooseIshopProducts($args)
    {
        $sqls = $args ['sql'];
        $sql  = "SELECT * FROM  zselex_products
                WHERE shop_id='".$args [shop_id]."'
                    $sqls";

        $res    = DBUtil::executeSQL($sql, $args ['start'] - 1,
                $args ['itemsperpage']);
        $result = DBUtil::marshallObjects($res);

        // echo "<pre>"; print_r($result); echo "</pre>";
        return $result;
    }

    public function getZenCartProduct($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $shop_id    = $args ['shop_id'];
        $product_id = $args ['product_id'];

        try {
            $limit = '';
            if ($args ['limit'] != '') {
                $limit = $args ['limit'];
            } else {
                // $limit = '2';
            }

            $orderBy = '';
            if ($args ['orderby'] != '') {
                $orderBy = " ORDER BY ".$args ['orderby'];
            }

            $dnName  = (!empty($args ['shop'] ['dbname']) ? $args ['shop'] ['dbname']
                        : 'nodb');
            $dnUser  = (!empty($args ['shop'] ['username']) ? $args ['shop'] ['username']
                        : 'root');
            $dbPswrd = (!empty($args ['shop'] ['password']) ? $args ['shop'] ['password']
                        : '');
            $dbHost  = (!empty($args ['shop'] ['host']) ? $args ['shop'] ['host']
                        : 'localhost');

            $dsn = "mysql:dbname='".$dnName."';host='".$dbHost."'";
            // echo $dsn; exit;

            $dsn         = "mysql:dbname=$dnName;host=$dbHost";
            $user        = $dnUser;
            $password    = $dbPswrd;
            $tableprefix = (!empty($args ['shop'] ['table_prefix']) ? $args ['shop'] ['table_prefix']
                        : '');

            $prdwhere = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
            $prdwhere = "a.products_status=1";
            $prdquery = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  ".$tableprefix."products a 
                         LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                         LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE ".$prdwhere." AND a.products_id='".$product_id."'";

            // echo $prdquery; exit;
            $dbh        = new PDO($dsn, $user, $password);
            $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
            $results    = $statement1->execute($prdquery);
            $sValues    = $results->fetch();

            // echo $config['domain'];
            // $imagearr = array('imageval'=>'lower');
            $list               = array();
            $sValues ['domain'] = $args ['shop'] ['domain'];
            $priceexplode       = explode('.', $sValues ['products_price']);

            if (strlen($priceexplode [0]) >= 4) { // converting price to DK
                $p1 = substr_replace($priceexplode [0], ".", 1, 0);
                $p2 = substr_replace($priceexplode [1], ",", 2);
                $p2 = substr($p2, 0, - 1);

                $sValues ['PRICE'] = $p1.','.$p2;
            } else {

                // echo $priceexplode[1] . '<br>';
                $newstring         = substr_replace($priceexplode [1], '', '2');
                // echo $newstring . '<br>';
                // echo $priceexplode[0] . ',' . $newstring . '<br>';
                $sValues ['PRICE'] = $priceexplode [0].','.$newstring;
            }

            // echo $sValues[$i]['PRICE'] . '<br>';

            if ($sValues ['products_image'] != '') { // resize image
                list($width, $height, $type, $attr) = getimagesize('http://'.$args ['shop'] ['domain'].'/images/'.str_replace(" ",
                        "%20", $sValues ['products_image']));
                $AW = $width;
                $AH = $height;

                $H = '';
                $W = '';

                if ($AH < 210 && $AW < 170) {
                    
                }

                if ($AH > 210 && $AW < 170) {
                    $H = 210;
                    $W = $AW * ((210 * 100) / $AH) / 100;

                    $sValues ['H'] = round($H);
                    $sValues ['W'] = round($W);
                }

                if ($AH < 210 && $AW > 170) {
                    $W             = 170;
                    $H             = $AH * ((170 * 100) / $AW) / 100;
                    $sValues ['H'] = round($H);
                    $sValues ['W'] = round($W);
                }

                if ($AH > 210 && $AW > 170) {
                    $H = 210;
                    $W = $AW * ((210 * 100) / $AH) / 100;

                    $WTmp = $W;
                    if ($W > 170) {
                        $W = 170;
                        $H = $H * ((170 * 100) / $WTmp) / 100;
                    }

                    $sValues ['H'] = round($H);
                    $sValues ['W'] = round($W);
                }
            }

            // return $sValues;
            // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
            $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
        } catch (PDOException $e) {
            // echo 'Caught exception: ', $e->getMessage(), "\n";
            // die;
            $sValues = array();
        }

        return $sValues;
    }

    public function getZenCartProducts($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $sValues = array();
        try {
            $limit = '';
            if ($args ['limit'] != '') {
                $limit = $args ['limit'];
            } else {
                // $limit = '2';
            }

            $orderBy = '';
            if ($args ['orderby'] != '') {
                $orderBy = " ORDER BY ".$args ['orderby'];
            }

            $sql = !empty($args ['sql']) ? $args ['sql'] : '';

            $dnName  = (!empty($args ['shop'] ['dbname']) ? $args ['shop'] ['dbname']
                        : 'nodb');
            $dnUser  = (!empty($args ['shop'] ['username']) ? $args ['shop'] ['username']
                        : 'root');
            $dbPswrd = (!empty($args ['shop'] ['password']) ? $args ['shop'] ['password']
                        : '');
            $dbHost  = (!empty($args ['shop'] ['host']) ? $args ['shop'] ['host']
                        : 'localhost');

            $dsn = "mysql:dbname='".$dnName."';host='".$dbHost."'";
            // echo $dsn; exit;

            $dsn         = "mysql:dbname=$dnName;host=$dbHost";
            $user        = $dnUser;
            $password    = $dbPswrd;
            $tableprefix = (!empty($args ['shop'] ['table_prefix']) ? $args ['shop'] ['table_prefix']
                        : '');

            $prdwhere   = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
            $prdwhere   = "a.products_status=1";
            $prdquery   = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  ".$tableprefix."products a 
                         LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                         LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE ".$prdwhere." ".$sql." group by b.products_id ".$orderBy." ".$limit;
            // echo $prdquery;
            $dbh        = new PDO($dsn, $user, $password);
            $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
            $results    = $statement1->execute($prdquery);
            $sValues    = $results->fetchAll();

            // echo $config['domain'];
            // $imagearr = array('imageval'=>'lower');
            $list = array();

            // return $sValues;
            // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
            $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
        } catch (PDOException $e) {
            // echo "comes here....";
            // echo 'Caught exception: ', $e->getMessage(), "\n";
            // die;
            //echo $this->__("Please try later");
            return array();
        }

        return $sValues;
    }

    /**
     * Make the images proportional
     * Balances the hight and width of the image with clarity
     * Outputs the balanced height and width
     */
    public function imageProportional($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>";
        $setheight = $args ['setheight'];
        $setwidth  = $args ['setwidth'];
        $imagepath = $args ['imagepath'];
        // echo $imagepath . '<br>';

        list($width, $height, $type, $attr) = @getimagesize($imagepath);

        $AW = $width; // Actual Width
        $AH = $height; // Actual Height
        // echo "Actual Width :" . $AW . " " . "Actual Height :" . $AH . '<br>';exit;

        $H = '';
        $W = '';

        if ($AH < $setheight && $AW < $setwidth) {
            $new_height = round($AH);
            $new_width  = round($AW);
        } elseif ($AH > $setheight && $AW < $setwidth) {
            $H = $setheight;
            $W = $AW * (($setheight * 100) / $AH) / 100;

            $new_height = round($H);
            $new_width  = round($W);
        } elseif ($AH < $setheight && $AW > $setwidth) {
            $W          = $setwidth;
            $H          = $AH * (($setwidth * 100) / $AW) / 100;
            $new_height = round($H);
            $new_width  = round($W);
        } elseif ($AH > $setheight && $AW > $setwidth) {
            $H    = $setheight;
            $W    = $AW * (($setheight * 100) / $AH) / 100;
            $WTmp = $W;
            if ($W > $setwidth) {
                $W = $setwidth;
                $H = $H * (($setwidth * 100) / $WTmp) / 100;
            }
            $new_height = round($H);
            $new_width  = round($W);
        } elseif ($AH >= $setheight && $AW >= $setwidth) {
            $H    = $setheight;
            $W    = $AW * (($setheight * 100) / $AH) / 100;
            $WTmp = $W;
            if ($W > $setwidth) {
                $W = $setwidth;
                $H = $H * (($setwidth * 100) / $WTmp) / 100;
            }
            $new_height = round($H);
            $new_width  = round($W);
        }

        $output ['new_height'] = $new_height;
        $output ['new_width']  = $new_width;
        // echo "<pre>"; print_r($output); echo "</pre>";
        return $output;
    }

    public function imageProportional1($args)
    {
        $setheight = $args ['setheight'];
        $setwidth  = $args ['setwidth'];
        $imagepath = $args ['imagepath'];
        // echo $imagepath . '<br>';

        list($width, $height, $type, $attr) = @getimagesize($imagepath);

        $AW = $width; // Actual Width
        $AH = $height; // Actual Height
        // echo "Actual Width :" . $AW . " " ."Actual Height :" . $AH . '<br>' ; exit;

        $H = '';
        $W = '';

        if ($AH < $setheight && $AW < $setwidth) {
            $new_height = round($AH);
            $new_width  = round($AW);
        }

        if ($AH > $setheight && $AW < $setwidth) {
            $H = $setheight;
            $W = $AW * (($setheight * 100) / $AH) / 100;

            $new_height = round($H);
            $new_width  = round($W);
        }

        if ($AH < $setheight && $AW > $setwidth) {
            $W          = $setwidth;
            $H          = $AH * (($setwidth * 100) / $AW) / 100;
            $new_height = round($H);
            $new_width  = round($W);
        }

        if ($AH > $setheight && $AW > $setwidth) {
            $H = $setheight;
            $W = $AW * (($setheight * 100) / $AH) / 100;

            $WTmp = $W;
            if ($W > $setwidth) {
                $W = $setwidth;
                $H = $H * (($setwidth * 100) / $WTmp) / 100;
            }

            $new_height = round($H);
            $new_width  = round($W);
        }

        $output ['new_height'] = $new_height;
        $output ['new_width']  = $new_width;
        return $output;
    }

    public function getZenCartProductsCount($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $sValues = 0;
        try {
            $limit = '';
            if ($args ['limit'] != '') {
                $limit = $args ['limit'];
            } else {
                // $limit = '2';
            }

            $orderBy = '';
            if ($args ['orderby'] != '') {
                $orderBy = " ORDER BY ".$args ['orderby'];
            }

            $sql = !empty($args ['sql']) ? $args ['sql'] : '';

            $dnName  = (!empty($args ['shop'] ['dbname']) ? $args ['shop'] ['dbname']
                        : 'nodb');
            $dnUser  = (!empty($args ['shop'] ['username']) ? $args ['shop'] ['username']
                        : 'root');
            $dbPswrd = (!empty($args ['shop'] ['password']) ? $args ['shop'] ['password']
                        : '');
            $dbHost  = (!empty($args ['shop'] ['host']) ? $args ['shop'] ['host']
                        : 'localhost');

            $dsn = "mysql:dbname='".$dnName."';host='".$dbHost."'";
            // echo $dsn; exit;

            $dsn         = "mysql:dbname=$dnName;host=$dbHost";
            $user        = $dnUser;
            $password    = $dbPswrd;
            $tableprefix = (!empty($args ['shop'] ['table_prefix']) ? $args ['shop'] ['table_prefix']
                        : '');

            $prdwhere   = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
            $prdwhere   = "a.products_status=1";
            $prdquery   = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  ".$tableprefix."products a 
                         LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                         LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE ".$prdwhere." ".$sql."   group by a.products_id "." ".$orderBy;
            // echo "<br>" . $prdquery;
            $dbh        = new PDO($dsn, $user, $password);
            $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
            $results    = $statement1->execute($prdquery);
            $sValues    = $results->rowCount();

            // return $sValues;
            // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
            $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
        } catch (PDOException $e) {
            // echo 'Caught exception: ', $e->getMessage(), "\n";
            echo "<br>".$this->__("Cannot retreive products");
            // die;
        }

        return $sValues;
    }

    public function getZenCartDOTDProducts($args)
    {

        // echo "<pre>"; print_r($args); echo "</pre>"; exit;
        $shop_id = $args ['shop_id'];
        $column  = $args ['column_name'];
        $value   = $args ['columnValue'];
        try {
            $limit = '';
            if ($args ['limit'] != '') {
                $limit = $args ['limit'];
            } else {
                // $limit = '2';
            }

            $orderBy = '';
            if ($args ['orderby'] != '') {
                $orderBy = " ORDER BY ".$args ['orderby'];
            }

            $dnName  = (!empty($args ['shop'] ['dbname']) ? $args ['shop'] ['dbname']
                        : 'nodb');
            $dnUser  = (!empty($args ['shop'] ['username']) ? $args ['shop'] ['username']
                        : 'root');
            $dbPswrd = (!empty($args ['shop'] ['password']) ? $args ['shop'] ['password']
                        : '');
            $dbHost  = (!empty($args ['shop'] ['host']) ? $args ['shop'] ['host']
                        : 'localhost');

            $dsn = "mysql:dbname='".$dnName."';host='".$dbHost."'";
            // echo $dsn; exit;

            $dsn         = "mysql:dbname=$dnName;host=$dbHost";
            $user        = $dnUser;
            $password    = $dbPswrd;
            $tableprefix = (!empty($args ['shop'] ['table_prefix']) ? $args ['shop'] ['table_prefix']
                        : '');

            $prdwhere = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
            $prdwhere = "a.products_status=1";
            $prdquery = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  ".$tableprefix."products a 
                         LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                         LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE ".$prdwhere." AND a.products_id='".$args [product_id]."'";

            // echo $prdquery; exit;
            $dbh        = new PDO($dsn, $user, $password);
            $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
            $results    = $statement1->execute($prdquery);
            $sValues    = $results->fetch();

            // echo $config['domain'];
            // $imagearr = array('imageval'=>'lower');
            $list = array();

            $sValues ['domain'] = $args ['shop'] ['domain'];

            $priceexplode = explode('.', $sValues ['products_price']);

            if (strlen($priceexplode [0]) >= 4) { // converting price to DK
                $p1 = substr_replace($priceexplode [0], ".", 1, 0);
                $p2 = substr_replace($priceexplode [1], ",", 2);
                $p2 = substr($p2, 0, - 1);

                $sValues ['PRICE'] = $p1.','.$p2;
            } else {

                // echo $priceexplode[1] . '<br>';
                $newstring = substr_replace($priceexplode [1], '', '2');

                // echo $newstring . '<br>';
                // echo $priceexplode[0] . ',' . $newstring . '<br>';

                $sValues ['PRICE'] = $priceexplode [0].','.$newstring;
            }

            // echo $sValues[$i]['PRICE'] . '<br>';

            if ($sValues ['products_image'] != '') { // resize image
                list($width, $height, $type, $attr) = getimagesize('http://'.$args ['shop'] ['domain'].'/images/'.str_replace(" ",
                        "%20", $sValues ['products_image']));
                $AW = $width;
                $AH = $height;

                $H = '';
                $W = '';

                if ($AH < 210 && $AW < 170) {
                    
                }

                if ($AH > 210 && $AW < 170) {
                    $H = 210;
                    $W = $AW * ((210 * 100) / $AH) / 100;

                    $sValues ['H'] = round($H);
                    $sValues ['W'] = round($W);
                }

                if ($AH < 210 && $AW > 170) {
                    $W             = 170;
                    $H             = $AH * ((170 * 100) / $AW) / 100;
                    $sValues ['H'] = round($H);
                    $sValues ['W'] = round($W);
                }

                if ($AH > 210 && $AW > 170) {
                    $H = 210;
                    $W = $AW * ((210 * 100) / $AH) / 100;

                    $WTmp = $W;
                    if ($W > 170) {
                        $W = 170;
                        $H = $H * ((170 * 100) / $WTmp) / 100;
                    }

                    $sValues ['H'] = round($H);
                    $sValues ['W'] = round($W);
                }
            }

            // return $sValues;
            // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
            $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
        } catch (PDOException $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            // die;
        }

        return $sValues;
    }

    public function getCount($args)
    {
        $where = '';
        if (!empty($args ['where']) || $args ['where'] != '') {
            $where = "WHERE".' '.$args ['where'];
        }
        $sql    = "SELECT COUNT(*) as count FROM $args[table] $where";
        // echo $sql . '<br>';
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $count  = $result ['count'];

        return $count;
    }
    /*
     * public function shopPermission($args) {
     *
     * $shop_id = $args['shop_id'];
     * $user_id = $args['user_id'];
     * if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
     * if (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD)) {
     * $sql = "SELECT COUNT(*) as count FROM zselex_shop s , zselex_shop_owners ow
     * WHERE s.shop_id=ow.shop_id AND s.shop_id=$shop_id AND ow.shop_id=$shop_id AND ow.user_id=$user_id";
     * } elseif (SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_EDIT)) {
     * $sql = "SELECT COUNT(*) as count FROM zselex_shop s , zselex_shop_admins ow
     * WHERE s.shop_id=ow.shop_id AND s.shop_id=$shop_id AND ow.shop_id=$shop_id AND ow.user_id=$user_id";
     * }
     *
     * //echo $sql . '<br>';
     * $query = DBUtil::executeSQL($sql);
     * $result = $query->fetch();
     * $count = $result['count'];
     *
     * return $count;
     * } else {
     * return 2;
     * }
     * }
     *
     */

    public function shopPermissionShopOwner($args)
    {

        // echo "shopPermissionShopOwner"; exit;

        $count = $this->entityManager->getRepository('ZSELEX_Entity_ShopOwner')->getPermission($args);

        return $count;
    }

    public function shopPermissionShopAdmin($args)
    {

        $count = $this->entityManager->getRepository('ZSELEX_Entity_ShopAdmin')->getPermission($args);

        return $count;
    }

    public function shopPermissionShopOwnerAdmin($args)
    {

        // echo "shopPermissionShopOwner"; exit;

        $count1 = $this->shopPermissionShopOwner($args);
        $count2 = $this->shopPermissionShopAdmin($args);
        $count  = $count1 + $count2;

        return $count;
    }

    public function shopPermissionUnregistered($args)
    {
        return 0;
    }

    public function shopPermission($args)
    {

        // echo "shopPermission"; exit;
        $shop_id = $args ['shop_id'];
        $user_id = $args ['user_id'];
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADD)) {
                //return $this->shopPermissionShopOwner($args);
                return $this->shopPermissionShopOwnerAdmin($args);
            } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_EDIT)) {
                //return $this->shopPermissionShopAdmin($args);
                return $this->shopPermissionShopOwnerAdmin($args);
            } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADD) && !SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_EDIT)) {
                return $this->shopPermissionUnregistered($args);
            }
        } else {
            return 2;
        }
    }

    public function shopPermission1($args)
    {
        $shop_id = $args ['shop_id'];
        $user_id = $args ['user_id'];
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) {
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADD)) {
                $this->shopPermissionShopOwner($args);
            } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_EDIT)) {
                $this->shopPermissionShopAdmin($args);
            } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADD) && !SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_EDIT)) {
                $this->shopPermissionUnregistered($args);
            }
        } else {
            return 2;
        }
    }

    public function selectWhere($args)
    {
        $table    = $args ['table'];
        $whereArr = $args ['where'];
        $pntable  = pnDBGetTables();
        $column   = $pntable [$table.'_column'];

        $where = "WHERE ";

        foreach ($whereArr as $key => $val) {
            $where .= $column [$key]."=".pnVarPrepForStore($val)." AND ";
        }

        $where    = substr($where, 0, - 4);
        // $fields = array('shop_id', 'id');
        // $where = "WHERE $customercolumn[country] = '" . pnVarPrepForStore($country) . "'";
        // $orderBy = "lastname";
        $objArray = DBUtil::selectObjectArray($table, $where, $orderBy);

        return $objArray;
    }

    public function serviceDisabled($type)
    {
        $servicetype = $type;
        $sql         = "SELECT status FROM zselex_plugin WHERE type='".$servicetype."'";
        $query       = DBUtil::executeSQL($sql);
        $result      = $query->fetch();
        $status      = $result ['status'];
        return $status;
    }

    public function serviceCheckShopSuperAdmin($args)
    {
        $shop_id       = $args ['shop_id'];
        $servicetype   = $args ['type'];
        $user_id       = $args ['user_id'];
        $quantitybased = $args ['quantitybased'];
        $quantitycheck = " AND quantity > availed";
        if ($quantitybased == 'no') {
            $quantitycheck = '';
        }
        // $sql = "SELECT count(*) as count FROM zselex_serviceshop
        // WHERE type='" . $servicetype . "' AND shop_id='" . $shop_id . "'" . " " . $quantitycheck;

        $sql = "SELECT count(*) as count FROM  zselex_serviceshop 
                WHERE  type='".$servicetype."' AND shop_id='".$shop_id."'"." ".$quantitycheck;

        // echo $sql;
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $count  = $result ['count'];
        return $count;
    }

    public function serviceCheckShopSuperAdminEdit($args)
    {
        $shop_id      = $args ['shop_id'];
        $servicetype  = $args ['type'];
        $item_idValue = $args ['item_idValue'];
        $servicetable = $args ['servicetable'];
        $item_id      = $args ['item_id'];
        $user_id      = $args ['user_id'];
        $sql          = "SELECT count(*) as count FROM  zselex_serviceshop a , $servicetable b
                WHERE a.type='".$servicetype."' AND a.shop_id='".$shop_id."'
                 AND b.$item_id='".$item_idValue."'";
        // echo $sql;
        $query        = DBUtil::executeSQL($sql);
        $result       = $query->fetch();
        $count        = $result ['count'];
        return $count;
    }

    public function serviceCheckExistShopOwner($args)
    {
        $shop_id     = $args ['shop_id'];
        $servicetype = $args ['type'];
        $user_id     = $args ['user_id'];
        $sql         = "SELECT count(*) as count FROM  zselex_serviceshop
                WHERE type='".$servicetype."' AND shop_id='".$shop_id."' 
                AND owner_id='".$user_id."'";
        // echo $sql;
        $query       = DBUtil::executeSQL($sql);
        $result      = $query->fetch();
        $count       = $result ['count'];
        return $count;
    }

    /**
     * Check service period expiry for blocks
     * 
     * @param array $args
     * @return boolean
     */
    public function serviceExistBlock($args)
    {
        // return;
        $shop_id     = $args ['shop_id'];
        $servicetype = $args ['type'];

        /*
         * $owner = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo', array('shop_id' => $shop_id));
         * $ownerId = $owner['uid'];
         */
        /*
         * if (!$ownerId) {
         * $permission = 0;
         * return $permission;
         * }
         */

        /*
         * $sql = "SELECT a.* , b.status as active_status , b.service_depended , b.shop_depended , b.depended_services
         * FROM zselex_serviceshop a
         * LEFT JOIN zselex_plugin b ON a.plugin_id=b.plugin_id
         * WHERE a.type='" . $servicetype . "' AND a.shop_id='" . $shop_id . "'";
         * // echo $sql;
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetch();
         * $count = $query->rowCount();
         */

        $result = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->serviceExistBlock($args);
        $count  = count($result);
        // echo "<pre>"; print_r($result); echo "</pre>";

        if ($count > 0) {
            // echo "helloo";
            $active_status  = $result ['active_status'];
            $service_status = $result ['service_status'];
            $quantity       = $result ['quantity'];
            $used           = $result ['availed'];
            $qty_based      = $result ['qty_based'];
            $timer_days     = $result ['timer_days'];
            $days           = $result ['days'];
            $isFree         = $result ['is_free'];

            if ($active_status < 1) {
                $permission = 0;
                return $permission;
            }

            if ($result ['service_depended'] == 1 || $result ['shop_depended'] == 1) {
                // echo "Comes here :" . $servicetype . '<br>';
                $buyStatus = ModUtil::apiFunc('ZSELEX', 'admin', 'canBuyStatus',
                        $args      = array(
                        'depended_services' => $result ['depended_services'],
                        'type' => $servicetype,
                        'shop_id' => $shop_id,
                        'shop_depended' => $result ['shop_depended'],
                        'service_depended' => $result ['service_depended'],
                        'timer_days' => $timer_days,
                        'days' => $days,
                        'owner_id' => $result ['owner_id']
                ));

                if ($buyStatus ['cantbuy']) {
                    $permission = 0;
                    return $permission;
                }
            }
            /*
              if ($service_status == 3) { // Free bundle never expires.
              return true;
              }
             */
            if ($isFree) { // Free bundle never expires.
                return true;
            }
            // echo $servicetype;
            // echo "<pre>"; print_r($buyStatus); echo "</pre>";
            // echo "Buy Status :" . $servicetype . "-" . $buyStatus['cantbuy'];
            // if ($service_status == self::PAID) {
            // $permission = 1;
            $serviceIs = "Paid";
            /*
             * $paiddays = ModUtil::apiFunc('ZSELEX', 'admin', 'paidDateCheck', $args = array(
             * 'type' => $servicetype,
             * 'shop_id' => $shop_id,
             * 'paid' => '1'
             * ));
             */
            if ($timer_days >= $days) {
                $isRunning = 1; // running
            } else {
                $isRunning = 0; // expired!
            }
            if ($isRunning) { // if its running
                /*
                 * if ($qty_based == 1) { // quantity based
                 * if ($quantity > $used) {
                 * $qtyLeft = $quantity - $used;
                 * //$message = $this->__("You have paid for this service and " . $qtyLeft . " usage is still left for it");
                 * //$perform = 'inactive';
                 * $permission = 1;
                 * } else {
                 * //$message = $this->__("Your service limit is over for this service");
                 * $permission = 0;
                 * }
                 * } else { // quantity not based
                 * //$message = $this->__("You have paid for this service");
                 * $permission = 1;
                 * }
                 */
                $permission = 1;
            } else {
                // $message = $this->__("Your paid period for this service has been expired");
                $permission = 0;
            }
            // }
        } else {
            $permission = 0;
        }
        // echo $permission;

        return $permission;
    }

    public function sendMail($args)
    {
        $to      = $args ['email'];
        $message = $args ['message']; // send message
        $subject = $args ['subject'];

        $message = $message;
        echo $message.'<br><br>';
        $headers = 'MIME-Version: 1.0'."\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";

        // Additional headers
        $headers .= 'To: '.$ownerInfo ['uname'].' <'.$ownerInfo ['email'].'>'."\r\n";
        $headers .= 'From: ZSELEX ADMIN <admin@zselex.com>'."\r\n";

        // Mail it
        // mail($to, $subject, $message, $headers);
    }

    public function servicExpiryReminder($args)
    {
        $services    = DBUtil::selectObjectArray('zselex_serviceshop');
        $modvariable = $this->getVars();

        foreach ($services as $key => $service) {
            $service_name = $this->getSingleItem($args         = array(
                'table' => 'zselex_plugin',
                'itemname' => 'plugin_name',
                'where' => "type='".$service [type]."'"
            ));
            // $owner_name = $this->getOwner($args = array('shop_id' => $service[shop_id]));

            $ownerInfo = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                    $args      = array(
                    'table' => 'zselex_shop_owners a ,  users b',
                    'where' => array(
                        "a.user_id=b.uid",
                        "a.shop_id=$service[shop_id]"
                    ),
                    'groupby' => 'b.uid'
            ));

            $service_status = $service ['service_status'];
            $quantity       = $service ['quantity'];
            $used           = $service ['availed'];
            $qty_based      = $service ['qty_based'];

            if ($service_status == self::DEMO) { // DEMO
                $serviceIs = "Demo";
                $demodays  = ModUtil::apiFunc('ZSELEX', 'admin',
                        'demoDateCheck1',
                        $args      = array(
                        'type' => $service [type],
                        'shop_id' => $service [shop_id],
                        'demo' => '1'
                ));
                if ($demodays ['demo'] == 1) { // IN DEMO PERIOD
                    $expiry_reminder_time = $modvariable ['serviceexpiryday'];

                    $diff       = $demodays ['diff'];
                    $demoperiod = $demodays ['demoperiod'];
                    // echo $diff; exit;
                    // echo $demoperiod; exit;

                    $days_remaining = $demoperiod - $diff;
                    // if ($diff >= $expiry_reminder_time) {
                    if ($expiry_reminder_time >= $days_remaining) {
                        $days_remaining = $demoperiod - $diff;
                        if ($days_remaining == 0) { // today
                            $days           = '';
                            $days_remaining = '';
                            $msg1           = $this->__("Demo period for this service will expire today");
                        } elseif ($days_remaining == 1) { // only 1 day left
                            $days = $this->__("day");
                            $msg1 = $this->__("Demo period for this service will expire in");
                        } else { // more than a day
                            $days = $this->__("days");
                            $msg1 = $this->__("Demo period for this service will expire in");
                        }
                        $message  = '';
                        $message .= "<div>".$this->__('Service Name').": $service_name</div>";
                        $message .= "<div>".$msg1." ".$days_remaining." ".$days."</div>";
                        $mailargs = array(
                            'to' => $ownerInfo ['email'],
                            'subject' => 'Service Expiry Reminder',
                            'message' => $message
                        );
                        $this->sendMail($mailargs);
                    }

                    if ($qty_based == 1) { // only for quantity based
                        if ($quantity > $used) { // in demo and quantity is still left for use
                            $qtyLeft    = $quantity - $used;
                            $permission = 1;
                        } else { // still in demo but usage limit is over
                            $expirytime = $modvariable ['serviceexpiryday'];
                            $message    = '';
                            $message .= "<div>".$this->__('Service Name').": $service_name</div>";
                            $message .= "<div>".$this->__("Demo period for this service is over. Please upgrade the service to continue to use it.")."</div>"; // send message
                            $permission = 0;
                            $mailargs   = array(
                                'to' => $ownerInfo ['email'],
                                'subject' => 'Service Expiry Reminder',
                                'message' => $message
                            );
                            $this->sendMail($mailargs);
                        }
                    } else {
                        $permission = 1;
                    }
                } else { // demo expired forever
                    $message    = '';
                    $message .= "<div>".$this->__('Service Name').": $service_name</div>";
                    $message .= $this->__("Demo period for this service has expired. Please buy the service to continue to use it.");
                    $mailargs   = array(
                        'to' => $ownerInfo ['email'],
                        'subject' => 'Service Expiry Reminder',
                        'message' => $message
                    );
                    $this->sendMail($mailargs);
                    $permission = 0;
                }
            } elseif ($service_status == self::PAID) { // PAID SERVICE
                // echo "Come here paid";
                $serviceIs = "Paid";

                $paiddays = ModUtil::apiFunc('ZSELEX', 'admin', 'paidDateCheck',
                        $args     = array(
                        'type' => $service [type],
                        'shop_id' => $service [shop_id],
                        'paid' => '1'
                ));
                if ($paiddays ['running'] == 1) {
                    if ($qty_based == 1) { // quantity based
                        if ($quantity > $used) {
                            $qtyLeft    = $quantity - $used;
                            // $message = $this->__("You have paid for this service and " . $qtyLeft . " usage is still left for it");
                            // $perform = 'inactive';
                            $permission = 1;
                        } else { // quantity is over
                            $message    = '';
                            $message .= "<div>".$this->__('ServiceName').": $service_name</div>";
                            $message .= "<div>".$this->__("Your service usage period has expired for this paid service.")."</div>";
                            $mailargs   = array(
                                'to' => $ownerInfo ['email'],
                                'subject' => 'Service Expiry Reminder',
                                'message' => $message
                            );
                            $this->sendMail($mailargs);
                            $permission = 0;
                        }
                    } else { // quantity not based
                        // echo "helloo world";
                        // $message = $this->__("You have paid for this service");
                        $permission = 1;
                    }
                } else { // paid expired!
                    $message    = '';
                    $message .= "<div>".$this->__('Service Name').": $service_name</div>";
                    $message .= $this->__("Paid period for this service has expired. Please buy/upgrade the service to continue to use it.");
                    $mailargs   = array(
                        'to' => $ownerInfo ['email'],
                        'subject' => 'Service Expiry Reminder',
                        'message' => $message
                    );
                    $this->sendMail($mailargs);
                    $permission = 0;
                }
            }
        }
    }

    public function serviceCheckCart($args)
    {
        $shop_id     = $args ['shop_id'];
        $servicetype = $args ['type'];
        $user_id     = $args ['user_id'];

        $sql           = "SELECT a.original_quantity , a.quantity , a.availed , a.status , a.service_status , a.qty_based , a.timer_date , a.timer_days , b.status as active_status
                FROM zselex_serviceshop a
                LEFT JOIN zselex_plugin b ON a.plugin_id=b.plugin_id
                WHERE a.type='".$servicetype."' AND a.shop_id='".$shop_id."'";
        // echo $sql;
        $query         = DBUtil::executeSQL($sql);
        $result        = $query->fetch();
        $active_status = $result ['active_status'];
        $count         = $query->rowCount();

        // echo $diff;
        // echo "active_status :" . $active_status;

        if ($count > 0) {
            $service_status = $result ['service_status'];
            $quantity       = $result ['quantity'];
            $used           = $result ['availed'];
            $qty_based      = $result ['qty_based'];

            $today      = date("Y-m-d");
            $timer_date = $result ['timer_date'];
            $timer_days = $result ['timer_days'];

            if ($service_status == self::DEMO) { // demo
                // echo "Come here demo";
                $serviceIs = "Demo";
                $demodays  = ModUtil::apiFunc('ZSELEX', 'admin',
                        'demoDateCheck1',
                        $args      = array(
                        'type' => $servicetype,
                        'shop_id' => $shop_id,
                        'demo' => '1'
                ));
                if ($demodays ['demo'] == 1) {
                    // $message = $this->__("This service is currently running as demo and is still valid untill its demo time gets over");
                    // $perform = 'inactive';
                    // echo "Come here";
                    if ($qty_based == 1) {
                        if ($quantity > $used) {
                            $qtyLeft    = $quantity - $used;
                            // $message = $this->__("You have paid for this service and " . $qtyLeft . " usage is still left for it");
                            // $perform = 'inactive';
                            $permission = 1;
                        } else {
                            $message    = $this->__("Your service limit is over for this demo service");
                            $permission = 0;
                        }
                    } else {
                        // $message = $this->__("You have paid for this service");
                        $permission = 1;
                    }

                    // $permission = 1;
                } else {
                    // $perform = 'delete';
                    $message    = $this->__("Your demo period for this service has been expired");
                    $permission = 0;
                }
            } elseif ($service_status == self::PAID) {
                // echo "Come here paid";
                $serviceIs = "Paid";
                $paiddays  = ModUtil::apiFunc('ZSELEX', 'admin',
                        'paidDateCheck',
                        $args      = array(
                        'type' => $servicetype,
                        'shop_id' => $shop_id,
                        'paid' => '1'
                ));
                if ($paiddays ['running'] == 1) { // if its running
                    if ($qty_based == 1) { // quantity based
                        if ($quantity > $used) {
                            $qtyLeft    = $quantity - $used;
                            // $message = $this->__("You have paid for this service and " . $qtyLeft . " usage is still left for it");
                            // $perform = 'inactive';
                            $permission = 1;
                        } else {
                            $message    = $this->__("Your service limit is over for this service");
                            $permission = 0;
                        }
                    } else { // quantity not based
                        // $message = $this->__("You have paid for this service");
                        $permission = 1;
                    }
                } else {
                    $message    = $this->__("Your paid period for this service has been expired");
                    $permission = 0;
                }
            }
        } else {
            $permission = 0;
            $message    = $this->__("The Service you try to use has to be purchased first");
        }

        $returnArray = array(
            'running' => $permission,
            'timer_date' => $timer_date,
            'timer_days' => $timer_days,
            'qty_based' => $qty_based,
            'service_status' => $service_status
        );

        return $returnArray;
    }

    public function serviceCheckCartBundle($args)
    {
        $repo      = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        $shop_id   = $args ['shop_id'];
        $user_id   = $args ['user_id'];
        $bundle_id = $args ['bundle_id'];

        /*
         * $sql = "SELECT a.original_quantity , a.quantity , a.service_status , a.timer_date , a.timer_days
         * FROM zselex_serviceshop_bundles a
         * WHERE a.bundle_id='" . $bundle_id . "' AND a.shop_id='" . $shop_id . "'";
         * // echo $sql;
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetch();
         */
        $result = $repo->get(array(
            'entity' => 'ZSELEX_Entity_ServiceBundle',
            'fields' => array(
                'a.original_quantity',
                'a.quantity',
                'a.service_status',
                'a.timer_date',
                'a.timer_days'
            ),
            'where' => array(
                'a.bundle' => $bundle_id,
                'a.shop' => $shop_id
            )
        ));
        // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        // $active_status = $result['active_status'];
        // $count = $query->rowCount();
        $count  = count($result);

        // echo $diff;
        // echo "active_status :" . $active_status;

        if ($count > 0) {
            $service_status = $result ['service_status'];
            $quantity       = $result ['quantity'];
            $used           = $result ['availed'];
            $qty_based      = $result ['qty_based'];

            $today      = date("Y-m-d");
            $timer_date = $result ['timer_date'];
            $timer_days = $result ['timer_days'];

            // if ($service_status == self::PAID) {
            // echo "Come here paid";
            $serviceIs = "Paid";
            $paiddays  = ModUtil::apiFunc('ZSELEX', 'admin',
                    'paidDateCheckBundle',
                    $args      = array(
                    'bundle_id' => $bundle_id,
                    'shop_id' => $shop_id,
                    'paid' => '1'
            ));
            if ($paiddays ['running'] == 1) { // if its running
                if ($qty_based == 1) { // quantity based
                    if ($quantity > $used) {
                        $qtyLeft    = $quantity - $used;
                        // $message = $this->__("You have paid for this service and " . $qtyLeft . " usage is still left for it");
                        // $perform = 'inactive';
                        $permission = 1;
                    } else {
                        $message    = $this->__("Your service limit is over for this service");
                        $permission = 0;
                    }
                } else { // quantity not based
                    // $message = $this->__("You have paid for this service");
                    $permission = 1;
                }
            } else {
                $message    = $this->__("Your paid period for this service has been expired");
                $permission = 0;
            }
            // }
        } else {
            $permission = 0;
            $message    = $this->__("The Service you try to use has to be purchased first");
        }

        $returnArray = array(
            'running' => $permission,
            'timer_date' => $timer_date,
            'timer_days' => $timer_days,
            'qty_based' => $qty_based,
            'service_status' => $service_status
        );

        return $returnArray;
    }

    public function servicePermission1($args)
    {
        // exit;
        $shop_id      = $args ['shop_id'];
        $servicetype  = $args ['type'];
        $user_id      = $args ['user_id'];
        $disableCheck = $args ['disablecheck'];
        $expired      = 0;
        $admin        = SecurityUtil::checkPermission('ZSELEX::', '::',
                ACCESS_ADMIN);

        /*
         * $owner = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo', array('shop_id' => $shop_id));
         * $ownerId = $owner['uid'];
         * if (!$ownerId) {
         * $returnArray = array(
         * 'perm' => 0,
         * 'expired' => 0,
         * 'message' => $this->__('Owner not assigned!')
         * );
         *
         * return $returnArray;
         * }
         */

        // if (!$admin) {
        if ($disableCheck) { // check if disabled.
            /*
             * $disble_arg = array('table' => 'zselex_plugin', 'where' => "type='" . $servicetype . "' AND status=0", 'Id' => 'plugin_id');
             * $service_disabled = ModUtil::apiFunc('ZSELEX', 'admin', 'countElements', $disble_arg);
             */

            $countArgs = array(
                'entity' => 'ZSELEX_Entity_Plugin',
                'field' => 'plugin_id',
                'where' => array(
                    'a.type' => $servicetype,
                    'a.status' => 0
                )
            );

            $service_disabled = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getCount($countArgs);

            // echo $service_disabled;
            if ($service_disabled) {
                if (!$admin) {
                    $perm = 0;
                } else {
                    $perm = 1;
                }
                $returnArray = array(
                    'perm' => $perm,
                    'expired' => 1,
                    'disabled' => 1,
                    'message' => $this->__('This service is currently disabled')
                );

                return $returnArray;
            }
        }
        // }

        $quantitybased = $args ['quantitybased'];
        $quantitycheck = " AND quantity > availed";
        if ($quantitybased == 'no') {
            $quantitycheck = '';
        }

        /*
         * $sql = "SELECT a.original_quantity , a.quantity , a.availed , a.status , a.service_status , a.qty_based , a.timer_date , a.timer_days , b.status as active_status ,b.service_depended , b.shop_depended , b.depended_services
         * FROM zselex_serviceshop a
         * LEFT JOIN zselex_plugin b ON a.plugin_id=b.plugin_id
         * WHERE a.type='" . $servicetype . "' AND a.shop_id='" . $shop_id . "'";
         * // echo $sql;
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetch();
         */

        $today   = date("Y-m-d");
        $sqlArgs = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                "date_diff('$today' , a.timer_date) as days",
                'a.original_quantity',
                'a.quantity',
                'a.availed',
                'a.status',
                'a.service_status',
                'a.qty_based',
                'a.timer_date',
                'a.timer_days',
                'b.status as active_status',
                'b.service_depended',
                'b.shop_depended',
                'b.depended_services'
            ),
            'where' => array(
                'a.type' => $servicetype,
                'a.shop' => $shop_id
            ),
            'joins' => array(
                'LEFT JOIN a.plugin b'
            )
        );
        $result  = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->get($sqlArgs);

        // echo "<pre>"; print_r($result); echo "</pre>";
        $active_status = $result ['active_status'];
        // $count = $query->rowCount();
        $count         = count($result);

        // echo $diff;
        // echo "active_status :" . $active_status;

        if ($count > 0) {
            if ($result ['service_depended'] == 1 || $result ['shop_depended'] == 1) {
                // echo "Comes here :" . $servicetype;
                $buyStatus = ModUtil::apiFunc('ZSELEX', 'admin', 'canBuyStatus',
                        $args      = array(
                        'depended_services' => $result ['depended_services'],
                        'type' => $servicetype,
                        'shop_id' => $shop_id,
                        'shop_depended' => $result ['shop_depended'],
                        'service_depended' => $result ['service_depended'],
                        'owner_id' => $result ['owner_id']
                ));
                // echo "<pre>"; print_r($buyStatus); echo "</pre>";
                if ($buyStatus ['cantbuy']) {
                    $permission  = 0;
                    $returnArray = array(
                        'perm' => 0,
                        'expired' => 0,
                        'message' => $this->__('Depended service(s) for this service has not bought or expired')
                    );

                    return $returnArray;
                }
            }

            $service_status = $result ['service_status'];
            $quantity       = $result ['quantity'];
            $used           = $result ['availed'];
            $qty_based      = $result ['qty_based'];

            $today      = date("Y-m-d");
            $timer_date = $result ['timer_date'];
            $timer_days = $result ['timer_days'];

            // echo "Come here paid";
            $serviceIs = "Paid";
            /*
             * $paiddays = ModUtil::apiFunc('ZSELEX', 'admin', 'paidDateCheck', $args = array(
             * 'type' => $servicetype,
             * 'shop_id' => $shop_id,
             * 'paid' => '1'
             * ));
             */
            if ($timer_days >= $result ['days']) {
                $isRunning = 1; // running
            } else {
                $isRunning = 0; // expired!
            }
            // if ($paiddays['running'] == 1) { // if its running
            if ($isRunning) { // if its running
                if ($qty_based == 1) { // quantity based
                    if ($quantity > $used) {
                        $qtyLeft    = $quantity - $used;
                        // $message = $this->__("You have paid for this service and " . $qtyLeft . " usage is still left for it");
                        // $perform = 'inactive';
                        $permission = 1;
                    } else {
                        $message    = ($service_status == self::PAID) ? $this->__("Your service limit is over for this service")
                                : $this->__("Your service limit is over for this demo service");
                        $permission = 0;
                    }
                } else { // quantity not based
                    // $message = $this->__("You have paid for this service");
                    $permission = 1;
                }
                $expired = 0;
            } else {
                $message    = ($service_status == self::PAID) ? $this->__("Your paid period for this service has been expired")
                        : $this->__("Your demo period for this service has been expired");
                $permission = 0;
                $expired    = 1;
            }
        } else {
            $permission = 0;
            $expired    = 1;
            $message    = $this->__("The service you try to use has to be purchased first");
        }

        $returnArray = array(
            'perm' => $permission,
            'message' => $message,
            'qty_left' => $qtyLeft,
            'expired' => $expired
        );

        return $returnArray;
    }

    /**
     * Check service permission/expiry
     * 
     * @param array $args
     * @return array
     */
    public function servicePermission($args)
    {
        // exit;
        $shop_id      = $args ['shop_id'];
        $servicetype  = $args ['type'];
        $user_id      = $args ['user_id'];
        $disableCheck = $args ['disablecheck'];
        $expired      = 0;
        $admin        = SecurityUtil::checkPermission('ZSELEX::', '::',
                ACCESS_ADMIN);

        // if (!$admin) {
        if ($disableCheck) { // check if disabled.
            $countArgs = array(
                'entity' => 'ZSELEX_Entity_Plugin',
                'field' => 'plugin_id',
                'where' => array(
                    'a.type' => $servicetype,
                    'a.status' => 0
                )
            );

            $service_disabled = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getCount($countArgs);

            // echo $service_disabled;
            if ($service_disabled) {
                if (!$admin) {
                    $perm = 0;
                } else {
                    $perm = 1;
                }
                $returnArray = array(
                    'perm' => $perm,
                    'expired' => 1,
                    'disabled' => 1,
                    'message' => $this->__('This service is currently disabled')
                );

                return $returnArray;
            }
        }
        // }

        $quantitybased = $args ['quantitybased'];
        $quantitycheck = " AND quantity > availed";
        if ($quantitybased == 'no') {
            $quantitycheck = '';
        }

        $today         = date("Y-m-d");
        $sqlArgs       = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                "date_diff('$today' , a.timer_date) as days",
                'a.original_quantity',
                'a.quantity',
                'a.availed',
                'a.status',
                'a.service_status',
                'a.qty_based',
                'a.timer_date',
                'a.timer_days',
                'b.status as active_status',
                'b.service_depended',
                'b.shop_depended',
                'b.depended_services',
                'c.is_free'
            ),
            'where' => array(
                'a.type' => $servicetype,
                'a.shop' => $shop_id
            ),
            'joins' => array(
                'LEFT JOIN a.plugin b',
                'JOIN a.bundle c'
            ),
            //  'print_result'=>true
        );
        $result        = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->get($sqlArgs);
        //  echo "comes here"; exit;
        // echo "<pre>"; print_r($result); echo "</pre>"; exit;
        $active_status = $result ['active_status'];
        // $count = $query->rowCount();
        $count         = count($result);

        // echo $diff;
        // echo "active_status :" . $active_status;

        if ($count > 0) {
            if ($result ['service_depended'] == 1 || $result ['shop_depended'] == 1) {
                // echo "Comes here :" . $servicetype;
                $buyStatus = ModUtil::apiFunc('ZSELEX', 'admin', 'canBuyStatus',
                        $args      = array(
                        'depended_services' => $result ['depended_services'],
                        'type' => $servicetype,
                        'shop_id' => $shop_id,
                        'shop_depended' => $result ['shop_depended'],
                        'service_depended' => $result ['service_depended'],
                        'owner_id' => $result ['owner_id']
                ));
                // echo "<pre>"; print_r($buyStatus); echo "</pre>";
                if ($buyStatus ['cantbuy']) {
                    $permission  = 0;
                    $returnArray = array(
                        'perm' => 0,
                        'expired' => 0,
                        'message' => $this->__('Depended service(s) for this service has not bought or expired')
                    );

                    return $returnArray;
                }
            }

            $service_status = $result ['service_status'];
            $quantity       = $result ['quantity'];
            $used           = $result ['availed'];
            $qty_based      = $result ['qty_based'];

            $today      = date("Y-m-d");
            $timer_date = $result ['timer_date'];
            $timer_days = $result ['timer_days'];
            $isFree     = $result ['is_free'];

            // echo "Come here paid";
            $serviceIs = "Paid";

            //  if ($service_status == 3) {
            if ($isFree) {
                $isRunning = 1;
            } else {
                if ($timer_days >= $result ['days']) {
                    $isRunning = 1; // running
                } else {
                    $isRunning = 0; // expired!
                }
            }

            if ($isRunning) { // if its running
                $permission = 1;
                $expired    = 0;
            } else {
                $message    = ($service_status == self::PAID) ? $this->__("Your paid period for this service has been expired")
                        : $this->__("Your demo period for this service has been expired");
                $permission = 0;
                $expired    = 1;
            }
        } else {
            $permission = 0;
            $expired    = 1;
            $message    = $this->__("The service you try to use has to be purchased first");
        }

        $returnArray = array(
            'perm' => $permission,
            'message' => $message,
            'quantity' => $quantity,
            'expired' => $expired,
            'service_status' => $service_status
        );

        return $returnArray;
    }

    public function serviceCheckShopOwner($args)
    {
        $shop_id       = $args ['shop_id'];
        $servicetype   = $args ['type'];
        $user_id       = $args ['user_id'];
        $quantitybased = $args ['quantitybased'];
        $quantitycheck = " AND quantity > availed";
        if ($quantitybased == 'no') {
            $quantitycheck = '';
        }
        $sql    = "SELECT count(*) as count FROM zselex_serviceshop
                WHERE type='".$servicetype."' AND shop_id='".$shop_id."'
                AND owner_id='".$user_id."'"." ".$quantitycheck;
        // echo $sql;
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $count  = $result ['count'];
        return $count;
    }

    public function serviceCheckShopOwnerEdit($args)
    {
        $shop_id      = $args ['shop_id'];
        $servicetype  = $args ['type'];
        $item_idValue = $args ['item_idValue'];
        $servicetable = $args ['servicetable'];
        $item_id      = $args ['item_id'];
        $user_id      = $args ['user_id'];
        $sql          = "SELECT count(*) as count FROM  zselex_serviceshop a , $servicetable b
                WHERE a.type='".$servicetype."' AND a.shop_id='".$shop_id."' 
                AND a.owner_id='".$user_id."'  AND b.$item_id='".$item_idValue."'";
        $query        = DBUtil::executeSQL($sql);
        $result       = $query->fetch();
        $count        = $result ['count'];
        return $count;
    }

    public function serviceCheckShopAdmin($args)
    {
        $shop_id       = $args ['shop_id'];
        $servicetype   = $args ['type'];
        $user_id       = $args ['user_id'];
        $quantitybased = $args ['quantitybased'];
        $quantitycheck = " AND a.quantity > a.availed";
        if ($quantitybased == 'no') {
            $quantitycheck = '';
        }

        $sql = "SELECT count(*) as count FROM  zselex_serviceshop a , zselex_shop_admins b
                WHERE a.type='".$servicetype."' AND b.shop_id='".$shop_id."' 
                AND a.owner_id=b.owner_id    
                AND b.user_id='".$user_id."'"." ".$quantitycheck;

        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $count  = $result ['count'];

        return $count;
    }

    public function serviceCheckShopAdminEdit($args)
    {
        $shop_id      = $args ['shop_id'];
        $servicetype  = $args ['type'];
        $item_idValue = $args ['item_idValue'];
        $servicetable = $args ['servicetable'];
        $item_id      = $args ['item_id'];
        $user_id      = $args ['user_id'];
        $sql          = "SELECT count(*) as count FROM  zselex_serviceshop a , zselex_shop_admins b , $servicetable c
                WHERE a.type='".$servicetype."' AND b.shop_id='".$shop_id."' 
                AND a.owner_id=b.owner_id
                AND c.$item_id='".$item_idValue."'
                AND b.user_id='".$user_id."' 
              ";
        $query        = DBUtil::executeSQL($sql);
        $result       = $query->fetch();
        $count        = $result ['count'];
        return $count;
    }

    public function serviceCheckUnregistered($args)
    {
        return 0;
    }

    public function serviceCheck($args)
    {
        $count       = 2;
        $shop_id     = $args ['shop_id'];
        $servicetype = $args ['type'];

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // super admin
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADD)) { // shop owner
                return $this->serviceCheckShopOwner($args);
            } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_EDIT)) { // shop admin
                return $this->serviceCheckShopAdmin($args);
            } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADD) && !SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_EDIT)) { // unregistered
                return $this->serviceCheckUnregistered($args);
            }
        } else {
            // return 2;
            return $this->serviceCheckShopSuperAdmin($args); // super admin
        }
    }

    public function checkDotdExist($args)
    {
        $shop_id  = $args ['shop_id'];
        $sql      = "SELECT dotd_date FROM zselex_dotd  WHERE shop_id='".$shop_id."'";
        $query    = DBUtil::executeSQL($sql);
        $result   = $query->fetch();
        $date     = $result ['dotd_date'];
        $currdate = date("Y-m-d");
        if ($date >= $currdate) {
            return true;
        } else {
            return false;
        }
    }

    public function serviceCheckEdit($args)
    {
        $count         = 2;
        $shop_id       = $args ['shop_id'];
        $servicetype   = $args ['type'];
        $serviceShopId = $args ['serviceshopid'];

        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // super admin
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADD)) { // shop owner
                return $this->serviceCheckShopOwnerEdit($args);
            } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_EDIT)) { // shop admin
                return $this->serviceCheckShopAdminEdit($args);
            } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADD) && !SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_EDIT)) { // unregistered
                return $this->serviceCheckUnregistered($args);
            }
        } else {
            // return 2;
            return $this->serviceCheckShopSuperAdminEdit($args);
        }
    }

    public function minishopExist($args)
    {
        $shop_id     = $args ['shop_id'];
        $servicetype = $args ['type'];

        $sql    = "SELECT count(*) as count FROM zselex_minishop
                WHERE shop_id='".$shop_id."' AND configured=1";
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $count  = $result ['count'];

        return $count;
    }

    public function serviceExist($args)
    {
        $shop_id     = $args ['shop_id'];
        $servicetype = $args ['type'];

        $sql    = "SELECT count(*) as count FROM zselex_serviceshop
                WHERE type='".$servicetype."' AND shop_id='".$shop_id."'";
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $count  = $result ['count'];

        return $count;
    }

    public function deleteService($args)
    {
        $shop_id     = $args ['shop_id'];
        $serviceType = $args ['servicetype'];

        /*
         * $availed = $this->getSingleItem($args = array(
         * 'table' => 'zselex_serviceshop',
         * 'itemname' => 'availed',
         * 'where' => "shop_id=$shop_id AND type='" . $serviceType . "'"
         * ));
         */

        $getArgs = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'a.availed'
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => $serviceType
            )
        );
        $item    = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->get($getArgs);
        $availed = $item ['availed'];

        if ($availed > 0) {
            if ($availed > 0) {
                $availedless = $availed - 1;
            } else {
                $availedless = $availed;
            }

            /*
             * $sql = "UPDATE zselex_serviceshop SET availed=$availedless WHERE shop_id='" . $shop_id . "'
             * AND type='" . $serviceType . "'";
             * // echo $sql; exit;
             * $query = DBUtil::executeSQL($sql);
             */

            $upd_args = array(
                'entity' => 'ZSELEX_Entity_ServiceShop',
                'fields' => array(
                    'availed' => $availedless
                ),
                'where' => array(
                    'a.shop' => $shop_id,
                    'a.type' => $serviceType
                )
                )
            // 'where' => "a.cart_id=:cart_id"
            ;
            $query    = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->updateEntity($upd_args);

            return $query;
        }
    }

    public function getSingleItem($args)
    {
        $table    = $args ['table'];
        $itemName = $args ['itemname'];
        $where    = $args ['where'];
        $sql      = "SELECT $itemName as item FROM $table WHERE"." ".$where;
        // echo $sql; exit;
        $query    = DBUtil::executeSQL($sql);
        $result   = $query->fetch();
        $item     = $result ['item'];
        return $item;
    }

    public function updateWhere($args)
    {

        // print_r($args); exit;
        // Argument check
        // if (!isset($args['IdValue'])) {
        // return LogUtil::registerArgsError();
        // }
        // Get the news item
        $item = ModUtil::apiFunc('ZSELEX', 'admin', 'getElement', $args);

        // print_r($item); exit;

        if ($item == false) {
            return LogUtil::registerError($this->__('Error! Item not found.'));
        }

        $this->throwForbiddenUnless($this->_isSubmittor($item) || SecurityUtil::checkPermission('ZSELEX::',
                $item ['cr_uid'].'::'.$args ['IdValue'], ACCESS_EDIT),
            LogUtil::getErrorMsgPermission());

        if (!DBUtil::updateObject($obj, $table, $where)) {
            return LogUtil::registerError($this->__('Error! Unable to save your changes.'));
        }
        // echo "hiiiii"; exit;
        // Let the calling process know that we have finished successfully
        return true;
    }

    public function getAdmins($args)
    {

        // $admins = ModUtil::apiFunc('ZSELEX', 'user', 'selectArray', $args = array('table' => 'zselex_shop_admins a , users b', 'where' => array("a.user_id=b.uid", "a.owner_id=$owner"), 'groupby' => 'b.uid'));
        $shop_id    = $args ['shop_id'];
        /*
         * $sql = "SELECT u.uname FROM users u , zselex_shop s , zselex_shop_admins ad
         * WHERE u.uid=ad.user_id AND ad.shop_id=s.shop_id AND s.shop_id='" . $shop_id . "'";
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetchAll();
         * $adminNames = $result;
         */
        $adminNames = $this->entityManager->getRepository('ZSELEX_Entity_ShopAdmin')->getAdmins(array(
            'shop_id' => $shop_id
        ));

        return $adminNames;
    }

    public function getOwner($args)
    {
        $shop_id = $args ['shop_id'];

        /*
         * $sql = "SELECT u.uname FROM users u , zselex_shop s , zselex_shop_owners ow
         * WHERE u.uid=ow.user_id AND ow.shop_id=s.shop_id AND s.shop_id='" . $shop_id . "'";
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetch();
         * $ownerName = $result['uname'];
         */

        $ownerName = $this->entityManager->getRepository('ZSELEX_Entity_ShopOwner')->getOwner($args);

        return $ownerName;
    }

    public function getOwnerInfo($args)
    {

        /*
         * $shop_id = $args['shop_id'];
         *
         * $sql = "SELECT u.uid,u.uname,u.email,s.shop_id FROM users u , zselex_shop s , zselex_shop_owners ow
         * WHERE u.uid=ow.user_id AND ow.shop_id=s.shop_id AND s.shop_id='" . $shop_id . "'";
         * //echo $sql;
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetch();
         */

        // echo "<pre>"; print_r($result); echo "</pre>";
        $result = $this->entityManager->getRepository('ZSELEX_Entity_ShopOwner')->getOwnerInfo($args);
        // echo "<pre>"; print_r($result); echo "</pre>";
        // $ownerName = $result['uname'];

        return $result;
    }

    public function getOwnerId($args)
    {
        $ownername = $args ['ownername'];

        $sql      = "SELECT uid FROM users WHERE uname='".$ownername."'";
        $query    = DBUtil::executeSQL($sql);
        $result   = $query->fetch();
        $owner_id = $result ['uid'];

        return $owner_id;
    }

    public function updateServiceUsedOwner($args)
    {
        $user_id = $args ['user_id'];
        $shop_id = $args ['shop_id'];
        $type    = $args ['type'];
        /*
         * $sql = "UPDATE zselex_serviceshop SET availed=availed+1
         * WHERE shop_id='" . $shop_id . "' AND type='" . $type . "'";
         * //echo $sql; exit;
         * $query = DBUtil::executeSQL($sql);
         */

        $getArgs = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'a.availed'
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => $type
            )
        );
        $item    = $this->entityManager->getRepository('ZSELEX_Entity_Event')->get($getArgs);

        $upd_args = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'availed' => $item ['availed'] + 1
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => $type
            )
            )
        // 'where' => "a.cart_id=:cart_id"
        ;
        $result   = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->updateEntity($upd_args);
        return true;
    }

    public function updateServiceShopAdmin($args)
    {
        $user_id = $args ['user_id'];
        $shop_id = $args ['shop_id'];
        $type    = $args ['type'];
        /*
         * $sql = "UPDATE zselex_serviceshop SET availed=availed+1
         * WHERE shop_id='" . $shop_id . "' AND type='" . $type . "' AND
         * user_id IN (SELECT owner_id FROM zselex_shop_admins WHERE user_id='" . $user_id . "')";
         * //echo $sql; exit;
         * $query = DBUtil::executeSQL($sql);
         */

        $getArgs = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'a.availed'
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => $type
            )
        );
        $item    = $this->entityManager->getRepository('ZSELEX_Entity_Event')->get($getArgs);

        $upd_args = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'availed' => $item ['availed'] + 1
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => $type
            )
        );
        $result   = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->updateEntity($upd_args);
        return true;
    }

    public function updateServiceUsed($args)
    {
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN)) { // super admin
            if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_ADD)) { // shop owner
                return $this->updateServiceUsedOwner($args);
            } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_EDIT)) { // shop admin
                return $this->updateServiceShopAdmin($args);
            } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::',
                    ACCESS_ADD) && !SecurityUtil::checkPermission('ZSELEX::',
                    '::', ACCESS_EDIT)) { // unregistered
                // return $this->serviceCheckUnregistered($args);
            }
        } else {
            // return 2;
            return $this->updateServiceUsedSuperAdmin($args);
        }
    }

    public function updateServiceUsedSuperAdmin($args)
    {
        $user_id = $args ['user_id'];
        $shop_id = $args ['shop_id'];
        $type    = $args ['type'];
        /*
         * $sql = "UPDATE zselex_serviceshop SET availed=availed+1
         * WHERE shop_id='" . $shop_id . "' AND type='" . $type . "'";
         * //echo $sql; exit;
         * $query = DBUtil::executeSQL($sql);
         */

        $getArgs = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'a.availed'
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => $type
            )
        );
        $item    = $this->entityManager->getRepository('ZSELEX_Entity_Event')->get($getArgs);

        $upd_args = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'availed' => $item ['availed'] + 1
            ),
            'where' => array(
                'a.shop' => $shop_id,
                'a.type' => $type
            )
            )
        // 'where' => "a.cart_id=:cart_id"
        ;
        $result   = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->updateEntity($upd_args);
        return true;
    }

    public function getDefaultShopTheme($args)
    {
        $shop_id = $args ['shop_id'];

        /*
         * $sql = "SELECT theme FROM zselex_shop
         * WHERE shop_id='" . $shop_id . "'";
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetch();
         * $theme = $result['theme'];
         */

        $repo   = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $result = $repo->get(array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'a.theme'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        ));
        $theme  = $result ['theme'];

        return $theme;
    }

    public function checkProductLinkerExist($args)
    {
        $shop_id  = $args ['shop_id'];
        $owner_id = $args ['owner_id'];

        $args = array(
            'table' => 'zselex_serviceshop',
            // 'where' => "owner_id=$owner_id AND type='productlinker'",
            'where' => "shop_id=$shop_id AND type='productlinker'",
            'Id' => 'id',
            'status' => $status
        );

        $count = $this->countElements($args);
        return $count;
    }

    public function zencartProducts($vars)
    {
        try {
            // echo "<pre>"; print_r($vars); echo "</pre>"; exit;

            if ($vars ['amount'] != '') {
                $limit = $vars ['amount'];
            } else {
                $limit = '25';
            }
            // echo $limit;
            $dnName  = (!empty($vars ['database']) ? $vars ['database'] : 'nodb');
            $dnUser  = (!empty($vars ['username']) ? $vars ['username'] : 'root');
            $dbPswrd = (!empty($vars ['password']) ? $vars ['password'] : '');
            $dbHost  = (!empty($vars ['host']) ? $vars ['host'] : 'localhost');

            $dsn = "mysql:dbname='".$dnName."';host='".$dbHost."'";
            // echo $dsn; exit;

            $dsn         = "mysql:dbname=$dnName;host=$dbHost";
            $user        = $dnUser;
            $password    = $dbPswrd;
            $tableprefix = (!empty($vars ['tableprefix']) ? $vars ['tableprefix']
                        : '');

            $tableQuery = "SELECT COUNT(*) AS count  FROM information_schema.tables
                           WHERE table_schema = '".$dnName."'   AND table_name = '".$tableprefix."products'";

            $dbh         = new PDO($dsn, $user, $password);
            $statement1  = Doctrine_Manager::getInstance()->connection($dbh);
            $resultTable = $statement1->execute($tableQuery);
            $tableExist  = $resultTable->fetch();
            $tableCount  = $tableExist ['count'];

            if ($tableCount > 0) {
                $prdwhere = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
                $prdwhere = "a.products_status=1";
                $prdquery = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                         mn.manufacturers_name
                         FROM  ".$tableprefix."products a 
                         LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                         LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                         WHERE ".$prdwhere."
                         GROUP BY a.products_id
                         ORDER BY RAND()  LIMIT  0,$limit";
                $results  = $statement1->execute($prdquery);
                $sValues  = $results->fetchAll();

                // echo $config['domain'];
                // $imagearr = array('imageval'=>'lower');
                $list = array();
                for ($i = 0; $i < count($sValues); $i ++) {

                    // echo number_format($sValues[$i]['products_price'], 2) . '<br>';
                    $priceexplode = explode('.',
                        $sValues [$i] ['products_price']);
                    // echo $priceexplode[1] . '<br>';

                    if (strlen($priceexplode [0]) >= 4) {
                        $p1                     = substr_replace($priceexplode [0],
                            ".", 1, 0);
                        $p2                     = substr_replace($priceexplode [1],
                            ",", 2);
                        $p2                     = substr($p2, 0, - 1);
                        $sValues [$i] ['PRICE'] = $p1.','.$p2;
                    } else {

                        // echo $priceexplode[1] . '<br>';
                        $newstring              = substr_replace($priceexplode [1],
                            '', '2');
                        // echo $newstring . '<br>';
                        // echo $priceexplode[0] . ',' . $newstring . '<br>';
                        $sValues [$i] ['PRICE'] = $priceexplode [0].','.$newstring;
                    }

                    // echo $sValues[$i]['PRICE'] . '<br>';

                    if ($sValues [$i] ['products_image'] != '') {
                        list($width, $height, $type, $attr) = getimagesize('http://'.$vars ['domain'].'/images/'.str_replace(" ",
                                "%20", $sValues [$i] ['products_image']));
                        $AW = $width;
                        $AH = $height;

                        $H = '';
                        $W = '';

                        if ($AH < 210 && $AW < 170) {
                            
                        }

                        if ($AH > 210 && $AW < 170) {
                            $H = 210;
                            $W = $AW * ((210 * 100) / $AH) / 100;

                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }

                        if ($AH < 210 && $AW > 170) {
                            $W                  = 170;
                            $H                  = $AH * ((170 * 100) / $AW) / 100;
                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }

                        if ($AH > 210 && $AW > 170) {
                            $H = 210;
                            $W = $AW * ((210 * 100) / $AH) / 100;

                            $WTmp = $W;
                            if ($W > 170) {
                                $W = 170;
                                $H = $H * ((170 * 100) / $WTmp) / 100;
                            }

                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }
                    }
                }
            } else {
                $error = "Table Doesnt Exists";
                $this->view->assign('error', $error);
            }
            // return $sValues;
            // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
            $statement1 = Doctrine_Manager::getInstance()->closeConnection($statement1);
        } catch (PDOException $e) {
            // echo 'Caught exception: ', $e->getMessage(), "\n";
            $error = $e->getMessage()."\n";
            $this->view->assign('error', $error);
            // die;
        }

        return $sValues;
    }

    public function getMiniShopBlockProductsProductLinker($args)
    {
        // echo "<pre>"; print_r($args); echo "</pre>";
        $shop_id     = $args ['shop_id'];
        $serviceType = $args ['type'];

        $obj = DBUtil::selectObjectByID('zselex_shop_owners', $shop_id,
                'shop_id');

        // echo "<pre>"; print_r($obj); echo "</pre>"; exit;

        $owner_id  = $obj ['user_id'];
        $ownerName = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                $args      = array(
                'shop_id' => $shop_id
        ));
        // echo $ownerName;

        $sql = "SELECT a.shop_id , d.shoptype 
                 FROM zselex_shop a , zselex_shop_owners b , zselex_serviceshop c , zselex_minishop d
                 WHERE b.user_id=$owner_id AND c.shop_id=b.shop_id AND a.shop_id=c.shop_id AND c.type='minishop' AND d.shop_id=c.shop_id";

        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetchAll();
        // echo "<pre>"; print_r($result); echo "</pre>";
        foreach ($result as $config) {

            // $shopType = $config['shoptype_id'];
            $shopType = $config ['shoptype'];
            $shopsId  = $config ['shop_id'];

            if ($shopType == 'zSHOP') { // ZEN-CART
                $zShop = DBUtil::selectObjectByID('zselex_zenshop', $shopsId,
                        'shop_id');

                $dnName  = (!empty($zShop ['dbname']) ? $zShop ['dbname'] : '');
                $dnUser  = (!empty($zShop ['username']) ? $zShop ['username'] : 'root');
                $dbPswrd = (!empty($zShop ['password']) ? $zShop ['password'] : '');
                $dbHost  = (!empty($zShop ['hostname']) ? $zShop ['hostname'] : 'localhost');

                // $dsn = "mysql:dbname='" . $dnName . "';host='" . $dbHost . "'";
                // echo $dsn; exit;

                $dsn         = "mysql:dbname=$dnName;host=$dbHost";
                $user        = $dnUser;
                $password    = $dbPswrd;
                $tableprefix = (!empty($zShop ['table_prefix']) ? $zShop ['table_prefix']
                            : '');

                $prdwhere = "b.products_name!='' AND a.products_image!='' AND a.manufacturers_id!=''";
                $prdwhere = "a.products_status=1";
                $prdquery = "SELECT a.products_id , a.products_image , a.products_price , LEFT(b.products_name, 20) AS products_name  , LEFT(b.products_description, 20) AS products_description,
                                    mn.manufacturers_name
                                    FROM  ".$tableprefix."products a 
                                    LEFT JOIN ".$tableprefix."products_description b ON b.products_id=a.products_id
                                    LEFT JOIN ".$tableprefix."manufacturers mn ON mn.manufacturers_id=a.manufacturers_id
                                    WHERE ".$prdwhere."
                                    group by a.products_id 
                                   ";

                $dbh        = new PDO($dsn, $user, $password);
                $statement1 = Doctrine_Manager::getInstance()->connection($dbh);
                $results    = $statement1->execute($prdquery);
                $sValues    = $results->fetchAll();
                // echo "<pre>"; print_r($sValues); echo "</pre>";
                // $imagearr = array('imageval'=>'lower');
                $list       = array();
                for ($i = 0; $i < count($sValues); $i ++) {
                    $sValues [$i] ['domainname'] = $zShop ['domain'];
                    $sValues [$i] ['adId']       = $config ['advertise_id'];
                    $sValues [$i] ['maxviews']   = $config ['maxviews'];
                    $sValues [$i] ['totalviews'] = $config ['totalviews'];
                    $sValues [$i] ['maxclicks']  = $config ['totalclicks'];
                    $sValues [$i] ['SHOPTYPE']   = $shopType;

                    $priceexplode = explode('.',
                        $sValues [$i] ['products_price']);
                    if (strlen($priceexplode [0]) >= 4) { // more than 1000
                        $p1 = substr_replace($priceexplode [0], ".", 1, 0);
                        $p2 = substr_replace($priceexplode [1], ",", 2);
                        $p2 = substr($p2, 0, - 1);

                        $sValues [$i] ['PRICE'] = $p1.','.$p2;
                    } else {
                        $newstring              = substr_replace($priceexplode [1],
                            '', '2');
                        $sValues [$i] ['PRICE'] = $priceexplode [0].','.$newstring;
                    }

                    // echo $sValues[$i]['PRICE'] . '<br>';

                    if ($sValues [$i] ['products_image'] != '') {
                        list($width, $height, $type, $attr) = @getimagesize('http://'.$zShop ['domain'].'/images/'.str_replace(" ",
                                    "%20", $sValues [$i] ['products_image']));
                        $AW = $width;
                        $AH = $height;
                        $H  = '';
                        $W  = '';

                        if ($AH < 210 && $AW < 170) {

                        }
                        if ($AH > 210 && $AW < 170) {
                            $H = 210;
                            $W = $AW * ((210 * 100) / $AH) / 100;

                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }
                        if ($AH < 210 && $AW > 170) {
                            $W                  = 170;
                            $H                  = $AH * ((170 * 100) / $AW) / 100;
                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }
                        if ($AH > 210 && $AW > 170) {
                            $H = 210;
                            $W = $AW * ((210 * 100) / $AH) / 100;

                            $WTmp = $W;
                            if ($W > 170) {
                                $W = 170;
                                $H = $H * ((170 * 100) / $WTmp) / 100;
                            }

                            $sValues [$i] ['H'] = round($H);
                            $sValues [$i] ['W'] = round($W);
                        }
                    }
                }

                $allValues [] = $sValues;
                // return $sValues;
                // echo "<pre>"; print_r($sValues); echo "</pre>"; exit;
                $statement1   = Doctrine_Manager::getInstance()->closeConnection($statement1);
            }  // /
            elseif ($shopType == 'iSHOP') { // INTERNAL-SHOP
                $iprdctQry = "SELECT p.* , LEFT(p.prd_description, 20) AS prd_description , s.theme AS shopTheme , u.uname , s.shop_name
                        FROM zselex_products p , zselex_shop s 
                        LEFT JOIN zselex_shop_owners ow ON ow.shop_id=s.shop_id
                        LEFT JOIN users u ON u.uid = ow.user_id
                        LEFT JOIN zselex_serviceshop sv ON sv.shop_id = s.shop_id AND sv.type='paybutton'
                        WHERE p.shop_id='".$shopsId."' AND p.shop_id=s.shop_id";
                $statement = Doctrine_Manager::getInstance()->connection();
                $results   = $statement->execute($iprdctQry);
                $iproducts = $results->fetchAll();

                // $output["data"] = $iprdctQry;
                // AjaxUtil::output($output);

                for ($i = 0; $i < count($iproducts); $i ++) {
                    $iproducts [$i] ['adId']           = $config ['advertise_id'];
                    $iproducts [$i] ['products_name']  = $iproducts [$i] ['product_name'];
                    $iproducts [$i] ['products_id']    = $iproducts [$i] ['product_id'];
                    $iproducts [$i] ['products_image'] = $iproducts [$i] ['prd_image'];
                    $iproducts [$i] ['PRICE']          = $iproducts [$i] ['prd_price'];
                    $iproducts [$i] ['SHOPTYPE']       = $shopType;
                    $iproducts [$i] ['SHOPID']         = $shopsId;
                    $iproducts [$i] ['THEME']          = $iproducts [$i] ['shopTheme'];

                    // echo $ownerName . '<br>'; exit;

                    if ($iproducts [$i] ['products_image'] != '') {
                        // echo "hellooo"; exit;
                        list($width, $height, $type, $attr) = @getimagesize(pnGetBaseURL()."zselexdata/".$ownerName."/products/".str_replace(" ",
                                    "%20", $iproducts [$i] ['products_image']));
                        $AW = $width;
                        $AH = $height;
                        $H  = '';
                        $W  = '';
                        if ($AH < 210 && $AW < 170) {

                        }
                        if ($AH > 210 && $AW < 170) {
                            $H                    = 210;
                            $W                    = $AW * ((210 * 100) / $AH) / 100;
                            $iproducts [$i] ['H'] = round($H);
                            $iproducts [$i] ['W'] = round($W);
                        }
                        if ($AH < 210 && $AW > 170) {
                            $W                    = 170;
                            $H                    = $AH * ((170 * 100) / $AW) / 100;
                            $iproducts [$i] ['H'] = round($H);
                            $iproducts [$i] ['W'] = round($W);
                        }
                        if ($AH > 210 && $AW > 170) {
                            $H    = 210;
                            $W    = $AW * ((210 * 100) / $AH) / 100;
                            $WTmp = $W;
                            if ($W > 170) {
                                $W = 170;
                                $H = $H * ((170 * 100) / $WTmp) / 100;
                            }
                            $iproducts [$i] ['H'] = round($H);
                            $iproducts [$i] ['W'] = round($W);
                        }
                    }
                }
                $allValues [] = $iproducts;
            }
            // echo "<pre>"; print_r($allValues); echo "</pre>";
        } // /////

        $aItem = array();
        foreach ($allValues as $productz) {
            foreach ($productz as $item) {
                $aItem [] = $item;
            }
        }

        // echo "<pre>"; print_r($aItem); echo "</pre>";
        return $aItem;
    }

    public function getShopArticleEvents($args)
    {
        $shop_id = $args ['shop_id'];
        $sql     = "SELECT a.* FROM news a , zselex_shop_news b WHERE a.sid=b.news_id AND b.shop_id='".$shop_id."'";
        $query   = DBUtil::executeSQL($sql);
        $result  = $query->fetchAll();
        return $result;
    }

    public function getShopEvents($args)
    {
        $shop_id  = $args ['shop_id'];
        $extrasql = $args ['sql'];
        $sql      = "SELECT * FROM zselex_shop_events
                     WHERE shop_id='".$shop_id."'"." ".$extrasql;
        $res      = DBUtil::executeSQL($sql, $args ['start'] - 1,
                $args ['itemsperpage']);
        $result   = DBUtil::marshallObjects($res);

        $rescount = DBUtil::executeSQL($sql);
        $count    = $rescount->rowCount();

        $returnarray = array(
            'items' => $result,
            'count' => $count
        );

        return $returnarray;
    }

    public function getServiceList($args)
    {
        $shop_id  = $args ['shop_id'];
        $extrasql = $args ['sql'];
        $shoptype = ModUtil::apiFunc('ZSELEX', 'admin', 'shopType',
                $typeargs = array(
                'shop_id' => $shop_id
        ));
        $shoptype = $shoptype ['shoptype'];
        // echo $shoptype; exit;
        // bundle_id condition in this query is to avoid displaying the bundle service in whichs item(service) is already purchased
        /*
         * $sql = "SELECT * FROM zselex_plugin
         * WHERE status='1'
         * AND bundle_id NOT IN
         * (SELECT bundle_id FROM zselex_service_bundle_items
         * WHERE plugin_id IN(SELECT plugin_id FROM zselex_serviceshop WHERE shop_id=$shop_id AND bundle_id=0))
         * ORDER BY plugin_name ASC";
         *
         */

        $sql    = "SELECT * FROM zselex_plugin
                WHERE status='1'
                ORDER BY IF(sort_order = 0, 999999999, sort_order) ASC";
        $res    = DBUtil::executeSQL($sql, $args ['start'] - 1,
                $args ['itemsperpage']);
        $result = DBUtil::marshallObjects($res);

        // get the total count
        $rescount = DBUtil::executeSQL($sql);
        $count    = $rescount->rowCount();

        $returnarray = array(
            'items' => $result,
            'count' => $count
        );

        return $returnarray;
    }

    public function demoCheck($args)
    {

        // $sql = "SELECT ";
    }

    /**
     * Difference between two dates
     * 
     * @param type $start
     * @param type $end
     * @return type
     */
    public function dateDiff($start, $end)
    { // returns number of days between two dates
        $start_ts = strtotime($start);

        $end_ts = strtotime($end);

        $diff = $end_ts - $start_ts;

        return round($diff / 86400);
    }

    public function getServiceCart($args)
    {
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');

        $user_id = $args ['user_id'];

        /*
         * $serviceCart = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinArray', $args = array(
         * 'table' => 'zselex_basket a',
         * 'fields' => array(
         * 'a.*,c.shop_name,b.bundle_name'
         * ),
         * 'where' => array(
         * "a.user_id=$user_id"
         * ),
         * 'joins' => array(
         * "LEFT JOIN zselex_service_bundles b ON b.bundle_id=a.bundle_id",
         * "LEFT JOIN zselex_shop c ON c.shop_id=a.shop_id"
         * )
         * ));
         */

        $serviceCart = $repo->getAll(array(
            'entity' => 'ZSELEX_Entity_ServiceBasket',
            'fields' => array(
                'a.basket_id',
                'a.type',
                'a.qty_based',
                'a.original_price',
                'a.price',
                'a.subtotal',
                'a.service_status',
                'a.quantity',
                'c.shop_name',
                'b.bundle_name',
                'b.bundle_id',
                'd.plugin_id'
            ),
            'joins' => array(
                'JOIN a.bundle b',
                'LEFT JOIN a.shop c',
                'LEFT JOIN a.plugin d'
            ),
            'where' => array(
                'a.user_id' => $user_id
            )
        ));
        // echo "<pre>"; print_r($serviceCart); echo "</pre>"; exit;
        return $serviceCart;
    }

    public function getServiceDemoDate($args)
    {
        $sql = "SELECT start_date FROM zselex_service_demo
                WHERE shop_id='".$args [shop_id]."' AND plugin_id='".$args [plugin_id]."' AND type='".$args [type]."'";

        // $sql = "SELECT timer_date FROM zselex_serviceshop
        // WHERE shop_id='" . $args[shop_id] . "' AND plugin_id='" . $args[plugin_id] . "' AND type='" . $args[type] . "' AND service_status='" . $args[demo] . "'";
        // echo $sql;
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $date   = $result ['start_date'];
        return $date;
    }

    public function demoDateCheck1($args)
    {
        $sql = "SELECT a.start_date , b.demoperiod , a.quantity , a.timer_days
                FROM zselex_service_demo a
                LEFT JOIN zselex_plugin b ON b.plugin_id=a.plugin_id
                WHERE a.shop_id='".$args [shop_id]."' AND a.type='".$args [type]."'";

        // echo $sql; exit;
        $query      = DBUtil::executeSQL($sql);
        $result     = $query->fetch();
        $date       = $result ['start_date'];
        // $demoperiod = $result['demoperiod'];
        $demoperiod = $result ['timer_days'];
        // echo $demoperiod; exit;
        $quantity   = $result ['quantity'];
        $today      = date("Y-m-d");

        $diff  = $this->dateDiff($start = $date, $end   = $today);
        // echo $diff; exit;

        if ($demoperiod >= $diff) {
            $isDemo = 1; // in demo
        } else {
            $isDemo = 0; // out of demo
        }

        $returnarray = array(
            'demo' => $isDemo,
            'demodate' => $date,
            'quantity' => $quantity,
            'demoperiod' => $demoperiod,
            'diff' => $diff
        );

        // return $isDemo;
        return $returnarray;
    }

    public function paidDateCheck($args)
    {

        /*
         * $sql = "SELECT a.timer_date , a.quantity , a.timer_days , a.qty_based
         * FROM zselex_serviceshop a
         * LEFT JOIN zselex_plugin b ON b.plugin_id=a.plugin_id
         * WHERE a.shop_id='" . $args[shop_id] . "' AND a.type='" . $args[type] . "'";
         *
         * //echo $sql; exit;
         * $query = DBUtil::executeSQL($sql);
         * $result = $query->fetch();
         *
         */
        $today   = date("Y-m-d");
        $sqlArgs = array(
            'entity' => 'ZSELEX_Entity_ServiceShop',
            'fields' => array(
                'a.timer_date',
                'a.quantity',
                'a.timer_days',
                'a.qty_based',
                "date_diff('$today' , a.timer_date) days"
            ),
            'where' => array(
                'a.shop' => $args ['shop_id'],
                'a.type' => $args ['type']
            )
            )
        // 'exit'=>true
        ;
        $result  = $this->entityManager->getRepository('ZSELEX_Entity_ServiceShop')->get($sqlArgs);

        // echo "<pre>"; print_r($result); echo "</pre>";
        $date        = $result ['timer_date'];
        // $demoperiod = $result['demoperiod'];
        $paid_period = $result ['timer_days'];
        // echo $demoperiod; exit;
        $quantity    = $result ['quantity'];
        $qty_based   = $result ['qty_based'];
        $today       = date("Y-m-d");

        // $diff = $this->dateDiff($start = $date, $end = $today);
        $diff = $result ['days'];
        // echo $diff; exit;

        if ($paid_period >= $diff) {
            $isRunning = 1; // running
        } else {
            $isRunning = 0; // expired!
        }

        $returnarray = array(
            'running' => $isRunning,
            'paiddate' => $date,
            'quantity' => $quantity,
            'paidperiod' => $paid_period,
            'timer_days' => $date,
            'qty_based' => $qty_based,
            'diff' => $diff
        );

        // return $isDemo;
        return $returnarray;
    }

    public function paidDateCheckBundle($args)
    {
        $sql = "SELECT a.timer_date , a.quantity , a.timer_days
                FROM zselex_serviceshop_bundles a
                WHERE a.shop_id='".$args [shop_id]."' AND a.bundle_id='".$args [bundle_id]."'";

        // echo $sql; exit;
        $query       = DBUtil::executeSQL($sql);
        $result      = $query->fetch();
        $date        = $result ['timer_date'];
        // $demoperiod = $result['demoperiod'];
        $paid_period = $result ['timer_days'];
        // echo $demoperiod; exit;
        $quantity    = $result ['quantity'];
        $qty_based   = $result ['qty_based'];
        $today       = date("Y-m-d");

        $diff  = $this->dateDiff($start = $date, $end   = $today);
        // echo $diff; exit;

        if ($paid_period >= $diff) {
            $isRunning = 1; // running
        } else {
            $isRunning = 0; // expired!
        }

        $returnarray = array(
            'running' => $isRunning,
            'paiddate' => $date,
            'quantity' => $quantity,
            'paidperiod' => $paid_period,
            'timer_days' => $date,
            'qty_based' => $qty_based,
            'diff' => $diff
        );

        // return $isDemo;
        return $returnarray;
    }

    public function demoDateCheck2($args)
    {
        $sql = "SELECT a.start_date , b.demoperiod , a.quantity
                FROM zselex_service_demo a
                LEFT JOIN zselex_plugin b ON b.plugin_id=a.plugin_id
                WHERE a.shop_id='".$args [shop_id]."' AND a.type='".$args [type]."'";

        // echo $sql; exit;
        $query      = DBUtil::executeSQL($sql);
        $result     = $query->fetch();
        $date       = $result ['start_date'];
        $demoperiod = $result ['demoperiod'];
        $quantity   = $result ['quantity'];
        $today      = date("Y-m-d");

        $diff  = $this->dateDiff($start = $date, $end   = $today);
        // echo $diff; exit;

        if ($demoperiod >= $diff) {
            $isDemo = 1; // in demo
        } else {
            $isDemo = 0; // out of demo
        }

        $returnarray = array(
            'demo' => $isDemo,
            'demodate' => $date,
            'quantity' => $quantity,
            'demoperiod' => $demoperiod,
            'diff' => $diff
        );

        // return $isDemo;
        return $returnarray;
    }

    public function demoDateCheck($args)
    {
        $sql = "SELECT a.start_date , b.demoperiod , a.quantity , a.timer_days
                FROM zselex_service_demo a
                LEFT JOIN zselex_plugin b ON b.plugin_id=a.plugin_id
                WHERE a.shop_id='".$args [shop_id]."' AND a.plugin_id='".$args [plugin_id]."' AND a.type='".$args [type]."'";

        // echo $sql; exit;
        // echo $sql . '<br>';
        $query               = DBUtil::executeSQL($sql);
        $result              = $query->fetch();
        $date                = $result ['start_date'];
        // $demoperiod = $result['demoperiod'];
        $demoperiod          = $result ['timer_days'];
        $quantity            = $result ['quantity'];
        $demo_existing_timer = $result ['timer_days'];
        $today               = date("Y-m-d");

        $diff  = $this->dateDiff($start = $date, $end   = $today);
        // echo $diff; exit;

        if ($demoperiod >= $diff) {
            $isDemo = 1; // in demo
        } else {
            $isDemo = 0; // out of demo
        }

        $returnarray = array(
            'demo' => $isDemo,
            'demodate' => $date,
            'quantity' => $quantity,
            'timer_days' => $demo_existing_timer
        );

        // return $isDemo;
        return $returnarray;
    }

    public function paidExistCheck($args)
    {
        $table = $args [table];
        if ($table == 'zselex_service_config') {
            $sql = "SELECT * FROM zselex_service_config
                WHERE shop_id='".$args [shop_id]."' AND plugin_d='".$args [plugin_id]."' AND type='".$args [type]."' AND service_status='2'";
        } else {
            $sql = "SELECT * FROM zselex_serviceshop
                WHERE shop_id='".$args [shop_id]."' AND plugin_id='".$args [plugin_id]."' AND type='".$args [type]."' AND service_status='2'";
        }

        // echo $sql; exit;
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();

        $count          = $query->rowCount();
        $quantity       = $result ['quantity'];
        $qty_based      = $result ['qty_based'];
        $used           = $result ['availed'];
        $service_status = $result ['service_status'];

        if ($quantity > $used) {
            $qtyLeft = $quantity - $used;
        } else {
            $qtyLeft = 0;
        }

        $returnarray = array(
            'count' => $count,
            'qtyLeft' => $qtyLeft,
            'quantity' => $quantity,
            'qty_based' => $qty_based
        );

        return $returnarray;
    }

    public function serviceOverCheck($args)
    {
        $id     = $args ['id'];
        $sql    = "SELECT * FROM zselex_serviceshop WHERE id=$id";
        $query  = DBUtil::executeSQL($sql);
        $result = $query->fetch();
        $count  = $query->rowCount();

        $message = '';
        if ($count > 0) {
            $service_status = $result ['service_status'];
            $top_bundle     = $result ['top_bundle'];
            $bundle_id      = $result ['bundle_id'];
            if ($service_status == self::DEMO) { // demo
                $serviceIs = "Demo";
                $demodays  = ModUtil::apiFunc('ZSELEX', 'admin',
                        'demoDateCheck',
                        $args      = array(
                        'type' => $result ['type'],
                        'plugin_id' => $result ['plugin_id'],
                        'user_id' => $result ['user_id'],
                        'shop_id' => $result ['shop_id'],
                        'demo' => '1'
                ));
                // echo "<pre>"; print_r($demodays); exit;
                if ($demodays ['demo'] == 1) {
                    $message = $this->__("This service is currently running as demo and is still valid untill its demo time gets over");
                    $perform = 'inactive';
                } else {
                    $perform = 'delete';
                }
            } elseif ($service_status == self::PAID) {
                $serviceIs = "Paid";
                $quantity  = $result ['quantity'];
                $used      = $result ['availed'];
                $qty_based = $result ['qty_based'];
                if ($qty_based == 1) {
                    if ($quantity > $used) {
                        $qtyLeft = $quantity - $used;
                        $message = $this->__("You have paid for this service and ".$qtyLeft." usage is still left for it");
                        $perform = 'inactive';
                    }
                } else {
                    $message = $this->__("You have paid for this service");
                    $perform = 'delete';
                }
            }
        }

        $returnArray = array(
            'count' => $count,
            'serviceIs' => $serviceIs,
            'message' => $message,
            'result' => $result,
            'top_bundle' => $top_bundle,
            'bundle_id' => $bundle_id,
            'action' => $perform
        );

        return $returnArray;
    }

    public function deleteWhere($args)
    {
        $table = $args ['table'];
        $where = $args ['where'];

        // echo $where; exit;
        if (DBUtil::deleteWhere($table, $where)) {
            return true;
        } else {
            return false;
        }
    }

    public function filesize_recursive($path)
    {
        error_reporting(0);
        if (!file_exists($path)) {
            return 0;
        }
        if (is_file($path)) {
            return filesize($path);
        }
        $ret = 0;
        foreach (glob($path."/*") as $fn) {
            $ret += $this->filesize_recursive($fn);
        }
        return $ret;
    }

    public function ownerShopDetails()
    {
        $loguser = UserUtil::getVar('uid');
        $item    = array();
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_Bundle');
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD)) {
            $item = ModUtil::apiFunc('ZSELEX', 'user', 'selectRow',
                    array(
                    'table' => 'zselex_shop a , zselex_shop_owners b',
                    'fields' => array(
                        'a.shop_id',
                        'a.shop_name'
                    ),
                    'where' => array(
                        "b.user_id=$loguser",
                        "a.shop_id=b.shop_id"
                    ),
                    'orderby' => "CASE WHEN b.main = '1'
              THEN b.main END DESC ,
              CASE WHEN b.main = '0'
              THEN a.shop_id  END ASC"
            ));

            /*
             * $item = $repo->get(array('entity' => 'ZSELEX_Entity_ShopOwner',
             * 'fields' => array('b.shop_id', 'b.shop_name'),
             * 'joins' => array('a.shop b'),
             * 'where' => array('a.user_id' => $loguser),
             * 'orderby' => 'CASE WHEN a.main = 1
             * THEN a.main END DESC ,
             * CASE WHEN a.main = 0
             * THEN a.shop_id END ASC'
             * ));
             */
            // exit;
        } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT)) {
            $item = ModUtil::apiFunc('ZSELEX', 'user', 'selectJoinRow',
                    array(
                    'table' => 'zselex_shop_admins a',
                    'fields' => array(
                        'a.shop_id as shop_id',
                        'b.shop_name'
                    ),
                    'joins' => array(
                        'LEFT JOIN zselex_shop b ON b.shop_id=a.shop_id'
                    ),
                    'where' => array(
                        "a.user_id=$loguser"
                    ),
                    'orderby' => "CASE WHEN b.main = '1' 
                                      THEN b.main END DESC ,
                                      CASE WHEN b.main = '0' 
                                      THEN b.shop_id END ASC"
            ));

            // echo "<pre>"; print_r($item); echo "</pre>"; exit;
        }

        return $item;
    }

    public function display_size($size)
    {
        $sizes = array(
            'B',
            'kB',
            'MB',
            'GB',
            'TB',
            'PB',
            'EB',
            'ZB',
            'YB'
        );
        if ($retstring === null) {
            $retstring = '%01.2f %s';
        }
        $lastsizestring = end($sizes);
        foreach ($sizes as $sizestring) {
            if ($size < 1024) {
                break;
            }
            if ($sizestring != $lastsizestring) {
                $size /= 1024;
            }
        }
        if ($sizestring == $sizes [0]) {
            $retstring = '%01d %s';
        } // Bytes aren't normally fractional
        return sprintf($retstring, $size, $sizestring);
    }

    public function format_size($size)
    {
        $units = explode(' ', 'B KB MB GB TB PB');
        $mod   = 1024;

        for ($i = 0; $size > $mod; $i ++) {
            $size /= $mod;
        }

        $endIndex = strpos($size, ".") + 3;

        // return substr($size, 0, $endIndex) . ' ' . $units[$i];

        return substr($size, 0, $endIndex);
    }

    public function byteconvert($input)
    {
        $input = $input."MB";
        preg_match('/(\d+)(\w+)/', $input, $matches);
        $type  = strtolower($matches [2]);
        switch ($type) {
            case "b" :
                $output = $matches [1];
                break;
            case "kb" :
                $output = $matches [1] * 1024;
                break;
            case "mb" :
                $output = $matches [1] * 1024 * 1024;
                break;
            case "gb" :
                $output = $matches [1] * 1024 * 1024 * 1024;
                break;
            case "tb" :
                $output = $matches [1] * 1024 * 1024 * 1024;
                break;
        }
        return $output;
    }

    /**
     * Check DiskQuoata 
     *
     * @param array $args
     * @return int
     */
    public function checkDiskquota($args)
    {
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $msg           = '';
        $limitover     = 1;
        $error         = 0;
        $returnArray   = array();
        $modvariable   = $this->getVars();
        $diskquotasize = !empty($modvariable ['diskquotaitem']) ? $modvariable ['diskquotaitem']
                : 1;

        if (!empty($args ['shop_id'])) {
            $shop_id = $args ['shop_id'];
            $owner   = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                    array(
                    'shop_id' => $shop_id
            ));
            // $ownername = $owner['uname'];;
            $ownerid = $owner ['uid'];
        } /*
         * else if (!empty($args['ownername'])) {
         * $ownername = $args['ownername'];
         * }
         */ elseif (!empty($args ['owner_id'])) {
            $ownerid = $args ['owner_id'];
        } else {
            $error ++;
            if ($error == 1) {
                $msg = $this->__('Error! Internal error. ShopId or ownername missing in CheckDiskQuota!');
            }
        }

        // echo "hellooo3"; exit;
        /*
         * $getAllShops = $repo->getAll(array(
         * 'entity' => 'ZSELEX_Entity_ShopOwner',
         * 'fields' => array('b.shop_id'),
         * 'joins' => array('JOIN a.shop b'),
         * 'where' => array('a.user_id' => $ownerid)
         * ));
         */
        // echo "ownerId :" . $ownerid;

        if ($ownerid != "") {
            $result = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceShop',
                'fields' => array(
                    'SUM(a.quantity) AS quantityallshops'
                ),
                'where' => array(
                    'a.type' => 'diskquota'
                ),
                'subquery' => array(
                    "a.shop IN(SELECT c.shop_id FROM ZSELEX_Entity_ShopOwner b JOIN b.shop c WHERE b.user_id=$ownerid)"
                )
            ));
            // echo "<pre>"; print_r($result); echo "</pre>";
            // $count = $query->rowCount();
            $count  = $result ['quantityallshops'];
            // echo "count : " . $count;
            if ($count < 1) {
                $error ++;
                if ($error == 1) {
                    $msg = $this->__('You have no diskquota. You need to aquire it to continue!');
                }
            }
        } else {
            $error ++;
            $ownerfoldersize = '0';
            $limitover       = 0;
            if ($error == 1) {
                $msg = __("Owner not assigned!");
            }
        }

        $ownerfoldersize = ModUtil::apiFunc('ZSELEX', 'service',
                'ownerFolderSize',
                $args            = array(
                'owner_id' => $ownerid
        ));

        // $sizeused = $this->filesize_recursive($fspath);
        $sizeused = $ownerfoldersize;

        $diskquotabytes = $this->byteconvert($diskquotasize); // convert the disquota to bytes
        // echo $diskquotabytes;
        $sizelimit      = $result ['quantityallshops'] * $diskquotabytes;
        if ($sizeused > $sizelimit) {
            $error ++;
            if ($error == 1) {
                $msg = __("Your diskquota is exceeded. Please upgrade to continue!");
            }
            $limitover = 0;
        }
        // return $result;

        $returnArray = array(
            'error' => $error,
            'sizeused' => $sizeused,
            'sizeperquantity' => $diskquotabytes,
            'sizelimit' => $sizelimit,
            'count' => $count,
            'message' => $msg,
            'limitover' => $limitover
        );
        // echo "<pre>"; print_r($returnArray); echo "</pre>";

        return $returnArray;
    }

    public function checkDiskquota1($args)
    {
        $repo = $this->entityManager->getRepository('ZSELEX_Entity_Shop');

        $msg           = '';
        $limitover     = 1;
        $error         = 0;
        $returnArray   = array();
        $modvariable   = $this->getVars();
        $diskquotasize = !empty($modvariable ['diskquotaitem']) ? $modvariable ['diskquotaitem']
                : 1;

        if (!empty($args ['shop_id'])) {
            $shop_id   = $args ['shop_id'];
            $ownername = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwner',
                    $args      = array(
                    'shop_id' => $shop_id
            ));
        } elseif (!empty($args ['ownername'])) {
            $ownername = $args ['ownername'];
        } else {
            $error ++;
            if ($error == 1) {
                $msg = $this->__('Error! Internal error. ShopId or ownername missing in CheckDiskQuota!');
            }
        }

        /*
         * $ownerid = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerId', $args = array(
         * 'ownername' => $ownername
         * ));
         */

        $owner   = ModUtil::apiFunc('ZSELEX', 'admin', 'getOwnerInfo',
                array(
                'shop_id' => $shop_id
        ));
        $ownerid = $owner ['uid'];
        // echo "ownerId :" . $ownerid;

        if ($ownerid != "") {
            /*
             * $sql = "SELECT SUM(quantity) AS quantityallshops
             * FROM zselex_serviceshop
             * WHERE owner_id=$ownerid AND type='diskquota'";
             */
            /*
             * $sql = "SELECT SUM(quantity) AS quantityallshops
             * FROM zselex_serviceshop
             * WHERE type='diskquota'
             * AND shop_id IN(SELECT shop_id FROM zselex_shop_owners WHERE user_id=$ownerid)";
             * // echo $sql;
             * $query = DBUtil::executeSQL($sql);
             * $result = $query->fetch();
             */

            $result = $repo->get(array(
                'entity' => 'ZSELEX_Entity_ServiceShop',
                'fields' => array(
                    'SUM(a.quantity) AS quantityallshops'
                ),
                'where' => array(
                    'a.type' => 'diskquota'
                ),
                'subquery' => array(
                    "a.shop IN(SELECT c.shop_id FROM ZSELEX_Entity_ShopOwner b JOIN b.shop c WHERE b.user_id=$ownerid)"
                )
            ));
            // echo "<pre>"; print_r($result); echo "</pre>";
            // $count = $query->rowCount();
            $count  = $result ['quantityallshops'];
            // echo "count : " . $count;
            if ($count < 1) {
                $error ++;
                if ($error == 1) {
                    $msg = $this->__('You have no diskquota. You need to aquire it to continue!');
                }
            }
        }

        if ($ownername != "") {
            $fspath = $_SERVER ['DOCUMENT_ROOT']."/zselexdata/".$ownername;
            if ($_SERVER ['SERVER_NAME'] == 'localhost') {
                $fspath = "zselexdata/".$ownername;
            }
            // echo $this->filesize_recursive($fspath);
            $ownerfoldersize = $this->display_size($this->filesize_recursive($fspath));
        } else {
            $error ++;
            $ownerfoldersize = '0';
            $limitover       = 0;
            if ($error == 1) {
                $msg = __("Owner not assigned!");
            }
        }

        $sizeused = $this->filesize_recursive($fspath);

        $diskquotabytes = $this->byteconvert($diskquotasize); // convert the disquota to bytes
        // echo $diskquotabytes;
        $sizelimit      = $result ['quantityallshops'] * $diskquotabytes;
        if ($sizeused > $sizelimit) {
            $error ++;
            if ($error == 1) {
                $msg = __("Your diskquota is exceeded. Please upgrade to continue!");
            }
            $limitover = 0;
        }
        // return $result;

        $returnArray = array(
            'error' => $error,
            'sizeused' => $sizeused,
            'sizeperquantity' => $diskquotabytes,
            'sizelimit' => $sizelimit,
            'count' => $count,
            'message' => $msg,
            'limitover' => $limitover
        );
        return $returnArray;
    }

    public function getShopCategories($args)
    {
        $shop_id       = $args ['shop_id'];
        $getArgs       = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'b.category_name'
            ),
            'joins' => array(
                'JOIN a.shop_to_category b'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        );
        $getCategories = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getAll($getArgs);
        $result        = $getCategories;
        // echo "<pre>"; print_r($getCategories); echo "</pre>";

        return $result;
    }

    public function getShopBranches($args)
    {
        $shop_id = $args ['shop_id'];

        $getArgs      = array(
            'entity' => 'ZSELEX_Entity_Shop',
            'fields' => array(
                'b.branch_name'
            ),
            'joins' => array(
                'JOIN a.shop_to_branch b'
            ),
            'where' => array(
                'a.shop_id' => $shop_id
            )
        );
        $get_branches = $this->entityManager->getRepository('ZSELEX_Entity_Shop')->getAll($getArgs);
        $result       = $get_branches;
        // echo "<pre>"; print_r($getCategories); echo "</pre>";

        return $result;
    }

    public function getMainShop($args)
    {
        $repo    = $this->entityManager->getRepository('ZSELEX_Entity_Shop');
        $user_id = $args ['user_id'];
        if (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADMIN) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_ADD)) {
            $item = $repo->getAll(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'fields' => array(
                    'a.shop_id',
                    'a.shop_name'
                ),
                'joins' => array(
                    'JOIN a.shop_owners b'
                ),
                'where' => array(
                    'b.user_id' => $user_id
                ),
                'orderby' => 'a.main DESC , a.shop_id ASC',
                'groupby' => 'a.shop_id'
            ));
            // exit;
        } elseif (!SecurityUtil::checkPermission('ZSELEX::', '::', ACCESS_ADD) && SecurityUtil::checkPermission('ZSELEX::',
                '::', ACCESS_EDIT)) {
            $item = $repo->getAll(array(
                'entity' => 'ZSELEX_Entity_Shop',
                'fields' => array(
                    'a.shop_id',
                    'a.shop_name'
                ),
                'joins' => array(
                    'JOIN a.shop_admins b'
                ),
                'where' => array(
                    'b.user_id' => $user_id
                ),
                'orderby' => 'a.main DESC , a.shop_id ASC',
                'groupby' => 'a.shop_id'
            ));
            //
        }
        // echo "<pre>"; print_r($item); echo "</pre>"; exit;

        return $item [0];
    }
}
// end class def
;
