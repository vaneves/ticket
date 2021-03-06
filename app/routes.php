<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * As rotas são para reescrita de URL. Veja um exemplo:
 * Route::add('^([\d]+)-([a-z0-9\-]+)$','home/view/$1/$2');
 * 
 * Também é possível criar prefixos. Veja um exemplo:
 * Route::prefix('admin');
 */
Route::add('^/?$', 'ticket/check');
Route::add('^login$', 'user/login');
Route::add('^logout$', 'user/logout');
Route::add('^register$', 'user/register');
Route::add('^remember$', 'user/remember');
Route::add('^admin/ticket/list/(open|closed|answered)/(.*)$', 'admin/ticket/list-status/$1/$2');
Route::add('^ticket/view/([\d]+)/(.+)$', 'ticket/view-free/$1/$2');
Route::add('^ping$', 'ticket/ping');
Route::prefix('admin');
Route::prefix('client');
