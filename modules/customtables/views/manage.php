<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-3">
				<h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
					<?php echo _l('customtables'); ?>
				</h4>
				<ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
					<?php
					$i = 0;
					foreach ($tabs as $group) { ?>
						<li class="settings-group-<?php echo $group['slug']; ?><?php echo (0 === $i) ? ' active' : ''; ?>">
							<a href="<?php echo admin_url('customtables?group=' . $group['slug']); ?>" data-group="<?php echo $group['slug']; ?>">
								<i class="<?php echo $group['icon'] ?: 'fa-regular fa-circle-question'; ?> menu-icon"></i>
								<?php echo $group['name']; ?>
							</a>
						</li>
					<?php ++$i;
					}
					?>
				</ul>
			</div>
			<?php echo form_open_multipart('', ['id' => 'table_customizer_form']); ?>
			<div class="col-md-9">
				<h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
					<?php echo $tab['name']; ?> <small class="text-danger pull-right" style="font-style: italic;"><?= _l('required_note') ?></small>
				</h4>
				<?php $this->config->load(CUSTOMTABLES_MODULE . '/config'); ?>
				<?php $this->load->view($tab['view']); ?>
			</div>
			<?php echo form_close(); ?>
			<div class="btn-bottom-toolbar text-right">
				<button type="button" class="btn btn-primary" onclick="resetDefaultTable('<?php echo $tab['slug']; ?>')">
					<?php echo _l('reset_table'); ?>
				</button>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>