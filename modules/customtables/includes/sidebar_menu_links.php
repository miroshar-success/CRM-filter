<?php

/*
 * Inject sidebar menu and links for customtables module
 */
hooks()->add_action('admin_init', 'customtables_module_init_menu_items');
function customtables_module_init_menu_items()
{
  if (has_permission('customtables', '', 'view')) {
    get_instance()->app_menu->add_setup_menu_item('customtables', [
      'slug'     => 'customtables',
      'name'     => _l('customtables'),
      'icon'     => '',
      'position' => 35,
    ]);
    get_instance()->app_menu->add_setup_children_item('customtables', [
      'slug'     => 'customtables',
      'name'     => _l('tablecustomize'),
      'href'     => admin_url('customtables/index'),
      'position' => 28,
    ]);
    get_instance()->app_menu->add_setup_children_item('customtables', [
      'slug'     => 'table_design',
      'name'     => _l('table_design'),
      'href'     => admin_url('customtables/tableDesign'),
      'position' => 28,
    ]);
  }

  //\modules\customtables\core\Apiinit::ease_of_mind(CUSTOMTABLES_MODULE);
}
