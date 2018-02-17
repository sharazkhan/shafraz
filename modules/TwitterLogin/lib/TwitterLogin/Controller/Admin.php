<?php

/**
 * TwitterLogin
 */

/**
 * Administrator-initiated actions for the FConnect module.
 */
class TwitterLogin_Controller_Admin extends Zikula_AbstractController {

    /**
     * The main administration entry point.
     *
     * Redirects to the {@link modifyConfig()} function.
     *
     * @return void
     */
    public function main() {
        $this->redirect(ModUtil::url($this->name, 'admin', 'modifyConfig'));
    }

    /**
     * Modify configuration.
     *
     * Modify the configuration parameters of the module.
     *
     * @return string The rendered output of the modifyconfig template.
     *
     * @throws Zikula_Exception_Forbidden Thrown if the user does not have the appropriate access level for the function.
     */
    public function modifyconfig() {
        // Security check
        if (!SecurityUtil::checkPermission('TwitterLogin::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        // Assign all the module vars
        return $this->view->assign(ModUtil::getVar('TwitterLogin'))
                        ->fetch('twitter_admin_modifyconfig.tpl');
    }

    /**
     * Update the configuration.
     *
     * Save the results of modifying the configuration parameters of the module. Redirects to the module's main page
     * when completed.
     *
     * @return void
     *
     * @throws Zikula_Exception_Forbidden Thrown if the user does not have the appropriate access level for the function.
     */
    public function updateconfig() {
        // Security check
        if (!SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new Zikula_Exception_Forbidden();
        }

        // Confirm the forms authorisation key
        $this->checkCsrfToken();

        // set our module variables
        $appid = $this->request->getPost()->get('consumerkey', true);
        $this->setVar('consumerkey', $appid);

        $secretkey = $this->request->getPost()->get('consumersecret', false);
        $this->setVar('consumersecret', $secretkey);

        $redirecturi = $this->request->getPost()->get('redirecturi', false);
        $this->setVar('redirecturi', $redirecturi);

        // the module configuration has been updated successfuly
        $this->registerStatus($this->__('Done! Saved module configuration.'));

        // This function generated no output, and so now it is complete we redirect
        // the user to an appropriate page for them to carry on their work
        $this->redirect(ModUtil::url($this->name, 'admin', 'main'));
    }

}