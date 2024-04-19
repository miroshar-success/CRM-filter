<?php

/*
 * Inject permissions Feature and Capabilities for customtables module
 */
hooks()->add_filter('staff_permissions', function ($permissions) {
    $viewGlobalName      = _l('permission_view') . '(' . _l('permission_global') . ')';
    $allPermissionsArray = [
        'view'     => $viewGlobalName,
        'create'   => _l('permission_create'),
        'edit'     => _l('permission_edit'),
        'delete'   => _l('permission_delete'),
    ];
    $permissions['customtables'] = [
        'name'         => _l('customtables'),
        'capabilities' => $allPermissionsArray,
    ];

    return $permissions;
});
