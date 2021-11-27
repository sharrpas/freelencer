<?php

namespace App\Constants;

class Status
{
    public const OPERATION_SUCCESSFUL = 200;
    public const VALIDATION_FAILED = 400;
    public const AUTHENTICATION_FAILED = 401;
    public const TOO_MANY_ATTEMPTS = 402;
    public const PERMISSION_DENIED = 403;
    public const NOT_FOUND = 404;
    public const ROUTE_NOT_FOUND = 410;
    public const OTHER_EXCEPTION_THROWN = 500;
    public const USERNAME_NOT_FOUND = 601;
    public const PASSWORD_IS_WRONG = 602;
    public const UPDATE_COMPLETED_PROJECT = 603;
    public const DELETE_OTHERS_PROJECT = 604;


    public static function getMessage($code)
    {
        $messages = [
            self::OPERATION_SUCCESSFUL => "Operation successful",
            self::VALIDATION_FAILED => "Validation failed",
            self::AUTHENTICATION_FAILED => "Authentication failed",
            self::TOO_MANY_ATTEMPTS => "Too many requests",
            self::PERMISSION_DENIED => "Permission denied",
            self::NOT_FOUND => "Not found",
            self::ROUTE_NOT_FOUND => "The selected route is invalid",
            self::OTHER_EXCEPTION_THROWN => "Other exception thrown",
            self::USERNAME_NOT_FOUND => "Username not found",
            self::PASSWORD_IS_WRONG => "Password is wrong",
            self::UPDATE_COMPLETED_PROJECT => "you can not update completed project",
            self::DELETE_OTHERS_PROJECT => "you can delete your project only",
        ];

        return $messages[$code];
    }
}
