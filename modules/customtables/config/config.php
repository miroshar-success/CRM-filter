<?php

// All Tabs For Custom Tables
$config['datatables_all_tabs'] = [
    'leads' => [
        'slug' => 'leads',
        'name' => _l('leads'),
        'view' => 'includes/leads',
        'icon' => 'fa-solid fa-l',
    ],
    'customers' => [
        'slug' => 'customers',
        'name' => _l('customers'),
        'view' => 'includes/customers',
        'icon' => 'fa-solid fa-users',
    ],
    'proposals' => [
        'slug' => 'proposals',
        'name' => _l('proposals'),
        'view' => 'includes/proposals',
        'icon' => 'fa-brands fa-codepen',
    ],
    'estimates' => [
        'slug' => 'estimates',
        'name' => _l('estimates'),
        'view' => 'includes/estimates',
        'icon' => 'fa-solid fa-sign-hanging',
    ],
    'invoices' => [
        'slug' => 'invoices',
        'name' => _l('invoices'),
        'view' => 'includes/invoices',
        'icon' => 'fa-solid fa-file-invoice-dollar',
    ],
    'expenses' => [
        'slug' => 'expenses',
        'name' => _l('expenses'),
        'view' => 'includes/expenses',
        'icon' => 'fa-solid fa-arrows-up-down-left-right',
    ],
    'projects' => [
        'slug' => 'projects',
        'name' => _l('projects'),
        'view' => 'includes/projects',
        'icon' => 'fa-solid fa-bars-progress',
    ],
    'tasks' => [
        'slug' => 'tasks',
        'name' => _l('tasks'),
        'view' => 'includes/tasks',
        'icon' => 'fa-solid fa-list-check',
    ],
    'contracts' => [
        'slug' => 'contracts',
        'name' => _l('contracts'),
        'view' => 'includes/contracts',
        'icon' => 'fa-solid fa-life-ring',
    ],
];
// Over Here All Tabs

$firstPart = [
    [
        'column'    => '1',
        'label'     => '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="leads"><label></label></div>',
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['class' => 'toggleable', 'id' => 'th-number'],
    ],
    [
        'column'    => db_prefix() . 'leads.id as id',
        'label'     => _l('id'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => db_prefix() . 'leads.name as name',
        'label'     => _l('leads_dt_name'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['class' => 'toggleable', 'id' => 'th-name'],
    ],
];
$gdpr = [];
if (is_gdpr() && '1' == get_option('gdpr_enable_consent_for_leads')) {
    $gdpr = [
        [
            'column'    => '1',
            'label'     => _l('gdpr_consent') . ' (' . _l('gdpr_short') . ')',
            'initial'   => true,
            'required'  => false,
            'th_attrs'  => ['id' => 'th-consent', 'class' => 'not-export'],
        ],
    ];
}

$secondPart = [
    [
        'column'    => 'company',
        'label'     => _l('lead_company'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['class' => 'toggleable', 'id' => 'th-company'],
    ],
    [
        'column'    => db_prefix() . 'leads.email as email',
        'label'     => _l('leads_dt_email'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['class' => 'toggleable', 'id' => 'th-email'],
    ],
    [
        'column'    => db_prefix() . 'leads.phonenumber as phonenumber',
        'label'     => _l('leads_dt_phonenumber'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['class' => 'toggleable', 'id' => 'th-phone'],
    ],
    [
        'column'    => 'lead_value',
        'label'     => _l('leads_dt_lead_value'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['class' => 'toggleable', 'id' => 'th-lead-value'],
    ],
    [
        'column'    => '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'leads.id and rel_type="lead" ORDER by tag_order ASC LIMIT 1) as tags',
        'label'     => _l('tags'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['class' => 'toggleable', 'id' => 'th-tags'],
    ],
    [
        'column'    => 'firstname as assigned_firstname',
        'label'     => _l('leads_dt_assigned'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['class' => 'toggleable', 'id' => 'th-assigned'],
    ],
    [
        'column'    => db_prefix() . 'leads_status.name as status_name',
        'label'     => _l('leads_dt_status'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['class' => 'toggleable', 'id' => 'th-status'],
    ],
    [
        'column'    => db_prefix() . 'leads_sources.name as source_name',
        'label'     => _l('leads_source'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['class' => 'toggleable', 'id' => 'th-source'],
    ],
    [
        'column'    => 'lastcontact',
        'label'     => _l('leads_dt_last_contact'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['class' => 'toggleable', 'id' => 'th-last-contact'],
    ],
    [
        'column'    => 'dateadded',
        'label'     => _l('leads_dt_datecreated'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['class' => 'date-created toggleable', 'id' => 'th-date-created'],
    ],
    // Custom Columns
    [
        'column'    => 'title',
        'label'     => _l('lead_title'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'description',
        'label'     => _l('lead_description'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'leads.country',
        'label'     => _l('lead_country'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'city',
        'label'     => _l('lead_city'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'state',
        'label'     => _l('lead_state'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'address',
        'label'     => _l('lead_address'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'last_status_change',
        'label'     => _l('lead_last_status_change'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'website',
        'label'     => _l('lead_website'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'is_public',
        'label'     => _l('lead_public'),
        'initial'   => false,
        'required'  => false,
    ],
];

$config['leads_columns'] = array_merge($firstPart, $gdpr, $secondPart);

$config['clients_columns'] = [
    [
        'column'    => '1',
        'label'     => '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="clients"><label></label></div>',
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => db_prefix() . 'clients.userid as userid',
        'label'     => _l('id'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'company',
        'label'     => _l('clients_list_company'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'CONCAT(firstname, " ", lastname) as fullname',
        'label'     => _l('contact_primary'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'email',
        'label'     => _l('company_primary_email'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'clients.phonenumber as phonenumber',
        'label'     => _l('clients_list_phone'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'clients.active',
        'label'     => _l('customer_active'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'customer_groups JOIN ' . db_prefix() . 'customers_groups ON ' . db_prefix() . 'customer_groups.groupid = ' . db_prefix() . 'customers_groups.id WHERE customer_id = ' . db_prefix() . 'clients.userid ORDER by name ASC) as customerGroups',
        'label'     => _l('customer_groups'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'clients.datecreated as datecreated',
        'label'     => _l('date_created'),
        'initial'   => true,
        'required'  => false,
    ],
    // custom Columns
    [
        'column'    => 'vat',
        'label'     => _l('vat'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'clients.country',
        'label'     => _l('country'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'city',
        'label'     => _l('city'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'state',
        'label'     => _l('state'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'address',
        'label'     => _l('address'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'website',
        'label'     => _l('website'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'billing_street',
        'label'     => _l('ctl_billing_street'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'billing_city',
        'label'     => _l('ctl_billing_city'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'billing_state',
        'label'     => _l('ctl_billing_state'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'clients.billing_country',
        'label'     => _l('ctl_billing_country'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'billing_zip',
        'label'     => _l('ctl_billing_zip'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'shipping_street',
        'label'     => _l('ctl_shipping_street'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'shipping_city',
        'label'     => _l('ctl_shipping_city'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'shipping_state',
        'label'     => _l('ctl_shipping_state'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'clients.shipping_country',
        'label'     => _l('ctl_shipping_country'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'shipping_zip',
        'label'     => _l('ctl_shipping_zip'),
        'initial'   => false,
        'required'  => false,
    ],
];

$firstProposals = [
    [
        'column'    => db_prefix() . 'proposals.id',
        'label'     => _l('proposal') . ' #',
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['id' => 'th-proposal-id', 'class' => 'proposal-id'],
    ],
    [
        'column'    => 'subject',
        'label'     => _l('proposal_subject'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['id' => 'th-proposal-subject', 'class' => 'proposal-subject'],
    ],
    [
        'column'    => 'proposal_to',
        'label'     => _l('proposal_to'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['id' => 'th-proposal-proposal_to', 'class' => 'proposal-proposal_to'],
    ],
    [
        'column'    => 'total',
        'label'     => _l('proposal_total'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['id' => 'th-proposal-total', 'class' => 'proposal-total'],
    ],
    [
        'column'    => 'date',
        'label'     => _l('proposal_date'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['id' => 'th-proposal-date', 'class' => 'proposal-date'],
    ],
    [
        'column'    => 'open_till',
        'label'     => _l('proposal_open_till'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['id' => 'th-proposal-open-till', 'class' => 'proposal-open-till'],
    ],
    [
        'column'    => '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'proposals.id and rel_type="proposal" ORDER by tag_order ASC) as tags',
        'label'     => _l('tags'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['id' => 'th-proposal-tag', 'class' => 'proposal-tag'],
    ],
    [
        'column'    => 'datecreated',
        'label'     => _l('proposal_date_created'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['id' => 'th-proposal-datecreated', 'class' => 'proposal-datecreated'],
    ],
    [
        'column'    => db_prefix() . 'proposals.status as proposal_status',
        'label'     => _l('proposal_status'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['id' => 'th-proposal-status', 'class' => 'proposal-status'],
    ],
];
// project set noi to te columns avse default table ma
$secondProposal = [];
if ('projects' != get_instance()->uri->segment(2)) {
    $secondProposal = [
        [
            'column'    => 'project_id',
            'label'     => _l('project'),
            'initial'   => true,
            'required'  => false,
            'th_attrs'  => ['id' => 'th-proposal-project-id', 'class' => 'proposal-project-id'],
        ],
    ];
}

$thirdProposals = [
    // custom Columns
    [
        'column'    => 'subtotal',
        'label'     => _l('subtotal'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'total_tax',
        'label'     => _l('total_tax'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'adjustment',
        'label'     => _l('adjustment'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'discount_percent',
        'label'     => _l('discount_percent'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'discount_total',
        'label'     => _l('discount_total'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'discount_type',
        'label'     => _l('discount_type'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'proposals.country',
        'label'     => _l('country'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'zip',
        'label'     => _l('zip'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'state',
        'label'     => _l('state'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'city',
        'label'     => _l('city'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'address',
        'label'     => _l('address'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'email',
        'label'     => _l('email'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'phone',
        'label'     => _l('phone'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'acceptance_firstname',
        'label'     => _l('acceptance_firstname'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'acceptance_lastname',
        'label'     => _l('acceptance_lastname'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'acceptance_email',
        'label'     => _l('acceptance_email'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'acceptance_date',
        'label'     => _l('acceptance_date'),
        'initial'   => false,
        'required'  => false,
    ],
];

$config['proposals_columns'] = array_merge($firstProposals, $secondProposal, $thirdProposals);

$config['estimates_columns'] = [
    [
        'column'    => 'number',
        'label'     => _l('estimate_dt_table_heading_number'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'total',
        'label'     => _l('estimate_dt_table_heading_amount'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'total_tax',
        'label'     => _l('estimates_total_tax'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'YEAR(date) as year',
        'label'     => _l('invoice_estimate_year'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => [
            'class' => 'not_visible',
        ],
    ],
    [
        'column'    => get_sql_select_client_company(),
        'label'     => _l('estimate_dt_table_heading_client'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['class' => ('client' == get_instance()->uri->segment(3)) ? 'not_visible' : ''],
    ],
    [
        'column'    => db_prefix() . 'projects.name as project_name',
        'label'     => _l('project'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'estimates.id and rel_type="estimate" ORDER by tag_order ASC) as tags',
        'label'     => _l('tags'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'date',
        'label'     => _l('estimate_dt_table_heading_date'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'expirydate',
        'label'     => _l('estimate_dt_table_heading_expirydate'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'reference_no',
        'label'     => _l('reference_no'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.status',
        'label'     => _l('estimate_dt_table_heading_status'),
        'initial'   => true,
        'required'  => false,
    ],
    // custom Columns
    [
        'column'    => 'datesend',
        'label'     => _l('datesend'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.datecreated',
        'label'     => _l('datecreated'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'subtotal',
        'label'     => _l('subtotal'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'adjustment',
        'label'     => _l('adjustment'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'clientnote',
        'label'     => _l('clientnote'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'adminnote',
        'label'     => _l('adminnote'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'discount_percent',
        'label'     => _l('discount_percent'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'discount_total',
        'label'     => _l('discount_total'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'discount_type',
        'label'     => _l('discount_type'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'invoiced_date',
        'label'     => _l('invoiced_date'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'terms',
        'label'     => _l('terms'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.billing_street',
        'label'     => _l('ctl_billing_street'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.billing_city',
        'label'     => _l('ctl_billing_city'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.billing_state',
        'label'     => _l('ctl_billing_state'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.billing_zip',
        'label'     => _l('ctl_billing_zip'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.billing_country',
        'label'     => _l('ctl_billing_country'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.shipping_street',
        'label'     => _l('ctl_shipping_street'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.shipping_city',
        'label'     => _l('ctl_shipping_city'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.shipping_state',
        'label'     => _l('ctl_shipping_state'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.shipping_zip',
        'label'     => _l('ctl_shipping_zip'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'estimates.shipping_country',
        'label'     => _l('ctl_shipping_country'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'acceptance_firstname',
        'label'     => _l('acceptance_firstname'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'acceptance_lastname',
        'label'     => _l('acceptance_lastname'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'acceptance_email',
        'label'     => _l('acceptance_email'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'acceptance_date',
        'label'     => _l('acceptance_date'),
        'initial'   => false,
        'required'  => false,
    ],
];

$config['invoices_columns'] = [
    [
        'column'    => 'number',
        'label'     => _l('invoice_dt_table_heading_number'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'total',
        'label'     => _l('invoice_dt_table_heading_amount'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'total_tax',
        'label'     => _l('invoice_total_tax'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'YEAR(date) as year',
        'label'     => _l('invoice_estimate_year'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => [
            'class' => 'not_visible',
        ],
    ],
    [
        'column'    => 'date',
        'label'     => _l('invoice_dt_table_heading_date'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => get_sql_select_client_company(),
        'label'     => _l('invoice_dt_table_heading_client'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['class' => ('client' == get_instance()->uri->segment(3)) ? 'not_visible' : ''],
    ],
    [
        'column'    => db_prefix() . 'projects.name as project_name',
        'label'     => _l('project'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'invoices.id and rel_type="invoice" ORDER by tag_order ASC) as tags',
        'label'     => _l('tags'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'duedate',
        'label'     => _l('invoice_dt_table_heading_duedate'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'invoices.status',
        'label'     => _l('invoice_dt_table_heading_status'),
        'initial'   => true,
        'required'  => false,
    ],
    // custom Columns
    [
        'column'    => 'datesend',
        'label'     => _l('datesend'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'invoices.datecreated',
        'label'     => _l('datecreated'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'subtotal',
        'label'     => _l('subtotal'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'adjustment',
        'label'     => _l('adjustment'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'clientnote',
        'label'     => _l('clientnote'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'adminnote',
        'label'     => _l('adminnote'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'last_overdue_reminder',
        'label'     => _l('last_overdue_reminder'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'last_due_reminder',
        'label'     => _l('last_due_reminder'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'discount_percent',
        'label'     => _l('discount_percent'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'discount_total',
        'label'     => _l('discount_total'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'discount_type',
        'label'     => _l('discount_type'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'recurring_type',
        'label'     => _l('recurring_type'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'last_recurring_date',
        'label'     => _l('last_recurring_date'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'total_cycles',
        'label'     => _l('total_cycles'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'terms',
        'label'     => _l('terms'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'invoices.billing_street',
        'label'     => _l('ctl_billing_street'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'invoices.billing_city',
        'label'     => _l('ctl_billing_city'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'invoices.billing_state',
        'label'     => _l('ctl_billing_state'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'invoices.billing_country',
        'label'     => _l('billing_country'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'invoices.billing_zip',
        'label'     => _l('ctl_billing_zip'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'invoices.shipping_street',
        'label'     => _l('ctl_shipping_street'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'invoices.shipping_city',
        'label'     => _l('ctl_shipping_city'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'invoices.shipping_zip',
        'label'     => _l('ctl_shipping_zip'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'invoices.shipping_country',
        'label'     => _l('ctl_shipping_country'),
        'initial'   => false,
        'required'  => false,
    ],
];

$config['expenses_columns'] = [
    [
        'column'    => '1',
        'label'     => '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="expenses"><label></label></div>',
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['class' => ('client' == get_instance()->uri->segment(3)) ? 'not_visible' : ''],
    ],
    [
        // default table ma display nthi thati.
        'column'    => db_prefix() . 'expenses.id as id',
        'label'     => _l('id'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => db_prefix() . 'expenses_categories.name as category_name',
        'label'     => _l('expense_dt_table_heading_category'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'amount',
        'label'     => _l('expense_dt_table_heading_amount'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'expense_name',
        'label'     => _l('expense_name'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'file_name',
        'label'     => _l('receipt'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'date',
        'label'     => _l('expense_dt_table_heading_date'),
        'initial'   => true,
        'required'  => false,
    ],
    // project empty na hoi tyare display thy
    [
        'column'    => db_prefix() . 'projects.name as project_name',
        'label'     => _l('project'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => get_sql_select_client_company(),
        'label'     => _l('expense_dt_table_heading_customer'),
        'initial'   => true,
        'required'  => false,
        'th_attrs'  => ['class' => ('client' == get_instance()->uri->segment(3)) ? 'not_visible' : ''],
    ],
    // over
    [
        'column'    => 'invoiceid',
        'label'     => _l('invoice'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'reference_no',
        'label'     => _l('expense_dt_table_heading_reference_no'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'paymentmode',
        'label'     => _l('expense_dt_table_heading_payment_mode'),
        'initial'   => true,
        'required'  => false,
    ],
    //custom Columns
    [
        'column'    => 'tax',
        'label'     => _l('tax'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'note',
        'label'     => _l('note'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'billable',
        'label'     => _l('billable'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'recurring_type',
        'label'     => _l('recurring_type'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'last_recurring_date',
        'label'     => _l('last_recurring_date'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => db_prefix() . 'expenses.dateadded',
        'label'     => _l('dateadded'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'total_cycles',
        'label'     => _l('total_cycles'),
        'initial'   => false,
        'required'  => false,
    ],
];

$config['projects_columns'] = [
    [
        'column'    => db_prefix() . 'projects.id as id',
        'label'     => _l('the_number_sign'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'name',
        'label'     => _l('project_name'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => get_sql_select_client_company(),
        'label'     => _l('project_customer'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['class' => ('client' == get_instance()->uri->segment(3)) ? 'not_visible' : ''],
    ],
    [
        'column'    => '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'projects.id and rel_type="project" ORDER by tag_order ASC) as tags',
        'label'     => _l('tags'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'start_date',
        'label'     => _l('project_start_date'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'deadline',
        'label'     => _l('project_deadline'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => '(SELECT GROUP_CONCAT(CONCAT(firstname, \' \', lastname) SEPARATOR ",") FROM ' . db_prefix() . 'project_members JOIN ' . db_prefix() . 'staff on ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'project_members.staff_id WHERE project_id=' . db_prefix() . 'projects.id ORDER BY staff_id) as members',
        'label'     => _l('project_members'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'status',
        'label'     => _l('project_status'),
        'initial'   => true,
        'required'  => false,
    ],
    // custom Columns
    [
        'column'    => 'description',
        'label'     => _l('description'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'project_created',
        'label'     => _l('project_created'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'date_finished',
        'label'     => _l('date_finished'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'project_cost',
        'label'     => _l('project_cost'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'estimated_hours',
        'label'     => _l('estimated_hours'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'project_rate_per_hour',
        'label'     => _l('project_rate_per_hour'),
        'initial'   => false,
        'required'  => false,
    ],
];

$config['tasks_columns'] = [
    [
        'column'    => '1',
        'label'     => '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="tasks"><label></label></div>',
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => db_prefix() . 'tasks.id as id',
        'label'     => _l('id'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => db_prefix() . 'tasks.name as task_name',
        'label'     => _l('tasks_dt_name'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'status',
        'label'     => _l('task_status'),
        'initial'   => true,
        'required'  => true,
    ],
    [
        'column'    => 'startdate',
        'label'     => _l('tasks_dt_datestart'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'duedate',
        'label'     => _l('task_duedate'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => [
            'class' => 'duedate',
        ],
    ],
    [
        'column'    => get_sql_select_task_asignees_full_names() . ' as assignees',
        'label'     => _l('task_assigned'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'tasks.id and rel_type="task" ORDER by tag_order ASC) as tags',
        'label'     => _l('tags'),
        'initial'   => true,
        'required'  => false,
    ],
    [
        'column'    => 'priority',
        'label'     => _l('tasks_list_priority'),
        'initial'   => true,
        'required'  => true,
    ],
    // custom Columns
    [
        'column'    => 'description',
        'label'     => _l('description'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'datefinished',
        'label'     => _l('datefinished'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'recurring_type',
        'label'     => _l('recurring_type'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'rel_type',
        'label'     => _l('rel_type'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'hourly_rate',
        'label'     => _l('hourly_rate'),
        'initial'   => false,
        'required'  => false,
    ],
    [
        'column'    => 'last_recurring_date',
        'label'     => _l('last_recurring_date'),
        'initial'   => false,
        'required'  => false,
    ],
];

$config['contracts_columns'] = [
    [
        'column'   => db_prefix() . 'contracts.id as id',
        'label'    => _l('the_number_sign'),
        'initial'  => true,
        'required' => true,
    ],
    [
        'column'   => 'subject',
        'label'    => _l('contract_list_subject'),
        'initial'  => true,
        'required' => true,
    ],
    [
        'column'    => get_sql_select_client_company(),
        'label'     => _l('contract_list_client'),
        'initial'   => true,
        'required'  => true,
        'th_attrs'  => ['class' => ('client' == get_instance()->uri->segment(3)) ? 'not_visible' : ''],
    ],
    [
        'column'   => db_prefix() . 'contracts_types.name as type_name',
        'label'    => _l('contract_types_list_name'),
        'initial'  => true,
        'required' => false,
    ],
    [
        'column'   => 'contract_value',
        'label'    => _l('contract_value'),
        'initial'  => true,
        'required' => false,
    ],
    [
        'column'   => 'datestart',
        'label'    => _l('contract_list_start_date'),
        'initial'  => true,
        'required' => false,
    ],
    [
        'column'   => 'dateend',
        'label'    => _l('contract_list_end_date'),
        'initial'  => true,
        'required' => false,
    ],
    [
        'column'   => db_prefix() . 'projects.name as project_name',
        'label'    => _l('project'),
        'initial'  => true,
        'required' => true,
    ],
    [
        'column'   => 'signature',
        'label'    => _l('signature'),
        'initial'  => true,
        'required' => false,
    ],
    // Custom Columns
    [
        'column'   => 'content',
        'label'    => _l('content'),
        'initial'  => false,
        'required' => false,
    ],
    [
        'column'   => db_prefix() . 'contracts.description',
        'label'    => _l('description'),
        'initial'  => false,
        'required' => false,
    ],
    [
        'column'   => db_prefix() . 'contracts.dateadded',
        'label'    => _l('dateadded'),
        'initial'  => false,
        'required' => false,
    ],
    [
        'column'   => 'acceptance_firstname',
        'label'    => _l('acceptance_firstname'),
        'initial'  => false,
        'required' => false,
    ],
    [
        'column'   => 'acceptance_lastname',
        'label'    => _l('acceptance_lastname'),
        'initial'  => false,
        'required' => false,
    ],
    [
        'column'   => 'acceptance_email',
        'label'    => _l('acceptance_email'),
        'initial'  => false,
        'required' => false,
    ],
    [
        'column'   => 'acceptance_date',
        'label'    => _l('acceptance_date'),
        'initial'  => false,
        'required' => false,
    ],
    [
        'column'   => 'last_sent_at',
        'label'    => _l('last_sent_at'),
        'initial'  => false,
        'required' => false,
    ],
    [
        'column'   => 'last_sign_reminder_at',
        'label'    => _l('last_sign_reminder_at'),
        'initial'  => false,
        'required' => false,
    ],
];

$config['sample_data'] = [
    'rResult' => [
        [
            'id'          => '1',
            'name'        => 'Sample',
            'email'       => 'Sample@gmail.com',
            'phone'       => '9909999099',
        ],
        [
            'id'          => '2',
            'name'        => 'Sample2',
            'email'       => 'Sample2@gmail.com',
            'phone'       => '81828390909',
        ],
        [
            'id'          => '3',
            'name'        => 'Sample3',
            'email'       => 'Sample3@gmail.com',
            'phone'       => '1234567890',
        ],
        [
            'id'          => '4',
            'name'        => 'Sample4',
            'email'       => 'Sample4@gmail.com',
            'phone'       => '0123456789',
        ],
        [
            'id'          => '5',
            'name'        => 'Sample5',
            'email'       => 'Sample5@gmail.com',
            'phone'       => '0125897890',
        ],
        [
            'id'          => '6',
            'name'        => 'Sample6',
            'email'       => 'Sample6@gmail.com',
            'phone'       => '9963481020',
        ],
        [
            'id'          => '7',
            'name'        => 'Sample7',
            'email'       => 'Sample7@gmail.com',
            'phone'       => '9574525208',
        ],
        [
            'id'          => '8',
            'name'        => 'Sample8',
            'email'       => 'Sample8@gmail.com',
            'phone'       => '9658418210',
        ],
    ],
    'output' => [
        'draw'                 => '1',
        'iTotalRecords'        => '9',
        'iTotalDisplayRecords' => '9',
        'aaData'               => [],
    ],
];
