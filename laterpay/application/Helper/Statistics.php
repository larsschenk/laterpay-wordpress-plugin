<?php

class LaterPay_Helper_Statistics
{

    /**
     * @var string|wpdb
     */
    public static $wpdb = '';

    /**
     * @var array
     */
    protected static $stat = array();

    /**
     * @var array
     */
    protected static $options = array(
        'secret'            => AUTH_SALT,
        'session_duration'  => 2678400,  // one month
    );

    /**
     *
     * @return  string $unique_id
     */
    protected static function get_unique_id() {
        return str_replace( '.', '', uniqid( rand( 0, 2147483647 ), true ) );
    }

    /**
     * Wrapper for inet_pton() with fallback.
     *
     * @param string $ip
     *
     * @return string $ip
     */
    protected static function inet_pton( $ip ) {
        // use inet_pton function, if it exists and PHP wasn't compiled with --disable-ipv6 option
        if ( function_exists( 'inet_pton' ) && defined( 'AF_INET6' ) ) {
            return inet_pton( $ip );
        }

        // IPv4
        if ( strpos( $ip, '.' ) !== false ) {
            if ( strpos( $ip, ':' ) === false ) {
                $ip = pack( 'N', ip2long( $ip ) );
            } else {
                $ip = explode( ':', $ip );
                $ip = pack( 'N', ip2long( $ip[count( $ip ) - 1] ) );
            }
        }
        // IPv6
        elseif ( strpos( $ip, ':' ) !== false ) {
            $ip         = explode( ':', $ip );
            $parts      = 8 - count( $ip );
            $res        = '';
            $replaced   = 0;
            foreach ( $ip as $seg ) {
                if ( $seg != '' ) {
                    $res .= str_pad( $seg, 4, '0', STR_PAD_LEFT );
                } elseif ( $replaced == 0 ) {
                    for ( $i = 0; $i <= $parts; $i++ ) {
                        $res .= '0000';
                    }
                    $replaced = 1;
                } elseif ( $replaced == 1 ) {
                    $res .= '0000';
                }
            }
            $ip = pack( 'H' . strlen( $res ), $res );
        }

        return $ip;
    }

    /**
     * Try to find the user's REAL IP address.
     *
     * @return string $ip
     */
    protected static function get_ip_2_long_remote_ip() {
        $long_ip = array( 0, 0 );

        if ( isset( $_SERVER['REMOTE_ADDR'] ) && filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP ) !== false ) {
            $long_ip[0] = self::inet_pton( $_SERVER['REMOTE_ADDR'] );
        }

        if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && filter_var( $_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP ) !== false ) {
            $long_ip[1] = self::inet_pton( $_SERVER['HTTP_CLIENT_IP'] );
        }

        if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            foreach ( explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] ) as $a_ip ) {
                if ( filter_var( $a_ip, FILTER_VALIDATE_IP ) !== false ) {
                    $long_ip[1] = self::inet_pton( $a_ip );
                    return $long_ip;
                }
            }
        }

        if ( isset( $_SERVER['HTTP_FORWARDED'] ) && filter_var( $_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP ) !== false ) {
            $long_ip[1] = self::inet_pton( $_SERVER['HTTP_FORWARDED'] );
        }

        if ( isset( $_SERVER['HTTP_X_FORWARDED'] ) && filter_var( $_SERVER['HTTP_X_FORWARDED'], FILTER_VALIDATE_IP ) !== false ) {
            $long_ip[1] = self::inet_pton( $_SERVER['HTTP_X_FORWARDED'] );
        }

        return $long_ip;
    }

    /**
     * Core tracking functionality.
     *
     * @param string|int $post_id
     *
     * @return void
     */
    public static function track( $post_id = '' ) {
        if ( empty( $post_id ) ) {
            return;
        }

        // ignore Firefox / Safari prefetching requests (X-Moz: Prefetch and X-purpose: Preview)
        if ( (isset( $_SERVER['HTTP_X_MOZ'] ) && strtolower( $_SERVER['HTTP_X_MOZ'] ) == 'prefetch' ) ||
            (isset( $_SERVER['HTTP_X_PURPOSE'] ) && strtolower( $_SERVER['HTTP_X_PURPOSE'] ) == 'preview' ) ) {
            return;
        }
        if ( LaterPay_Helper_Browser::is_crawler() ) {
            return;
        }
        if ( isset( $_COOKIE['laterpay_tracking_code'] ) ) {
            list( $uniqueId, $control_code ) = explode( '.', $_COOKIE['laterpay_tracking_code'] );

            // make sure only authorized information is recorded
            if ( $control_code != md5( $uniqueId . self::$options['secret'] ) ) {
                return;
            }
        } else {
            $uniqueId = self::get_unique_id();
            setcookie(
                'laterpay_tracking_code',
                $uniqueId . '.' . md5( $uniqueId . self::$options['secret'] ),
                time() + self::$options['session_duration'],
                '/'
            );
        }

        $model = new LaterPay_Model_Post_Views();

        $data = array(
            'post_id'   => $post_id,
            'user_id'   => $uniqueId,
            'date'      => time(),
            'ip'        => 0,
        );
        list( $data['ip'], $longOtherIp ) = self::get_ip_2_long_remote_ip();

        $model->update_post_views( $data );
    }

    /**
     * Get full URL.
     *
     * @param array $s $_SERVER
     *
     * @return string $url
     */
    public static function get_full_url( $s ) {
        if ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' ) {
            $ssl = true;
        } else {
            $ssl = false;
        }
        $sp = strtolower( $s['SERVER_PROTOCOL'] );
        if ( $ssl ) {
            $aux = 's';
        } else {
            $aux = '';
        }
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( $aux );
        $port = $s['SERVER_PORT'];
        if ( ( ! $ssl && $port == '80' ) || ( $ssl && $port == '443' ) ) {
            $port = '';
        } else {
            $port = ':' . $port;
        }
        if ( isset( $s['HTTP_X_FORWARDED_HOST'] ) ) {
            $host = $s['HTTP_X_FORWARDED_HOST'];
        } else {
            if ( isset( $s['HTTP_HOST'] ) ) {
                $host = $s['HTTP_HOST'];
            } else {
                $host = $s['SERVER_NAME'];
            }
        }

        return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
    }

    /**
     * Get user unique id.
     *
     * @return null|string user id
     */
    public static function get_user_unique_id( ) {
        if ( isset( $_COOKIE['laterpay_tracking_code'] ) ) {
            list( $uniqueId, $control_code ) = explode( '.', $_COOKIE['laterpay_tracking_code'] );

            // make sure only authorized information is recorded
            if ( $control_code != md5( $uniqueId . self::$options['secret'] ) ) {
                return null;
            }

            return $uniqueId;
        }

        return null;
    }

    /**
     * Generate performance data statistics for post.
     *
     * @param WP_Post $post
     *
     * @return array  $statistic_args
     */
    public static function get_post_statistics( WP_Post $post ) {
        // get currency
        $currency = get_option( 'laterpay_currency' );

        // get historical performance data for post
        $payments_history_model = new LaterPay_Model_Payments_History();
        $post_views_model       = new LaterPay_Model_Post_Views();
        $currency_model         = new LaterPay_Model_Currency();

        // get total revenue and total sales
        $total = array();
        $history_total = (array) $payments_history_model->get_total_history_by_post_id( $post->ID );
        foreach ( $history_total as $item ) {
            $currency_short_name = $currency_model->get_short_name_by_currency_id( $item->currency_id );
            $total[$currency_short_name]['sum']      = round( $item->sum, 2 );
            $total[$currency_short_name]['quantity'] = $item->quantity;
        }

        // get revenue
        $last30DaysRevenue = array();
        $history_last30DaysRevenue = (array) $payments_history_model->get_last_30_days_history_by_post_id( $post->ID );
        foreach ( $history_last30DaysRevenue as $item ) {
            $currency_short_name = $currency_model->get_short_name_by_currency_id( $item->currency_id );
            $last30DaysRevenue[$currency_short_name][$item->date] = array(
                'sum'       => round( $item->sum, 2 ),
                'quantity'  => $item->quantity,
            );
        }

        $todayRevenue = array();
        $history_todayRevenue = (array) $payments_history_model->get_todays_history_by_post_id( $post->ID );
        foreach ( $history_todayRevenue as $item ) {
            $currency_short_name = $currency_model->get_short_name_by_currency_id( $item->currency_id );
            $todayRevenue[$currency_short_name]['sum']       = round( $item->sum, 2 );
            $todayRevenue[$currency_short_name]['quantity']  = $item->quantity;
        }

        // get visitors
        $last30DaysVisitors = array();
        $history_last30DaysVisitors = (array) $post_views_model->get_last_30_days_history( $post->ID );
        foreach ( $history_last30DaysVisitors as $item ) {
            $last30DaysVisitors[$item->date] = array(
                'quantity' => $item->quantity,
            );
        }

        $todayVisitors = (array) $post_views_model->get_todays_history( $post->ID );
        $todayVisitors = $todayVisitors[0]->quantity;

        // get buyers (= conversion rate)
        $last30DaysBuyers = array();
        if ( isset( $last30DaysRevenue[$currency] ) ) {
            $revenues = $last30DaysRevenue[$currency];
        } else {
            $revenues = array();
        }
        foreach ( $revenues as $date => $item ) {
            $percentage = 0;
            if ( isset( $last30DaysVisitors[$date] ) && ! empty( $last30DaysVisitors[$date]['quantity'] ) ) {
                $percentage = round( 100 * $item['quantity'] / $last30DaysVisitors[$date]['quantity'] );
            }
            $last30DaysBuyers[$date] = array( 'percentage' => $percentage );
        }

        $todayBuyers = 0;
        if ( ! empty( $todayVisitors ) && isset( $todayRevenue[$currency] ) ) {
            // percentage of buyers (sales divided by visitors)
            $todayBuyers = round( 100 * $todayRevenue[$currency]['quantity'] / $todayVisitors );
        }

        // assign variables
        $statistic_args = array(
            'total'                 => $total,
            'last30DaysRevenue'     => $last30DaysRevenue,
            'todayRevenue'          => $todayRevenue,
            'last30DaysBuyers'      => $last30DaysBuyers,
            'todayBuyers'           => $todayBuyers,
            'last30DaysVisitors'    => $last30DaysVisitors,
            'todayVisitors'         => $todayVisitors,
        );

        return $statistic_args;
    }


}
