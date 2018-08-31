<?php

// Dashboard
Breadcrumbs::register('settings.dashboard', function ($trail) {
    $trail->push('Dashboard', route('settings.dashboard'));
});

Breadcrumbs::register('publish.dataset.create', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('Publish', route('publish.dataset.create'));
});

Breadcrumbs::register('settings.user.index', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('Users Management', route('settings.user.index'));
});

Breadcrumbs::register('settings.user.edit', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('settings.user.index');
    $breadcrumbs->push("users.edit", route('settings.user.edit', $id));
});

Breadcrumbs::register('settings.user.create', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.user.index');
    $breadcrumbs->push('users.create', route('settings.user.create'));
});
