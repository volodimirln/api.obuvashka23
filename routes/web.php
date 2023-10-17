<?php
$token = 'aJbwFL^4@xg6UF$M';


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Illuminate\Http\Request;

$router->post('/', "ExampleController@store");
$router->post('/test', function (Request $request) {
       return $request->all();
   });

#Accessories
$router->get('/Accessories', 'AccessoriesController@getAccessoriesJSON');
$router->get('/Accessories/filter', 'AccessoriesController@getAccessoriesFilterJSON');
$router->get('/Accessories/accessoriesBorderPrice', 'AccessoriesController@accessoriesBorderPrice');

#Bags
$router->get('/Bags', 'BagsController@getBagsJSON');
$router->get('/Bags/filter', 'BagsController@getBagsFilterJSON');
$router->get('/Bags/bagBorderPrice', 'BagsController@bagBorderPrice');

 #Shoes
 $router->get('Shoes/filter', 'ShoesController@getShoesFilterJSON');
 $router->get('/Shoes', 'ShoesController@getShoesJSON');
 $router->get('/Shoes/shoesBorderPrice', 'ShoesController@shoesBorderPrice');
 $router->get('/Shoes/shoesSizeBorderPrice', 'ShoesController@shoesSizeBorderPrice');
 $router->get('/Shoes/ShoesSize', 'ShoesController@ShoesSize');

 #Other
 $router->get('/Genders', 'OtherController@getGenders');
 $router->get('/vendorShoes', 'OtherController@getvendorShoes');
 $router->get('/vendorAccessories', 'OtherController@getvendorAccessories');
 $router->get('/vendorBags', 'OtherController@vendorBags');
 $router->get('/Brands', 'OtherController@getBrands');

 $router->post('/ShoesImage', 'ShoesController@UploadImage');
 $router->post('/formProcessor', 'ShoesController@UploadImage');

 $router->post('/'.$token.'/AddBrand', 'OtherController@AddBrand');
 $router->patch('/'.$token.'/AddBrand', 'OtherController@UdpAddBrand');

 $router->post('/'.$token.'/AddType', 'OtherController@AddType');
 $router->patch('/'.$token.'/AddType', 'OtherController@UdpAddType');


 $router->post('/AddSeason', 'OtherController@AddSeason');
 $router->patch('/'.$token.'/AddSeason', 'OtherController@UdpAddSeason');

 $router->post('/'.$token.'/AddMaterial', 'OtherController@AddMaterial');
 $router->patch('/'.$token.'/AddMaterial', 'OtherController@UdpAddMaterial');

 $router->post('/'.$token.'/AddGender', 'OtherController@AddGender');
 $router->patch('/'.$token.'/AddGender', 'OtherController@UdpAddGender');


 
 $router->post('/AddSession', 'OtherController@AddSession');
 $router->post('/'.$token.'/Orders', 'OtherController@Orders');
 $router->post('/'.$token.'/Orders', 'OtherController@Orders');

 $router->post('/'.$token.'/faq', 'OtherController@faq');

 $router->post('/'.$token.'/AddGender', 'OtherController@AddGender');
 $router->patch('/'.$token.'/AddGender', 'OtherController@UdpAddGender');

 $router->post('/'.$token.'/AddAccessories', 'OtherController@AddAccessories');
 $router->patch('/'.$token.'/AddAccessories', 'OtherController@UdpAddAccessories');

 $router->post('/'.$token.'/AddBag', 'OtherController@AddBag');
 $router->patch('/'.$token.'/AddBag', 'OtherController@UdpAddBag');

 $router->post('/'.$token.'/AddShoes', 'OtherController@AddShoes');
 $router->patch('/'.$token.'/AddShoes', 'OtherController@UdpAddShoes');

 $router->post('/'.$token.'/AddShoesSize', 'OtherController@AddShoesSize');
 $router->patch('/'.$token.'/AddShoesSize', 'OtherController@UdpAddShoesSize');



 $router->post('/GetSession', 'OtherController@GetSession');
 $router->post('/GetAdministration', 'OtherController@GetAdministration');


 $router->get('/sumorder', 'OtherController@getsumorder');
 $router->get('/Materials', 'OtherController@getMaterials');
 $router->get('/Seasons', 'OtherController@getSeasons');
 $router->get('/Size', 'OtherController@getSize');
 $router->get('/Type', 'OtherController@getType');
 $router->get('/BrandCount', 'OtherController@getBrandCount');

 #Image
 $router->get('/ImagesShoes', 'OtherController@getImagesShoes');
 $router->get('/ImagesAccessories', 'OtherController@getImagesAccessories');
 $router->get('/ImagesBags', 'OtherController@getImagesBags');

