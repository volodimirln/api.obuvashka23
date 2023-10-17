<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\Brands;
use App\Models\Materials;
use App\Models\Season;
use App\Models\Size;
use App\Models\Type;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtherController extends Controller
{
    public function getGenders() {
        return Gender::all();
     }
     public function getvendorShoes(Request $request) {
        return DB::select(DB::raw("SELECT Shoes.id as id, discount as realdiscount, ROUND(discount * 100) as discount, Gender.gender as gender, Season.title as season, Type.title as type, Brands.title as brand, Materials.title as material, vendorCode, CONCAT(Type.title, ' ', Brands.title) as title, Shoes.description as description, price, markdown, popularity, outmaterial, insoleMaterial FROM `Shoes`, `Season`, `Type`, `Materials`, `Brands`, `Gender` WHERE Shoes.genderId = Gender.id and Shoes.seasonId = Season.id AND Shoes.typeId = Type.id and Shoes.materials = Materials.id and Shoes.brand = Brands.id AND `vendorCode` = '$request->vendorCode'"));
     }
     public function getvendorAccessories(Request $request) {
        return DB::select(DB::raw("SELECT Accessories.id as id, discount as realdiscount, ROUND(discount * 100) as discount, typeId, vendorCode, Accessories.title as title, Accessories.description, num, price, color, sizeHead, Type.title as type, `popularity` FROM `Accessories`, `Type` WHERE Accessories.typeId = Type.id AND `vendorCode` = '$request->vendorCode'"));
     }
     public function getvendorBags(Request $request) {
        return DB::select(DB::raw("SELECT Bag.id as id, num, discount as realdiscount, ROUND(discount * 100) as discount, vendorCode, Bag.title as title, price, Brands.title as brand, Gender.gender as gender, Bag.description  FROM `Bag`, `Brands`, `Gender` WHERE Bag.brandId = Brands.id and Bag.genderId = Gender.id  AND `vendorCode` = '$request->vendorCode'"));
     }
     public function getBrands() {
        return Brands::all();
     }
     public function getsumorder() {
        return DB::select(DB::raw("SELECT SUM(sum) as sum FROM `Order`"));
     }
     public function getMaterials() {
        return Materials::all();
     }
     public function getSeasons() {
        return Season::all(); 
     }
     public function getSize(Request $request) {
        $sortField = (isset($request->sortField)) ? $sortField =  $request->sortField: $sortField = "popularity";
        $sortType = ($request->sortType == 1) ? $sortType =  "desc" : $sortType = "asc";
        $result = DB::table("Size")
        ->select(DB::raw('Size.id, shoesId, size, num, Shoes.title as shoesTitle, `vendorCode` '))
        ->join('Shoes','Shoes.id','Size.shoesId');
        $result = (isset($request->offsetRec)) ? $result->offset($request->offsetRec) : $result;
        $result = (isset($request->limitRec)) ? $result->limit($request->limitRec) : $result;
        $result = $result->orderBy($sortField, $sortType)->get();
        return $result;
     }
     public function getType(Request $request) {
        $typeObject = $request->typeObject;
        $result = DB::table("Type")
        ->when($typeObject, function ($q) use ($typeObject) { return $q->where('typeObject','=', $typeObject); })
        ->select(DB::raw('*'))
        ->get();
        return $result;
     }
     public function getBrandCount() {  
        return DB::select(DB::raw("SELECT DISTINCT Brands.title as brand, COUNT(Shoes.id) as `count` FROM Brands, Shoes WHERE Shoes.brand = Brands.id GROUP by Brands.title"));
     }

    public function getImagesShoes(Request $request){
        $sortField = (isset($request->sortField)) ? $sortField =  $request->sortField: $sortField = "popularity";
        $sortType = ($request->sortType == 1) ? $sortType =  "desc" : $sortType = "asc";
        $productId = $request->productId;
        $status = $request->status;
        $result = DB::table('PIctureToProduct')
        ->orderBy($sortField, $sortType)
        ->when($productId, function ($q) use ($productId) { return $q->where('productId','=', $productId); })
        ->when($status, function ($q) use ($status) { return $q->where('status','=', $status); })
            ->join('Shoes','Shoes.id','PIctureToProduct.productId')
            ->select(DB::raw("PIctureToProduct.id, `productId`, `photoPath`, `status`, Shoes.vendorCode"));
        $result = (isset($request->offsetRec)) ? $result->offset($request->offsetRec) : $result;
        $result = (isset($request->limitRec)) ? $result->limit($request->limitRec) : $result;
        $result = $result->get();
        return $result;
     }

    public function getImagesAccessories(Request $request){
        $sortField = (isset($request->sortField)) ? $sortField =  $request->sortField: $sortField = "popularity";
        $sortType = ($request->sortType == 1) ? $sortType =  "desc" : $sortType = "asc";
        $accessoriesId = $request->accessoriesId;
        $status = $request->status;
        $result = DB::table('PIctureToAccessories')
        ->orderBy($sortField, $sortType)
        ->when($accessoriesId, function ($q) use ($accessoriesId) { return $q->where('productId','=', $accessoriesId); })
        ->when($status, function ($q) use ($status) { return $q->where('status','=', $status); })
            ->join('Accessories','Accessories.id','PIctureToAccessories.productId')
            ->select(DB::raw("PIctureToAccessories.id, `productId`, `photoPath`, `status`, Accessories.vendorCode"));
        $result = (isset($request->offsetRec)) ? $result->offset($request->offsetRec) : $result;
        $result = (isset($request->limitRec)) ? $result->limit($request->limitRec) : $result;
        $result = $result->get();
        return $result;
    }
    public function getImagesBags(Request $request) {
        $sortField = (isset($request->sortField)) ? $sortField =  $request->sortField: $sortField = "popularity";
        $sortType = ($request->sortType == 1) ? $sortType =  "desc" : $sortType = "asc";
        $bagsId = $request->bagsId;
        $status = $request->status;
        $result = DB::table('PIctureToBag')
        ->orderBy($sortField, $sortType)
        ->when($bagsId, function ($q) use ($bagsId) { return $q->where('productId','=', $bagsId); })
        ->when($status, function ($q) use ($status) { return $q->where('status','=', $status); })
            ->join('Bag','Bag.id','PIctureToBag.productId')
            ->select(DB::raw("PIctureToBag.id, `productId`, `photoPath`, `status`, Bag.vendorCode "));
        $result = (isset($request->offsetRec)) ? $result->offset($request->offsetRec) : $result;
        $result = (isset($request->limitRec)) ? $result->limit($request->limitRec) : $result;
        $result = $result->get();
        return $result;
    }
   public function AddBrand(Request $request){
      //$requestData = json_decode($request->getContent(), true);
      return var_dump($_SERVER);


      return $request->all();
   }
   public function UdpAddBrand(Request $request){
      return $this->UdpAddSecondaryTable($request, "Brands");
   }

   public function AddType(Request $request){
      return $this->AddSecondaryTable($request, "Type");
   }
   public function UdpAddType(Request $request){
      return $this->UdpAddSecondaryTable($request, "Type");
   }
   public function AddSeason(Request $request){
      return $this->AddSecondaryTable($request, "Season");
   }
   public function UdpAddSeason(Request $request){
      return $this->UdpAddSecondaryTable($request, "Season");
   }
   public function AddMaterial(Request $request){
      return $this->AddSecondaryTable($request, "Materials");
   }
   public function UdpAddMaterial(Request $request){
      return $this->UdpAddSecondaryTable($request, "Materials");
   }
   #no token url
   public function AddSession(Request $request){
      if($request->token == 'aJbwFL^4@xg6UF$M'){
         DB::table('Session')->insert([
            'adminId' => $request->adminId,
            'ipAddress' => $request->ipAddress
         ]);
         $result = ['type' => 'Successfully'];
         return $result;
      }
   }
   public function Orders() {
      $result = DB::select(DB::raw("SELECT * FROM `Order` ORDER BY `id` DESC"));
      
     
      return $result;
   }
   public function faq() {
      return DB::select(DB::raw("SELECT * FROM `faq` ORDER BY `id` DESC"));
   }
   public function AddGender(Request $request) {
      DB::table("Gender")->insert(['gender' => $request->gender]);
      return DB::select(DB::raw("SELECT * FROM `Gender` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1"));
   }
   public function AddAccessories(Request $request)  {
      $discount = $request->discount / 100;
      $insert = [
         'vendorCode' => $request->vendorCode,
         'title' => $request->title,
         'price' => $request->price,
         'popularity' => $request->popularity,
               ];
      if(isset($request->typeId)){$insert += ['typeId'=> $request->typeId];}
      if(isset($request->sizeHead)){$insert += ['sizeHead'=> $request->sizeHead];}
      if(isset($request->color)){$insert += ['color'=> $request->color];}
      if(isset($discount)){$insert += ['discount'=> $discount];}
      if(isset($request->num)){$insert += ['num'=> $request->num];}
      if(isset($request->description)){$insert += ['description'=> $request->description];}
      DB::table("Accessories")->insert([$insert]);
      return DB::select(DB::raw("SELECT * FROM `Accessories` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1"));
   }
   public function AddBag(Request $request) {
      $discount = $request->discount / 100;
      $insert = [
         'vendorCode' => $request->vendorCode,
         'title' => $request->title,
         'price' => $request->price,
         'popularity' => $request->popularity,
         'genderId' => $request->genderId,
               ];
      if(isset($request->materialOutside)){$insert += ['materialOutside'=> $request->materialOutside];}
      if(isset($request->materialInside)){$insert += ['materialInside'=> $request->materialInside];}
      if(isset($request->brandId)){$insert += ['brandId'=> $request->brandId];}
      if(isset($request->color)){$insert += ['color'=> $request->color];}
      if(isset($discount)){$insert += ['discount'=> $discount];}
      if(isset($request->num)){$insert += ['num'=> $request->num];}
      if(isset($request->description)){$insert += ['description'=> $request->description];}
      DB::table("Bag")->insert([$insert]);
      return DB::select(DB::raw("SELECT * FROM `Bag` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1"));
  }

   public function AddShoes(Request $request) {
      $discount = $request->discount / 100;
      $insert = [
      'vendorCode' => $request->vendorCode,
      'title' => $request->title,          
      'price' => $request->price,          
      'popularity' => $request->popularity,
      'genderId' => $request->genderId,
      'markdown' => $request->markdown,  
      'typeId' => $request->typeId,
            ];

      if(isset($request->materialOutside)){$insert += ['outmaterial'=> $request->materialOutside];}
      if(isset($request->materialInside)){$insert += ['materials'=> $request->materialInside];}
      if(isset($request->brandId)){$insert += ['brand'=> $request->brandId];}
      if(isset($request->seasonId)){$insert += ['seasonId'=> $request->seasonId];}
      if(isset($discount)){$insert += ['discount'=> $discount];}
      if(isset($request->description)){$insert += ['description'=> $request->description];}
      if(isset($request->insoleMaterial)){$insert += ['insoleMaterial'=> $request->insoleMaterial];}
      if(isset($request->timeToAdd)){$insert += ['timeToAdd'=> $request->timeToAdd];}

      DB::table("Shoes")->insert([$insert]);
      return DB::select(DB::raw("SELECT * FROM `Shoes` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1"));
   }

   public function AddShoesSize(Request $request)  {
      $insert = [
         'shoesId' => $request->shoesId,
         'size' => $request->size,
         'num' => $request->num,
               ];
      DB::table("Size")->insert([$insert]);
      return DB::select(DB::raw("SELECT * FROM `Size` WHERE id = (select max(id)) ORDER BY id DESC LIMIT 1"));
   }

   #no token url
   public function GetAdministration(Request $request){
      $login = $request->login;
      $password = $request->password;
      if(isset($login) && isset($request->password)){
         $result = DB::table('Administrarion')
        ->when($login, function ($q) use ($login) { return $q->where('login','=', $login); })
        ->when($password, function ($q) use ($password) { return $q->where('password','=', $password); })
        ->select(DB::raw('*'))
        ->get();
        return $result;
      }
   }
   public function convertNumbersToStrings($item) {
      if (is_numeric($item)) {
          return strval($item);
      }
      return $item;
  }
   #no token url
   public function GetSession(Request $request) {
      $ipAddress = $request->ipAddress;
      if(isset($ipAddress)){
         $result = DB::table('Session')->select("id", "adminId", "ipAddress", "token", "timeEnter")
         ->when($ipAddress, function ($q) use ($ipAddress) { return $q->where('ipAddress','=', $ipAddress); })
         ->join('Administrarion','Administrarion.id','Session.adminId')
         ->select(DB::raw(' Session.id as id, adminId, ipAddress, Administrarion.token as  token'))
         ->get();
         return $result;
      }
   }
  
   public function AddSecondaryTable(Request $request, $table){
       $insert = ['title' => $request->title];
       if(isset($request->description)){$insert += ['description'=> $request->description];}
       DB::table($table)->insert($insert);
      return DB::table($table)->latest("id")->take(1)->select("*")->get();
      //$data = $request->title;

      // Теперь переменная $data содержит данные из формы в формате application/x-www-form-urlencoded
  
      // Далее вы можете обработать данные и выполнить необходимые действия
  
      // Например, вернуть ответ с данными
      //return $insert;

   }

   public function UdpAddSecondaryTable(Request $request, $table){      
      $req = json_decode($request->getContent(), true);
      $insert = ['title' => $request->title];
      if(isset($request->description)){$insert += ['description'=> $request->description];}
      DB::table($table)->where('id', '=', $request->id)->update($insert);
      return DB::select(DB::raw("SELECT * FROM `$table` WHERE id = $request->id"));
   }



   #upd
   public function UpdAddGender(Request $request) {
      DB::table("Gender")->where('id', '=', $request->id)->update(['gender' => $request->gender]);
      return DB::select(DB::raw("SELECT * FROM `Gender` WHERE id = $request->id"));
   }

   public function UpdAddAccessories(Request $request)  {
      $discount = $request->discount / 100;
      $insert = [
         'vendorCode' => $request->vendorCode,
         'title' => $request->title,
         'price' => $request->price,
         'popularity' => $request->popularity,
               ];
      if(isset($request->typeId)){$insert += ['typeId'=> $request->typeId];}
      if(isset($request->sizeHead)){$insert += ['sizeHead'=> $request->sizeHead];}
      if(isset($request->color)){$insert += ['color'=> $request->color];}
      if(isset($discount)){$insert += ['discount'=> $discount];}
      if(isset($request->num)){$insert += ['num'=> $request->num];}
      if(isset($request->description)){$insert += ['description'=> $request->description];}
      DB::table("Accessories")->where('id', '=', $request->id)->update([$insert]);
      return DB::select(DB::raw("SELECT * FROM `Accessories` WHERE `id` = $request->id"));
   }
   public function UdpAddBag(Request $request) {
      $discount = $request->discount / 100;
      $insert = [
         'vendorCode' => $request->vendorCode,
         'title' => $request->title,
         'price' => $request->price,
         'popularity' => $request->popularity,
         'genderId' => $request->genderId,
               ];
      if(isset($request->materialOutside)){$insert += ['materialOutside'=> $request->materialOutside];}
      if(isset($request->materialInside)){$insert += ['materialInside'=> $request->materialInside];}
      if(isset($request->brandId)){$insert += ['brandId'=> $request->brandId];}
      if(isset($request->color)){$insert += ['color'=> $request->color];}
      if(isset($discount)){$insert += ['discount'=> $discount];}
      if(isset($request->num)){$insert += ['num'=> $request->num];}
      if(isset($request->description)){$insert += ['description'=> $request->description];}
      DB::table("Bag")->where('id', '=', $request->id)->update([$insert]);
      return DB::select(DB::raw("SELECT * FROM `Bag` WHERE id = $request->id"));
  }
   public function UdpAddShoes(Request $request) {
      $discount = $request->discount / 100;
      $insert = [
         'vendorCode' => $request->vendorCode,
         'title' => $request->title,          
         'price' => $request->price,          
         'popularity' => $request->popularity,
         'genderId' => $request->genderId,
         'markdown' => $request->markdown,  
         'typeId' => $request->typeId,
         ];

      if(isset($request->materialOutside)){$insert += ['outmaterial'=> $request->materialOutside];}
      if(isset($request->materialInside)){$insert += ['materials'=> $request->materialInside];}
      if(isset($request->brandId)){$insert += ['brand'=> $request->brandId];}
      if(isset($request->seasonId)){$insert += ['seasonId'=> $request->seasonId];}
      if(isset($discount)){$insert += ['discount'=> $discount];}
      if(isset($request->description)){$insert += ['description'=> $request->description];}
      if(isset($request->insoleMaterial)){$insert += ['insoleMaterial'=> $request->insoleMaterial];}
      if(isset($request->timeToAdd)){$insert += ['timeToAdd'=> $request->timeToAdd];}
      DB::table("Shoes")->where('id', '=', $request->id)->update([$insert]);
      return DB::select(DB::raw("SELECT * FROM `Shoes` WHERE id = $request->id"));
   }
   public function UdpAddShoesSize(Request $request)  {
      $insert = [
         'shoesId' => $request->shoesId,
         'size' => $request->size,
         'num' => $request->num,
               ];
      DB::table("Size")->where('id', '=', $request->id)->update([$insert]);
      return DB::select(DB::raw("SELECT * FROM `Size` WHERE id = $request->id"));
   }
}
