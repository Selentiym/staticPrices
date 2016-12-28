<?php
/**
 * @var PriceList $list
 */
$date = $list -> date;
?>
<div class="oldPriceList tableColumn">
    <div class="tableRow" title="<?php echo $list -> date; ?>">
        <?php echo $list -> date; ?>
    </div>
    <?php
    $values = $list -> getIndexedValues();
    foreach ($list -> getSite() -> prices as $price) {
        if ($pv = $values[$price -> id]) {
            unset($values[$price -> id]);
            $this -> renderPartial('oldPrice',['price' => $pv]);
        }
    }
    ?>
</div>
