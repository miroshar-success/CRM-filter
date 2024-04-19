<div class="row">
    <?php $columns = getColumnFromTable('estimates'); ?>
    <div class="col-md-6 mbot20">
        <div class="panel-heading btn-primary">
            <h3 class="panel-title"><?php echo _l('available_columns'); ?></h3>
        </div>
        <ul id="allow_columns" class="connectedSortable min-ul-size">
            <?php foreach ($columns['available_options'] as $key => $column) { ?>
                <?php if (!$column['required']) { ?>
                    <li class="dd-item" data-column-id="<?php echo $key; ?>">
                        <div class="dd-handle dd3-handle"></div>
                        <div class="dd3-content"><?php echo $column['label']; ?></div>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
    <div class="col-md-6">
        <div class="panel-heading btn-success">
            <h3 class="panel-title "><?php echo _l('selected_columns'); ?></h3>
        </div>
        <ul id="display_columns" class="connectedSortable" data-table-name="estimates">
            <?php foreach ($columns['selected_options'] as $key => $column) { ?>
                <?php $disabled = ($column['required']) ? 'disabled' : '';
                $required       = ($column['required']) ? 'opacity:0.6' : '';
                ?>
                <li data-column-id="<?php echo $key; ?>" class="dd-item <?php echo $disabled; ?>" style="<?php echo $required; ?>">
                    <div class="dd-handle dd3-handle"></div>
                    <div class="dd3-content"><?php echo $column['label']; ?></div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>