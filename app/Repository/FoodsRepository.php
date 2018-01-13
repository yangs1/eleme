<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 18-1-5
 * Time: 下午2:30
 */
namespace App\Repository;

use App\Models\Foods;

class FoodsRepository
{
    public function create( array $data, array $specs)
    {
        $food = Foods::create($data);

        $this->saveSpecs( $specs, $food->id );
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

        empty($updateData) ?: Foods::where( 'food_id', $food_id )
            ->whereIn( 'id', array_keys($updateData) )->delete();
        empty($updateData) ?: Foods::insert( $insertData );

    }
}


