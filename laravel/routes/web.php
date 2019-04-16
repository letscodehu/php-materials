<?php

Route::get('/', "MainPageController@index");
Route::get("/{postSlug}", "SinglePostController@handle");