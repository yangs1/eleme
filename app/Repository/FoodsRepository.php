<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 18-1-5
 * Time: 下午2:30
 */
namespace App\Repository;

use App\Models\Food;
use App\Models\Menu;
use App\Models\Spec;

class FoodsRepository
{
    public function save( array $data, array $specs)
    {
        if ( isset($data['food_id']) ){
            $food = Food::query()->where('id', $data['food_id'] )->first();
            if ( !$food ){
                return null;
            }
            $food->update($data);

        }else{
            $food = Food::query()->create($data);
        }

        empty($specs) ?: $this->saveSpecs( $specs, $food->id );

        return $food;
    }

    protected function saveSpecs(array $specs, $food_id){

        $insertData = [];
        $updateData = [];

        foreach ( $specs as $item){
            if ( isset($item['sku_id']) ){
                $updateData[$item['sku_id']] = $item;
            }else{
                $item['food_id'] = $food_id;
                $insertData[] = $item;
            }
        }

        if ( $updateData ){
            Spec::where( 'food_id', $food_id )
                ->whereNotIn( 'id', array_keys($updateData) )->delete();

            app( Spec::class )->updateBatch( $updateData, 'id', 'sku_id');
        }

        empty($insertData) ?: Spec::query()->insert( $insertData );

    }


    public function getMenusWithFoods( $store_id )
    {
        return Menu::query()->where('store_id', $store_id)->with('foods')->get();
    }

    public function getFoods( $store_id, $page, $perPage )
    {
        return Food::query()->where( 'store_id', $store_id )->forPage( $page, $perPage)->get();
    }
}


