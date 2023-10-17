<?php

namespace App\Http\Controllers;

use App\Model\Gender;
use App\Model\Shoes as Shoes;
use App\Model\Type;
use App\Model\Brands;
use App\Model\Materials;
use App\Model\Season;
use App\Model\Size;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BagsController extends Controller
{

    public function getBagsJSON(Request $request){

        $gender = $request->gender;
        $brand = $request->brand;
        $season = $request->season;
        $color = $request->color;
        $materialInside = $request->materialInside;
        $materialOutside = $request->materialOutside;

        $sortField = "";
        $sortType = "";
        $sortField = (isset($request->sortField)) ? $sortField =  $request->sortField: $sortField = "popularity";
        $sortType = ($request->sortType == 1) ? $sortType =  "desc" : $sortType = "asc";
        
        $result = DB::table('Bag')
        ->orderBy($sortField, $sortType)
        ->when($color, function ($q) use ($color) { return $q->where('color','=', $color); })
        ->when($gender, function ($q) use ($gender) { return $q->where('genderId','=', $gender); })
        ->when($brand, function ($q) use ($brand) { return $q->where('brand','=', $brand); })
        ->when($season, function ($q) use ($season) { return $q->where('seasonId','=', $season); })
        ->when($materialInside, function ($q) use ($materialInside) { return $q->where('materialInside','=', $materialInside); })
        ->when($materialOutside, function ($q) use ($materialOutside) { return $q->where('typeId','=', $materialOutside); })
                        ->join('Gender','Gender.id','Bag.genderId')
                        ->join('Brands','Brands.id','Bag.brandId')
                        ->select(DB::raw('Bag.id as id, discount as realdiscount, ROUND(discount * 100) as discount, num, Bag.genderId as genderId, vendorCode, Bag.title as title, price, Brands.title as brand, Gender.gender as gender, Bag.description'))
                        ->groupBy("Bag.id");
                        $result = (isset($request->priceUp) && isset($request->priceDown)) ? $result->whereBetween("price", [$request->priceUp, $request->priceDown]) : $result;
                        $result = (isset($request->discountUp) && isset($request->discountDown)) ? $result->whereBetween("discount", [$request->discountUp, $request->discountDown]) : $result;   
                        $result = (isset($request->sizeUp) && isset($request->sizeDown)) ? $result->whereBetween("Size.size", [$request->sizeUp, $request->sizeDown]) : $result;   
                        $result = (isset($request->оffsetRec)) ? $result->offset($request->оffsetRec) : $result;
                        $result = (isset($request->limitRec)) ? $result->limit($request->limitRec) : $result;
                        $result = $result->get();

                        return $result;
                    }
     public function bagBorderPrice() {
        $result = DB::select(DB::raw("SELECT MAX(price) as maxprice, MIN(price) as minprice FROM `Bag`"));
        return $result;
    }
    
    public function getBagsFilterJSON(Request $request){
        $gender = $request->gender;
        $brand = $request->brand;
        $season = $request->season;
        $color = $request->color;
        $materialInside = $request->materialInside;
        $materialOutside = $request->materialOutside;
        $count = $request->count;
        $qr = "Bag.id as id, num, Bag.genderId as genderId, Bag.brandId as brandId, vendorCode, Bag.title as title, price, Brands.title as brand, Gender.gender as gender, materialOutside, materialInside, Bag.description, color,  `discount`, `popularity`";
        if(isset($count)){
            $qr="COUNT(Bag.id)  as count";
        }
        $sortField = "";
        $sortType = "";
        $sortField = (isset($request->sortField)) ? $sortField =  $request->sortField: $sortField = "popularity";
        $sortType = ($request->sortType == 1) ? $sortType =  "desc" : $sortType = "asc";
        
        $result = DB::table('Bag')
        ->orderBy($sortField, $sortType)
        ->when($color, function ($q) use ($color) { return $q->where('color','=', $color); })
        ->when($gender, function ($q) use ($gender) { return $q->where('genderId','=', $gender); })
        ->when($brand, function ($q) use ($brand) { return $q->where('brand','=', $brand); })
        ->when($season, function ($q) use ($season) { return $q->where('seasonId','=', $season); })
        ->when($materialInside, function ($q) use ($materialInside) { return $q->where('materialInside','=', $materialInside); })
        ->when($materialOutside, function ($q) use ($materialOutside) { return $q->where('typeId','=', $materialOutside); })
                        ->join('Gender','Gender.id','Bag.genderId')
                        ->join('Brands','Brands.id','Bag.brandId')
                        ->select(DB::raw($qr))
                        ->groupBy("Bag.id");
                        $result = (isset($request->priceUp) && isset($request->priceDown)) ? $result->whereBetween("price", [$request->priceUp, $request->priceDown]) : $result;
                        $result = (isset($request->discountUp) && isset($request->discountDown)) ? $result->whereBetween("discount", [$request->discountUp, $request->discountDown]) : $result;   
                        $result = (isset($request->sizeUp) && isset($request->sizeDown)) ? $result->whereBetween("Size.size", [$request->sizeUp, $request->sizeDown]) : $result;   
                        $result = (isset($request->оffsetRec)) ? $result->offset($request->оffsetRec) : $result;
                        $result = (isset($request->limitRec)) ? $result->limit($request->limitRec) : $result;
                        $result = $result->get();

                        return $result;
                    }
}
