<div class="modal-body clearfix search-modal">
    <div class="container-fluid">
        <div class="form-group mb0">
            <div class="row">
                <div class=" col-sm-3">
                    <?php
                    helper('cookie');
                    $selected_search_field_of_user_cookie = get_cookie("selected_search_field_of_user_" . $login_user->id);

                    echo form_input(array(
                        "id" => "search_field",
                        "name" => "search_field",
                        "class" => "form-control float-start",
                        "value" => $selected_search_field_of_user_cookie ? $selected_search_field_of_user_cookie : "task"
                    ));
                    ?>
                </div>
                <div class="col-sm-9 pl0">
                    <?php
                    echo form_input(array(
                        "id" => "search",
                        "name" => "search",
                        "value" => "",
                        "autocomplete" => "false",
                        "class" => "form-control help-search-box",
                        "placeholder" => app_lang('search'),
                        "type" => "search"
                    ));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo modal_anchor(get_uri("todo/view/"), "", array("class" => "hide", "data-post-id" => "", "id" => "show_todo_hidden")); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".search-modal").closest(".modal-content").css({"border-radius": "40px"});
        $('#ajaxModal').on('hidden.bs.modal', function () {
            $(this).find(".modal-content").css({"border-radius": "0"});
        });

        var $searchBox = $("#search"),
                $searchField = $("#search_field");

        $searchField.select2({
            data: <?php echo ($search_fields_dropdown); ?>
        });

        var awesomplete = new Awesomplete($searchBox[0], {
            minChars: 1,
            autoFirst: true,
            maxItems: 10
        });

        $searchBox.on("keyup", function (e) {
            if (!(e.which >= 37 && e.which <= 40)) {

                //show/hide loder icon in searchbox
                if (this.value) {
                    $searchBox.addClass("searching");
                } else {
                    $searchBox.removeClass("searching");
                }

                //witin 200 ms to request ajax cll
                clearTimeout($.data(this, 'timer'));
                var wait = setTimeout(getAwesompleteList, 200);
                $(this).data('timer', wait);
            }

            //hide the no result found message
            if (!this.value) {
                $(".awesomplete").find("ul").html("").attr("hidden");
            }

        });

        function getAwesompleteList() {

            $.ajax({
                url: "<?php echo get_uri('search/get_search_suggestion/'); ?>",
                data: {search: $searchBox.val(), search_field: $searchField.val()},
                cache: false,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    //hide the loader icon in search box
                    $searchBox.removeClass("searching");

                    //set the results
                    awesomplete.list = response;

                    //show no result found 
                    if (!response.length && $searchBox.val()) {
                        $(".awesomplete").find("ul").html("<li aria-selected='false'> <?php echo app_lang('no_result_found'); ?> </li>").removeAttr("hidden");
                    }
                }
            });
        }


        $searchBox.on('awesomplete-selectcomplete', function () {
            //serch result selected, redirect to the details view
            if (this.value) {
                var location = "",
                        searchFieldValue = $searchField.val();

                if (searchFieldValue === "todo") {
                    $("#show_todo_hidden").attr("data-post-id", this.value);
                    $("#show_todo_hidden").trigger("click");
                } else {
                    if (searchFieldValue === "task") {
                        location = "<?php echo get_uri("projects/task_view"); ?>/" + this.value;
                    } else if (searchFieldValue === "project") {
                        location = "<?php echo get_uri("projects/view"); ?>/" + this.value;
                    } else if (searchFieldValue === "client") {
                        location = "<?php echo get_uri("clients/view"); ?>/" + this.value;
                    }

                    window.location.href = location;
                }
            }

            this.value = "";
        });

        //remove search field text on changing type
        $searchField.on("change", function () {
            $searchBox.val("").focus();
            setCookie("selected_search_field_of_user_" + "<?php echo $login_user->id; ?>", $(this).val());
        });

        setTimeout(function () {
            $searchBox.focus();
        }, 200);
    });
</script>    