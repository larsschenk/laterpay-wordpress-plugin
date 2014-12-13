<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="lp_timePassWidget <?php echo $laterpay_widget['time_pass_widget_class']; ?>">
    <?php if ( $laterpay_widget['time_pass_introductory_text'] ): ?>
        <p class="lp_timePass__introductoryText"><?php echo $laterpay_widget['time_pass_introductory_text']; ?></p>
    <?php endif; ?>

    <?php foreach ( $laterpay_widget['passes_list'] as $pass ): ?>
        <?php echo $this->render_pass( (array) $pass ); ?>
    <?php endforeach; ?>

    <?php if ( $laterpay_widget['has_vouchers'] ): ?>
        <?php if ( $laterpay_widget['time_pass_call_to_action_text'] ): ?>
             <p class="lp_timePass__callToActionText"><?php echo $laterpay_widget['time_pass_call_to_action_text']; ?></p>
         <?php endif; ?>

        <div id="lp_js_voucherCodeWrapper" class="lp_js_voucherCodeWrapper lp_timePassWidget__voucherCodeWrapper lp_clearfix">
            <input type="text" name="time_pass_voucher_code" class="lp_js_voucherCodeInput lp_timePassWidget__voucherCode" maxlength="6">
            <p class="lp_timePassWidget__voucherCodeInputHint"><?php _e( 'Code', 'laterpay' ); ?></p>
            <a href="#" class="lp_js_voucherRedeemButton lp_timePassWidget__redeemVoucherCode lp_button"><?php _e( 'Redeem', 'laterpay' ); ?></a>
            <p class="lp_timePassWidget__voucherCodeHint"><?php _e( 'Redeem Voucher >', 'laterpay' ); ?></p>
        </div>
    <?php endif; ?>
</div>
