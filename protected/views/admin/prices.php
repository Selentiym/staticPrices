<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.12.2016
 * Time: 18:11
 */
/**
 * @var Site $site
 */
$sites = Site::model() -> findAll();
Yii::app() -> getClientScript() -> registerScriptFile(Yii::app() -> baseUrl . '/js/script.js',CClientScript::POS_END);
Yii::app() -> getClientScript() -> registerCssFile(Yii::app() -> baseUrl . '/css/styles.css');
?>
<a href="<?php echo Yii::app() -> createUrl('login/logout'); ?>">Выйти</a>
<div id="wrapper">
    <div id="prices_tabs_cont">
        <?php
            foreach($sites as $s) {
                $this -> renderPartial('siteTab',['site' => $s, 'siteId' => $site -> id]);
            }
        ?>
    </div>
    <div id="siteContainer">
        <?php
            $this -> renderPartial('siteMenu',['site' => $site]);
            /*foreach ($sites as $site) {
                $this -> renderPartial('siteMenu',['site' => $site]);
            }*/
        ?>
    </div>
</div>
