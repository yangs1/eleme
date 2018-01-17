<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 18-1-5
 * Time: 下午2:30
 */
namespace App\Repository;

use App\Models\Foods;
use App\Models\Specs;

class FoodsRepository
{
    public function save( array $data, array $specs)
    {
        if ( $data['food_id'] ){
            $food = Foods::where('id', $data['food_id'] )->first();
            if ( !$food ){
                return null;
            }
            $food->update($data);

        }else{
            $food = Foods::create($data);
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
            Specs::where( 'food_id', $food_id )
                ->whereNotIn( 'id', array_keys($updateData) )->delete();

            app( Specs::class )->updateBatch( $updateData, 'id', 'sku_id');
        }

        empty($insertData) ?: Specs::insert( $insertData );

    }
}


