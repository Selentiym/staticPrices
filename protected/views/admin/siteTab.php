<?php
/**
 *
 * @var Site $site
 */
?>
<a class="siteTab <?php if ($site -> id == $siteId) echo 'active'; ?>" href="<?php echo Yii::app() -> createUrl('admin/prices',['siteId' => $site -> id]) ?>"><?php echo $site -> name; ?></a>