<table class="header-style">
    <tr class="invoice-preview-header-row">
        <td style="width: 45%; vertical-align: top;">
            <?php echo view('estimates/estimate_parts/company_logo'); ?>
        </td>
        <td class="hidden-invoice-preview-row" style="width: 20%;"></td>
        <td class="invoice-info-container" style="width: 35%; vertical-align: top; text-align: right"><?php
            $data = array(
                "client_info" => $client_info,
                "color" => $color,
                "estimate_info" => $estimate_info
            );
            echo view('estimates/estimate_parts/estimate_info', $data);
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
            echo view('estimates/estimate_parts/estimate_from', $data);
            ?>
        </td>
        <td></td>
        <td><?php
            echo view('estimates/estimate_parts/estimate_to', $data);
            ?>
        </td>
    </tr>
</table>