<?php

Route::get('/', "MainPageController@index");
Route::get("/{year}/{month}/{day}/{hour}/{minute}/{second}/{postSlug}", "SinglePostController@handle");