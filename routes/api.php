<?php

Route::resource('/tasks','TaskController',[
    'except' => ['create','show','edit']
]);