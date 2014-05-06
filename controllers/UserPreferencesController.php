<?php

class UserPreferencesController extends BaseController {

    const MODULE_KEY = 'userPreferences';

    public function run(Resource $resource) {

    }

    private function flushAll($userId) {

    }

    private function encodeContents(array $contentArray) {
        return base64_encode(json_encode($contentArray));
    }
}