<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 18-1-5
 * Time: 下午2:30
 */
namespace App\Repository;

use App\Models\Menu;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MenusRepository
{
    public function save( $store_id, $data, $menu_id = null )
    {
        if ( $menu_id ){

            $menu = $this->getMenuById( $menu_id, $store_id);
            $menu->update($data);

        }else{
            $data['store_id'] = $store_id;
            $menu = Menu::query()->create($data);
        }

        return $menu;
    }

    public function delete( $menu_id, $store_id, $force)
    {
        $menu = $this->getMenuById( $menu_id, $store_id);

        if ( !$force && $menu->foods_count > 0){
            throw new BadRequestHttpException('the menu is used.');
        }

        $menu->delete();
    }

    public function getMenuById( $menu_id, $store_id = null)
    {
        $menu = Menu::query()->where('id', $menu_id )->first();

        if ( !$menu ){
            throw new BadRequestHttpException('the menu item not exit.');
        }

        if ($store_id && $menu->store_id != $store_id){
            throw new BadRequestHttpException('this menu not belong the store.');
        }
        return $menu;
    }

    public function getMenus( $store_id )
    {
        return Menu::query()->where('store_id', $store_id)->with('foods')->get();
    }
}