<div class="bg-white p15 pt0">
    <?php
    $recurring_stopped = false;
    $recurring_status_class = "text-primary";
    $recurring_cycle_class = "";
    if ($invoice_info->no_of_cycles_completed > 0 && $invoice_info->no_of_cycles_completed == $invoice_info->no_of_cycles) {
        $recurring_status_class = "text-danger";
        $recurring_cycle_class = "text-danger";
        $recurring_stopped = true;
    }
    ?>

    <span class="badge large b-a" title="<?php echo app_lang('recurring'); ?>"><i data-feather="refresh-cw" class="icon-18 <?php echo $recurring_status_class; ?>"></i></span>


    <?php
    $cycles = $invoice_info->no_of_cycles_completed . "/" . $invoice_info->no_of_cycles;
    if (!$invoice_info->no_of_cycles) { //if not no of cycles, so it's infinity
        $cycles = $invoice_info->no_of_cycles_completed . "/&#8734;";
    }
    ?>

    <span class="mr15"><?php echo app_lang("repeat_every") . ": " . $invoice_info->repeat_every . " " . app_lang("interval_" . $invoice_info->repeat_type); ?></span>

    <span class="mr15 <?php echo $recurring_cycle_class ?>"><?php echo app_lang("cycles") . ": " . $cycles; ?></span>

    <?php
    if (!$recurring_stopped && (int) $invoice_info->next_recurring_date) {
        ?>
        <span class="mr15"><?php echo app_lang("next_recurring_date") . ": " . format_to_date($invoice_info->next_recurring_date, false); ?></span>
    <?php }; ?>


</div>