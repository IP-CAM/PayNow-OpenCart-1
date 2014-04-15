<?php
/**
 * catalog/view/theme/default/template/payment/paynow.tpl
 */

?>

<form action="<?php echo $action; ?>" method="post">   
  <input type="hidden" name="service_key" value="<?php echo $service_key; ?>" /> 
  <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <input type="hidden" name="item_name" value="<?php echo $item_name; ?>" />
  <input type="hidden" name="item_description" value="<?php echo $item_description; ?>" />
  <input type="hidden" name="name_first" value="<?php echo $name_first; ?>" />
  <input type="hidden" name="name_last" value="<?php echo $name_last; ?>" />
  <input type="hidden" name="email_address" value="<?php echo $email_address; ?>" />
  <input type="hidden" name="return_url" value="<?php echo $return_url; ?>" />
  <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
  <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>" />
  <input type="hidden" name="custom_str1" value="<?php echo $custom_str1; ?>" />
  <input type="hidden" name="m_payment_id" value="<?php echo $m_payment_id; ?>" />
  <input type="hidden" name="signature" value="<?php echo $signature; ?>" />
  <div class="buttons">
    <div class="right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>
</form>
