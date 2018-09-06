<?php

// Dashboard
Breadcrumbs::register('settings.dashboard', function ($trail) {
    $trail->push('Dashboard', route('settings.dashboard'));
});

Breadcrumbs::register('publish.dataset.create', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('Publish', route('publish.dataset.create'));
});


Breadcrumbs::register('access.user.index', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('User Management', route('access.user.index'));
});

Breadcrumbs::register('access.user.edit', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('access.user.index');
    $breadcrumbs->push("edit" . $id, route('access.user.edit', $id));
});

Breadcrumbs::register('access.user.create', function ($breadcrumbs) {
    $breadcrumbs->parent('access.user.index');
    $breadcrumbs->push('users.create', route('access.user.create'));
});

Breadcrumbs::register('access.role.index', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('Role Management', route('access.role.index'));
});
Breadcrumbs::register('access.role.edit', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('access.role.index');
    $breadcrumbs->push('edit ' . $id, route('access.role.edit', $id));
});



Breadcrumbs::register('settings.document', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('Dataset Management', route('settings.document'));
});

Breadcrumbs::register('settings.document.edit', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('settings.document');
    $breadcrumbs->push('edit ' . $id, route('settings.document.edit', $id));
});

Breadcrumbs::register('settings.document.show', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('settings.document');
    $breadcrumbs->push('show ' . $id, route('settings.document.show', $id));
});

Breadcrumbs::register('settings.page.index', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('Page Management', route('settings.page.index'));
});
