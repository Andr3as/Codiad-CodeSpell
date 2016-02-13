<?php
/*
 * Copyright (c) Codiad & Andr3as, distributed
 * as-is and without warranty under the MIT License. 
 * See http://opensource.org/licenses/MIT for more information.
 * This information must remain intact.
 */
    error_reporting(0);

    require_once('../../common.php');
    checkSession();
    
    define('USERDIC', "dictionary." . $_SESSION['user'] . ".php");
    
    switch($_GET['action']) {
        
        case 'addMistakeToOwnDictionary':
            if (isset($_GET['mistake'])) {
                $dictionary = getOwnDictionary();
                $dictionary = explode("\n",$dictionary);
                array_shift($dictionary);
                array_push($dictionary, $_GET['mistake']);
                array_unshift($dictionary, count($dictionary));
                $dictionary = implode("\n", $dictionary);
                saveOwnDictionary($dictionary);
                echo '{"status":"success","message":"Misstake added"}';
            } else {
                echo '{"status":"error","message":"Missing data"}';
            }
            break;
        case 'deleteUserDictionary':
            file_put_contents(DATA . "/config/" . USERDIC, "");
            echo '{"status":"success","message":"User dictionary deleted"}';
            break;
        case 'loadOwnDictionary':
            $dictionary = getOwnDictionary();
            echo json_encode(array("status" => "success", "dictionary" => $dictionary));
            break;
        
        default:
            echo '{"status":"error","message":"No Type"}';
            break;
    }
    
    function getOwnDictionary() {
        if (!file_exists(DATA . "/config/" . USERDIC)) {
            return "";
        }
        $content = getJSON(USERDIC, "config");
        return $content['dictionary'];
    }
    
    function getWorkspacePath($path) {
		//Security check
		if (!Common::checkPath($path)) {
			die('{"status":"error","message":"Invalid path"}');
		}
        if (strpos($path, "/") === 0) {
            //Unix absolute path
            return $path;
        }
        if (strpos($path, ":/") !== false) {
            //Windows absolute path
            return $path;
        }
        if (strpos($path, ":\\") !== false) {
            //Windows absolute path
            return $path;
        }
        return "../../workspace/".$path;
    }
    
    function saveOwnDictionary($dictionary) {
        if (!file_exists(DATA . "/config/" . USERDIC)) {
            file_put_contents(DATA . "/config/" . USERDIC, "");
        }
        $content = array('dictionary' => $dictionary);
        saveJSON(USERDIC, $content, "config");
    }
?>