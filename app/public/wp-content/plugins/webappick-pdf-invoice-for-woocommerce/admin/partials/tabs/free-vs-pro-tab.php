<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<!--START FREE VS PREMIUM TAB-->
<li>
    <div class="woo-invoice-row">
        <div class="woo-invoice-col-sm-8">
            <div class="woo-invoice-card woo-invoice-mr-0">
                <div class="woo-invoice-card-body">
                    <table width="100%">
                        <tr>
                            <th style="padding: 20px;font-size:18px" width="50%">Features</th>
                            <th width="25%" style="text-align: center;font-size:18px">Free</th>
                            <th width="25%" style="text-align: center;font-size:18px">Premium</th>
                        </tr>
                        <?php
                        $woo_invoice_comparetable = array(

                            array(
                                'title' => __('Automatic Invoicing', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Attach to Order Email', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Invoice Download From My Account', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Custom Date Format', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Display ID/SKU', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Display Currency Code', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Display Payment Method', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Packing Slip', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Footer Info Section', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Bulk Invoice/Packing Slip Download', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Invoice Template Translation', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Display Shipping Cost With Tax / Without Tax', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Total Tax', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),
                            array(
                                'title' => __('Invoice/Packing Slip Print', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => true,
                            ),

                            array(
                                'title' => __('Bar Code', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('QR Code', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('ZATCA QR code', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('GST Invoice', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Invoice Backup To Dropbox', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Write Custom CSS', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Custom Invoice Numbering Options', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('WPML Compatibility', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Polylang Compatibility', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Individual Product Tax & Tax %', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Total Without Tax', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Paid Stamp', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Authorized Signature', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Custom Background', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Display Product Image', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Display Product Category', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Display Product Short Description', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Display Discounted Price', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Proforma Invoice', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),

                            array(
                                'title' => __('WooCommerce Subscription Compatibility', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Order Delivery Address Print', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Download Bulk Delivery Address Print', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Custom Paper Size', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Pagination Style', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Display refund details', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Display fee details', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),
                            array(
                                'title' => __('Generate credit note template', 'webappick-pdf-invoice-for-woocommerce'),
                                'free'  => false,
                            ),

                        );
                        foreach ($woo_invoice_comparetable as $invoice_feature) {
                        ?>
                            <tr>
                                <td class="woo-invoice-proFree-feature"><?php printf(esc_html__('%1$s', 'webappick-pdf-invoice-for-woocommerce'), $invoice_feature['title']); //phpcs:ignore 
                                                                        ?></td>
                                <?php if (false === $invoice_feature['free']) { ?>
                                    <td class="woo-invoice-proFree-free"><span class="dashicons dashicons-no"></span></td>
                                <?php } else { ?>
                                    <td class="woo-invoice-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                                <?php } ?>
                                <td class="woo-invoice-proFree-pro"><span class="dashicons dashicons-yes"></span></td>
                            </tr>
                        <?php } ?>
                        </tfoot>
                    </table>
                </div><!-- end .woo-invoice-card -->
            </div><!-- end .woo-invoice-card -->
        </div><!-- end .woo-invoice-col-8 -->
        <div class="woo-invoice-col-sm-4 woo-invoice-col-12 ">
            <div class="woo-invoice-banner-sidebar">
                <div class="woo-invoice-card">
                    <div class="woo-invoice-banner-card">
                        <div class="woo-invoice-circle woo-invoice-circle-1"></div>
                        <div class="woo-invoice-circle woo-invoice-circle-2"></div>
                        <div class="woo-invoice-banner-content">
                            <span class="woo-invoice-upto-text">UPTO</span>
                            <div class="woo-invoice-banner-icon">
                                <!-- SVG ICON -->
                                <svg width="231" height="103" viewBox="0 0 231 103" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M67.0972 12.364C67.0972 14.3088 66.6341 16.2073 65.708 18.0596C64.8745 19.8192 63.8557 21.1621 62.6518 22.0882C60.1513 20.9768 57.5118 20.1433 54.7335 19.5877C52.0478 18.9394 49.5472 18.6152 47.2319 18.6152C41.6752 18.6152 37.3688 20.097 34.3126 23.0606C31.349 25.9316 29.3579 30.0991 28.3392 35.5632C30.1914 34.2666 32.6456 33.109 35.7018 32.0902C38.758 30.9789 41.9068 30.4232 45.1482 30.4232C53.6685 30.4232 60.4291 32.8774 65.4301 37.7859C70.5238 42.6943 73.0706 49.7328 73.0706 58.9013C73.0706 62.8836 72.376 66.6807 70.9868 70.2926C69.5977 73.9044 67.5139 77.0995 64.7356 79.8779C61.9572 82.5636 58.4843 84.74 54.3167 86.407C50.2418 87.9814 45.4723 88.7686 40.0082 88.7686C29.0801 88.7686 20.4672 85.1104 14.1696 77.7941C7.9646 70.4778 4.86211 60.059 4.86211 46.5377C4.86211 38.8509 5.78823 32.0902 7.64046 26.2557C9.58531 20.4212 12.271 15.5591 15.6977 11.6694C19.2169 7.77967 23.3844 4.86241 28.2003 2.91756C33.0161 0.972716 38.3412 0.000295022 44.1758 0.000295022C51.5847 0.000295022 57.234 1.01902 61.1237 3.05648C65.106 5.09393 67.0972 8.19642 67.0972 12.364ZM39.8693 48.4825C38.1097 48.4825 36.2112 48.8529 34.1737 49.5938C32.2289 50.3347 30.6545 51.3535 29.4505 52.65V56.123C29.4505 61.124 30.4692 64.6895 32.5067 66.8196C34.6368 68.9497 37.1836 70.0147 40.1472 70.0147C43.2033 70.0147 45.4723 68.996 46.9541 66.9585C48.5285 64.9211 49.3157 62.2816 49.3157 59.0402C49.3157 55.7988 48.4822 53.252 46.8152 51.3998C45.2408 49.4549 42.9255 48.4825 39.8693 48.4825ZM133.03 44.315C133.03 36.1652 131.826 29.8676 129.418 25.4222C127.103 20.9768 123.769 18.7542 119.416 18.7542C115.063 18.7542 111.729 20.9768 109.414 25.4222C107.191 29.8676 106.08 36.1652 106.08 44.315C106.08 52.4648 107.191 58.8087 109.414 63.3467C111.729 67.792 115.063 70.0147 119.416 70.0147C123.769 70.0147 127.103 67.792 129.418 63.3467C131.826 58.8087 133.03 52.4648 133.03 44.315ZM156.785 44.315C156.785 51.4461 155.859 57.79 154.006 63.3467C152.154 68.9034 149.561 73.5803 146.227 77.3773C142.986 81.0818 139.05 83.9065 134.419 85.8513C129.881 87.7962 124.926 88.7686 119.555 88.7686C114.091 88.7686 109.043 87.7962 104.413 85.8513C99.8748 83.9065 95.9388 81.0818 92.6048 77.3773C89.3634 73.5803 86.8166 68.9034 84.9643 63.3467C83.2047 57.79 82.3249 51.4461 82.3249 44.315C82.3249 37.1839 83.2047 30.8863 84.9643 25.4222C86.8166 19.8655 89.3634 15.2349 92.6048 11.5304C95.9388 7.73337 99.8748 4.86241 104.413 2.91756C109.043 0.972716 114.091 0.000295022 119.555 0.000295022C124.926 0.000295022 129.881 0.972716 134.419 2.91756C139.05 4.86241 142.986 7.73337 146.227 11.5304C149.561 15.2349 152.154 19.8655 154.006 25.4222C155.859 30.8863 156.785 37.1839 156.785 44.315Z" fill="white" /> <path d="M204.249 4.09062C206.116 4.36526 207.764 4.88708 209.192 5.65607C210.675 6.37014 211.472 7.35885 211.581 8.6222C211.581 9.50106 211.362 10.4074 210.922 11.3412C210.483 12.2749 209.906 13.2362 209.192 14.2249L180.684 55.5035C178.927 55.2289 177.444 54.7345 176.235 54.0204C175.082 53.2514 174.45 52.2353 174.34 50.9719C174.34 50.0931 174.395 49.2417 174.505 48.4178C174.67 47.5389 175.109 46.5502 175.823 45.4516L204.249 4.09062ZM188.841 17.191C188.841 21.3106 187.605 24.5239 185.133 26.8309C182.662 29.1379 179.476 30.2914 175.576 30.2914C171.621 30.2914 168.408 29.1379 165.936 26.8309C163.519 24.5239 162.311 21.3106 162.311 17.191C162.311 13.1263 163.519 9.94048 165.936 7.6335C168.408 5.32651 171.621 4.17301 175.576 4.17301C179.476 4.17301 182.662 5.32651 185.133 7.6335C187.605 9.94048 188.841 13.1263 188.841 17.191ZM172.28 17.191C172.28 18.8389 172.582 20.0748 173.187 20.8987C173.846 21.6677 174.642 22.0522 175.576 22.0522C176.51 22.0522 177.279 21.6677 177.883 20.8987C178.542 20.0748 178.872 18.8389 178.872 17.191C178.872 15.5981 178.542 14.4171 177.883 13.6481C177.279 12.8791 176.51 12.4946 175.576 12.4946C174.642 12.4946 173.846 12.8791 173.187 13.6481C172.582 14.4171 172.28 15.5981 172.28 17.191ZM223.364 42.4855C223.364 46.6051 222.128 49.8184 219.656 52.1254C217.184 54.4324 213.998 55.5859 210.098 55.5859C206.144 55.5859 202.93 54.4324 200.458 52.1254C198.042 49.8184 196.833 46.6051 196.833 42.4855C196.833 38.4208 198.042 35.235 200.458 32.928C202.93 30.621 206.144 29.4675 210.098 29.4675C213.998 29.4675 217.184 30.621 219.656 32.928C222.128 35.235 223.364 38.4208 223.364 42.4855ZM206.803 42.4855C206.803 44.1333 207.105 45.3692 207.709 46.1932C208.368 46.9622 209.165 47.3467 210.098 47.3467C211.032 47.3467 211.801 46.9622 212.405 46.1932C213.065 45.3692 213.394 44.1333 213.394 42.4855C213.394 40.8926 213.065 39.7116 212.405 38.9426C211.801 38.1736 211.032 37.7891 210.098 37.7891C209.165 37.7891 208.368 38.1736 207.709 38.9426C207.105 39.7116 206.803 40.8926 206.803 42.4855Z" fill="white" /> <path d="M161.263 75.063C161.263 73.0702 161.569 71.3074 162.182 69.7745C162.821 68.2417 163.677 66.9643 164.75 65.9423C165.849 64.8949 167.126 64.1029 168.582 63.5664C170.064 63.0299 171.661 62.7616 173.372 62.7616C175.084 62.7616 176.668 63.0299 178.124 63.5664C179.606 64.1029 180.896 64.8949 181.995 65.9423C183.093 66.9643 183.949 68.2417 184.562 69.7745C185.201 71.3074 185.521 73.0702 185.521 75.063C185.521 77.0557 185.214 78.8313 184.601 80.3897C183.988 81.9226 183.132 83.2128 182.033 84.2603C180.96 85.2822 179.683 86.0614 178.201 86.5979C176.719 87.1344 175.11 87.4027 173.372 87.4027C171.635 87.4027 170.026 87.1344 168.544 86.5979C167.062 86.0359 165.785 85.2311 164.712 84.1836C163.639 83.1362 162.796 81.846 162.182 80.3131C161.569 78.7802 161.263 77.0302 161.263 75.063ZM168.161 75.063C168.161 77.3879 168.633 79.1251 169.579 80.2748C170.524 81.4244 171.788 81.9993 173.372 81.9993C174.982 81.9993 176.259 81.4244 177.205 80.2748C178.15 79.1251 178.623 77.3879 178.623 75.063C178.623 72.7637 178.15 71.0392 177.205 69.8895C176.285 68.7398 175.02 68.165 173.411 68.165C171.827 68.165 170.549 68.7398 169.579 69.8895C168.633 71.0136 168.161 72.7381 168.161 75.063ZM205.683 63.4897C205.862 63.7707 206.015 64.154 206.143 64.6394C206.296 65.0992 206.373 65.5847 206.373 66.0956C206.373 67.092 206.155 67.8073 205.721 68.2417C205.312 68.6504 204.75 68.8548 204.035 68.8548H195.987V73.1852H204.38C204.584 73.4662 204.75 73.8367 204.878 74.2965C205.031 74.7564 205.108 75.2418 205.108 75.7528C205.108 76.7236 204.891 77.4262 204.456 77.8605C204.048 78.2693 203.486 78.4736 202.77 78.4736H196.064V86.5596C195.783 86.6362 195.336 86.7129 194.723 86.7895C194.135 86.8662 193.547 86.9045 192.96 86.9045C192.372 86.9045 191.848 86.8534 191.389 86.7512C190.954 86.6746 190.584 86.5213 190.277 86.2913C189.971 86.0614 189.741 85.7421 189.587 85.3333C189.434 84.9245 189.358 84.388 189.358 83.7238V67.0537C189.358 65.9551 189.677 65.0865 190.316 64.4478C190.954 63.8091 191.823 63.4897 192.921 63.4897H205.683ZM226.041 63.4897C226.22 63.7707 226.373 64.154 226.501 64.6394C226.654 65.0992 226.731 65.5847 226.731 66.0956C226.731 67.092 226.514 67.8073 226.08 68.2417C225.671 68.6504 225.109 68.8548 224.393 68.8548H216.346V73.1852H224.738C224.943 73.4662 225.109 73.8367 225.237 74.2965C225.39 74.7564 225.466 75.2418 225.466 75.7528C225.466 76.7236 225.249 77.4262 224.815 77.8605C224.406 78.2693 223.844 78.4736 223.129 78.4736H216.422V86.5596C216.141 86.6362 215.694 86.7129 215.081 86.7895C214.494 86.8662 213.906 86.9045 213.318 86.9045C212.731 86.9045 212.207 86.8534 211.747 86.7512C211.313 86.6746 210.942 86.5213 210.636 86.2913C210.329 86.0614 210.099 85.7421 209.946 85.3333C209.793 84.9245 209.716 84.388 209.716 83.7238V67.0537C209.716 65.9551 210.035 65.0865 210.674 64.4478C211.313 63.8091 212.182 63.4897 213.28 63.4897H226.041Z" fill="white" /> </svg>
                                <p class="woo-invoice-banner-subtext">Yearly & Lifetime All Plans</p>
                            </div>
                            <p class="woo-invoice-banner-title">Upgrade to Challan Pro Today!</p>
                            <button
                                    onclick="window.open('https://webappick.com/discount-deal/?utm_source=10YC-fix&utm_medium=free-to-pro&utm_campaign=10Y-25&utm_id=1')"
                                    class="woo-invoice-banner-btn"
                            >
                                Get 60% Off Now ➜
                            </button>
                            <div class="woo-invoice-divider-section">
                                <div class="woo-invoice-divider"></div>
                                <p class="woo-invoice-offer-text">Hurry! Offer Ends 25th Nov.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="woo-invoice-banner-container">
                    <div class="woo-invoice-banner-logo">
                        <a class="wapk-woo-invoice-banner-logo" href="<?php echo esc_url('https://webappick.com/plugin/woocommerce-pdf-invoice-packing-slips/?utm_source=customer_site&utm_medium=free_vs_pro&utm_campaign=woo_invoice_free'); ?>" target="_blank"><img src="<?php echo esc_url($woo_invoice_banner_logo_dir); ?>" alt="Woo Invoice"></a>
                    </div>
                    <div class="woo-invoice-banner-title">
                        <h2>Why Upgrade to the Challan Pro?</h2>
                        <ul>
                            <li>
                                <span>
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="ctx-flex-shrink-0 ctx-w-4 ctx-h-4 ctx-text-blue-600 dark:text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                Customizable Readymade Templates
                            </li>

                            <li>
                                <span>
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="ctx-flex-shrink-0 ctx-w-4 ctx-h-4 ctx-text-blue-600 dark:text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                Paid Stamp, Signature, Watermark
                            <li>
                                <span>
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="ctx-flex-shrink-0 ctx-w-4 ctx-h-4 ctx-text-blue-600 dark:text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                Multilingual & Multicurrency Invoice
                            </li>
                            <li>
                                <span>
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="ctx-flex-shrink-0 ctx-w-4 ctx-h-4 ctx-text-blue-600 dark:text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                Multiple Tax Classes Support
                            </li>
                            <li>
                                <span>
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="ctx-flex-shrink-0 ctx-w-4 ctx-h-4 ctx-text-blue-600 dark:text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                Add Image, QR & BAR Code
                            </li>
                            <li>
                                <span>
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="ctx-flex-shrink-0 ctx-w-4 ctx-h-4 ctx-text-blue-600 dark:text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                ZATCA-compliant (for Saudi Arabia)
                            </li>
                            <li>
                                <span>
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="ctx-flex-shrink-0 ctx-w-4 ctx-h-4 ctx-text-blue-600 dark:text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                GST Invoice (for India)
                            </li>
                            <li>
                                <span>
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="ctx-flex-shrink-0 ctx-w-4 ctx-h-4 ctx-text-blue-600 dark:text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                Compatible with a Variety of Plugins
                            </li>
                            <li>
                                <span>
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="ctx-flex-shrink-0 ctx-w-4 ctx-h-4 ctx-text-blue-600 dark:text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                Dedicated Customer Support and Live Chat
                            </li>
                            <li>
                                <span>
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" class="ctx-flex-shrink-0 ctx-w-4 ctx-h-4 ctx-text-blue-600 dark:text-blue-500" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                30-Day Money Back Guarantee
                            </li>

                        </ul>

                    </div>
                    <button class="woo-invoice-banner-btn" onclick=" window.open('https://webappick.com/plugin/woocommerce-pdf-invoice-packing-slips', '_blank'); return false;">
                        Get Challan pro Now
                        <svg width="20" height="20" fill="#fff" viewBox="0 0 24 24" version="1.2" baseProfile="tiny" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.396 18.433 17 12l-6.604-6.433A2 2 0 0 0 7 7v10a2 2 0 0 0 3.396 1.433z" />
                        </svg>
                    </button>


                </div>
            </div>
        </div><!-- end .woo-invoice-col-sm-4 -->
    </div><!-- end .woo-invoice-row -->
</li>
<!--END FREE VS PREMIUM TAB-->