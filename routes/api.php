<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//test router
Route::post('/signIn', 'Api\UserController@login');
Route::post('/register', 'Api\UserController@register');
Route::post('/registerDesigner', 'Api\UserController@registerDesigner');
// Sizes
Route::post('/getSizes', 'Api\HomeController@getSizes');
Route::get('/getQuestions', 'Api\QuestionsController@getQuestions');
// Colors
Route::post('/getColors', 'Api\ColorController@getColors');
// Countries
Route::post('/getCountries', 'Api\HomeController@getCountries');

Route::any('/home', 'Api\HomeController@home');
Route::any('/homeTrends', 'Api\HomeController@home');


Route::any('/trending', 'Api\HomeController@trending');

// Search
Route::post('/search', 'Api\HomeController@search');
// Brands
Route::post('/getBrands', 'Api\ItemsController@getBrands');
Route::any('/browsePopular', 'Api\HomeController@browsePopular');
//filters
Route::post('/filter', 'Api\HomeController@filter');

Route::post('/itemDetails', 'Api\ItemsController@itemDetails');
Route::get('/getPages/{slug}', 'Api\PagesController@getPages');

Route::post('/passwordReset', 'ResetPasswordController@passwordReset');
//Route::get('/passwordReset/{token}', 'ResetPasswordController@RePassword')->name('api.password.reset');
//Route::post('/passwordTokenCheck/{token}', 'ResetPasswordController@passwordTokenCheck')->name('api.password.token_check');


//Categories
Route::post('getItemsFromCategory', 'Api\CategoriesController@getItemsFromCategory');
Route::post('getItemsCategories', 'Api\CategoriesController@getItemsCategories');

Route::group(["middleware" => ['api-auth']], function ($router) {

    // Home
    $router->post('/feed', 'Api\HomeController@feed');
    $router->post('/homeFeeds', 'Api\HomeController@feed');
    // Collection
    $router->post('createCollection', 'Api\CollectionController@createCollection');
    $router->post('getCollections', 'Api\CollectionController@getCollections');
    $router->post('deleteCollection', 'Api\CollectionController@deleteCollection');
    $router->post('addItemToCollection', 'Api\CollectionController@addItemToCollection');
    $router->post('addSetToCollection', 'Api\CollectionController@addSetToCollection');
    $router->post('editCollection', 'Api\CollectionController@editCollection');
    $router->post('collectionDetails', 'Api\CollectionController@collectionDetails');
    // Collection >> comments
    $router->post('addCommentToCollection', 'Api\CollectionController@addCommentToCollection');
    $router->post('getCollectionComments', 'Api\CollectionController@getCollectionComments');
    $router->post('deleteCollectionComment', 'Api\CollectionController@deleteCollectionComment');

    // Sets
    $router->post('setDetails', 'Api\SetsController@setDetails');
    $router->post('addCommentToSet', 'Api\SetsController@addCommentToSet');
    $router->post('getSetComments', 'Api\SetsController@getSetComments');
    $router->post('getLikedSets', 'Api\SetsController@getLikedSets');
    $router->post('addSet', 'Api\SetsController@addSet');
    $router->post('deleteComment', 'Api\SetsController@deleteComment');
    $router->post('deleteSet', 'Api\SetsController@deleteSet');
    $router->post('getSets', 'Api\SetsController@getSets');
    $router->post('editSet', 'Api\SetsController@editSet');

    // Items
    $router->post('switchLike', 'Api\ItemsController@switchLike');
    $router->post('getLikedItems', 'Api\ItemsController@getLikedItems');
    $router->post('getSearchForAddSet', 'Api\ItemsController@getSearchForAddSet');


    //Contests
    $router->post('getContests', 'Api\ContestController@getContests');
    $router->post('getContestPhotos', 'Api\ContestController@getContestPhotos');
    $router->post('publishContestPhoto', 'Api\ContestController@publishContestPhoto');
    $router->post('getWins', 'Api\ContestController@getWins');

    // Contests >> comments
    $router->post('addCommentToContest', 'Api\ContestController@addCommentToContest');
    $router->post('getContestComments', 'Api\ContestController@getContestComments');
    $router->post('deleteContestComment', 'Api\ContestController@deleteContestComment');


    // Users
    $router->post('followUser', 'Api\UserController@followUser');
    $router->post('unfollowUser', 'Api\UserController@unfollowUser');
    $router->post('getProfile', 'Api\UserController@getProfile');
    $router->post('getFollowingUsers', 'Api\UserController@getFollowingUsers');
    $router->post('getFollowersUsers', 'Api\UserController@getFollowersUsers');
    $router->post('profileUpdate', 'Api\UserController@profileUpdate');
    $router->post('blockUser', 'Api\UserController@blockUser');
    $router->post('unblockUser', 'Api\UserController@unblockUser');
    $router->post('listBlocked', 'Api\UserController@listBlocked');
    $router->post('recommendedUser', 'Api\UserController@recommendedUser');
    $router->post('myProfile', 'Api\UserController@myProfile');
    $router->any('setPushToken', 'Api\UserController@setPushToken');

    //Notifications
    $router->post('getNotifications', 'Api\NotificationsController@getNotifications');
    $router->post('setNotificationSeen', 'Api\NotificationsController@setNotificationSeen');

    //Messages
    $router->post('getChannels', 'Api\MessagesController@getChannels');
    $router->post('pushMessage', 'Api\MessagesController@pushMessage');
    $router->post('getChannelMessages', 'Api\MessagesController@getChannelMessages');


    // Reports
    $router->post('pushReport', 'Api\ReportsController@pushReport');


    // Groups
    $router->post('/createGroup', 'Api\GroupController@createGroup');
    $router->post('/inviteMembers', 'Api\GroupController@inviteMembers');
    $router->post('/groupDetails', 'Api\GroupController@groupDetails');
    $router->post('/getUserGroups', 'Api\GroupController@getUserGroups');
    $router->post('/joinToGroup', 'Api\GroupController@joinToGroup');
    $router->post('/getJoinInvites', 'Api\GroupController@getJoinInvites');
    $router->post('/approveJoinInvite', 'Api\GroupController@approveJoinInvite');
    $router->post('/leaveGroup', 'Api\GroupController@leaveGroup');
    $router->post('/getGroupMembers', 'Api\GroupController@getGroupMembers');
    $router->post('/removeGroupMember', 'Api\GroupController@removeGroupMember');
    $router->post('/deleteGroup', 'Api\GroupController@deleteGroup');
    $router->post('/homeGroups', 'Api\GroupController@homeGroups');

});

Route::group(["middleware" => ['api-auth:designer']], function ($router) {
    $router->post('/listItems', 'Api\ItemsController@listItems');
    $router->post('/deleteItems', 'Api\ItemsController@deleteItems');
    $router->post('/addItem', 'Api\ItemsController@addItem');
    $router->post('/getEditingItemDetails', 'Api\ItemsController@getEditingItemDetails');
    $router->post('/editItem', 'Api\ItemsController@editItem');
    // Exports
    $router->post('/importFile', 'Api\ExportsController@importFile');
});


Route::get('/designer-register', function () {
    return view('designer-register');
});

Route::group(["middleware" => ['api-auth:designer']], function ($router) {


});
Route::fallback('Api\NotFoundController@notFound')->name('fallback');
