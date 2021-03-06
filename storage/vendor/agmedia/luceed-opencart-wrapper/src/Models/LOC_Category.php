<?php


namespace Agmedia\LuceedOpencartWrapper\Models;


use Agmedia\Helpers\Log;
use Agmedia\Models\Category\Category;
use Agmedia\Models\Category\CategoryDescription;
use Agmedia\Models\Category\CategoryPath;
use Agmedia\Models\Category\CategoryToLayout;
use Agmedia\Models\Category\CategoryToStore;
use Agmedia\Models\SeoUrl;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class LOC_Category
 * @package Agmedia\LuceedOpencartWrapper\Models
 */
class LOC_Category
{
    
    /**
     * @var array
     */
    private $list;
    
    /**
     * @var array
     */
    private $categories;
    
    /**
     * @var array
     */
    private $existing;
    
    /**
     * @var array
     */
    private $categories_to_add = [];
    
    
    /**
     * LOC_Category constructor.
     *
     * @param null $categories
     */
    public function __construct($categories = null)
    {
        $this->list       = $this->setCategories($categories);
        $this->categories = $this->sort(
            $this->setCategories($categories)
        );
    }
    
    
    /**
     * @return Collection
     */
    public function getList(): Collection
    {
        return collect($this->list);
    }
    
    
    /**
     * @return Collection
     */
    public function getCategories(): Collection
    {
        return collect($this->categories);
    }
    
    
    /**
     * @return $this
     */
    public function checkDiff()
    {
        $this->existing = Category::pluck('luceed_uid');
        $list_diff      = $this->getList()
            ->where('grupa_artikla_uid', '!=', '')
            ->where('naziv', '!=', '')
            ->pluck('grupa_artikla_uid')
            ->diff($this->existing)
            ->flatten();
        
        $categories = $this->getList()->whereIn('grupa_artikla_uid', $list_diff);
        
        foreach ($categories as $category) {
            $parent = Category::where('luceed_uid', $category->nadgrupa__grupa_artikla_uid)->first();
            
            if (isset($parent->luceed_uid) && $parent->luceed_uid != '') {
                $top_parent = Category::where('category_id', $parent->parent_id)->first();
                
                if ($top_parent) {
                    $this->pushToAdd($top_parent);
                }
    
                $this->pushToAdd($parent);
            }
            
            $this->pushToAdd($category, false);
        }
        
        return $this;
    }
    
    
    /**
     * @param $parent
     *
     * @return \stdClass
     */
    private function addDummy($parent): \stdClass
    {
        $new_cats   = new \stdClass();
        $top = Category::where('category_id', $parent->parent_id)->first();
    
        $new_cats->grupa_artikla_uid           = $parent->luceed_uid;
        $new_cats->nadgrupa__grupa_artikla_uid = $top ? $top->luceed_uid : null;
        
        return $new_cats;
    }
    
    
    /**
     * @param      $obj
     * @param bool $dummy
     */
    private function pushToAdd($obj, $dummy = true): void
    {
        array_push($this->categories_to_add, $dummy ? $this->addDummy($obj) : $obj);
    }
    
    
    /**
     * @return int
     */
    public function import()
    {
        $count = 0;
        $categories = $this->sort($this->categories_to_add);
        
        foreach ($categories as $i => $category) {
            $exist = Category::where('luceed_uid', $category->grupa_artikla_uid)->first();
            
            if ( ! $exist) {
                $cat_id = $this->save($category, 0, $i);
                $count++;
            } else {
                $cat_id = $exist->category_id;
            }
            
            if (isset($category->sub) && ! empty($category->sub)) {
                foreach ($category->sub as $k => $subcat) {
                    $exist = Category::where('luceed_uid', $subcat->grupa_artikla_uid)->first();
    
                    if ( ! $exist) {
                        $sub_cat_id = $this->save($subcat, $cat_id, $k);
                        $count++;
                    } else {
                        $sub_cat_id = $exist->category_id;
                    }
                    
                    if (isset($subcat->sub) && ! empty($subcat->sub)) {
                        foreach ($subcat->sub as $n => $subsubcat) {
                            $this->save($subsubcat, $sub_cat_id, $n);
                            $count++;
                        }
                    }
                }
            }
        }
        
        return $count;
    }
    
    
    /**
     * @param     $category
     * @param int $parent_id
     * @param int $sort
     *
     * @return mixed
     */
    public function save($category, $parent_id = 0, $sort = 0)
    {
        $id = Category::insertGetId([
            'parent_id'     => $parent_id,
            'luceed_uid'    => $category->grupa_artikla_uid,
            'top'           => $parent_id ? 0 : 1,
            'column'        => 1,
            'sort_order'    => $sort,
            'status'        => 1,
            'date_added'    => Carbon::now(),
            'date_modified' => Carbon::now()
        ]);
        
        CategoryDescription::insert([
            'category_id'      => $id,
            'language_id'      => 2,
            'name'             => $category->naziv,
            'description'      => $category->naziv,
            'meta_title'       => $category->naziv,
            'meta_description' => $category->naziv,
            'meta_keyword'     => $category->naziv,
        ]);
        
        $level = 0;
        
        $paths = CategoryPath::where('category_id', $parent_id)->orderBy('level')->pluck('path_id');
        
        foreach ($paths as $path) {
            CategoryPath::insert([
                'category_id' => $id,
                'path_id'     => $path,
                'level'       => $level
            ]);
            
            $level++;
        }
        
        CategoryPath::insert([
            'category_id' => $id,
            'path_id'     => $id,
            'level'       => $level
        ]);
        
        CategoryToLayout::insert([
            'category_id' => $id,
            'store_id'    => 0,
            'layout_id'   => 0
        ]);
        
        CategoryToStore::insert([
            'category_id' => $id,
            'store_id'    => 0
        ]);
        
        SeoUrl::insert([
            'store_id'    => 0,
            'language_id' => 2,
            'query'       => 'category_id=' . $id,
            'keyword'     => Str::slug($category->naziv)
        ]);
        
        return $id;
    }
    
    
    /**
     * @param $category
     *
     * @return bool
     */
    public function hasUid($category): bool
    {
        if (empty($category->grupa_artikla_uid)) {
            return false;
        }
        
        return true;
    }
    
    
    /**
     * @param $category
     *
     * @return bool
     */
    public function hasTitle($category): bool
    {
        if (empty($category->naziv)) {
            return false;
        }
        
        return true;
    }
    
    
    /**
     * @param $category_list
     *
     * @return array
     */
    public function sort($category_list): array
    {
        $temp_category = [];
        
        foreach ($category_list as $category) {
            if (empty($category->nadgrupa__grupa_artikla_uid)) {
                $temp_category[] = $category;
            }
        }
        
        foreach ($category_list as $category) {
            for ($i = 0; $i < count($temp_category); $i++) {
                if ($category->nadgrupa__grupa_artikla_uid == $temp_category[$i]->grupa_artikla_uid) {
                    $temp_category[$i]->sub[] = $category;
                }
            }
        }
        
        foreach ($category_list as $category) {
            for ($i = 0; $i < count($temp_category); $i++) {
                if (isset($temp_category[$i]->sub) && is_array($temp_category[$i]->sub)) {
                    for ($k = 0; $k < count($temp_category[$i]->sub); $k++) {
                        if ($category->nadgrupa__grupa_artikla_uid == $temp_category[$i]->sub[$k]->grupa_artikla_uid) {
                            $temp_category[$i]->sub[$k]->sub[] = $category;
                        }
                    }
                }
            }
        }
        
        return $temp_category;
    }
    
    
    /**
     * @param $categories
     *
     * @return array
     */
    private function setCategories($categories): array
    {
        $cats = json_decode($categories);
        
        return $cats->result[0]->grupe_artikala;
    }
}