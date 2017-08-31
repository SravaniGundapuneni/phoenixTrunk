<?php
namespace Toolbox\Service;

class ErrorHandler
{
    protected $settings;
    protected $errors = array();

    public function __construct($settings)
    {
        if (!defined('E_NONE')) {
            define('E_NONE', 0);
        }

        if (!defined('E_RECOVERABLE_ERROR')) {
            define('E_RECOVERABLE_ERROR', E_ERROR);
        }

        $this->settings = $settings;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function logError($phpErrorValue, $info, $file, $line)
    {
        $this->errors[] = $this->errorCodeToText($phpErrorValue) . '<br>' . $info . '<br>' . $file . '<br>' . $line . '<hr>';
    }

    public function getErrors()
    {
        return $this->errors;
    }

    // public function logPHPError($phpErrorValue, $info, $file, $line)
    // {
    //     //Support the @ error suppression in php which temporarily sets the reporting level to none
    //     if (error_reporting() == E_NONE) {
    //         return;
    //     }


    //     $infoArray = array('errorNumber' => $phpErrorValue,
    //         'file' => $file,
    //         'line' => $line);

    //     if (isset($settings['showDebug']['logErrorsOnly']) && $settings['showDebug']['logErrorsOnly'] == 'true') {
    //         if (
    //                 $phpErrorValue != E_ERROR
    //                 && $phpErrorValue != E_USER_ERROR
    //                 && $phpErrorValue != E_RECOVERABLE_ERROR
    //                 && $phpErrorValue != E_PARSE
    //                 && $phpErrorValue != E_COMPILE_ERROR
    //         )
            
    //         return;
    //     }


    //     //Skip errors except for localhost
    //     if ($phpErrorValue == E_STRICT && !mb_strpos($_SERVER['HTTP_HOST'], 'localhost'))
    //         return;

    //     switch ($php_error_value) {
    //         case E_RECOVERABLE_ERROR :
    //             $main->messageLog->addError('(E_RECOVERABLE_ERROR) ' . $info, $infoArray);
    //             break;

    //         case E_ERROR : $main->messageLog->addError('(E_ERROR) ' . $info, $infoArray);
    //             break;

    //         case E_PARSE : $main->messageLog->addError('(E_PARSE) ' . $info, $infoArray);
    //             break;

    //         case E_COMPILE_ERROR : $main->messageLog->addError('(E_COMPILE_ERROR) ' . $info, $infoArray);
    //             break;

    //         case E_WARNING: $main->messageLog->addWarning('(E_WARNING) ' . $info, $infoArray);
    //             break;

    //         case E_NOTICE: $main->messageLog->addNotice('(E_NOTICE) ' . $info, $infoArray);
    //             break;

    //         case E_USER_ERROR: $main->messageLog->addError('(E_USER_ERROR) ' . $info, $infoArray);
    //             break;

    //         case E_USER_WARNING: $main->messageLog->addWarning('(E_USER_WARNING) ' . $info, $infoArray);
    //             break;

    //         case E_USER_NOTICE: $main->messageLog->addNotice('(E_USER_NOTICE) ' . $info, $infoArray);
    //             break;

    //         case E_STRICT:
    //         case E_DEPRECATED:
                
    //             if ((isset($main->localCXML->showDebug['ignoreStrict'])
    //                     && (string) $main->localCXML->showDebug['ignoreStrict'] == 'true')
    //                     || (isset($main->iniSettings['errors']['ignoreStrict'])
    //                     && (string) $main->iniSettings['errors']['ignoreStrict'] == true)
    //             ) {
    //                 break;
    //             }

    //             $main->messageLog->addNotice('(E_STRICT) ' . $info, $infoArray);
    //             break;

    //         default : $main->messageLog->addError("($php_error_value) " . $info, $infoArray);
    //             break;
    //     }

    //     if (function_exists("fb")) {
    //         // Output error to firebug console if firephp is installed
    //         $errorText = errorCodeToText($php_error_value);
    //         switch ($php_error_value) {
    //             case E_NOTICE:
    //             case E_USER_NOTICE:
    //                 $errorColor = "#C2C2C2";
    //                 break;
    //             case E_WARNING:
    //             case E_USER_WARNING:
    //                 $errorColor = "#F27822";
    //                 break;
    //             case E_ERROR:
    //             case E_USER_ERROR:
    //             default :
    //                 $errorColor = "#ff0000";
    //         }
    //         if (class_exists('FB')) {
    //             $fileName = pathinfo($infoArray['file'], PATHINFO_FILENAME);
    //             FB::group("PHP LOG MESSAGE ($errorText) - $fileName - line {$infoArray['line']}", array('Collapsed' => true, 'Color' => $errorColor));
    //             FB::log("File: {$infoArray['file']} (Line: {$infoArray['line']})");
    //             FB::info($info);
    //             FB::groupEnd();
    //         }
    //     }
    // }

    protected function errorCodeToText($errorCode) {
        foreach (get_defined_constants () as $constantName => $constantValue) {
            if (preg_match("/^E_/", $constantName) && $constantValue == $errorCode) {
                return $constantName;
            }
        }
        return "UNDEFINED ERROR";
    }
}