<?php
/**
 * Cetralized message store for the app.
 * 
 * @author Chandra Shekhar <shekharsharma@gmail.com>
 * @since Jun 14, 2014
 */
final class Messages {

    const SUCCESS_PASSWORD_CHANGED = 'Password successfully changed.';
    const SUCCESS_USER_CREATED = 'User Successfully Created !';
    const SUCCESS_UPDATE = 'Successfully Updated!';
    const SUCCESS_FILE_DELETION = 'File successfully deleted!';
    const SUCCESS_LOGIN = 'Login successful !';
    const SUCCESS_CODE_UPDATED = 'Source code successfully updated';
    const SUCCESS_CODE_SUBMITTED = 'Source code successfully submitted';
    const ERROR_CODE_UPDATION_FAILED = 'Source code couldn\'t be updated, Retry!';
    const ERROR_CODE_SUBMISSION_FAILED = 'Source code couldn\'t be submitted, Retry!';
    const ERROR_OPERATION_FAILED = 'Operation failed, Retry!';
    const ERROR_SOMETHING_WENT_WRONG = 'Something went wrong! Retry';
    const ERROR_AUTH_INVALID_USER_NAME = 'No user details exist with provided user name!';
    const ERROR_AUTH_INVALID_PASSWORD = 'Username and password do not match!';
    const ERROR_AUTH_USER_INACTIVE = 'User account is inactive, please contact administrator!';
    const ERROR_USER_ALREADY_EXISTS = 'User with this name already exists, Retry with another name';
    const ERROR_WRONG_PASSWORD = 'Wrong password provided';
    const ERROR_USER_NOT_LOGGED_IN = 'Please login before the action';
    const ERROR_SOMETHING_WRONG = 'Oops! Something went wrong! Please refresh the page and try again!';
    const ERROR_RESOURCE_NOT_FOUND = 404;
    const ERROR_USERNAME_OR_PASSWORD_EMPTY = 'UserName & Password can\'t be empty!';
    const ERROR_FILE_DELETION = 'File could not be deleted, Retry!';
    const UPLOAD_INVALID_FILE_TYPE = 'File upload encountered an invalid file type.';
}