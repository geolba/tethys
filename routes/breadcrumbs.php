<?php
// use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Diglactic\Breadcrumbs\Breadcrumbs;

// Dashboard
Breadcrumbs::for('settings.dashboard', function ($trail) {
    $trail->push('Dashboard', route('settings.dashboard'));
});

Breadcrumbs::for('publish.dataset.create', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('Publish', route('publish.dataset.create'));
});


Breadcrumbs::for('access.user.index', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('User Management', route('access.user.index'));
});

Breadcrumbs::for('access.user.edit', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('access.user.index');
    $breadcrumbs->push("edit" . $id, route('access.user.edit', $id));
});

Breadcrumbs::for('access.user.create', function ($breadcrumbs) {
    $breadcrumbs->parent('access.user.index');
    $breadcrumbs->push('users.create', route('access.user.create'));
});

Breadcrumbs::for('access.role.index', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('Role Management', route('access.role.index'));
});
Breadcrumbs::for('access.role.edit', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('access.role.index');
    $breadcrumbs->push('edit ' . $id, route('access.role.edit', $id));
});



Breadcrumbs::for('settings.document', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('Dataset Management', route('settings.document'));
});

Breadcrumbs::for('settings.document.edit', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('settings.document');
    $breadcrumbs->push('edit ' . $id, route('settings.document.edit', $id));
});

Breadcrumbs::for('settings.document.show', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('settings.document');
    $breadcrumbs->push('show ' . $id, route('settings.document.show', $id));
});

Breadcrumbs::for('settings.page.index', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('Page Management', route('settings.page.index'));
});


Breadcrumbs::for('settings.collectionrole.index', function ($breadcrumbs) {
    $breadcrumbs->parent('settings.dashboard');
    $breadcrumbs->push('Collection Roles', route('settings.collectionrole.index'));
});
Breadcrumbs::for('settings.collectionrole.show', function ($breadcrumbs, $collectionrole) {
    $breadcrumbs->parent('settings.collectionrole.index');
    $breadcrumbs->push(
        'top level collections of role ' . $collectionrole->name,
        route('settings.collectionrole.show', $collectionrole)
    );
});
Breadcrumbs::for('settings.collection.show', function ($breadcrumbs, $collection) {
    // $breadcrumbs->parent('settings.collectionrole.show', $collection->collectionrole);
    if ($collection->parent()->exists()) {
        $breadcrumbs->parent('settings.collection.show', $collection->parent);
    } else {
        $breadcrumbs->parent('settings.collectionrole.show', $collection->collectionrole);
    }
    $breadcrumbs->push('show collection: ' . $collection->name, route('settings.collection.show', $collection));
});
Breadcrumbs::for('settings.collection.edit', function ($breadcrumbs, $id) {
    $collection = App\Models\Collection::findOrFail($id);
    if ($collection->parent()->exists()) {
        $breadcrumbs->parent('settings.collection.show', $collection->parent);
    } else {
        $breadcrumbs->parent('settings.collectionrole.show', $collection->collectionrole);
    }
    $breadcrumbs->push('edit collection: ' . $collection->name, route('settings.collection.edit', $id));
});
