<?php
/**
 *
 * @var \Site $site
 */
?>
<div class="price_form tableColumn">
    <div class="tableRow"></div>
        <?php
            foreach ($site -> prices as $price) {
                $this -> renderPartial('priceFormElement', ['price' => $price]);
            }
        ?>
</div>
