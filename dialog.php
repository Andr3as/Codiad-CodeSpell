<!--
    Copyright (c) Codiad & Andr3as, distributed
    as-is and without warranty under the MIT License. 
    See http://opensource.org/licenses/MIT for more information.
    This information must remain intact.
-->
<form>
    <?php
        include_once('../../common.php');
        
        switch($_GET['action']) {
            case 'settings':
                include('templates/settings.php');
                break;
            case 'view':
                include('templates/view.php');
                break;
            default:
                echo "No page defined!";
                break;
        }
    ?>
</form>