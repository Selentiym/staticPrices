<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.12.2016
 * Time: 18:12
 */
Yii::app() -> getClientScript() -> registerCoreScript('jquery');
?>
<html>
<head></head>
<body>
    <?php
        echo $content;
    ?>
</body>
</html>
