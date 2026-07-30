<!--START SETTING TAB-->
<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$challan_order_meta_query = challan_order_meta_query();
$challan_product_meta_query = challan_product_meta_query();
$challan_item_meta_query = challan_item_meta_query();
// $woo_invoice_banner_bg_dir = plugin_dir_url(dirname(__FILE__)) . 'images/get_challan_banner_bg.png';

?>
<li>

    <div class="woo-invoice-row">
        <div class="woo-invoice-col-sm-8 woo-invoice-col-12">
            <div class="woo-invoice-card woo-invoice-mr-0">
                <div class="woo-invoice-card-body">
                    <form action="" method="post">
                        <?php wp_nonce_field( 'invoice_form_nonce', 'invoice_form_nonce' ); ?>
                        <div class="woo-invoice-header-title woo-invoice-mt-0">
                            <h4><?php esc_html_e('General Settings', 'webappick-pdf-invoice-for-woocommerce'); ?></h4>
                        </div>
                        <div class="woo-invoice-form-group">
                            <div class="woo-invoice-custom-control woo-invoice-custom-switch" style="padding-left:0!important;">
                                <div class="woo-invoice-toggle-label">
                                    <span class="woo-invoice-checkbox-label"><?php esc_html_e('Enable Invoicing', 'webappick-pdf-invoice-for-woocommerce'); ?></span>
                                </div>
                                <div class="woo-invoice-toggle-container" tooltip="Enable Invoicing to generate automatically." flow="right">
                                    <input type="hidden" name="wpifw_invoicing" value="0">
                                    <input type="checkbox" class="woo-invoice-custom-control-input" id="wpifw_invoicing" name="wpifw_invoicing" value="1" <?php checked(get_option('wpifw_invoicing'), $woo_invoice_current, true); ?>>
                                    <label class="woo-invoice-custom-control-label" for="wpifw_invoicing"></label>
                                </div>
                            </div>
                        </div><!-- end .woo-invoice-form-group -->
                        <div class="woo-invoice-form-group">
                            <div class="woo-invoice-custom-control woo-invoice-custom-switch" style="padding-left:0!important;">
                                <div class="woo-invoice-toggle-label">
                                    <span class="woo-invoice-checkbox-label"><?php esc_html_e('Allow My Account To Download Invoice', 'webappick-pdf-invoice-for-woocommerce'); ?></span>
                                </div>
                                <div class="woo-invoice-toggle-container" tooltip="Allow customer to download invoice from my account order list." flow="right">
                                    <input type="hidden" name="wpifw_download" value="0">
                                    <input title="Allow Customer to Download Invoice From My Account" type="checkbox" class="woo-invoice-custom-control-input" id="wpifw_download" name="wpifw_download" value="1" <?php checked(get_option('wpifw_download'), $woo_invoice_current, true); ?>>
                                    <label class="woo-invoice-custom-control-label tips" for="wpifw_download" title="Allow Customer to Download Invoice From My Account"></label>
                                </div>
                            </div>
                        </div><!-- end .woo-invoice-form-group -->

                        <!--  Allow to download invoice from my account base on order status.-->
                        <div class="woo-invoice-form-group" id="downloadAttechedData" style="display:
                        <?php
                        if ((get_option('wpifw_download') == 1)) {
                            echo 'block';
                        } else {
                            echo 'none';
                        }
                        ?>
                                ">
                            <div class="woo-invoice-custom-checkbox-label"></div>
                            <div class="woo-invoice-custom-checkbox-container">
                                <div class="woo-invoice-custom-control woo-invoice-custom-checkbox">

                                    <input type="checkbox" name="wpifw_invoice_download_check_list[]" class="woo-invoice-custom-control-input" id="downloadChecked1" value="processing" <?php
                                    if (in_array('processing', $wpifw_invoice_download_check_list)) {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <label class="woo-invoice-custom-control-label" for="downloadChecked1"><?php esc_html_e('Processing Order', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                                </div>
                                <div class="woo-invoice-custom-control woo-invoice-custom-checkbox">
                                    <input type="checkbox" name="wpifw_invoice_download_check_list[]" class="woo-invoice-custom-control-input" id="downloadChecked5" value="on-hold" <?php
                                    if (in_array('on-hold', $wpifw_invoice_download_check_list)) {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <label class="woo-invoice-custom-control-label" for="downloadChecked5"><?php esc_html_e('On Hold Order', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                                </div>
                                <div class="woo-invoice-custom-control woo-invoice-custom-checkbox">

                                    <input type="checkbox" name="wpifw_invoice_download_check_list[]" class="woo-invoice-custom-control-input" id="downloadChecked2" value="completed" <?php
                                    if (in_array('completed', $wpifw_invoice_download_check_list)) {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <label class="woo-invoice-custom-control-label" for="downloadChecked2"><?php esc_html_e('Complete Order', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                                </div>
                                <div class="woo-invoice-custom-control woo-invoice-custom-checkbox">
                                    <input type="checkbox" name="wpifw_invoice_download_check_list[]" class="woo-invoice-custom-control-input" id="downloadChecked3" value="payment_complete" <?php
                                    if (in_array('payment_complete', $wpifw_invoice_download_check_list)) {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <label class="woo-invoice-custom-control-label" for="downloadChecked3"><?php esc_html_e('After Payment Complete', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                                </div>
                                <div class="woo-invoice-custom-control woo-invoice-custom-checkbox">

                                    <input type="checkbox" name="wpifw_invoice_download_check_list[]" class="woo-invoice-custom-control-input" id="downloadChecked4" value="always_allow" <?php
                                    if (in_array('always_allow', $wpifw_invoice_download_check_list)) {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <label class="woo-invoice-custom-control-label" for="downloadChecked4"><?php esc_html_e('Always Allow', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="woo-invoice-form-group">
                            <div class="woo-invoice-custom-control woo-invoice-custom-switch" style="padding-left:0!important;">
                                <div class="woo-invoice-toggle-label">
                                    <span class="woo-invoice-checkbox-label"><?php esc_html_e('Invoice Attach to Email', 'webappick-pdf-invoice-for-woocommerce'); ?></span>
                                </div>

                                <div class="woo-invoice-toggle-container" tooltip="Attach Invoice to completed order email." flow="right">
                                    <input type="hidden" name="wpifw_order_email" value="0">

                                    <input type="checkbox" id="atttoorder" class="woo-invoice-custom-control-input atttoorder" name="wpifw_order_email" value="1" <?php checked(get_option('wpifw_order_email'), $woo_invoice_current, true); ?>>
                                    <label class="woo-invoice-custom-control-label tips" for="atttoorder"></label>
                                </div>
                            </div>
                        </div><!-- end .woo-invoice-form-group -->
                        <!-- Attach Invoice with email based on order status.-->
                        <div class="woo-invoice-form-group" id="emailAttechedData" style="display:
                        <?php
                        if ((get_option('wpifw_order_email') == 1)) {
                            echo 'block';
                        } else {
                            echo 'none';
                        }
                        ?>
                                ">
                            <span class="woo-invoice-custom-checkbox-label"></span>
                            <div class="woo-invoice-custom-checkbox-container">
                                <div class="woo-invoice-custom-control woo-invoice-custom-checkbox">

                                    <input type="checkbox" name="wpifw_email_attach_check_list[]" class="woo-invoice-custom-control-input" id="emailChecked1" value="new_order" <?php
                                                                                                                                                                                if (in_array('new_order', $wpifw_email_attach_check_list)) {
                                                                                                                                                                                    echo 'checked';
                                                                                                                                                                                }
                                                                                                                                                                                ?>>
                                    <label class="woo-invoice-custom-control-label" for="emailChecked1"><?php esc_html_e('New Order', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                                </div>

                                <div class="woo-invoice-custom-control woo-invoice-custom-checkbox">

                                    <input type="checkbox" name="wpifw_email_attach_check_list[]" class="woo-invoice-custom-control-input" id="emailChecked6" value="customer_completed_order" <?php
                                    if (in_array('customer_completed_order', $wpifw_email_attach_check_list)) {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <label class="woo-invoice-custom-control-label" for="emailChecked6"><?php esc_html_e('Completed Order', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                                </div>

                                <div class="woo-invoice-custom-control woo-invoice-custom-checkbox">

                                    <input type="checkbox" name="wpifw_email_attach_check_list[]" class="woo-invoice-custom-control-input" id="emailChecked9" value="customer_on_hold_order" <?php
                                    if (in_array('customer_on_hold_order', $wpifw_email_attach_check_list)) {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <label class="woo-invoice-custom-control-label" for="emailChecked9"><?php esc_html_e('On Hold Order', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                                </div>
                                <div class="woo-invoice-custom-control woo-invoice-custom-checkbox">

                                    <input type="checkbox" name="wpifw_email_attach_check_list[]" class="woo-invoice-custom-control-input" id="emailChecked5" value="customer_processing_order" <?php
                                                                                                                                                                                                if (in_array('customer_processing_order', $wpifw_email_attach_check_list)) {
                                                                                                                                                                                                    echo 'checked';
                                                                                                                                                                                                }
                                                                                                                                                                                                ?>>
                                    <label class="woo-invoice-custom-control-label" for="emailChecked5"><?php esc_html_e('Processing Order', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                                </div>
                                <div class="woo-invoice-custom-control woo-invoice-custom-checkbox">

                                    <input type="checkbox" name="wpifw_email_attach_check_list[]" class="woo-invoice-custom-control-input" id="emailChecked7" value="customer_refunded_order" <?php
                                    if (in_array('customer_refunded_order', $wpifw_email_attach_check_list)) {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <label class="woo-invoice-custom-control-label" for="emailChecked7"><?php esc_html_e('Refunded Order', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                                </div>
                                <div class="woo-invoice-custom-control woo-invoice-custom-checkbox">

                                    <input type="checkbox" name="wpifw_email_attach_check_list[]" class="woo-invoice-custom-control-input" id="emailChecked8" value="customer_invoice" <?php
                                    if (in_array('customer_invoice', $wpifw_email_attach_check_list)) {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <label class="woo-invoice-custom-control-label" for="emailChecked8"><?php esc_html_e('Customer Invoice', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                                </div>
                            </div>
                        </div>

                        <!--document output type html -->
                        <div class="woo-invoice-form-group">
                            <div class="woo-invoice-custom-control woo-invoice-custom-switch" style="padding-left:0!important;">
                                <div class="woo-invoice-toggle-label">
                                    <span class="woo-invoice-checkbox-label"><?php esc_html_e('Document Output Type HTML', 'webappick-pdf-invoice-for-woocommerce'); ?></span>
                                </div>
                                <div class="woo-invoice-toggle-container" tooltip="Document output type html. Default is PDF." flow="right">
                                    <input type="hidden" name="wpifw_output_type_html" value="0">
                                    <input type="checkbox" id="atttoorder09" class="woo-invoice-custom-control-input atttoorder09" name="wpifw_output_type_html" value="1" <?php checked(get_option('wpifw_output_type_html'), $woo_invoice_current, true); ?>>
                                    <label class="woo-invoice-custom-control-label tips" for="atttoorder09"></label>
                                </div>
                            </div>
                        </div><!-- end document output type html -->

                        <div class="woo-invoice-form-group">
                            <div class="woo-invoice-custom-control woo-invoice-custom-switch" style="padding-left:0!important;">
                                <div class="woo-invoice-toggle-label">
                                    <span class="woo-invoice-checkbox-label"><?php esc_html_e('Display Currency Code', 'webappick-pdf-invoice-for-woocommerce'); ?></span>
                                </div>
                                <div class="woo-invoice-toggle-container" tooltip="" flow="right">
                                    <input type="hidden" name="wpifw_currency_code" value="0">
                                    <input type="checkbox" class="woo-invoice-custom-control-input" id="discurrency" name="wpifw_currency_code" value="1" <?php checked(get_option('wpifw_currency_code'), $woo_invoice_current, true); ?>>
                                    <label class="woo-invoice-custom-control-label" title="Display Currency Code into Invoice Total" for="discurrency"></label>
                                </div>
                            </div>
                        </div><!-- end .woo-invoice-form-group -->
                        <div class="woo-invoice-form-group woo-invoice-template-select" tooltip="" flow="right">
                            <label class="woo-invoice-custom-label" for="templateid"><?php esc_html_e('Invoice Template', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <a class="woo-invoice-btn woo-invoice-btn-primary" data-toggle="modal" data-target="#winvoiceModalTemplates" style="color:#fff"><?php esc_html_e('Select Template', 'webappick-pdf-invoice-for-woocommerce'); ?></a>
                            <div class="woo-invoice-modal fade" id="winvoiceModalTemplates" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="woo-invoice-modal-dialog woo-invoice-modal-dialog-centered" role="document">
                                    <div class="woo-invoice-modal-content">
                                        <div class="woo-invoice-modal-card" data-toggle="lists" data-lists-values="[&quot;name&quot;]">
                                            <div class="woo-invoice-card-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true" style="font-size: 30px;text-align: right;display: block;">×</span>
                                                </button>
                                            </div>
                                            <div class="woo-invoice-card-body" style="height:650px">
                                                <div class="woo-invoice-row">
                                                    <div class="woo-invoice-col-sm-4">
                                                        <a href="#" class="woo-invoice-template-selection" data-template="invoice-1"><img src="<?php echo esc_attr(CHALLAN_FREE_PLUGIN_URL . 'admin/images/templates/invoice-1.png'); //phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>" alt="" style="
                                                            <?php
                                                            if ('invoice-1' === get_option('wpifw_templateid')) {
                                                                echo esc_attr($woo_invoice_style2);
                                                            } else {
                                                                echo esc_attr($woo_invoice_style);
                                                            }
                                                            ?>
                                                                    "></a>
                                                    </div>
                                                    <div class="woo-invoice-col-sm-4">
                                                        <a href="#" class="woo-invoice-template-selection" data-template="invoice-2"><img src="<?php echo esc_attr(CHALLAN_FREE_PLUGIN_URL . 'admin/images/templates/invoice-2.png'); //phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>" alt="" style="
                                                            <?php
                                                            if ('invoice-2' === get_option('wpifw_templateid')) {
                                                                echo esc_attr($woo_invoice_style2);
                                                            } else {
                                                                echo esc_attr($woo_invoice_style);
                                                            }
                                                            ?>
                                                                    "></a>
                                                    </div>

                                                    <div class="woo-invoice-col-sm-4" style="position:relative">
                                                        <a href="#" class="woo-invoice-element-disable" data-template="" style="position: absolute;top: 0;z-index: 3333;"><img src="<?php echo esc_attr(CHALLAN_FREE_PLUGIN_URL . 'admin/images/templates/invoice-3.png'); //phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>" alt="" style="max-width:80%;display:block;margin:0 auto;border: 3px solid #0F74A6;"></a>
                                                        <div style="width: 80%;height: 284px;position: absolute;top: 0;z-index:1111;background: #ddd;opacity: 0.7;margin-left: 25px;"></div>
                                                        <a href="https://webappick.com/plugin/woocommerce-pdf-invoice-packing-slips/?utm_source=customer_site&utm_medium=free_vs_pro&utm_campaign=woo_invoice_free" target="_blank" style="position: absolute;top: 0;z-index: 2222;background: #DC4C40;color: #fff;padding: 5px;border-radius: 3px;text-transform: uppercase;margin: 120px 80px;">Premium</a>
                                                    </div>
                                                    <div class="woo-invoice-col-sm-4" style="position:relative;margin-top: 25px;">
                                                        <a href="#" class="woo-invoice-element-disable" data-template="" style="position: absolute;top: 0;z-index: 3333;"><img src="<?php echo esc_attr(CHALLAN_FREE_PLUGIN_URL . 'admin/images/templates/invoice-4.png'); //phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>" alt="" style="max-width:80%;display:block;margin:0 auto;border: 3px solid #0F74A6;"></a>
                                                        <div style="width: 80%;height: 281px;position: absolute;top: 0;z-index:1111;background: #ddd;opacity: 0.7;margin-left: 25px;"></div>
                                                        <a href="https://webappick.com/plugin/woocommerce-pdf-invoice-packing-slips/?utm_source=customer_site&utm_medium=free_vs_pro&utm_campaign=woo_invoice_free" target="_blank" style="position: absolute;top: 0;z-index: 2222;background: #DC4C40;color: #fff;padding: 5px;border-radius: 3px;text-transform: uppercase;margin: 120px 80px;">Premium</a>
                                                    </div>
                                                    <div class="woo-invoice-col-sm-4" style="position:relative;margin-top: 25px;">
                                                        <a href="#" class="woo-invoice-element-disable" data-template="" style="position: absolute;top: 0;z-index: 3333;"><img src="<?php echo esc_attr(CHALLAN_FREE_PLUGIN_URL . 'admin/images/templates/invoice-5.png'); //phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>" alt="" style="max-width:80%;display:block;margin:0 auto;border: 3px solid #0F74A6;"></a>
                                                        <div style="width: 80%;height: 281px;position: absolute;top: 0;z-index:1111;background: #ddd;opacity: 0.7;margin-left: 25px;"></div>
                                                        <a href="https://webappick.com/plugin/woocommerce-pdf-invoice-packing-slips/?utm_source=customer_site&utm_medium=free_vs_pro&utm_campaign=woo_invoice_free" target="_blank" style="position: absolute;top: 0;z-index: 2222;background: #DC4C40;color: #fff;padding: 5px;border-radius: 3px;text-transform: uppercase;margin: 120px 80px;">Premium</a>
                                                    </div>
                                                    <div class="woo-invoice-col-sm-4" style="position:relative;margin-top: 25px;">
                                                        <a href="#" class="woo-invoice-element-disable" data-template="" style="position: absolute;top: 0;z-index: 3333;"><img src="<?php echo esc_attr(CHALLAN_FREE_PLUGIN_URL . 'admin/images/templates/invoice-6.png'); //phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>" alt="" style="max-width:80%;display:block;margin:0 auto;border: 3px solid #0F74A6;"></a>
                                                        <div style="width: 80%;height: 281px;position: absolute;top: 0;z-index:1111;background: #ddd;opacity: 0.7;margin-left: 25px;"></div>
                                                        <a href="https://webappick.com/plugin/woocommerce-pdf-invoice-packing-slips/?utm_source=customer_site&utm_medium=free_vs_pro&utm_campaign=woo_invoice_free" target="_blank" style="position: absolute;top: 0;z-index: 2222;background: #DC4C40;color: #fff;padding: 5px;border-radius: 3px;text-transform: uppercase;margin: 120px 80px;">Premium</a>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="woo-invoice-card-footer">
                                                <button class="woo-invoice-btn woo-invoice-btn-primary" data-dismiss="modal" aria-label="Close" style="float:right;margin-bottom: 20px;">Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end .woo-invoice-template -->

                        <div class="woo-invoice-form-group" tooltip="" flow="right">
                            <label class="woo-invoice-custom-label" for="woo_invoice_paper_size"> <?php esc_html_e('Paper Size', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <select class="woo-invoice-fixed-width woo-invoice-select-control" id="woo_invoice_paper_size" name="wpifw_invoice_paper_size">
                                <option value="A4" <?php selected(get_option('wpifw_invoice_paper_size'), 'A4', true); ?>>A4</option>
                                <option value="A5" <?php selected(get_option('wpifw_invoice_paper_size'), 'A5', true); ?>>A5</option>
                                <option value="letter" <?php selected(get_option('wpifw_invoice_paper_size'), 'letter', true); ?>>Letter</option>
                            </select>
                        </div><!-- end .woo-invoice-form-group -->

                        <div class="woo-invoice-header-title">
                            <h4><?php esc_html_e('Order Settings', 'webappick-pdf-invoice-for-woocommerce'); ?></h4>
                        </div>

                        <div class="woo-invoice-form-group" tooltip="" flow="right">
                            <label class="woo-invoice-custom-label" for="invno"><?php esc_html_e('Next Invoice No.', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <input class="woo-invoice-fixed-width woo-invoice-form-control woo-invoice-number-input" type="number" id="invno" name="wpifw_invoice_no" value="<?php echo esc_attr(get_option('wpifw_invoice_no')); ?>">
                        </div><!-- end .woo-invoice-form-group -->

                        <div class="woo-invoice-form-group" tooltip="" flow="right">
                            <label class="woo-invoice-custom-label" for="invprefix"><?php esc_html_e('Invoice No. Prefix', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <input class="woo-invoice-fixed-width woo-invoice-form-control" type="text" id="invprefix" name="wpifw_invoice_no_prefix" value="<?php echo esc_attr(get_option('wpifw_invoice_no_prefix')); ?>">
                        </div><!-- end .woo-invoice-form-group -->

                        <div class="woo-invoice-form-group" tooltip="" flow="right">
                            <label class="woo-invoice-custom-label" for="insuff"><?php esc_html_e('Invoice No. Suffix', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <input class="woo-invoice-fixed-width woo-invoice-form-control" type="text" id="insuff" name="wpifw_invoice_no_suffix" value="<?php echo esc_attr(get_option('wpifw_invoice_no_suffix')); ?>">
                        </div><!-- end .woo-invoice-form-group -->
                        <div class="woo-invoice-form-group" tooltip="" flow="right">
                            <label class="woo-invoice-custom-label" for="wpifw_pdf_invoice_button_behaviour"> <?php esc_html_e('Invoice Download as', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <select class="woo-invoice-fixed-width woo-invoice-select-control" id="wpifw_pdf_invoice_button_behaviour" name="wpifw_pdf_invoice_button_behaviour">
                                <option value="new_tab" <?php selected(get_option('wpifw_pdf_invoice_button_behaviour'), 'new_tab', true); ?>>
                                    Open in new tab
                                </option>
                                <option value="download" <?php selected(get_option('wpifw_pdf_invoice_button_behaviour'), 'download', true); ?>>
                                    Direct download
                                </option>
                            </select>
                        </div><!-- end .woo-invoice-form-group -->
                        <div class="woo-invoice-form-group" tooltip="" flow="right">
                            <label class="woo-invoice-custom-label" for="date"> <?php esc_html_e('Date Format', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <select class="woo-invoice-fixed-width woo-invoice-select-control" id="date" name="wpifw_date_format">
                                <option value="d M, o" <?php selected(get_option('wpifw_date_format'), 'd M, o', true); ?>>
                                    Date Month, Year
                                </option>
                                <option value="m/d/y" <?php selected(get_option('wpifw_date_format'), 'm/d/y', true); ?>>
                                    mm/dd/yy
                                </option>
                                <option value="d/m/y" <?php selected(get_option('wpifw_date_format'), 'd/m/y', true); ?>>
                                    dd/mm/yy
                                </option>
                                <option value="y/m/d" <?php selected(get_option('wpifw_date_format'), 'y/m/d', true); ?>>
                                    yy/mm/dd
                                </option>
                                <option value="d/m/Y" <?php selected(get_option('wpifw_date_format'), 'd/m/Y', true); ?>>
                                    dd/mm/yyyy
                                </option>
                                <option value="Y/m/d" <?php selected(get_option('wpifw_date_format'), 'Y/m/d', true); ?>>
                                    yyyy/mm/dd
                                </option>
                                <option value="m/d/Y" <?php selected(get_option('wpifw_date_format'), 'm/d/Y', true); ?>>
                                    mm/dd/yyyy
                                </option>
                                <option value="y-m-d" <?php selected(get_option('wpifw_date_format'), 'y-m-d', true); ?>>
                                    yy-mm-dd
                                </option>
                                <option value="Y-m-d" <?php selected(get_option('wpifw_date_format'), 'Y-m-d', true); ?>>
                                    yyyy-mm-dd
                                </option>
                            </select>
                        </div><!-- end .woo-invoice-form-group -->


                        <?php $wpifw_payment_method_show = get_option('wpifw_payment_method_show') == '' ? true : get_option('wpifw_payment_method_show'); ?>
                        <div class="woo-invoice-form-group">
                            <div class="woo-invoice-custom-control woo-invoice-custom-switch" style="padding-left:0!important;">
                                <div class="woo-invoice-toggle-label">
                                    <span class="woo-invoice-checkbox-label"><?php esc_html_e('Display Payment Method', 'webappick-pdf-invoice-for-woocommerce'); ?></span>
                                </div>
                                <div class="woo-invoice-toggle-container" tooltip="" flow="right">
                                    <input type="hidden" name="wpifw_payment_method_show" value="0">
                                    <input type="checkbox" class="woo-invoice-custom-control-input" id="disPayment" name="wpifw_payment_method_show" value="1" <?php checked($wpifw_payment_method_show, $woo_invoice_current, true); ?>>
                                    <label class="woo-invoice-custom-control-label" title="Display Payment Method into Invoice" for="disPayment"></label>
                                </div>
                            </div>
                        </div><!-- end .woo-invoice-form-group -->
                        <!-- ===========================
                         Add order meta for invoice.
                         =========================== -->
                        <div class="woo-invoice-form-group" tooltip="" flow="right">
                            <label class="woo-invoice-fixed-label woo-invoice-custom-label" for="wpifw_product_order_meta_show"> <?php esc_html_e('Add Order Meta', 'webappick-pdf-invoice-for-woocommerce'); ?></label>

                            <a href="#" class="woo-invoice-add-order-meta">
                                <div class="woo-invoice-add-order-meta-btn">
                                    <span class="dashicons dashicons-plus-alt"></span>
                                    <?php esc_html_e('Add Order Meta', 'webappick-pdf-invoice-for-woocommerce'); ?>
                                </div>
                            </a>

                            <div class="woo_invoice_order_meta" style="float: none; padding-top: 10px;">
                                <?php
                                //                                update_option('_winvoice_order_meta_label', ['email']);
                                //                                update_option('_winvoice_order_meta_name', ['_billing_email']);
                                //                                update_option('_winvoice_order_meta_name_position', ['after_order_meta']);

                                $order_meta_label = get_option('_winvoice_order_meta_label');
                                $order_meta_name = get_option('_winvoice_order_meta_name');
                                $order_meta_place = get_option('_winvoice_order_meta_name_position');

                                if (is_array($order_meta_label) && !empty($order_meta_label)) {
                                    foreach ($order_meta_label as $key => $value) {
                                        ?>
                                        <div class="woo_invoice_order_meta_html woo_invoice_meta_html woo_invoice_col_3">
                                            <input type="text" placeholder="Meta Label" class="woo-invoice-form-control woo-invoice-order-meta-label" name="_winvoice_order_meta_label[]" value="<?php echo $value; //phpcs:ignore 
                                            ?>">
                                            <select id="_winvoice_order_meta_name" class="woo-invoice-select-control woo-invoice-order-meta" name="_winvoice_order_meta_name[]">
                                                <option value="" disabled><?php esc_html_e('Select Order Meta', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                                                <?php
                                                if (count($challan_order_meta_query)) {
                                                    foreach ($challan_order_meta_query as $meta) {
                                                        $selected = $order_meta_name[$key] == $meta->meta_key ? 'selected' : '';
                                                        echo '<option value=' . $meta->meta_key . ' ' . $selected . ' >' . $meta->meta_key . '</option>'; //phpcs:ignore
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <!--where to show?-->
                                            <select id="wpifw_product_order_meta_position" class="woo-invoice-select-control woo-invoice-order-meta-position" name="_winvoice_order_meta_name_position[]">
                                                <option value="" disabled><?php esc_html_e('Where to show ?', 'webappick-pdf-invoice-for-woocommerce'); ?></option>

                                                <?php
                                                if (count(challan_order_meta_data_position())) {
                                                    foreach (challan_order_meta_data_position() as $index => $val) {
                                                        $selected = $index == $order_meta_place[$key] ? 'selected' : '';
                                                        echo '<option value=' . $index . ' ' . $selected . '>' . $val . '</option>'; //phpcs:ignore
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <a href="#" class="woo-invoice-delete-order-meta"><span class="dashicons dashicons-trash" style="color:#D94D40"></span></a>
                                        </div>

                                        <?php
                                    }
                                } else {

                                    ?>
                                    <div style="display: none;">
                                        <div class="woo_invoice_order_meta_html woo_invoice_meta_html woo_invoice_col_3">
                                            <input data="2" type="text" placeholder="Meta Label" class="woo-invoice-form-control woo-invoice-order-meta-label" name="_winvoice_order_meta_label[]">
                                            <select id="wpifw_product_order_meta_show" class="woo-invoice-select-control woo-invoice-order-meta" name="_winvoice_order_meta_name[]">
                                                <option value="" disabled><?php esc_html_e('Select Order Meta', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                                                <?php
                                                if (count($challan_order_meta_query)) {
                                                    foreach ($challan_order_meta_query as $meta) {
                                                        echo '<option value=' . $meta->meta_key . '>' . $meta->meta_key . '</option>'; //phpcs:ignore
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <!--where to show?-->
                                            <select id="wpifw_product_order_meta_position" class="woo-invoice-select-control woo-invoice-order-meta-position" name="_winvoice_order_meta_name_position[]">
                                                <option value="" disabled><?php esc_html_e('Where to show ?', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                                                <?php

                                                if (count(challan_order_meta_data_position())) {
                                                    foreach (challan_order_meta_data_position() as $key => $val) {
                                                        echo '<option value=' . $key . '>' . $val . '</option>'; //phpcs:ignore
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <a href="#" class="woo-invoice-delete-order-meta"><span class="dashicons dashicons-trash" style="color:#D94D40"></span></a>
                                        </div>

                                    </div>

                                <?php  } ?>
                            </div>
                            <p style="opacity: .7" class="woo_invoice_meta_html woo_invoice_col_3">Notice: If order meta value is empty or an array will not display.</p>

                        </div>
                        <!-- ===========================
                         End Add order meta for invoice.
                         =========================== -->
                        <div class="woo-invoice-header-title">
                            <h4><?php esc_html_e('Product Settings', 'webappick-pdf-invoice-for-woocommerce'); ?></h4>
                        </div>
                        <div class="woo-invoice-form-group" tooltip="Keep Empty for no limit" flow="right">
                            <label class="woo-invoice-custom-label" for="title-limit"><?php esc_html_e('Product Title limit', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <input class="woo-invoice-fixed-width woo-invoice-number-input woo-invoice-form-control" type="number" id="title-limit" name="wpifw_invoice_product_title_length" value="<?php echo esc_attr(get_option('wpifw_invoice_product_title_length')); ?>">
                        </div><!-- end .woo-invoice-form-group -->

                        <div class="woo-invoice-form-group" tooltip="" flow="right">
                            <label class="woo-invoice-custom-label" for="disid"> <?php esc_html_e('Display ID/SKU', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <select class="woo-invoice-fixed-width woo-invoice-select-control" id="disid" name="wpifw_disid">
                                <option value="SKU" <?php selected(get_option('wpifw_disid'), 'SKU', true); ?>><?php esc_html_e('SKU', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                                <option value="ID" <?php selected(get_option('wpifw_disid'), 'ID', true); ?>><?php esc_html_e('ID', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                                <option value="None" <?php selected(get_option('wpifw_disid'), 'None', true); ?>><?php esc_html_e('None', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                            </select>
                        </div><!-- end .woo-invoice-form-group -->

                        <!--===========================
                        Add Product meta for invoice.
                        =========================== -->
                        <div class="woo-invoice-form-group" tooltip="" flow="right">
                            <label class="woo-invoice-fixed-label woo-invoice-custom-label" for="wpifw_product_post_meta_show"> <?php esc_html_e('Add Product Meta', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <a href="#" class="woo-invoice-product-add-meta">
                                <div class="woo-invoice-product-add-meta">
                                    <span class="dashicons dashicons-plus-alt"></span>
                                    <?php esc_html_e('Add Product Meta', 'webappick-pdf-invoice-for-woocommerce'); ?>
                                </div>
                            </a>
                            <div class="woo_invoice_product_meta" style="float:none; padding-top: 10px;">
                                <?php
                                $post_meta_label = get_option('_winvoice_post_meta_label');
                                $post_meta_name = get_option('_winvoice_post_meta_name');
                                if (false == $post_meta_label) {
                                    $post_meta_label = '';
                                }
                                if ($post_meta_label) {
                                    ?>
                                    <div class="woo_invoice_product_meta_html woo_invoice_meta_html woo_invoice_col_2">

                                        <input type="text" placeholder="Meta Label" class="woo-invoice-form-control woo-invoice-product-post-meta-label" name="_winvoice_post_meta_label" value="<?php echo esc_html($post_meta_label); ?>">

                                        <select id="wpifw_product_post_meta_show" class="woo-invoice-select-control woo-invoice-product-post-meta" name="_winvoice_post_meta_name">
                                            <option value="" <?php selected(get_option('wpifw_product_post_meta_show'), '', true); ?>><?php esc_html_e('Select Post Meta', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                                            <?php
                                            if (!empty($challan_product_meta_query)) {
                                                foreach ($challan_product_meta_query as $meta) {
                                                    $selected = $post_meta_name == $meta->meta_key ? 'selected' : '';
                                                    echo '<option value=' . $meta->meta_key . ' ' . $selected . '>' . $meta->meta_key . '</option>'; //phpcs:ignore
                                                }
                                            }
                                            ?>
                                        </select>
                                        <a href="#" class="woo-invoice-delete-product-meta"><span class="dashicons dashicons-trash" style="color:#D94D40"></span></a>
                                    </div>
                                    <?php

                                } else {
                                    ?>
                                    <div style="display: none">
                                        <div class="woo_invoice_product_meta_html woo_invoice_meta_html woo_invoice_col_2">
                                            <input type="text" name="_winvoice_post_meta_label" placeholder="Meta Label" class="woo-invoice-form-control woo-invoice-product-post-meta-label">
                                            <select name="_winvoice_post_meta_name" id="wpifw_product_post_meta_show" class="woo-invoice-select-control woo-invoice-product-post-meta">
                                                <option disabled value="" <?php selected(get_option('wpifw_product_post_meta_show'), '', true); ?>><?php esc_html_e('Select Post Meta', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                                                <?php
                                                if (count($challan_product_meta_query)) {
                                                    foreach ($challan_product_meta_query as $meta) {
                                                        echo '<option value=' . esc_attr($meta->meta_key) . ' ' . selected(get_option('wpifw_product_post_meta_show'), $meta->meta_key, true) . '>' . esc_attr($meta->meta_key) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <a href="#" class="woo-invoice-delete-product-meta"><span class="dashicons dashicons-trash" style="color:#D94D40"></span></a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <p style="opacity: .7" class="woo_invoice_meta_html woo_invoice_col_3">Notice: If product meta value is empty or an array will not display.</p>

                        </div>
                        <!-- ==================================
                            End Add Product meta for invoice.
                         ================================== -->

                        <!-- ===============================
                            Add order item meta for invoice.
                         =============================== -->

                        <div class="woo-invoice-form-group" tooltip="" flow="right">
                            <label class="woo-invoice-fixed-label woo-invoice-custom-label" for="wpifw_order_item_meta_show"> <?php esc_html_e('Add Order Item Meta', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <a href="#" class="woo-invoice-add-order-item-meta">
                                <div class="woo-invoice-add-order-item-meta">
                                    <span class="dashicons dashicons-plus-alt"></span>
                                    <?php esc_html_e('Add Order Item Meta', 'webappick-pdf-invoice-for-woocommerce'); ?>
                                </div>
                            </a>
                            <div class="woo_invoice_order_item_meta" style="float:none; padding-top: 10px;">
                                <?php
                                $item_meta_label = get_option('_winvoice_order_item_meta_label');
                                $item_meta_name = get_option('_winvoice_order_item_meta_name');

                                //                                print_r($challan_item_meta_query);
                                if (false == $item_meta_label) {
                                    $item_meta_label = '';
                                }
                                if ($item_meta_label) {
                                    ?>
                                    <div class="woo_invoice_order_item_meta_html woo_invoice_meta_html woo_invoice_col_2">
                                        <input type="text" placeholder="Item Meta Label" class="woo-invoice-form-control woo-invoice-order-item-meta-label" name="_winvoice_order_item_meta_label" value="<?php echo esc_html($item_meta_label); ?>">
                                        <select id="wpifw_order_item_meta_show" class="woo-invoice-select-control woo-invoice-order-item-meta" name="_winvoice_order_item_meta_name">
                                            <option value="" <?php selected(get_option('wpifw_order_item_meta_show'), '', true); ?>><?php esc_html_e('Select Order Item Meta', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                                            <?php
                                            if (!empty($challan_item_meta_query)) {
                                                foreach ($challan_item_meta_query as $meta) {
                                                    $selected = $item_meta_name == $meta->meta_key ? 'selected' : '';
                                                    echo '<option value="' . esc_attr( $meta->meta_key ) . '" ' . $selected . '>' . esc_html( $meta->meta_key ) . '</option>'; //phpcs:ignore
                                                }
                                            }
                                            ?>
                                        </select>
                                        <a href="#" class="woo-invoice-delete-order-item-meta"><span class="dashicons dashicons-trash" style="color:#D94D40"></span></a>
                                    </div>
                                    <?php

                                } else {
                                    ?>
                                    <div style="display: none">
                                        <div class="woo_invoice_order_item_meta_html woo_invoice_meta_html woo_invoice_col_2">
                                            <input type="text" name="_winvoice_order_item_meta_label" placeholder="Item Meta Label" class="woo-invoice-form-control woo-invoice-order-item-meta-label">
                                            <select name="_winvoice_order_item_meta_name" id="wpifw_order_item_meta_show" class="woo-invoice-select-control woo-invoice-order-item-meta">
                                                <option disabled value="" <?php selected(get_option('wpifw_order_item_meta_show'), '', true); ?>><?php esc_html_e('Select Order Item Meta', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                                                <?php

                                                if (count($challan_item_meta_query)) {

                                                    foreach ($challan_item_meta_query as $meta) {
                                                        echo '<option value=' . esc_attr($meta->meta_key) . ' ' . selected(get_option('wpifw_order_item_meta_show'), $meta->meta_key, true) . '>' . esc_attr($meta->meta_key) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <a href="#" class="woo-invoice-delete-order-item-meta"><span class="dashicons dashicons-trash" style="color:#D94D40"></span></a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <p style="opacity: .7" class="woo_invoice_meta_html woo_invoice_col_3">Notice: If order item meta value is empty or an array will not display.</p>

                        </div>
                        <!-- =================================
                            End order item meta for invoice.
                         =================================== -->

                        <!-- =================================
                            Start select column item.
                         =================================== -->

                        <div class="woo-invoice-form-group"
                             tooltip="" flow="right">
                            <label class="woo-invoice-fixed-label woo-invoice-custom-label"
                                   for="wpifw_product_table_header"> <?php esc_html_e( 'Select Product Column', 'webappick-pdf-invoice-for-woocommerce' ); ?></label>
                            <select
                                    id="wpifw_product_table_header"
                                    name="wpifw_product_table_header[]"
                                    class="wpifw_header woo-invoice-fixed-width "
                                    multiple>
                                <?php
                                if ( class_exists( 'WooCommerce' ) ) {

                                    $options = [
                                        'price'                     => 'Cost',
                                        'quantity'                  => 'Qty',
                                        'tax'                       => 'Tax (Pro)',
                                        'tax_inc_discounted'        => 'Tax Inc. Discount (Pro)',
                                        'tax_ex_discounted'         => 'Tax Ex. Discount (Pro)',
                                        'total'                     => 'Total',
                                        'total_inc_discounted'      => 'Total Inc. Discount (Pro)',
                                        'total_ex_discounted'       => 'Total Ex. Discount (Pro)',
                                        'tax_rate'                  => 'Tax % (Pro)',
                                        'regular_price'             => 'Regular Price (Pro)',
                                        'sale_price'                => 'Sale Price (Pro)',
                                        'regular_price_with_tax'    => 'Regular Price with Tax (Pro)',
                                        'sale_price_with_tax'       => 'Sale Price with Tax (Pro)',
                                        'price_with_tax'            => 'Price with Tax (Pro)',
                                        'discount'                  => 'Discount (Pro)',
                                        'total_inc_tax'             => 'Total Inc. Tax (Pro)',
                                        'total_ex_tax'              => 'Total Ex. Tax (Pro)',
                                    ];

	                                // Sort the options array alphabetically by values
	                                asort($options);

                                    foreach ( $options as $key => $value ) {
                                        ?>
                                        <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
                                        <?php
                                    }
                                } ?>
                            </select>
                            <p class="woo_invoice_meta_html woo_invoice_col_3 woo_invoice_pro_notice" style="display: none;">Notice: <span class="woo_invoice_pro_notice_name"></span> column is <a type='button' href='https://webappick.com/plugin/woocommerce-pdf-invoice-packing-slips/?utm_source=customer_site&utm_medium=free_vs_pro&utm_campaign=woo_invoice_free' target='_blank' style="color: red; text-decoration: underline">Challan Pro</a> only.</p>
                        </div>

                        <!-- =================================
                            End select column item.
                         =================================== -->



                        <div class="woo-invoice-header-title">
                            <h4><?php esc_html_e('Order Total Settings', 'webappick-pdf-invoice-for-woocommerce'); ?></h4>
                        </div>
                        <!--=======================================================================-->
                        <!--  Display shipping total with tax and without tax-->
                        <!--=======================================================================-->
                        <div class="woo-invoice-form-group" tooltip="" flow="right">
                            <label class="woo-invoice-custom-label" for="wpifw_invoice_display_shipping_total"> <?php esc_html_e('Display Shipping Total', 'webappick-pdf-invoice-for-woocommerce'); ?></label>
                            <select id="wpifw_invoice_display_shipping_total" name="wpifw_invoice_display_shipping_total" class="woo-invoice-fixed-width woo-invoice-select-control">
                                <option value="wpifw_invoice_display_shipping_total_with_tax" <?php selected(get_option('wpifw_invoice_display_shipping_total'), 'wpifw_invoice_display_shipping_total_with_tax', true); ?>><?php esc_html_e('With Tax', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                                <option value="wpifw_invoice_display_shipping_total_without_tax" <?php selected(get_option('wpifw_invoice_display_shipping_total'), 'wpifw_invoice_display_shipping_total_without_tax', true); ?>><?php esc_html_e('Without Tax', 'webappick-pdf-invoice-for-woocommerce'); ?></option>
                            </select>
                        </div>
                        <!--=======================================================================-->
                        <!--  Display shipping total with tax and without tax-->
                        <!--=======================================================================-->

                        <?php $wpifw_show_order_note = get_option('wpifw_show_order_note') == '' ? 1 : get_option('wpifw_show_order_note'); ?>

                        <div class="woo-invoice-form-group">
                            <div class="woo-invoice-custom-control woo-invoice-custom-switch" style="padding-left:0!important;">
                                <div class="woo-invoice-toggle-label">
                                    <span class="woo-invoice-checkbox-label"><?php esc_html_e('Display Order Note', 'webappick-pdf-invoice-for-woocommerce'); ?></span>
                                </div>
                                <div class="woo-invoice-toggle-container" tooltip="" flow="right">
                                    <input type="hidden" name="wpifw_show_order_note" value="0">
                                    <input type="checkbox" class="woo-invoice-custom-control-input" id="wpifw_show_order_note" name="wpifw_show_order_note" value="1" <?php checked($wpifw_show_order_note, $woo_invoice_current, true); ?>>
                                    <label class="woo-invoice-custom-control-label" for="wpifw_show_order_note"></label>
                                </div>
                            </div>
                        </div><!-- end .woo-invoice-form-group -->

                        <!-------------------------------------------
                                   Write custom css.
                        -------------------------------------------->


                        <div class="woo-invoice-form-group">
                            <label class="woo-invoice-fixed-label woo-invoice-custom-label" for="wpifw_custom_css"><?php echo esc_html_e('Invoice Template CSS', 'webappick-pdf-invoice-for-woocommerce'); ?></label>

                            <div class="woo-invoice-tinymce-textarea wpifw-wirte-custom-css">
                                <div tooltip="<?php esc_html_e('Write Invoice Template CSS', 'webappick-pdf-invoice-for-woocommerce'); ?>" flow="right">
                                    <textarea style="height:100px;" class="woo-invoice-form-control" id="wpifw_custom_css" name="wpifw_custom_css"><?php echo (!empty(get_option('wpifw_custom_css')) ? esc_html(get_option('wpifw_custom_css')) : ''); ?></textarea>
                                </div>
                                <p style="opacity: 0.7">Example: body{ color:red } span{ color:green } td{ color:blue } th{ color:yellow
                                    }</p>
                            </div>
                        </div> <!-- End write custom css -->

                        <div class="woo-invoice-form-group">
                            <div class="woo-invoice-custom-control woo-invoice-custom-switch" style="padding-left:0!important;">
                                <div class="woo-invoice-toggle-label">
                                    <span class="woo-invoice-checkbox-label"><?php esc_html_e('Enable Debug Mode', 'webappick-pdf-invoice-for-woocommerce'); ?></span>
                                </div>
                                <div class="woo-invoice-toggle-container" tooltip="<?php esc_html_e('Enable debug mode to show errors.', 'webappick-pdf-invoice-for-woocommerce'); ?>" flow="right">
                                    <input type="hidden" name="wpifw_pdf_invoice_debug_mode" value="0">
                                    <input type="checkbox" class="woo-invoice-custom-control-input" id="wpifw_pdf_invoice_debug_mode" name="wpifw_pdf_invoice_debug_mode" value="1" <?php checked(get_option('wpifw_pdf_invoice_debug_mode'), $woo_invoice_current, true); ?>>
                                    <label class="woo-invoice-custom-control-label" for="wpifw_pdf_invoice_debug_mode"></label>
                                </div>
                            </div>
                        </div><!-- end .woo-invoice-form-group -->

                        <div class="woo-invoice-header-title">
                            <h4><?php esc_html_e('Troubleshooting', 'webappick-pdf-invoice-for-woocommerce'); ?></h4>
                        </div>

                        <!-- Enable this option to remove invalid characters from the Product title. This helps prevent the "Invalid input characters. Did you set $mpdf->in_charset properly?" message. -->
                        <div class="woo-invoice-form-group">
                            <div class="woo-invoice-custom-control woo-invoice-custom-switch" style="padding-left:0!important;">
                                <div class="woo-invoice-toggle-label">
                                    <span class="woo-invoice-checkbox-label"><?php esc_html_e( 'Remove Invalid Characters', 'webappick-pdf-invoice-for-woocommerce' ); ?></span>
                                </div>
                                <div class="woo-invoice-toggle-container" tooltip="" flow="right">
                                    <input type="hidden" name="remove_invalid_characters_from_title" value="0">
                                    <input type="checkbox" class="woo-invoice-custom-control-input" id="remove_invalid_characters_from_title" name="remove_invalid_characters_from_title" value="1" <?php checked( get_option( 'remove_invalid_characters_from_title' ), 1 ); ?>>
                                    <label class="woo-invoice-custom-control-label" title="Fixing 'Invalid input characters. Did you set $mpdf->in_charset properly?'" for="remove_invalid_characters_from_title"></label>
                                </div>
                            </div>
                        </div><!-- end .woo-invoice-form-group -->
                        <!-- End Troubleshooting option-->

                        <div class="woo-invoice-card-footer woo-invoice-save-changes-selector">
                            <input style="float:right;" class="woo-invoice-btn woo-invoice-btn-primary" type="submit" name="wpifw_submit" value="<?php esc_html_e('Save Changes', 'webappick-pdf-invoice-for-woocommerce'); ?>" />
                        </div><!-- end .woo-invoice-card-footer -->
                    </form>
                </div><!-- end .woo-invoice-card-body -->
            </div><!-- end .woo-invoice-card -->
        </div><!-- end .woo-invoice-col-sm-8 -->

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
                <?php
                $countries = ['ar', 'ary'];
                global $locale;
                if (in_array($locale, $countries)) : ?>
                    <div class="woo-invoice-col-sm-12 woo-invoice-col-12">
                        <div class="woo-invoice-card">
                            <div class="woo-invoice-card-header woo-invoice_zatca_notice_header" style="text-align: center;">
                                <h1 style="color: red!important;font-size: 20px;"><?php esc_html_e('Challan Pro Implements ZATCA.', 'webappick-pdf-invoice-for-woocommerce') ?></h1>
                            </div>
                            <div class="woo-invoice-card-body woo-invoice_zatca_notice_body">
                                <p><?php esc_html_e('Challan Pro version implements ZATCA ( Zakat, Tax and Customs Authority ) QR code rules according to The Saudi Arab govt law. ', 'webappick-pdf-invoice-for-woocommerce') ?></p>
                                <a class="woo-invoice-btn woo-invoice-btn-primary" style="margin-left: 45%;" href="https://webappick.com/plugin/woocommerce-pdf-invoice-packing-slips/?utm_source=customer_site&utm_medium=free_vs_pro&utm_campaign=woo_invoice_free" target="_blank"><?php esc_html_e('Buy Now', 'webappick-pdf-invoice-for-woocommerce') ?></a>
                                <a class="invoice_template_preiview_btn" target="_blank" href="https://rb.gy/1v530d"><?php echo esc_html_e('TEST QR CODE', 'webappick-pdf-invoice-for-woocommerce') ?></a>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="woo-invoice-card">
                    <div class="woo-invoice-card-header">
                        <h4><?php esc_html_e('SELECTED TEMPLATE', 'webappick-pdf-invoice-for-woocommerce'); ?></h4>
                    </div><!-- end .woo-invoice-card-header -->
                    <div class="" style="text-align: center">
                        <?php
                        $template_name = get_option('wpifw_templateid');

                        // Function to get the last order ID
                        function get_last_order_id() {
                            $cache_key = 'woo_last_order_id';
                            $last_order_id = wp_cache_get( $cache_key );

                            if ( false === $last_order_id ) {
                                $statuses = wc_get_is_paid_statuses(); // safer than all possible statuses

                                $args = array(
                                    'limit'        => 1,
                                    'orderby'      => 'date',
                                    'order'        => 'DESC',
                                    'status'       => $statuses,
                                    'return'       => 'ids',
                                );

                                $orders = wc_get_orders( $args );
                                $last_order_id = ! empty( $orders ) ? $orders[0] : 0;

                                wp_cache_set( $cache_key, $last_order_id, '', 60 ); // cache for 60 seconds
                            }

                            return $last_order_id;
                        }

                        $order_id = get_last_order_id();
                        $preview_url = esc_url(wp_nonce_url(admin_url('admin-ajax.php?action=wpifw_generate_invoice&order_id=' . $order_id) .'#toolbar=0', 'woo_invoice_ajax_nonce'));
                        $template_image_url = esc_attr(CHALLAN_FREE_PLUGIN_URL . 'admin/images/templates/' . $template_name . '.png');
                        ?>

                        <?php if ('' != $order_id) { ?>
                            <div class="_winvoice-iframe-container">
                                <iframe class="_winvoice-iframe-responsive woo-invoice-template-preview" src="<?php echo esc_url($preview_url); ?>"></iframe>
                            </div>
                        <?php } else { ?>
                            <img class="woo-invoice-template-preview" src="<?php echo esc_url($template_image_url); //phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>" alt="Preview Template">
                        <?php } ?>

                        <?php if ('' != $order_id) { ?>
                            <a class="invoice_template_preiview_btn" target="_blank" href="<?php echo esc_url($preview_url); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#fff" viewBox="0 0 467.4 467.4" style="enable-background:new 0 0 467.4 467.4" xml:space="preserve">
                                    <path d="M236.182 15v70h70.001z" />
                                    <path d="M197.289 347.5H71.182v-30h120.641c-.03-1.009-.07-2.016-.07-3.033 0-13.036 2.472-25.506 6.968-36.967H71.182v-30h145.855c18.622-21.177 45.899-34.566 76.248-34.566 9.671 0 19.03 1.361 27.897 3.898V115h-115V0h-185v415h257.885c-38.14-5.369-69.53-32.012-81.778-67.5zm-126.107-170h200v30h-200v-30zM374.87 374.838a102.39 102.39 0 0 1-21.213 21.213l71.349 71.349 21.213-21.213-71.349-71.349z" />
                                    <path d="M293.286 242.934c-39.443 0-71.533 32.089-71.533 71.533S253.843 386 293.286 386s71.533-32.09 71.533-71.533-32.09-71.533-71.533-71.533z" />
                                </svg>
                                <?php echo esc_html_e('PREVIEW LAST ORDER', 'webappick-pdf-invoice-for-woocommerce') ?>
                            </a>
                        <?php } else { ?>
                            <a class="invoice_template_preiview_btn" target="_blank" href="<?php esc_url($template_image_url); ?>"><?php echo esc_html_e('PREVIEW', 'webappick-pdf-invoice-for-woocommerce') ?></a>
                        <?php } ?>
                    </div><!-- end .woo-invoice-card-body -->
                </div><!-- end .woo-invoice-card -->

                <!-- <div class="woo_invoice_template_free_vs_pro">
                <div class="woo-invoice-card-body" style="text-align: center">
                    <a class="invoice_template_preiview_btn" target="_blank" href="<?php echo esc_url(admin_url() . 'admin.php?page=webappick-woo-pro-vs-free'); ?>"><?php echo esc_html_e('Free VS Pro', 'webappick-pdf-invoice-for-woocommerce') ?></a>
                </div>< end .woo-invoice-card-body -->
                <!-- </div>end .woo-invoice-card -->

                <div class="woo-invoice-banner-container">
                    <div class="woo-invoice-banner-logo">
                        <a class="wapk-woo-invoice-banner-logo" href="<?php echo esc_url('https://webappick.com/plugin/woocommerce-pdf-invoice-packing-slips/?utm_source=customer_site&utm_medium=free_vs_pro&utm_campaign=woo_invoice_free'); ?>" target="_blank"><img src="<?php echo esc_url($woo_invoice_banner_logo_dir); //phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>" alt="Woo Invoice"></a>
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
<!--END SETTING TAB-->
