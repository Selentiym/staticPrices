<?php
/**
 * @var Price $price
 */
$lastVal = $price -> getLastValue(true);
$priceInteger = $lastVal -> price;
$highlightClass = (!$lastVal -> id_list) ? 'modified' : '';
?>
<div class="tableRow">
<span class="priceName" title="<?php echo $price -> name; ?>"><?php echo $price -> name; ?></span><span class="priceContainer inputable <?php echo $highlightClass; ?>" data-price-id="<?php echo $price -> id; ?>"><?php  echo $priceInteger; ?></span>
</div>