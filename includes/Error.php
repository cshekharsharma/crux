<?php

class Error {
    
    const ERR_TYPE_FATAL = 1;
    const ERR_TYPE_DISPLAY = 2;
    
    const AUTH_INVALID_USER_NAME = 'No user details exist with provided user name!';
    const AUTH_INVALID_PASSWORD = 'Username and password do not match!';
    const AUTH_USER_INACTIVE = 'User account is inactive, please contact administrator!';
    
    const ERR_WRONG_PASSWORD = 'Wrong password provided';
    const ERR_USER_NOT_LOGGED_IN = 'Please login before the action';
    
    const ERR_RESOURCE_NOT_FOUND = 404;
    
    const UPLOAD_INVALID_FILE_TYPE = 'File upload encountered an invalid file type.';
}