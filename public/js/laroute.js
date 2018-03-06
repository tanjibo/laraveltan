(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://localhost',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/stats","name":"horizon.stats.index","action":"Laravel\Horizon\Http\Controllers\DashboardStatsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/workload","name":"horizon.workload.index","action":"Laravel\Horizon\Http\Controllers\WorkloadController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/masters","name":"horizon.masters.index","action":"Laravel\Horizon\Http\Controllers\MasterSupervisorController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/monitoring","name":"horizon.monitoring.index","action":"Laravel\Horizon\Http\Controllers\MonitoringController@index"},{"host":null,"methods":["POST"],"uri":"horizon\/api\/monitoring","name":"horizon.monitoring.store","action":"Laravel\Horizon\Http\Controllers\MonitoringController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/monitoring\/{tag}","name":"horizon.monitoring-tag.paginate","action":"Laravel\Horizon\Http\Controllers\MonitoringController@paginate"},{"host":null,"methods":["DELETE"],"uri":"horizon\/api\/monitoring\/{tag}","name":"horizon.monitoring-tag.destroy","action":"Laravel\Horizon\Http\Controllers\MonitoringController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/metrics\/jobs","name":"horizon.jobs-metrics.index","action":"Laravel\Horizon\Http\Controllers\JobMetricsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/metrics\/jobs\/{id}","name":"horizon.jobs-metrics.show","action":"Laravel\Horizon\Http\Controllers\JobMetricsController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/metrics\/queues","name":"horizon.queues-metrics.index","action":"Laravel\Horizon\Http\Controllers\QueueMetricsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/metrics\/queues\/{id}","name":"horizon.queues-metrics.show","action":"Laravel\Horizon\Http\Controllers\QueueMetricsController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/jobs\/recent","name":"horizon.recent-jobs.index","action":"Laravel\Horizon\Http\Controllers\RecentJobsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/jobs\/failed","name":"horizon.failed-jobs.index","action":"Laravel\Horizon\Http\Controllers\FailedJobsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/api\/jobs\/failed\/{id}","name":"horizon.failed-jobs.show","action":"Laravel\Horizon\Http\Controllers\FailedJobsController@show"},{"host":null,"methods":["POST"],"uri":"horizon\/api\/jobs\/retry\/{id}","name":"horizon.retry-jobs.show","action":"Laravel\Horizon\Http\Controllers\RetryController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"horizon\/{view?}","name":null,"action":"Laravel\Horizon\Http\Controllers\HomeController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/open","name":"debugbar.openhandler","action":"Barryvdh\Debugbar\Controllers\OpenHandlerController@handle"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/clockwork\/{id}","name":"debugbar.clockwork","action":"Barryvdh\Debugbar\Controllers\OpenHandlerController@clockwork"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/assets\/stylesheets","name":"debugbar.assets.css","action":"Barryvdh\Debugbar\Controllers\AssetController@css"},{"host":null,"methods":["GET","HEAD"],"uri":"_debugbar\/assets\/javascript","name":"debugbar.assets.js","action":"Barryvdh\Debugbar\Controllers\AssetController@js"},{"host":null,"methods":["POST"],"uri":"oauth\/token","name":null,"action":"\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth\/tokens","name":null,"action":"\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@forUser"},{"host":null,"methods":["DELETE"],"uri":"oauth\/tokens\/{token_id}","name":null,"action":"\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy"},{"host":null,"methods":["POST"],"uri":"api\/customer\/miniLogin","name":"customer.mini.login","action":"App\Http\ApiControllers\Experience\LoginController@miniLogin"},{"host":null,"methods":["POST"],"uri":"api\/customer\/refreshToken","name":null,"action":"App\Http\ApiControllers\Experience\LoginController@refreshToken"},{"host":null,"methods":["POST"],"uri":"api\/room\/list","name":"room.list","action":"App\Http\ApiControllers\Experience\ExperienceRoomController@roomList"},{"host":null,"methods":["POST"],"uri":"api\/customer\/logout","name":"customer.logout","action":"App\Http\ApiControllers\Experience\LoginController@logout"},{"host":null,"methods":["POST"],"uri":"api\/room\/detail\/{room_id}","name":"room.detail","action":"App\Http\ApiControllers\Experience\ExperienceRoomController@roomDetail"},{"host":null,"methods":["POST"],"uri":"api\/room\/questions","name":null,"action":"App\Http\ApiControllers\Experience\ExperienceRoomController@question"},{"host":null,"methods":["POST"],"uri":"api\/booking\/room_checkin_disable\/{room_id}","name":"room.booking.roomCheckinDisable","action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@RoomCheckinDisableBy"},{"host":null,"methods":["POST"],"uri":"api\/booking\/room_checkout_disable\/{room_id}","name":"room.booking.roomCheckoutDisable","action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@RoomCheckoutDisableBy"},{"host":null,"methods":["POST"],"uri":"api\/booking\/room_left\/{room_id}","name":"room.booking.leftRoom","action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@leftCheckinRoom"},{"host":null,"methods":["POST"],"uri":"api\/booking\/orderTotalFee","name":"room.booking.orderTotal","action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@orderTotalFee"},{"host":null,"methods":["POST"],"uri":"api\/booking\/createBookingOrder","name":"room.booking.create","action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@createBookingOrder"},{"host":null,"methods":["POST"],"uri":"api\/booking\/repay","name":null,"action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@repay"},{"host":null,"methods":["POST"],"uri":"api\/booking\/orderList","name":"room.booking.orderList","action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@orderList"},{"host":null,"methods":["POST"],"uri":"api\/booking\/orderDetail\/{booking_id}\/{type?}","name":"room.booking.orderList","action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@orderDetail"},{"host":null,"methods":["POST"],"uri":"api\/comment\/list\/{room_id}","name":"room.comment.list","action":"App\Http\ApiControllers\Experience\ExperienceBookingCommentController@commentList"},{"host":null,"methods":["POST"],"uri":"api\/comment\/add\/{booking_id}\/{type?}","name":"room.comment.add","action":"App\Http\ApiControllers\Experience\ExperienceBookingCommentController@addComment"},{"host":null,"methods":["POST"],"uri":"api\/comment\/rooms\/{booking_id}\/{type?}","name":"room.comment.rooms","action":"App\Http\ApiControllers\Experience\ExperienceBookingCommentController@getBookingRooms"},{"host":null,"methods":["POST"],"uri":"api\/booking\/calendarInit","name":"booking.calendarInit","action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@calendarInit"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/booking\/changeOrderStatus\/booking_id\/{booking_id}\/status\/{status}\/form_id\/{form_id?}","name":"room.booking.changeStatus","action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@orderStatusToChange"},{"host":null,"methods":["POST"],"uri":"api\/tpl\/sendPayTpl","name":"tpl.sendPayTpl","action":"App\Http\ApiControllers\Experience\WechatTemplateController@sendPayTpl"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/tpl\/changeBookingOrder","name":null,"action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@changeBookingOrder"},{"host":null,"methods":["POST"],"uri":"api\/user\/userInfo","name":null,"action":"App\Http\ApiControllers\Experience\UserController@userInfo"},{"host":null,"methods":["POST"],"uri":"api\/mini\/callback\/{booking_id}","name":"mini.callback","action":"App\Http\ApiControllers\Experience\ExperienceRoomBookingController@miniNotifyCallback"},{"host":null,"methods":["POST"],"uri":"api\/art\/miniLogin","name":null,"action":"App\Http\ApiControllers\Art\LoginController@miniLogin"},{"host":null,"methods":["POST"],"uri":"api\/art\/refreshToken","name":null,"action":"App\Http\ApiControllers\Art\LoginController@refreshToken"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/art_show","name":"art_show.index","action":"App\Http\ApiControllers\Art\ArtShowController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/art_show\/{art_show}","name":"art_show.show","action":"App\Http\ApiControllers\Art\ArtShowController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/art_show\/comment\/{art_show}","name":null,"action":"App\Http\ApiControllers\Art\ArtShowController@getArtShowComment"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/art_show\/share\/{art_show}","name":null,"action":"App\Http\ApiControllers\Art\ArtShowController@shareArtShow"},{"host":null,"methods":["POST"],"uri":"api\/art_comment","name":"art_comment.store","action":"App\Http\ApiControllers\Art\CommentController@store"},{"host":null,"methods":["DELETE"],"uri":"api\/art_comment\/{art_comment}","name":"art_comment.destroy","action":"App\Http\ApiControllers\Art\CommentController@destroy"},{"host":null,"methods":["POST"],"uri":"api\/art_comment_list\/{art_show}","name":null,"action":"App\Http\ApiControllers\Art\CommentController@commentList"},{"host":null,"methods":["POST"],"uri":"api\/art_comment_detail\/{art_comment}","name":null,"action":"App\Http\ApiControllers\Art\CommentController@commentDetail"},{"host":null,"methods":["POST"],"uri":"api\/art_like","name":"art_like.store","action":"App\Http\ApiControllers\Art\LikesController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/art_collection","name":"art_collection.index","action":"App\Http\ApiControllers\Art\CollectionController@index"},{"host":null,"methods":["POST"],"uri":"api\/art_collection\/store\/{art_show}","name":null,"action":"App\Http\ApiControllers\Art\CollectionController@store"},{"host":null,"methods":["POST"],"uri":"api\/art_suggestion","name":"art_suggestion.store","action":"App\Http\ApiControllers\Art\SuggestionController@store"},{"host":null,"methods":["POST"],"uri":"api\/art_user\/userInfo","name":null,"action":"App\Http\ApiControllers\Art\UserController@userInfo"},{"host":null,"methods":["POST"],"uri":"api\/art_user\/unReadMsg","name":null,"action":"App\Http\ApiControllers\Art\UserController@unReadMsg"},{"host":null,"methods":["GET","HEAD"],"uri":"login","name":null,"action":"App\Http\Controllers\LoginController@showLoginForm"},{"host":null,"methods":["POST"],"uri":"login","name":"login","action":"App\Http\Controllers\LoginController@login"},{"host":null,"methods":["GET","HEAD"],"uri":"logout","name":"logout","action":"App\Http\Controllers\LoginController@logout"},{"host":null,"methods":["GET","HEAD"],"uri":"home","name":"home","action":"App\Http\Controllers\HomeController@home"},{"host":null,"methods":["GET","HEAD"],"uri":"user","name":"user.index","action":"App\Http\Controllers\UserController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"user\/create","name":"user.create","action":"App\Http\Controllers\UserController@create"},{"host":null,"methods":["POST"],"uri":"user","name":"user.store","action":"App\Http\Controllers\UserController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"user\/{user}","name":"user.show","action":"App\Http\Controllers\UserController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"user\/{user}\/edit","name":"user.edit","action":"App\Http\Controllers\UserController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"user\/{user}","name":"user.update","action":"App\Http\Controllers\UserController@update"},{"host":null,"methods":["DELETE"],"uri":"user\/{user}","name":"user.destroy","action":"App\Http\Controllers\UserController@destroy"},{"host":null,"methods":["POST"],"uri":"user\/index_api","name":"user.index_api","action":"App\Http\Controllers\UserController@indexApi"},{"host":null,"methods":["POST","GET","HEAD"],"uri":"user\/give_permissions\/{user}","name":"user.give_permissions","action":"App\Http\Controllers\UserController@givePermissions"},{"host":null,"methods":["POST","GET","HEAD"],"uri":"user\/user_all_order\/{user}","name":"user.user_all_order","action":"App\Http\Controllers\UserController@userAllOrder"},{"host":null,"methods":["GET","HEAD"],"uri":"permission","name":"permission.index","action":"App\Http\Controllers\PermissionsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"permission\/create","name":"permission.create","action":"App\Http\Controllers\PermissionsController@create"},{"host":null,"methods":["POST"],"uri":"permission","name":"permission.store","action":"App\Http\Controllers\PermissionsController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"permission\/{permission}","name":"permission.show","action":"App\Http\Controllers\PermissionsController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"permission\/{permission}\/edit","name":"permission.edit","action":"App\Http\Controllers\PermissionsController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"permission\/{permission}","name":"permission.update","action":"App\Http\Controllers\PermissionsController@update"},{"host":null,"methods":["DELETE"],"uri":"permission\/{permission}","name":"permission.destroy","action":"App\Http\Controllers\PermissionsController@destroy"},{"host":null,"methods":["POST"],"uri":"permission\/mass_destroy","name":"permission.mass_destroy","action":"App\Http\Controllers\PermissionsController@massDestroy"},{"host":null,"methods":["GET","HEAD"],"uri":"roles","name":"roles.index","action":"App\Http\Controllers\RolesController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"roles\/create","name":"roles.create","action":"App\Http\Controllers\RolesController@create"},{"host":null,"methods":["POST"],"uri":"roles","name":"roles.store","action":"App\Http\Controllers\RolesController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"roles\/{role}","name":"roles.show","action":"App\Http\Controllers\RolesController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"roles\/{role}\/edit","name":"roles.edit","action":"App\Http\Controllers\RolesController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"roles\/{role}","name":"roles.update","action":"App\Http\Controllers\RolesController@update"},{"host":null,"methods":["DELETE"],"uri":"roles\/{role}","name":"roles.destroy","action":"App\Http\Controllers\RolesController@destroy"},{"host":null,"methods":["POST"],"uri":"roles\/mass_destroy","name":"roles.mass_destroy","action":"App\Http\Controllers\RolesController@massDestroy"},{"host":null,"methods":["GET","HEAD"],"uri":"notification","name":"notification.index","action":"App\Http\Controllers\NotificationController@index"},{"host":null,"methods":["GET","HEAD","POST","PUT","PATCH","DELETE","OPTIONS"],"uri":"upload\/qiniu","name":"upload.qiniu","action":"App\Http\Controllers\UploadController@uploadToQiniuBy"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_rooms","name":"experience_rooms.index","action":"App\Http\Controllers\Experience\RoomController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_rooms\/create","name":"experience_rooms.create","action":"App\Http\Controllers\Experience\RoomController@create"},{"host":null,"methods":["POST"],"uri":"experience_rooms","name":"experience_rooms.store","action":"App\Http\Controllers\Experience\RoomController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_rooms\/{experience_room}","name":"experience_rooms.show","action":"App\Http\Controllers\Experience\RoomController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_rooms\/{experience_room}\/edit","name":"experience_rooms.edit","action":"App\Http\Controllers\Experience\RoomController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"experience_rooms\/{experience_room}","name":"experience_rooms.update","action":"App\Http\Controllers\Experience\RoomController@update"},{"host":null,"methods":["DELETE"],"uri":"experience_rooms\/{experience_room}","name":"experience_rooms.destroy","action":"App\Http\Controllers\Experience\RoomController@destroy"},{"host":null,"methods":["POST"],"uri":"experience_rooms\/makePrice\/{experience_room}","name":"experience_rooms.makePrice","action":"App\Http\Controllers\Experience\RoomController@makePrice"},{"host":null,"methods":["POST","GET","HEAD"],"uri":"experience_rooms\/lockDate\/{experience_room}","name":"experience_rooms.lockDate","action":"App\Http\Controllers\Experience\RoomController@lockDate"},{"host":null,"methods":["POST"],"uri":"experience_rooms\/makeDate","name":"experience_rooms.makeDate","action":"App\Http\Controllers\Experience\RoomController@makeDate"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_bookings","name":"experience_bookings.index","action":"App\Http\Controllers\Experience\BookingController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_bookings\/create","name":"experience_bookings.create","action":"App\Http\Controllers\Experience\BookingController@create"},{"host":null,"methods":["POST"],"uri":"experience_bookings","name":"experience_bookings.store","action":"App\Http\Controllers\Experience\BookingController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_bookings\/{experience_booking}","name":"experience_bookings.show","action":"App\Http\Controllers\Experience\BookingController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_bookings\/{experience_booking}\/edit","name":"experience_bookings.edit","action":"App\Http\Controllers\Experience\BookingController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"experience_bookings\/{experience_booking}","name":"experience_bookings.update","action":"App\Http\Controllers\Experience\BookingController@update"},{"host":null,"methods":["DELETE"],"uri":"experience_bookings\/{experience_booking}","name":"experience_bookings.destroy","action":"App\Http\Controllers\Experience\BookingController@destroy"},{"host":null,"methods":["POST"],"uri":"experience_bookings\/index_api","name":"experience_bookings.index_api","action":"App\Http\Controllers\Experience\BookingController@indexApi"},{"host":null,"methods":["POST"],"uri":"experience_bookings\/changeStatus\/{experience_booking}","name":"experience_bookings.changeStatus","action":"App\Http\Controllers\Experience\BookingController@changeStatus"},{"host":null,"methods":["POST"],"uri":"experience_bookings\/editRequirements\/{experience_booking}","name":"experience_bookings.editRequirements","action":"App\Http\Controllers\Experience\BookingController@editRequirements"},{"host":null,"methods":["POST"],"uri":"experience_bookings\/calendarInit\/{room_id}","name":"experience_bookings.calendarInit","action":"App\Http\Controllers\Experience\BookingController@calendarInit"},{"host":null,"methods":["POST"],"uri":"experience_bookings\/leftCheckinRoom\/{experience_room}","name":"experience_bookings.leftCheckinRoom","action":"App\Http\Controllers\Experience\BookingController@leftCheckinRoom"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_comments","name":"experience_comments.index","action":"App\Http\Controllers\Experience\CommentController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_comments\/create","name":"experience_comments.create","action":"App\Http\Controllers\Experience\CommentController@create"},{"host":null,"methods":["POST"],"uri":"experience_comments","name":"experience_comments.store","action":"App\Http\Controllers\Experience\CommentController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_comments\/{experience_comment}","name":"experience_comments.show","action":"App\Http\Controllers\Experience\CommentController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_comments\/{experience_comment}\/edit","name":"experience_comments.edit","action":"App\Http\Controllers\Experience\CommentController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"experience_comments\/{experience_comment}","name":"experience_comments.update","action":"App\Http\Controllers\Experience\CommentController@update"},{"host":null,"methods":["DELETE"],"uri":"experience_comments\/{experience_comment}","name":"experience_comments.destroy","action":"App\Http\Controllers\Experience\CommentController@destroy"},{"host":null,"methods":["POST"],"uri":"experience_comments\/index_api","name":"experience_comments.index_api","action":"App\Http\Controllers\Experience\CommentController@indexApi"},{"host":null,"methods":["POST"],"uri":"experience_comment_reply\/{experience_comment}","name":"experience_comments.reply","action":"App\Http\Controllers\Experience\CommentController@reply"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_settings","name":"experience_settings.index","action":"App\Http\Controllers\Experience\SettingsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_settings\/create","name":"experience_settings.create","action":"App\Http\Controllers\Experience\SettingsController@create"},{"host":null,"methods":["POST"],"uri":"experience_settings","name":"experience_settings.store","action":"App\Http\Controllers\Experience\SettingsController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_settings\/{experience_setting}","name":"experience_settings.show","action":"App\Http\Controllers\Experience\SettingsController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_settings\/{experience_setting}\/edit","name":"experience_settings.edit","action":"App\Http\Controllers\Experience\SettingsController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"experience_settings\/{experience_setting}","name":"experience_settings.update","action":"App\Http\Controllers\Experience\SettingsController@update"},{"host":null,"methods":["DELETE"],"uri":"experience_settings\/{experience_setting}","name":"experience_settings.destroy","action":"App\Http\Controllers\Experience\SettingsController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_article\/create","name":"experience_article.create","action":"App\Http\Controllers\Experience\ArticleController@create"},{"host":null,"methods":["POST"],"uri":"experience_article","name":"experience_article.store","action":"App\Http\Controllers\Experience\ArticleController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_article\/{experience_article}\/edit","name":"experience_article.edit","action":"App\Http\Controllers\Experience\ArticleController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"experience_article\/{experience_article}","name":"experience_article.update","action":"App\Http\Controllers\Experience\ArticleController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_more_article","name":"experience_more_article.index","action":"App\Http\Controllers\Experience\MoreArticleController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_more_article\/create","name":"experience_more_article.create","action":"App\Http\Controllers\Experience\MoreArticleController@create"},{"host":null,"methods":["POST"],"uri":"experience_more_article","name":"experience_more_article.store","action":"App\Http\Controllers\Experience\MoreArticleController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_more_article\/{experience_more_article}","name":"experience_more_article.show","action":"App\Http\Controllers\Experience\MoreArticleController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"experience_more_article\/{experience_more_article}\/edit","name":"experience_more_article.edit","action":"App\Http\Controllers\Experience\MoreArticleController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"experience_more_article\/{experience_more_article}","name":"experience_more_article.update","action":"App\Http\Controllers\Experience\MoreArticleController@update"},{"host":null,"methods":["DELETE"],"uri":"experience_more_article\/{experience_more_article}","name":"experience_more_article.destroy","action":"App\Http\Controllers\Experience\MoreArticleController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"art","name":"art.index","action":"App\Http\Controllers\Art\ArtShowController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"art\/create","name":"art.create","action":"App\Http\Controllers\Art\ArtShowController@create"},{"host":null,"methods":["POST"],"uri":"art","name":"art.store","action":"App\Http\Controllers\Art\ArtShowController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"art\/{art}","name":"art.show","action":"App\Http\Controllers\Art\ArtShowController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"art\/{art}\/edit","name":"art.edit","action":"App\Http\Controllers\Art\ArtShowController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"art\/{art}","name":"art.update","action":"App\Http\Controllers\Art\ArtShowController@update"},{"host":null,"methods":["DELETE"],"uri":"art\/{art}","name":"art.destroy","action":"App\Http\Controllers\Art\ArtShowController@destroy"},{"host":null,"methods":["POST"],"uri":"art\/index_api","name":"art.index_api","action":"App\Http\Controllers\Art\ArtShowController@indexApi"},{"host":null,"methods":["GET","HEAD"],"uri":"art_comment","name":"art_comment.index","action":"App\Http\Controllers\Art\CommentController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"art_comment\/create","name":"art_comment.create","action":"App\Http\Controllers\Art\CommentController@create"},{"host":null,"methods":["POST"],"uri":"art_comment","name":"art_comment.store","action":"App\Http\Controllers\Art\CommentController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"art_comment\/{art_comment}","name":"art_comment.show","action":"App\Http\Controllers\Art\CommentController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"art_comment\/{art_comment}\/edit","name":"art_comment.edit","action":"App\Http\Controllers\Art\CommentController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"art_comment\/{art_comment}","name":"art_comment.update","action":"App\Http\Controllers\Art\CommentController@update"},{"host":null,"methods":["DELETE"],"uri":"art_comment\/{art_comment}","name":"art_comment.destroy","action":"App\Http\Controllers\Art\CommentController@destroy"},{"host":null,"methods":["POST"],"uri":"art_comment\/index_api","name":"art_comment.index_api","action":"App\Http\Controllers\Art\CommentController@indexApi"},{"host":null,"methods":["POST"],"uri":"art_comment_like","name":"art_comment_like.store","action":"App\Http\Controllers\Art\LikesController@store"},{"host":null,"methods":["POST"],"uri":"art_show_collect\/{art_show}","name":"art_show_collect.store","action":"App\Http\Controllers\Art\ArtShowCollectController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"art_suggestion","name":"art_suggestion.index","action":"App\Http\Controllers\Art\SuggestionController@index"},{"host":null,"methods":["DELETE"],"uri":"art_suggestion\/{art_suggestion}","name":"art_suggestion.destroy","action":"App\Http\Controllers\Art\SuggestionController@destroy"},{"host":null,"methods":["POST"],"uri":"art_suggestion\/index_api","name":"art_suggestion.index_api","action":"App\Http\Controllers\Art\SuggestionController@indexApi"},{"host":null,"methods":["POST"],"uri":"art_suggestion\/reply\/{art_suggestion}","name":"art_suggestion.reply","action":"App\Http\Controllers\Art\SuggestionController@reply"},{"host":null,"methods":["GET","HEAD"],"uri":"tearoom","name":"tearoom.index","action":"App\Http\Controllers\Tearoom\TearoomController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"tearoom\/create","name":"tearoom.create","action":"App\Http\Controllers\Tearoom\TearoomController@create"},{"host":null,"methods":["POST"],"uri":"tearoom","name":"tearoom.store","action":"App\Http\Controllers\Tearoom\TearoomController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"tearoom\/{tearoom}","name":"tearoom.show","action":"App\Http\Controllers\Tearoom\TearoomController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"tearoom\/{tearoom}\/edit","name":"tearoom.edit","action":"App\Http\Controllers\Tearoom\TearoomController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"tearoom\/{tearoom}","name":"tearoom.update","action":"App\Http\Controllers\Tearoom\TearoomController@update"},{"host":null,"methods":["DELETE"],"uri":"tearoom\/{tearoom}","name":"tearoom.destroy","action":"App\Http\Controllers\Tearoom\TearoomController@destroy"},{"host":null,"methods":["POST","GET","HEAD"],"uri":"tearoom\/lockDate\/{tearoom}","name":"tearoom.lockDate","action":"App\Http\Controllers\Tearoom\TearoomController@lockDate"},{"host":null,"methods":["POST"],"uri":"tearoom\/makeDate","name":"tearoom.makeDate","action":"App\Http\Controllers\Tearoom\TearoomController@makeDate"},{"host":null,"methods":["POST"],"uri":"tearoom\/initGetLockDate\/{tearoom}","name":"tearoom.initGetLockDate","action":"App\Http\Controllers\Tearoom\TearoomController@initGetLockDate"},{"host":null,"methods":["GET","HEAD"],"uri":"tearoom_booking","name":"tearoom_booking.index","action":"App\Http\Controllers\Tearoom\BookingController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"tearoom_booking\/create","name":"tearoom_booking.create","action":"App\Http\Controllers\Tearoom\BookingController@create"},{"host":null,"methods":["POST"],"uri":"tearoom_booking","name":"tearoom_booking.store","action":"App\Http\Controllers\Tearoom\BookingController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"tearoom_booking\/{tearoom_booking}","name":"tearoom_booking.show","action":"App\Http\Controllers\Tearoom\BookingController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"tearoom_booking\/{tearoom_booking}\/edit","name":"tearoom_booking.edit","action":"App\Http\Controllers\Tearoom\BookingController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"tearoom_booking\/{tearoom_booking}","name":"tearoom_booking.update","action":"App\Http\Controllers\Tearoom\BookingController@update"},{"host":null,"methods":["DELETE"],"uri":"tearoom_booking\/{tearoom_booking}","name":"tearoom_booking.destroy","action":"App\Http\Controllers\Tearoom\BookingController@destroy"},{"host":null,"methods":["POST"],"uri":"tearoom_booking\/index_api","name":"tearoom_booking.index_api","action":"App\Http\Controllers\Tearoom\BookingController@indexApi"},{"host":null,"methods":["POST"],"uri":"tearoom_booking\/getInitTimeTable","name":"tearoom_booking.getInitTimeTable","action":"App\Http\Controllers\Tearoom\BookingController@getInitTimeTable"},{"host":null,"methods":["POST"],"uri":"tearoom_booking\/editRequirements\/{booking}","name":"tearoom_booking.editRequirements","action":"App\Http\Controllers\Tearoom\BookingController@editRequirements"},{"host":null,"methods":["POST"],"uri":"tearoom_booking\/changeStatus\/{booking}","name":"tearoom_booking.changeStatus","action":"App\Http\Controllers\Tearoom\BookingController@changeStatus"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // laroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // laroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // laroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // laroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // laroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // laroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.laroute = laroute;
    }

}).call(this);

