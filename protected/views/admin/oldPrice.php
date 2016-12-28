<?php
/**
 * @var PriceValue $price
 */
$d1 = $price -> last_altered;
$d2 = $price -> priceList -> date;
$date = abs(strtotime($d1) - strtotime($d2)) < 5  ? date('d.m', strtotime($price -> last_altered)) : '';
?>
<div class="price tableRow">
    <span class="value" title="<?php echo $price->priceObj->name; ?>"><?php echo $price -> price; ?></span><span class="last_update" title="<?php echo $d1; ?>"><?php echo $date; ?></span>
</div>
