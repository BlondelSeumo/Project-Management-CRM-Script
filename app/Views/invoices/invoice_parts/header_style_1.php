<table class="header-style">
    <tr class="invoice-preview-header-row">
        <td style="width: 45%; vertical-align: top;">
            <?php echo view('invoices/invoice_parts/company_logo'); ?>
        </td>
        <td class="hidden-invoice-preview-row" style="width: 20%;"></td>
        <td class="invoice-info-container invoice-header-style-one" style="width: 35%; vertical-align: top; text-align: right"><?php
            $data = array(
                "client_info" => $client_info,
                "color" => $color,
                "invoice_info" => $invoice_info
            );
            echo view('invoices/invoice_parts/invoice_info', $data);
            ?>
        </td>
    </tr>
    <tr>
        <td style="padding: 5px;"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><?php
            echo view('invoices/invoice_parts/bill_from', $data);
            ?>
        </td>
        <td></td>
        <td><?php
            echo view('invoices/invoice_parts/bill_to', $data);
            ?>
        </td>
    </tr>
</table>