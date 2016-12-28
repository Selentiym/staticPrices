<?php
/**
 *
 * @var Site $site
 * @type PriceList[] $priceLists
 */
$priceLists = $site -> getPriceLists();
?>
<form name="Site" method="post" class="listForm">
<input type="submit" value="Сохранить" name="site">
    <div>
        <textarea name="script" placeholder="script"><?php echo $site -> lastList -> script; ?></textarea>
    </div>
<input type="hidden" name="siteId" value="<?php echo $site -> id; ?>" />
<div class="site_container">
    <?php
    $this->renderPartial('siteForm', ['site' => $site]);
        foreach ($priceLists as $priceList) {
            $this -> renderPartial('oldPriceList', ['list' => $priceList]);
        }
    ?>
</div>
</form>