<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php $currency = $laterpay[ 'currency' ]; ?>

<div class="lp_postStatistics_totals">
    <ul>
        <li>
            <big><?php if ( isset( $laterpay['statistic']['total'][$currency] ) ) { $aux = $laterpay['statistic']['total'][$currency]['sum']; } else { $aux = 0; }; echo LaterPay_Helper_View::format_number( (float) $aux ); ?><small><?php echo $laterpay['currency']; ?></small></big>
            <small><?php _e( 'Total Committed Revenue', 'laterpay' ); ?></small>
        </li>
        <li>
            <big><?php if ( isset( $laterpay['statistic']['total'][$currency] ) ) { $aux = $laterpay['statistic']['total'][$currency]['quantity']; } else { $aux = 0; }; echo LaterPay_Helper_View::format_number( (float) $aux, false ); ?></big>
            <small><?php _e( 'Total Sales', 'laterpay' ); ?></small>
        </li>
    </ul>
</div>
<div class="lp_postStatistics_separator">
    <ul>
        <li><p><?php _e( 'Last 30 days', 'laterpay' ); ?></p><hr></li>
        <li><p><?php _e( 'Today', 'laterpay' ); ?></p><hr></li>
    </ul>
</div>
<div class="lp_postStatistics_details">
    <ul>
        <li>
            <span class="lp_sparklineBar"><?php if ( isset( $laterpay['statistic']['last30DaysRevenue'][$currency] ) ) { $aux = $laterpay['statistic']['last30DaysRevenue'][$currency]; } else { $aux = array(); }; echo LaterPay_Helper_View::get_days_statistics_as_string( $aux, 'sum', ';' ); ?></span>
        </li>
        <li>
            <big><?php if ( isset( $laterpay['statistic']['todayRevenue'][$currency] ) ) { $aux = $laterpay['statistic']['todayRevenue'][$currency]['sum']; } else { $aux = 0; }; echo LaterPay_Helper_View::format_number( (float) $aux ); ?><small><?php echo $laterpay['currency']; ?></small></big>
            <small><?php _e( 'Committed Revenue', 'laterpay' ); ?></small>
        </li>
    </ul>
</div>
<div class="lp_postStatistics_details">
    <ul>
        <li>
            <span class="lp_sparklineBar" data-max="0.5"><?php echo LaterPay_Helper_View::get_days_statistics_as_string( $laterpay['statistic']['last30DaysBuyers'], 'percentage', ';' ); ?></span>
            <span class="lp_sparklineBackgroundBar">1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1;1</span>
        </li>
        <li>
            <big><?php echo $laterpay['statistic']['todayBuyers']; ?><small>%</small></big>
            <small><?php _e( 'Conversion', 'laterpay' ); ?></small>
        </li>
    </ul>
</div>
<div class="lp_postStatistics_details">
    <ul>
        <li>
            <span class="lp_sparklineBar"><?php echo LaterPay_Helper_View::get_days_statistics_as_string( $laterpay['statistic']['last30DaysVisitors'], 'quantity', ';' ); ?></span>
        </li>
        <li>
            <big><?php echo LaterPay_Helper_View::format_number( $laterpay['statistic']['todayVisitors'], false ); ?></big>
            <small><?php _e( 'Views', 'laterpay' ); ?></small>
        </li>
    </ul>
</div>