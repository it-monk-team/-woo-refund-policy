<?php
/*
Plugin Name: woo-refund-policy
Author: huanyichuang, IT-MONK
Description:  woocommerce "退貨政策" 的自訂欄位，在 WooCommerce 結帳頁顯示內容。
*/



// 註冊設定欄位
add_action( 'admin_init', 'woo_refund_policy_option_register' );
function woo_refund_policy_option_register(){

    register_setting( 'woo_refund_policy', 'woo_refund_policy_title' );
    register_setting( 'woo_refund_policy', 'woo_refund_policy' );

}

// 在 wordpress 的管理者目錄新增選項
add_action( 'admin_menu', 'woo_refund_policy_settings_page' );
function woo_refund_policy_settings_page() {
    $title = esc_html__( 'wooCommerce 退貨政策', "woo-refund-policy" );
    add_menu_page( $title , $title, 'edit_posts', "woo-refund-policy", 'woo_refund_policy_ui' );
}

// 介面設計
function woo_refund_policy_ui() {
    $woo_refund_policy_txt = get_option('woo_refund_policy');
    $woo_refund_policy_title = get_option('woo_refund_policy_title');
    ?>
    <div class="wrap">
        <h1><?php echo _e( 'WooCommerce 退貨政策設定', "woo-refund-policy" ); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'woo_refund_policy' ); ?>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="blogname"><?php echo _e( 'WooCommerce 退貨政策標題', "woo-refund-policy" ); ?></label></th>
                    <td>
                        <input name="woo_refund_policy_title" type="text"  value="<?php echo $woo_refund_policy_title;?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="blogname"><?php echo _e( 'WooCommerce 退貨政策', "woo-refund-policy" ); ?></label></th>
                    <td>
                        <?php
                        wp_editor (
                            $woo_refund_policy_txt ,
                            'woo_refund_policy',
                            array ( "media_buttons" => true, 'textarea_name' => 'woo_refund_policy','textarea_rows' => 3 )
                        );
                        ?>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>

    <?php
}



//    結帳頁面新增 policy 欄位
add_action( 'woocommerce_review_order_before_submit', 'hyc_refund', 99 );
if ( ! function_exists( 'hyc_refund' ) ) {
    function hyc_refund() {
        $refund_policy_txt = get_option('woo_refund_policy');
        $woo_refund_policy_title = get_option('woo_refund_policy_title');
        if (  $refund_policy_txt ) {
            ?>
            <div id="refund" style="overflow-y: scroll; max-height: 10em; margin-bottom: 2em; background: #fff; padding: 15px;">
                <?php
                if($woo_refund_policy_title):
                ?>
                    <h3>
                        <?php echo $woo_refund_policy_title; ?>
                    </h3>
                <?php
                endif;
                ?>

                <?php
                //比照文章內容使用 filter ，讓 shortcode 也能正確顯示
                    echo apply_filters( 'the_content',  $refund_policy_txt  );
                ?>
            </div>

        <?php } }
}




// 如果沒有安裝 WooCommerce 跳出提示
add_action( 'admin_notices', 'pls_install_woocommerce' );
function pls_install_woocommerce() {

    if(!class_exists( 'WooCommerce' )):
    ?>

    <div class="notice notice-warning is-dismissible">
        <p><?php _e( '兄弟 (姊妹)，<strong>woo-refund-policy</strong> 外掛要先安裝 WooCommerce! 才會生效', "woo-refund-policy" ); ?></p>
    </div>

    <?php
    endif;
}



