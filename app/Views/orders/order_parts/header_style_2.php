<table class="header-style">
    <tr class="invoice-preview-header-row">
        <td class="invoice-info-container" style="width: 40%; vertical-align: top;"><?php
            $data = array(
                "client_info" => $client_info,
                "color" => $color,
                "order_info" => $order_info
            );
            echo view('orders/order_parts/order_info', $data);
            ?>
        </td>
        <td class="hidden-invoice-preview-row" style="width: 20%;"></td>
        <td style="width: 40%; vertical-align: top;">
            <?php echo view('orders/order_parts/company_logo'); ?>
        </td>
    </tr>
    <tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><?php
            echo view('orders/order_parts/order_to', $data);
            ?>
        </td>
        <td></td>
        <td><?php
            echo view('orders/order_parts/order_from', $data);
            ?>
        </td>
    </tr>
</table>