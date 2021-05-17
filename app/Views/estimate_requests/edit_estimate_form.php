<div id="page-content" class="page-wrapper clearfix">

    <div id="estimate-form-editable" class="card  p15 no-border clearfix" style="max-width: 1000px; margin: auto;">
        <div class="clearfix pl10 pr10">
            <h3 id="estimate-form-title" class="float-start"> <?php echo $model_info->title; ?></h3>
            <?php echo anchor(get_uri("estimate_requests/preview_estimate_form/" . $model_info->id), app_lang('preview'), array("class" => "btn btn-default round mt15 float-end", "title" => app_lang('preview'))); ?> 
        </div>

        <div class="pl10 pr10"><?php echo nl2br($model_info->description); ?></div>
        <div class="table-responsive mt20 general-form">
            <table id="estimate-form-table" class="display no-thead b-t b-b-only no-hover" cellspacing="0" width="100%">            
            </table>
        </div>
        <div class="p15">
            <div class='text-center'> <?php echo modal_anchor(get_uri("estimate_requests/estimate_form_field_modal_form/" . $model_info->id), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_field'), array("class" => "btn btn-default round ", "title" => app_lang('add_field'))); ?> </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-form-table").appTable({
            source: '<?php echo_uri("estimate_requests/estimate_form_filed_list_data/" . $model_info->id) ?>',
            order: [[1, "asc"]],
            hideTools: true,
            displayLength: 100,
            columns: [
                {title: '<?php echo app_lang("title") ?>'},
                {visible: false},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-right option w100"}
            ],
            onInitComplete: function () {
                //apply sortable
                $("#estimate-form-table").find("tbody").attr("id", "estimate-form-table-sortable");
                var $selector = $("#estimate-form-table-sortable");

                Sortable.create($selector[0], {
                    animation: 150,
                    chosenClass: "sortable-chosen",
                    ghostClass: "sortable-ghost",
                    onUpdate: function (e) {
                        appLoader.show();
                        //prepare sort indexes 
                        var data = "";
                        $.each($selector.find(".field-row"), function (index, ele) {
                            if (data) {
                                data += ",";
                            }

                            data += $(ele).attr("data-id") + "-" + index;
                        });

                        //update sort indexes
                        $.ajax({
                            url: '<?php echo_uri("estimate_requests/update_form_field_sort_values/" . $model_info->id) ?>',
                            type: "POST",
                            data: {sort_values: data},
                            success: function () {
                                appLoader.hide();
                            }
                        });
                    }
                });

                $(".dataTables_empty").hide();
            }
        });
    });



</script>
