<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Gate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShoesController extends Controller
{

    public function getShoesJSON(Request $request) {
        $markdown = $request->markdown;
        $gender = $request->gender;
        $brand = $request->brand;
        $season = $request->season;
        $typeShoes = $request->typeShoes;
        $materials = $request->materials;
        $search = $request->search;
        $outmaterial = $request->outmaterial;
        $count = $request->count;

        $sortField = "";
        $sortType = "";
        $sortField = (isset($request->sortField)) ? $sortField =  $request->sortField: $sortField = "popularity";
        $sortType = ($request->sortType == 1) ? $sortType =  "desc" : $sortType = "asc";
        
        $result = DB::table('Shoes')
        ->orderBy($sortField, $sortType)
        ->when($markdown, function ($q) use ($markdown) { return $q->where('markdown','=', $markdown); })
        ->when($gender, function ($q) use ($gender) { return $q->where('genderId','=', $gender); })
        ->when($brand, function ($q) use ($brand) { return $q->where('brand','=', $brand); })
        ->when($season, function ($q) use ($season) { return $q->where('seasonId','=', $season); })
        ->when($materials, function ($q) use ($materials) { return $q->where('materials','=', $materials); })
        ->when($typeShoes, function ($q) use ($typeShoes) { return $q->where('typeId','=', $typeShoes); })
        ->when($outmaterial, function ($q) use ($outmaterial) { return $q->where('outmaterial','=', $outmaterial); })
        ->when($search, function ($q) use ($search) { return $q->where('vendorCode','LIKE' ,"%{$search}%"); })
                        ->join('Season','Season.id','Shoes.seasonId')
                        ->join('Gender','Gender.id','Shoes.genderId')
                        ->join('Brands as c','c.id','Shoes.brand')
                        ->join('Type','Type.id','Shoes.typeId')
                        ->join('Materials','Materials.id','Shoes.materials')
                        ->join('Brands','Brands.id','Shoes.brand')
                        ->join('Size','Size.shoesId','Shoes.id')
                        ->select(DB::raw(' Shoes.id AS id, vendorCode, typeId, seasonId, brand, genderId, insoleMaterial, CONCAT(Type.title,  " ", Brands.title) as title, Shoes.description, `price`, `markdown`, Season.title AS season, Gender.gender AS gender, Type.title AS type,  materials, `outmaterial`, Brands.title AS brands, discount as realdiscount, ROUND(discount * 100) as discount, Shoes.popularity, Shoes.timeToAdd'))
                        ->groupBy("Shoes.id");
                        $result = (isset($request->priceUp) && isset($request->priceDown)) ? $result->whereBetween("price", [$request->priceUp, $request->priceDown]) : $result;
                        $result = (isset($request->discountUp) && isset($request->discountDown)) ? $result->whereBetween("discount", [$request->discountUp, $request->discountDown]) : $result;   
                        $result = (isset($request->sizeUp) && isset($request->sizeDown)) ? $result->whereBetween("Size.size", [$request->sizeUp, $request->sizeDown]) : $result;   
                        $result = (isset($request->offsetRec)) ? $result->offset($request->offsetRec) : $result;
                        $result = (isset($request->limitRec)) ? $result->limit($request->limitRec) : $result;
                        $result = $result->get();
                        if(isset($count)){
                            $countr = $result->count();
                            $ar = [[
                                'count' => "$countr",
                                'countSize' => "1",
                                'allprice' => "1",
                            ]
                            ];
                            return $ar;
                        }else{
                            return $result; 
                
                        }

    }
    public function getShoesFilterJSON(Request $request){
        $markdown = $request->markdown;
        $gender = $request->gender;
        $brand = $request->brand;
        $season = $request->season;
        $typeShoes = $request->typeShoes;
        $materials = $request->materials;
        $outmaterial = $request->outmaterial;
        $outmaterial = $request->outmaterial;
        $search = $request->search;
        $limit = $request->limitRec;
        $offset = $request->offsetRec;

        $count = $request->count;
        $qr = "Shoes.id AS id, Shoes.brand as brandId, vendorCode, CONCAT(Type.title, ' ', Brands.title) as title, Shoes.description, price, markdown, Season.title AS season, Gender.gender AS gender, Type.title AS type, Materials.title AS materials, outmaterial, Brands.title AS brands,  discount , popularity ";
        
        $sortField = "";
        $sortType = "";
        $sortField = (isset($request->sortField)) ? $sortField =  $request->sortField: $sortField = "popularity";
        $sortType = ($request->sortType == 1) ? $sortType =  "desc" : $sortType = "asc";
        $result = DB::table('Shoes')
        ->when($limit, function ($q) use ($limit) { return $q->take($limit); })
        ->when($offset, function ($q) use ($offset) { return $q->skip($offset); })
        ->orderBy($sortField, $sortType)->groupBy("Shoes.id")->select(DB::raw($qr))
        ->join('Season','Season.id','Shoes.seasonId')
        ->join('Gender','Gender.id','Shoes.genderId')
        ->join('Brands as c','c.id','Shoes.brand')
        ->join('Type','Type.id','Shoes.typeId')
        ->join('Materials','Materials.id','Shoes.materials')
        ->join('Brands','Brands.id','Shoes.brand')
        ->join('Size','Size.shoesId','Shoes.id')
                    ->when($markdown, function ($q) use ($markdown) { return $q->where('markdown','=', $markdown); })
                    ->when($gender, function ($q) use ($gender) { return $q->where('genderId','=', $gender); })
                    ->when($brand, function ($q) use ($brand) { return $q->where('brand','=', $brand); })
                    ->when($season, function ($q) use ($season) { return $q->where('seasonId','=', $season); })
                    ->when($materials, function ($q) use ($materials) { return $q->where('materials','=', $materials); })
                    ->when($typeShoes, function ($q) use ($typeShoes) { return $q->where('typeId','=', $typeShoes); })
                    ->when($outmaterial, function ($q) use ($outmaterial) { return $q->where('outmaterial','=', $outmaterial); })
                    ->when($search, function ($q) use ($search) { return $q->where('vendorCode','LIKE' ,"%{$search}%"); })
                    ;
                    if(isset($request->priceUp) && isset($request->priceDown)){$result = $result->whereBetween("price", [$request->priceUp, $request->priceDown]);}
                    if(isset($request->discountUp) && isset($request->discountDown)){$result = $result->whereBetween("discount", [$request->discountUp, $request->discountDown]);}
                    if(isset($request->sizeUp) && isset($request->sizeDown)){$result =$result->whereBetween("Size.size", [$request->sizeUp, $request->sizeDown]);}
                        $result = $result->get();

        if(isset($count)){
            $count = $result->count();
            $ar = [[
                'count' => "$count",
                'countSize' => "1",
                'allprice' => "1",
            ]
            ];
            return $ar;
        }else{
            return $result;

        }
    }
    public function ShoesSize(Request $request){
        if(isset($request->idShoes)){
            $result = DB::select(DB::raw("SELECT DISTINCT Shoes.id, Size.size, Size.num FROM `Size`, `Shoes` WHERE shoesId = Shoes.id AND Shoes.id =  $request->idShoes"));
            return json_encode($result);
        }
    }
    public function shoesSizeBorderPrice() {
        $result = DB::select(DB::raw("SELECT MAX(size) as maxsize, MIN(size) as minsize FROM `Size`"));
        return $result;
    }


    
    public function UploadImage(Request $request){
        
    }
}
