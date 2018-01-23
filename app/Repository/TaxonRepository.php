<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 18-1-5
 * Time: 下午2:30
 */
namespace App\Repository;

use App\Models\Taxon;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TaxonRepository
{
    /**
     * 添加子分类
     * @param $name
     * @param $parent_id
     * @param $description
     * @param $position
     */
    public function addNote( $name, $parent_id, $description, $position)
    {
        $parentNode = Taxon::where('id', $parent_id)->first();
        if ( empty($parentNode) ){
            throw new BadRequestHttpException( 'this parent node is not exit.');
        }

        Taxon::create([
            'name' => $name,
            'description' => $description,
            'parent_id' => $parent_id,
            'tree_root' => $parentNode->tree_root,
            'tree_left' => $parentNode->tree_right,
            'tree_right'=> $parentNode->tree_right+1,
            'tree_level'=> $parentNode->tree_level+1,
            'position' => $position
        ]);

        Taxon::where('tree_left',">", $parentNode->tree_right)->increment('tree_left',2);
        Taxon::where('tree_right',">", $parentNode->tree_right)->increment('tree_right',2);

    }

    /**
     * 添加根分类
     * @param $name
     * @param $description
     * @param $position
     */
    public function addRootNode( $name, $description, $position )
    {
        $parentNode = Taxon::orderBy('id','desc')->first();

        $tree_left = $parentNode ? $parentNode->tree_left + 2 : 1;
        $tree_right = $parentNode ? $parentNode->tree_right + 2 : 2;

        $taxon = Taxon::create([
            'name' => $name,
            'description' => $description,
            'tree_left' => $tree_left,
            'tree_right'=> $tree_right,
            'tree_level'=> 1,
            'position' => $position
        ]);
        $taxon->tree_root = $taxon->id;
        $taxon->parent_id = $taxon->id;
        $taxon->save();
    }

    /**
     * 删除分类
     * @param $node_id
     * @param bool $force
     * @return bool
     */
    public function delNode( $node_id, $force = false )
    {
        $node = Taxon::where('id', $node_id)->first();

        if ( empty($parentNode) ){
            return true;
        }

        if ( ($node->tree_right - $node->tree_left) > 1 && !$force){
            throw new BadRequestHttpException( 'this node have many child nodes.');
        }

        return Taxon::whereBetween( 'tree_left', [$node->tree_left, $node->tree_right] )->delete();
    }

    /**
     * 更新分类
     * @param $node_id
     * @param $params (only： name, position)
     * @return int
     */
    public function updateNode( $node_id, $params )
    {
        return Taxon::where('id', $node_id )->update( $params );
    }

    /*public function initRoot()
    {
        DB::table('taxon')->truncate();

        Taxon::create([
            'name' => "root",
            'description' => "根目录",
            'tree_left'=> 1,
            'tree_right'=> 2,
            'parent_id' => 0,
            'tree_level'=>0
        ]);
    }*/

}


