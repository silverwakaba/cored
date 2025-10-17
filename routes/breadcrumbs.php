<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

/**
 * Index
*/

// Index
Breadcrumbs::for('index', function (BreadcrumbTrail $trail){
    $trail->push('Home', route('fe.page.index'));
});

/**
 * Auth
*/

// Auth
Breadcrumbs::for('auth', function (BreadcrumbTrail $trail){
    $trail->parent('index');
    $trail->push('Auth', route('fe.page.auth'));
});

// Auth-Register
Breadcrumbs::for('auth.register', function (BreadcrumbTrail $trail){
    $trail->parent('auth');
    $trail->push('Register', route('fe.auth.register'));
});

// Auth-Login
Breadcrumbs::for('auth.login', function (BreadcrumbTrail $trail){
    $trail->parent('auth');
    $trail->push('Login', route('fe.auth.login'));
});

// Auth-Verify Account
Breadcrumbs::for('auth.verify-account', function (BreadcrumbTrail $trail){
    $trail->parent('auth');
    $trail->push('Verify Account', route('fe.auth.verify-account'));
});

/**
 * Apps
*/

// Apps
Breadcrumbs::for('apps', function (BreadcrumbTrail $trail){
    $trail->parent('index');
    $trail->push('Apps', route('fe.apps.index'));
});

/**
 * Apps RBAC
*/

// Apps-RBAC
Breadcrumbs::for('apps.rbac', function (BreadcrumbTrail $trail){
    $trail->parent('apps');
    $trail->push('RBAC', route('fe.apps.rbac.index'));
});

// Apps-RBAC-Permission
Breadcrumbs::for('apps.rbac.permission', function (BreadcrumbTrail $trail){
    $trail->parent('apps.rbac');
    $trail->push('Permission', route('fe.apps.rbac.permission.index'));
});

// Apps-RBAC-Role
Breadcrumbs::for('apps.rbac.role', function (BreadcrumbTrail $trail){
    $trail->parent('apps.rbac');
    $trail->push('Role', route('fe.apps.rbac.role.index'));
});

// Apps-RBAC-UAC
Breadcrumbs::for('apps.rbac.uac', function (BreadcrumbTrail $trail){
    $trail->parent('apps.rbac');
    $trail->push('User Access Control', route('fe.apps.rbac.uac.index'));
});
