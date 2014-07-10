<?php

function error_handler($level, $message, $file, $line, $context) {
    //Handle user errors, warnings, and notices ourself
    if($level === E_USER_ERROR || $level === E_USER_WARNING || $level === E_USER_NOTICE) {
        echo '<div><strong>Error : </strong></div>' . $message;

        //And prevent the PHP error handler from continuing
        return true;
    }
    //Otherwise, use PHP's error handler
    return false;
}

function error($message, $level = E_USER_NOTICE) {
    //Get the caller of the calling function and details about it
    $caller = current(debug_backtrace());

    switch ($level) {
        case E_USER_ERROR:
            $levelText = 'Error';
            break;
        case E_USER_WARNING:
            $levelText = 'Warning';
            break;
        case E_USER_NOTICE:
            $levelText = 'Notice';
            break;
        default:
            $levelText = 'User error';
    }

    $errorMsg = '<div style="font-family: sans-serif; border: 2px solid #222; background-color: #Ff3535; padding: 7px 5px; display: inline-block">';
    $errorMsg .= $message . '.<div style="margin-top: 7px;">' . $levelText . ' triggered in <strong>' . $caller['file'] . '</strong> on line <strong>';
    $errorMsg .= $caller['line'] . '</strong>.</div></div>';

    //Trigger appropriate error
    trigger_error($errorMsg, $level);

    if($level < E_USER_WARNING) {
        exit();
    }
}
