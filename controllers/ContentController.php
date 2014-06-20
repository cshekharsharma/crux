<?php
/**
 * Controller class for Content module.
 * This modules provide dynamic markup content when requested from client
 *
 * @author Chandra Shekhar <chandra.sharma@jabong.com>
 * @package controllers
 * @since Jun 20, 2014
 */
class ContentController extends AbstractController {

    const MODULE_KEY = "content";

    private $actionMapping = array(
        'changePassword' => 'getChangePasswordUI',
        'userPreferences' => 'getUserPreferenceUI'
    );

    public function __construct() {
        parent::__construct();
        $this->view = new ContentView();
    }

    /**
     * @see AbstractController::run()
     */
    public function run(Resource $resource) {
        $uriParams = $resource->getParams();
        $contentKey = $uriParams[Constants::INPUT_PARAM_ACTION];
        $this->getView()->setViewName($contentKey)->display();
    }
}