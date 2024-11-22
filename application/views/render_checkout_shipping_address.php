<h3>SHIPPING ADDRESS</h3>
<span id="shipping-address-error" class="error"></span>
<div class="add-address">
    <a href="javascript:void(0);" id="AddShippingAddressbtn">Add New Address</a>
    <input type="hidden" name="shipping_address_count" id="shipping_address_count" value="<?php echo $shipping_address_count; ?>" >
</div>
<div class="your-address">
    <span>Your Addresses</span>
    <?php if(!empty($shipping_address)){ 
        for($i = 0; $i < count($shipping_address); $i++){ ?>
            <div class="address-box">
                <input type="radio" name="shipping_id" id="shipping_id<?php echo $i; ?>" data-zipcode="<?php echo $shipping_address[$i]['shipping_zipcode']; ?>" data-tax="<?php echo $shipping_address[$i]['tax']; ?>" data-state="<?php echo $shipping_address[$i]['shipping_state_id']; ?>" value="<?php echo $shipping_address[$i]['shipping_id']; ?>">
                <div class="detail-box">
                    <p><b><?php echo $shipping_address[$i]['first_name'] . ' '. $shipping_address[$i]['last_name']; ?></b></p>
                    <?php $address = '';
                    $address = ($address != "") ? $address .= ", ".$shipping_address[$i]['shipping_street_address'] : $address = $shipping_address[$i]['shipping_street_address'];
                    $address = ($address != "") ? $address .= ", ".$shipping_address[$i]['shipping_apartment'] : $address = $shipping_address[$i]['shipping_apartment'];
                    $address = ($address != "") ? $address .= ", ".$shipping_address[$i]['shipping_city'] : $address = $shipping_address[$i]['shipping_city'];
                    $address = ($address != "") ? $address .= ", ".$shipping_address[$i]['state_name'] : $address = $shipping_address[$i]['state_name']; ?>
                    <p><?php echo $address; ?></p>
                </div>
                <div class="add-edit">
                    <p>
                        <a href="javascript:void(0);" data-id="<?php echo $shipping_address[$i]['shipping_id']; ?>" class="edit_shipping_data_btn" >
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="25" height="25" viewBox="0 0 25 25">
                            <image xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAiCAMAAADiW5DOAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAABiVBMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADwwLPBAAAAgnRSTlMAT9rkZQQhMBFpiBue8e4L00nAOu3eCHjJDwaylwEX7O9yIxADjvUth8OlfeGL1RWs/lOWz/3XHFBb3EL2vKMFVPokTiKvmPv5Crq+wnzKZJXFgauokY+2efRinHNgDCU5UWewNp9A28FrSx8J/FdIneV+PfhKMjGS4xPY4Cx20NQNoV6DzAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAG0SURBVDjLjdNpV9NAGIbhV4qgbFIWkdKqQFEsoKAUsNSlRaUBFAEXFq0LoIIWVGRzA+5fbrNMJwktx+fDnMzJdTKTeRKRUjlVRqBcT09XVFo54yJnMVPlzKprarFTd85N6oMNCjU2odJ8XpEWuNAqoTYIR0QuXuJye0enlaiXiEJdXLnq3243XLOXjcagR3rp85Oq/LLXb9jX0X7qZICbXhG5BYNxhobV5gcERrwkDG2h23ESFhpNcsdvonchdk/kformtMhYksADnzH3GLNebyxFIG0N4jWhh/DIOYFgkqZxEhXiNerM7IxmcDblMsYE9BWIlMeprxavMSZ1h05jziEVTGgKHmvSDk8cos00tGiiGvOap8xoMgsNBaLNHM/cjU0actzM8FzV8QJeuog2Zcy7G5NiZoFFu44lmFMk8uq1yxgQzL55++79smrMzApktemAceezXi0Q+QCd2gzHrfsLHz+t6Tp8Rhqn11c+fxFvfKZo/t/k2DjBbEL+Jxri6wmmi2/58Tu5zZJk64f192UT5MLbO5XHsxvcy7D/07TpX5TO7z/2A42/B5miIHXYc2SCfx+PrwcAjj9tAAAAAElFTkSuQmCC" width="25" height="25"/>
                            </svg>
                        </a>
                    </p>
                </div>
            </div>
        <?php }
    } ?>
</div>