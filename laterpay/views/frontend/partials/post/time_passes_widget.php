<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="lp_timePassWidget <?php echo $laterpay['time_pass_widget_class']; ?>">
    <?php if ( $laterpay['time_pass_introductory_text'] ): ?>
        <p class="lp_timePass__introductory-text"><?php echo $laterpay['time_pass_introductory_text']; ?></p>
    <?php endif; ?>

    <?php foreach ( $laterpay['passes_list'] as $pass ): ?>
        <?php echo $this->render_pass( (array) $pass ); ?>
    <?php endforeach; ?>

    <?php if ( $laterpay['has_vouchers'] ): ?>
        <?php if ( $laterpay['time_pass_call_to_action_text'] ): ?>
             <p class="lp_timePass__call-to-action-text"><?php echo $laterpay['time_pass_call_to_action_text']; ?></p>
         <?php endif; ?>

        <div id="lp_js_voucherCodeWrapper" class="lp_timePassWidget__voucher-code-wrapper lp_clearfix">
            <input type="text"
                    name="time_pass_voucher_code"
                    class="lp_js_voucherCodeInput lp_timePassWidget__voucher-code"
                    maxlength="6">
            <p class="lp_timePassWidget__voucher-code-input-hint"><?php _e( 'Code', 'laterpay' ); ?></p>
            <a href="#" class="lp_js_voucherRedeemButton lp_timePassWidget__redeem-voucher-code lp_button"><?php _e( 'Redeem', 'laterpay' ); ?></a>
            <p class="lp_timePassWidget__voucher-code-hint"><?php _e( 'Redeem Voucher >', 'laterpay' ); ?></p>
        </div>
    <?php endif; ?>
</div>
