<?php
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('/process', 'FilepondController@uploadFile')->name('filepond.upload');
    Route::delete('/process', 'FilepondController@delete')->name('filepond.delete');
});
