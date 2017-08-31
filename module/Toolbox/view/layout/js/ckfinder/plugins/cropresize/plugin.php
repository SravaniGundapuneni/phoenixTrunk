<?php

// All of this is currently unused.
// However, we may wish to save crops to database.
// That will probably be handled in dbsavenewfiles,
// but if not, we will do so below.
// For now, this is simply dummy data.
 
// A simple protection against calling this file directly.
if (!defined('IN_CKFINDER')) exit;
 
// Include base XML command handler
require_once CKFINDER_CONNECTOR_LIB_DIR . "/CommandHandler/XmlCommandHandlerBase.php";
 
// Since we will send a XML response, we'll reuse the XmlCommandHandler
class CKFinder_Connector_CommandHandler_FileSize extends CKFinder_Connector_CommandHandler_XmlCommandHandlerBase
{
    // The buildXml method is used to construct an XML response
    function buildXml()
    {
        // A "must have", checking whether the connector is enabled and the basic parameters (like current folder) are safe.
        $this->checkConnector();
        $this->checkRequest();
 
        // Checking ACL permissions, we're just getting an information about a file, so FILE_VIEW permission seems to be ok.
        if (!$this->_currentFolder->checkAcl(CKFINDER_CONNECTOR_ACL_FILE_VIEW)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_UNAUTHORIZED);
        }
 
        // Make sure we actually received a file name
        if (!isset($_GET["fileName"])) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_NAME);
        }
 
        $fileName = CKFinder_Connector_Utils_FileSystem::convertToFilesystemEncoding($_GET["fileName"]);
        $resourceTypeInfo = $this->_currentFolder->getResourceTypeConfig();
 
        // Use the resource type configuration object to check whether the extension of a file to check is really allowed.
        if (!$resourceTypeInfo->checkExtension($fileName)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_EXTENSION);
        }
 
        // Make sure that the file name is really ok and has not been sent by a hacker
        if (!CKFinder_Connector_Utils_FileSystem::checkFileName($fileName) || $resourceTypeInfo->checkIsHiddenFile($fileName)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_INVALID_REQUEST);
        }
 
        $filePath = CKFinder_Connector_Utils_FileSystem::combinePaths($this->_currentFolder->getServerPath(), $fileName);
 
        if (!file_exists($filePath) || !is_file($filePath)) {
            $this->_errorHandler->throwError(CKFINDER_CONNECTOR_ERROR_FILE_NOT_FOUND);
        }

        $myNode = new Ckfinder_Connector_Utils_XmlNode("MyMessage");
        $myNode->addAttribute("message", "Hello world!");
        $this->_connectorNode->addChild($myNode);
    }

    public function myCommand ( &$command )
    {
        if ( $command == 'FileSize' )
        {
            // The sendResponse method is defined in XmlCommandHandlerBase, it creates
            // a basic XML response and calls the buildXml()method
            $this->sendResponse();
            // false = stop further execution.
            return false;
        }

        return true;
    }
}
 
$CommandHandler_FileSize = new CKFinder_Connector_CommandHandler_FileSize();

// Register the onBeforeExecuteCommand method to be called by the BeforeExecuteCommand hook.
$config['Hooks']['BeforeExecuteCommand'][] = array($CommandHandler_FileSize, "myCommand");

// (Optional) Register a javascript plugin named "myplugin"
$config['Plugins'][] = 'cropresize';