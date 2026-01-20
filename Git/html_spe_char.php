
<?php


    function h($val): string {
        return htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8');
    }


?>
