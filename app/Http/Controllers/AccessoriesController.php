<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessoriesController extends Controller
{

    public function getAccessoriesJSON(Request $request){

        $sortField = "";
        $sortType = "";
        $sortField = (isset($request->sortField)) ? $sortField =  $request->sortField: $sortField = "popularity";
        $sortType = ($request->sortType == 1) ? $sortType =  "desc" : $sortType = "asc";
        
        $result = DB::table('Accessories')
        ->orderBy($sortField, $sortType)
                       ->join('Type','Type.id','Accessories.typeId')
                        ->select(DB::raw('Accessories.id as id, discount as realdiscount, ROUND(discount * 100) as discount, typeId, num, vendorCode, Accessories.title as title, Accessories.description, price, color, sizeHead, Type.title as type, `popularity`'))
                        ->groupBy("Accessories.id");
                        $result = (isset($request->priceUp) && isset($request->priceDown)) ? $result->whereBetween("price", [$request->priceUp, $request->priceDown]) : $result;
                        $result = (isset($request->discountUp) && isset($request->discountDown)) ? $result->whereBetween("discount", [$request->discountUp, $request->discountDown]) : $result;   
                        $result = (isset($request->sizeUp) && isset($request->sizeDown)) ? $result->whereBetween("Size.size", [$request->sizeUp, $request->sizeDown]) : $result;   
                        $result = (isset($request->оffsetRec)) ? $result->offset($request->оffsetRec) : $result;
                        $result = (isset($request->limitRec)) ? $result->limit($request->limitRec) : $result;
                        $result = $result->get();

        return $result;
     }


     public function accessoriesBorderPrice() {
        $result = DB::select(DB::raw("SELECT MAX(price) as maxprice, MIN(price) as minprice FROM `Accessories`"));
        return $result;
    }
    
    public function getAccessoriesFilterJSON(Request $request){
        $color = $request->color;
        $sizeHead = $request->sizeHead;
        $typeId = $request->typeId;
        $count = $request->count;

        $qr = "Accessories.id as id, vendorCode, num, Accessories.title as title, Accessories.description, price, color, sizeHead, Type.title as type,  `discount`, `popularity`";
        if(isset($count)){
            $qr="COUNT(Accessories.id) as count";
        }
        $sortField = "";
        $sortType = "";
        $sortField = (isset($request->sortField)) ? $sortField =  $request->sortField: $sortField = "popularity";
        $sortType = ($request->sortType == 1) ? $sortType =  "desc" : $sortType = "asc";
        $result = DB::table('Accessories')
        ->orderBy($sortField, $sortType)
        ->when($color, function ($q) use ($color) { return $q->where('color','=', $color); })
        ->when($sizeHead, function ($q) use ($sizeHead) { return $q->where('sizeHead','=', $sizeHead); })
        ->when($typeId, function ($q) use ($typeId) { return $q->where('typeId','=', $typeId); })
                        ->join('Type','Type.id','Accessories.typeId')
                        ->select(DB::raw($qr))
                        ->groupBy("Accessories.id");
                        $result = (isset($request->priceUp) && isset($request->priceDown)) ? $result->whereBetween("price", [$request->priceUp, $request->priceDown]) : $result;
                        $result = (isset($request->discountUp) && isset($request->discountDown)) ? $result->whereBetween("discount", [$request->discountUp, $request->discountDown]) : $result;   
                        $result = (isset($request->sizeUp) && isset($request->sizeDown)) ? $result->whereBetween("Size.size", [$request->sizeUp, $request->sizeDown]) : $result;   
                        $result = (isset($request->оffsetRec)) ? $result->offset($request->оffsetRec) : $result;
                        $result = (isset($request->limitRec)) ? $result->limit($request->limitRec) : $result;
                        $result = $result->get();
                        return $result;
                    }
}
