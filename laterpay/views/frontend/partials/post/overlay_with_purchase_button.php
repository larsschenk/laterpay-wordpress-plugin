<?php
    if ( ! defined( 'ABSPATH' ) ) {
        // prevent direct access to this file
        exit;
    }
?>

<div class="lp_paid-content">
    <div class="lp_full-content">
        <!-- <?php _e( 'Preview a short excerpt from the paid post:', 'laterpay' ); ?> -->
        <?php echo LaterPay_Helper_String::truncate(
                $laterpay['content'],
                LaterPay_Helper_String::determine_number_of_words( $laterpay['content'] ),
                array(
                    'html'  => true,
                    'words' => true,
                )
            ); ?>
        <br>
        <?php _e( 'Thanks for reading this short excerpt from the paid post! Fancy buying it to read all of it?', 'laterpay' ); ?>
    </div>

    <?php if ( ! $laterpay['only_time_pass_purchases_allowed'] ) : ?>
        <div class="lp_overlay-text">
            <div class="lp_benefits">
                <header class="lp_benefits__header">
                    <h2 class="lp_benefits__title">
                        <span data-icon="a"></span>
                        <?php _e( 'Read Now, Pay Later', 'laterpay' ); ?>
                    </h2>
                </header>
                <ul class="lp_benefits__list">
                    <?php if ( $laterpay['revenue_model'] == 'sis' ): ?>
                        <li class="lp_benefits__list-item lp_benefit--buy-now">
                            <h3 class="lp_benefit__title"><?php _e( 'Buy Now', 'laterpay' ); ?></h3>
                            <p class="lp_benefit__text">
                                <?php _e( 'Buy this post now with LaterPay and <br>pay with a payment method you trust.', 'laterpay' ); ?>
                            </p>
                        </li>
                        <li class="lp_benefits__list-item lp_benefit--use-immediately">
                            <h3 class="lp_benefit__title"><?php _e( 'Read Immediately', 'laterpay' ); ?></h3>
                            <p class="lp_benefit__text">
                                <?php _e( 'Immediately access your purchase. <br>You only buy this post. No subscription, no fees.', 'laterpay' ); ?>
                            </p>
                        </li>
                    <?php else: ?>
                        <li class="lp_benefits__list-item lp_benefit--buy-now">
                            <h3 class="lp_benefit__title"><?php _e( 'Buy Now', 'laterpay' ); ?></h3>
                            <p class="lp_benefit__text">
                                <?php _e( 'Just agree to pay later.<br> No upfront registration and payment.', 'laterpay' ); ?>
                            </p>
                        </li>
                        <li class="lp_benefits__list-item lp_benefit--use-immediately">
                            <h3 class="lp_benefit__title"><?php _e( 'Read Immediately', 'laterpay' ); ?></h3>
                            <p class="lp_benefit__text">
                                <?php _e( 'Get immediate access to your purchase.<br> You are only buying this article, not a subscription.', 'laterpay' ); ?>
                            </p>
                        </li>
                        <li class="lp_benefits__list-item lp_benefit--pay-later">
                            <h3 class="lp_benefit__title"><?php _e( 'Pay Later', 'laterpay' ); ?></h3>
                            <p class="lp_benefit__text">
                                <?php _e( 'Buy with LaterPay until you reach a total of 5 Euro.<br> Only then do you have to register and pay.', 'laterpay' ); ?>
                            </p>
                        </li>
                    <?php endif; ?>
                </ul>
                <div>
                    <?php if ( defined( 'DOING_AJAX' ) && DOING_AJAX ): ?>
                        <?php
                            ob_start();
                            do_action( 'laterpay_purchase_button' );
                            $html = ob_get_contents();
                            ob_clean();
                            echo $html;
                        ?>
                    <?php else: ?>
                        <?php do_action( 'laterpay_purchase_button' ); ?>
                    <?php endif; ?>
                </div>
                <div class="lp_powered-by">
                    powered by<span data-icon="a"></span>beta
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
