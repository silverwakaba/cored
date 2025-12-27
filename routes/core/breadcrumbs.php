<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

/**
 * Core Breadcrumbs
 * DO NOT MODIFY in child projects
 */

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

// Auth-Reset password
Breadcrumbs::for('auth.reset-password', function (BreadcrumbTrail $trail){
    $trail->parent('auth');
    $trail->push('Reset Password', route('fe.auth.reset-password'));
});

// Auth-Reset password tokenized
Breadcrumbs::for('auth.reset-password-tokenized', function (BreadcrumbTrail $trail){
    $trail->parent('auth.reset-password');
    $trail->push(request()->token, route('fe.auth.reset-password-tokenized', ['token' => request()->token]));
});

// CTA
Breadcrumbs::for('cta', function (BreadcrumbTrail $trail){
    $trail->parent('index');
    $trail->push('CTA', route('fe.page.cta'));
});

// CTA
Breadcrumbs::for('cta.message', function (BreadcrumbTrail $trail){
    $trail->parent('cta');
    $trail->push('Message', route('fe.cta.message'));
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
 * Apps Base
*/

// Apps-Base
Breadcrumbs::for('apps.base', function (BreadcrumbTrail $trail){
    $trail->parent('apps');
    $trail->push('Base', route('fe.apps.base.index'));
});

// Apps-Base-Module
Breadcrumbs::for('apps.base.module', function (BreadcrumbTrail $trail){
    $trail->parent('apps.base');
    $trail->push('Module', route('fe.apps.base.module.index'));
});

// Apps-Base-Request
Breadcrumbs::for('apps.base.request', function (BreadcrumbTrail $trail){
    $trail->parent('apps.base');
    $trail->push('Request', route('fe.apps.base.request.index'));
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

