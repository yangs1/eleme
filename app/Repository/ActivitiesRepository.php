<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 18-2-3
 * Time: 下午3:36
 */

namespace App\Repository;


use App\Models\Activity;
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
        $query = Activity::query()
            ->orderBy('time_begin', 'asc')
            ->orderBy('dateline', 'asc');

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
            case 'store-joined':
                //todo
                break;
            default:
                break;
        }
        return $query->forPage( $page, $perPage )->get();
    }

    public function getActivityFilter($request_filter)
    {
        $filters = ['going', 'finished', 'store-joined', 'default'];
        if (in_array($request_filter, $filters)) {
            return $request_filter;
        }
        return 'default';
    }
}