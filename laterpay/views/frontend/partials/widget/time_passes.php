<?php
    if ( ! defined( 'ABSPATH' ) ) {
        // prevent direct access to this file
        exit;
    }
?>

<div class="lp_time-pass-widget <?php echo $laterpay_widget['time_pass_widget_class']; ?>">
    <?php if ( $laterpay_widget['time_pass_introductory_text'] ): ?>
        <p class="lp_time-pass__introductory-text"><?php echo $laterpay_widget['time_pass_introductory_text']; ?></p>
    <?php endif; ?>

    <?php foreach ( $laterpay_widget['passes_list'] as $pass ): ?>
        <?php echo $this->render_time_pass( (array) $pass ); ?>
    <?php endforeach; ?>

    <?php if ( $laterpay_widget['has_vouchers'] ): ?>
        <?php if ( $laterpay_widget['time_pass_call_to_action_text'] ): ?>
             <p class="lp_time-pass__call-to-action-text"><?php echo $laterpay_widget['time_pass_call_to_action_text']; ?></p>
         <?php endif; ?>

        <div id="lp_js_voucherCodeWrapper" class="lp_redeem-code__wrapper lp_clearfix">
            <input type="text" name="time_pass_voucher_code" class="lp_js_voucherCodeInput lp_redeem-code__value lp_is-hidden" maxlength="6">
            <p class="lp_redeem-code__input-hint"><?php _e( 'Code', 'laterpay' ); ?></p>
            <div class="lp_js_voucherRedeemButton lp_redeem-code__button lp_button"><?php _e( 'Redeem', 'laterpay' ); ?></div>
            <p class="lp_redeem-code__hint"><?php _e( 'Redeem Voucher >', 'laterpay' ); ?></p>
        </div>
    <?php endif; ?>
</div>
