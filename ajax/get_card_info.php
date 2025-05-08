<?php

include '../includes/conn.php';
include '../includes/functions.php';


session_start();
$cart = $_SESSION['cart'] ?? array();
echo "<ul style='list-style:none;padding:0;'>";
$i = 0;
foreach ($cart as $key => $item) { ?>
    <li>
        <?php $item_info = get_item_info($item['item_id']); ?>
        <div class="item" data-index="<?php echo $key; ?>" style="position: relative;display: flex;flex-direction: row;<?php echo ($i > 0) ? 'border-top: 1px solid #969696;' : ''; ?>padding: 10px 5px;">
            <div class="right pe-2" style="width:110px;height:110px; overflow:hidden;">
                <img src="<?php echo $item_info['img']; ?>" style="border-radius:7px;width: 100%;position:relative;top:50%;left:50%;translate: -50% -50%;">
            </div>
            <div class="content" style="flex: 1;">
                <h3 class="fs-6">
                    <?php echo $item_info['title']; ?>
                    <p style="font-size: 12px;margin-top: 10px;"><?php echo (isset($item['size'])) ? get_size_info($item['size'])['size_name'] : ''; ?></p>
                    <p style="font-size: 12px;margin-top: 10px;">
                        <?php
                        $options_price = 0;
                        if (isset($item['options']) && count($item['options']) > 0) {
                            foreach ($item['options'] as $key => $option) {
                                if (is_array($option['option_value'])) {
                                    $option_info = get_option_info($option['option_id']);
                                    echo $option_info['name'] . ": ";
                                    foreach ($option['option_value'] as $k => $val) {
                                        $option_value_info = get_option_value_info($val);
                                        echo ($k == 0) ? '' : ',';
                                        echo $option_value_info['name'] . " ";
                                        $options_price += $option_value_info['price'];
                                    }
                                } else {
                                    $option_info = get_option_info($option['option_id']);
                                    $option_value_info = get_option_value_info($option['option_value']);
                                    echo $option_info['name'] . ": " . $option_value_info['name'];
                                    $options_price += $option_value_info['price'];
                                }
                                echo "<br>";
                            }
                        }
                        ?>
                    </p>
                </h3>
                <?php
                $single_price = $item_info['price'];
                if (isset($item['size'])) {
                    $single_price = get_size_info($item['size'])['size_price'];
                }
                ?>
                <div class="item-footer" style="display: flex;justify-content: space-between;align-items:center;flex-wrap: wrap;">
                    <div class="item_price" data-price="<?php echo $single_price + $options_price; ?>">
                        <span><?php echo calc_item_price($item); ?></span>
                        <span><?php echo $site_setting['currency']; ?> </span>
                    </div>
                    <div class="item_count_custom" style="background: var(--button-back);color: var(--button-color);border-radius: 5px;padding: 3px;height:27px;direction:ltr;">
                        <span class="add" style="font-size: 20px;margin: 0 10px;cursor:pointer;color:var(--button-color);font-weight:bold;">+</span>
                        <span class="count" style="font-size:20px;margin:0 5px;"><?php echo $item['count']; ?></span>
                        <span class="minus" style="font-size: 20px;margin: 0 10px;cursor:pointer;color:var(--button-color);font-weight:bold;">-</span>
                    </div>
                </div>
            </div>
        </div>
    </li>
<?php $i++;
}
echo "</ul>";
?>

<script>
    $("#card_info .item_count_custom .add").click(function() {
        var index = $(this).parent().parent().parent().parent().attr("data-index")
        var price_for_one = $(this).parent().parent().find("div.item_price").attr("data-price")
        var current_count = parseInt($(this).parent().find(".count").text())
        $(this).parent().find(".count").text(current_count + 1)
        $(this).parent().parent().find("div.item_price span:first-of-type").text((current_count + 1) * price_for_one)
        $.post("ajax/update_card_item.php", {
            index: index,
            count: (current_count + 1)
        }, function(res) {
            console.log(res)
            var data = JSON.parse(res);
            $(".order-footer .count").text(data.count)
            $(".order-footer .price").text(data.price + " <?php echo $site_setting['currency']; ?>")
        });
    })

    $("#card_info .item_count_custom .minus").click(function() {
        var index = $(this).parent().parent().parent().parent().attr("data-index")
        var price_for_one = $(this).parent().parent().find("div.item_price").attr("data-price")
        var current_count = parseInt($(this).parent().find(".count").text())
        if (current_count - 1 <= 0) {
            $(this).parent().parent().parent().parent().remove()
        } else {
            $(this).parent().find(".count").text(current_count - 1)
            $(this).parent().parent().find("div.item_price span:first-of-type").text((current_count - 1) * price_for_one)
        }
        $.post("ajax/update_card_item.php", {
            index: index,
            count: (current_count - 1)
        }, function(res) {
            console.log(res)
            var data = JSON.parse(res);
            $(".order-footer .count").text(data.count)
            $(".order-footer .price").text(data.price + " <?php echo $site_setting['currency']; ?>")
            if (data.count == 0) {
                $(".order-footer button").attr("data-min", "")
                $(".order-footer button").attr("style", " filter: grayscale(100%);")
                $(".order-footer button").attr("disabled", "disabled")
                $("#card_info").modal("hide")
            }
        });
    })
</script>