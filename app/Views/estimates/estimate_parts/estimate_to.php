<div><b><?php echo app_lang("estimate_to"); ?></b></div>
<div class="b-b" style="line-height: 2px; border-bottom: 1px solid #f2f4f6;"> </div>
<div style="line-height: 3px;"> </div>
<strong><?php echo $client_info->company_name; ?> </strong>
<div style="line-height: 3px;"> </div>
<span class="invoice-meta text-default" style="font-size: 90%; color: #666;">
    <?php if ($client_info->address) { ?>
        <div><?php echo nl2br($client_info->address); ?>
            <?php if ($client_info->city) { ?>
                <br /> <?php echo $client_info->city; ?>
            <?php } ?>
            <?php if ($client_info->state) { ?>
                , <?php echo $client_info->state; ?>
            <?php } ?>
            <?php if ($client_info->zip) { ?>
                <?php echo $client_info->zip; ?>
            <?php } ?>
            <?php if ($client_info->country) { ?>
                <br /><?php echo $client_info->country; ?>
            <?php } ?>
            <?php if ($client_info->vat_number) { ?>
                <br /><?php echo app_lang("vat_number") . ": " . $client_info->vat_number; ?>
            <?php } ?>
        </div>
    <?php } ?>
</span>