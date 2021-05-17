<div class="clearfix mb20">
    <div class="p20 bg-white pb0 rounded-top" id="js-kanban-filter-container">
        <div class="row">
            <div class="col-md-1 col-xs-2 pb20">
                <button class="btn btn-default" id="reload-kanban-button"><i data-feather="refresh-cw" class="icon-16"></i></button>
            </div>
            <div id="kanban-filters" class="col-md-11 col-xs-10 kanban-widget-filters"></div>
        </div>
    </div>


    <div id="load-kanban"></div>

</div>

<?php echo view("projects/tasks/batch_update/batch_update_script"); ?>
<?php echo view("projects/tasks/kanban/all_tasks_kanban_helper_js"); ?>
<?php echo view("projects/tasks/quick_filters_helper_js"); ?>