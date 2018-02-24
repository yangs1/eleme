<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 18-2-3
 * Time: 下午3:36
 */

namespace App\Repository;


use App\Models\Activity;
use App\Models\ActivityApply;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ActivitiesRepository
{
    public function save( $data, $activity_id = null )
    {
        if ( $activity_id ){

            $activity = $this->getActivityById( $activity_id );
            $activity->update($data);

        }else{
            $activity = Activity::query()->create($data);
        }

        return $activity;
    }

    public function getActivityById( $activity_id )
    {
        $activity = Activity::query()->where('id', $activity_id )->first();

        if ( !$activity ){
            throw new BadRequestHttpException('the activity not exit.');
        }

        return $activity;
    }

    public function getActivitiesWithFilter( $page, $perPage, $request_filter = 'default', $store_id = null )
    {
        $query = Activity::query();

        switch ( $request_filter ){
            case 'going':
                $current = time();
                $query->where('status', 1)
                    ->where('time_begin', '<', $current)
                    ->where('time_end','>',$current);
                break;
            case 'finished':
                $query->where('status', 1)
                    ->where('time_end','<',time());
                break;
            default:
                break;
        }
        return $query->orderBy('time_begin', 'asc')
            ->orderBy('dateline', 'asc')
            ->forPage( $page, $perPage )->get();
    }

    public function getActivitiesByStoreId( $store_id, $page, $perPage )
    {
        return db()->table('activities_apply')
            ->where('activities_apply.store_id', $store_id)
            ->where('activities_apply.status', '>=', 1)
            ->join( 'activities', 'activities_apply.active_id', '=', 'activities.id')
            ->join( 'taxon', 'taxon.id', '=', 'activities.limit_taxon')
            ->orderBy('activities.time_begin', 'asc')
            ->select(['activities.*', 'taxon.name as taxon_name', 'activities_apply.status as apply_status', 'activities_apply.comment'])
            ->forPage( $page, $perPage )
            ->get();
        //id, store_id, active_id, comment, status, created_at
        //id, title, time_begin, time_end, dateline, status, limit_taxon
    }


    public function applyJoin( $activity_id, $sotre_id, $food_id, $price_active, $number_total, $limit_number_user)
    {
        $activity =  ActivityApply::query()->where('store_id', $sotre_id)
            ->where('active_id', $activity_id )->first();

        if ( $activity ){
            throw new BadRequestHttpException('exit');
        }

        return ActivityApply::query()->create([
            'store_id' => $sotre_id,
            'food_id' => $food_id,
            'active_id' => $activity_id,
            'price_active' => $price_active,
            'number_total' => $number_total,
            'limit_number_user' => $limit_number_user
        ]);
    }

}