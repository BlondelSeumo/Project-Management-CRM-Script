<script type="text/javascript">
    AppLanugage = {};
    AppLanugage.locale = "<?php echo app_lang('language_locale'); ?>";
    AppLanugage.localeLong = "<?php echo app_lang('language_locale_long'); ?>";

    AppLanugage.days = <?php echo json_encode(array(app_lang("sunday"), app_lang("monday"), app_lang("tuesday"), app_lang("wednesday"), app_lang("thursday"), app_lang("friday"), app_lang("saturday"))); ?>;
    AppLanugage.daysShort = <?php echo json_encode(array(app_lang("short_sunday"), app_lang("short_monday"), app_lang("short_tuesday"), app_lang("short_wednesday"), app_lang("short_thursday"), app_lang("short_friday"), app_lang("short_saturday"))); ?>;
    AppLanugage.daysMin = <?php echo json_encode(array(app_lang("min_sunday"), app_lang("min_monday"), app_lang("min_tuesday"), app_lang("min_wednesday"), app_lang("min_thursday"), app_lang("min_friday"), app_lang("min_saturday"))); ?>;

    AppLanugage.months = <?php echo json_encode(array(app_lang("january"), app_lang("february"), app_lang("march"), app_lang("april"), app_lang("may"), app_lang("june"), app_lang("july"), app_lang("august"), app_lang("september"), app_lang("october"), app_lang("november"), app_lang("december"))); ?>;
    AppLanugage.monthsShort = <?php echo json_encode(array(app_lang("short_january"), app_lang("short_february"), app_lang("short_march"), app_lang("short_april"), app_lang("short_may"), app_lang("short_june"), app_lang("short_july"), app_lang("short_august"), app_lang("short_september"), app_lang("short_october"), app_lang("short_november"), app_lang("short_december"))); ?>;

    AppLanugage.today = "<?php echo app_lang('today'); ?>";
    AppLanugage.yesterday = "<?php echo app_lang('yesterday'); ?>";
    AppLanugage.tomorrow = "<?php echo app_lang('tomorrow'); ?>";

    AppLanugage.search = "<?php echo app_lang('search'); ?>";
    AppLanugage.noRecordFound = "<?php echo app_lang('no_record_found'); ?>";
    AppLanugage.print = "<?php echo app_lang('print'); ?>";
    AppLanugage.excel = "<?php echo app_lang('excel'); ?>";
    AppLanugage.printButtonTooltip = "<?php echo app_lang('print_button_help_text'); ?>";

    AppLanugage.fileUploadInstruction = "<?php echo app_lang('file_upload_instruction'); ?>";
    AppLanugage.fileNameTooLong = "<?php echo app_lang('file_name_too_long'); ?>";

    AppLanugage.custom = "<?php echo app_lang('custom'); ?>";
    AppLanugage.clear = "<?php echo app_lang('clear'); ?>";

    AppLanugage.total = "<?php echo app_lang('total'); ?>";
    AppLanugage.totalOfAllPages = "<?php echo app_lang('total_of_all_pages'); ?>";

    AppLanugage.all = "<?php echo app_lang('all'); ?>";

    AppLanugage.preview_next_key = "<?php echo app_lang('preview_next_key'); ?>";
    AppLanugage.preview_previous_key = "<?php echo app_lang('preview_previous_key'); ?>";

    AppLanugage.filters = "<?php echo app_lang('filters'); ?>";

</script>