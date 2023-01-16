<?php

namespace App;

use App\Support\Model\IncludeExclude;
use Illuminate\Support\Facades\DB;
use App\Support\Model\Status;

class Category extends BaseModel {

    use Status, IncludeExclude;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tHE_SetItemCateg';
    protected $primaryKey = 'anQid';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];
    
    /**
     * Languange
     * @var string
     */
    public $langId;
    
    /**
     * Class constructor
     * @retrun void
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        
        $this->langId = config('app.locale');
    }

    public function rFatherCategory()
    {
        return $this->belongsTo('App\CategoryTranslation', 'father_id', 'category_id')->where('lang_id', $this->langId);
    }
    
    public function translation()
    {
        return $this->hasOne('App\CategoryTranslation')->where('lang_id', $this->langId);
    }
    
    public function translations()
    {
        return $this->hasMany('App\CategoryTranslation');
    }

    /**
        * Update categbory slug and path.
        * @param string $from
        * @param string $to
        * @param string $id
        * @return void
    */
    public function updateSlugAndPath($from, $to, $id)
    {
        $from_slug = Slug::make($from);
        $to_slug = Slug::make($to);
        
        DB::select( DB::raw("UPDATE category_translations
            SET path = REPLACE(path, '$from', '$to'),
                slug = REPLACE(slug, '$from_slug', '$to_slug')
            WHERE category_id = '$id' LIMIT 1") );
    }
    
    /**
     * Update list of parents after creating category
     * @param array $category
     * @return void
    */
    public function updateListOfParents($category)
    {
        if ($category->id > 0 && $category->father_id > 0)
        {
            $fatherInfo = $this->getCatInfo($category->father_id);

            $data['list_of_parents'] = $fatherInfo->list_of_parents . ',' . $category->id;

            $this->edit($category->id, $data);
        }
    }

    /**
     * Get data about category
     * @param mixed $id
     * @return array
     */
    public function getCatInfo($id)
    {
        $langId = $this->langId;
        $ids = '';
        $col_name = is_numeric($id) ? 'parent.id' : 'ct.slug';
        $attr_name = is_numeric($id) ? 'id' : 'slug';
        
        $categories = DB::select(DB::raw("
               SELECT ct.path ,
                      node.id ,
                      ct.name ,
                      node.father_id ,
                      ct.description ,
                      ct.slug ,
                      node.status ,
                      node.photo ,
                      node.priority ,
                      node.list_of_parents
                 FROM categories AS node,
                      categories AS parent,
                      category_translations ct
                  WHERE node.lft BETWEEN parent.lft AND parent.rgt
                   AND ct.category_id = node.id
                   AND ct.category_id = parent.id
                   AND ct.lang_id = :langId
                   AND $col_name = :id
                 ORDER BY node.lft"), array(
                    'langId' => $langId,
                     'id' => $id
                ));

                
        $categoryCollect = new Category();
		      
        foreach ($categories as $cid => $category)
        {
            $ids .= $category->id . ',';
        }
				
        foreach ($categories as $cid => $category)
        {
            if ($category->$attr_name == $id)
            {
                $main_cat = explode(',', $category->list_of_parents);
				
		$categoryCollect->ids = rtrim($ids, ',');
                $categoryCollect->id = $category->id;
                $categoryCollect->category_id = $category->id;
                $categoryCollect->path = $category->path;
                $categoryCollect->name = $category->name;
                $categoryCollect->slug = $category->slug;
                $categoryCollect->status = $category->status;
                $categoryCollect->photo = $category->photo;
                $categoryCollect->father_id = $category->father_id;
                $categoryCollect->priority = $category->priority;
                $categoryCollect->description = $category->description;
                $categoryCollect->list_of_parents = $category->list_of_parents;
                $categoryCollect->lang_id = $langId;
                $categoryCollect->root_cat = $main_cat[0];
                $categoryCollect->sub_cat = isset($main_cat[1]) ? $main_cat[1] : 0;
				
		break;
            }
            
        }

        return $categoryCollect;
    }
    
    /**
        * Regenerate values for the LFT and RGT columns.
        * @param int $parent
        * @param int $left
        * @return array
    */
    public function rebuildTree($parent = 0, $left = 0)
    {
        // the right value of this node is the left value + 1
        $right = $left + 1;

        // get all children of this node
        $categories = Category::whereFatherId($parent)->get(array('id'));
        
        foreach ($categories as $id => $category)
        {
            // recursive execution of this function for each
            // child of this node
            // $right is the current right value, which is
            // incremented by the rebuild_tree function

            $right = Category::rebuildTree($category->id, $right);
        }
        
        // we've got the left value, and now that we've processed
        // the children of this node we also know the right value
        Category::whereId($parent)->update(array('lft' => $left, 'rgt' => $right));
        
        // return the right value of this node + 1
        return $right + 1;
    }
    
    public function getAll()
    {
        $categories =  Category::relation()->join('category_translations as t', 'categories.id', '=', 't.category_id')
            ->where('t.lang_id', '=', $this->langId)
            ->sIncludeIds()
            ->sStatus()
            ->orderBy('categories.lft', 'asc')
            ->orderBy('categories.priority', 'asc')
            ->get(array('categories.id', 't.name', 't.slug', 't.lang_id as lang_id', 'categories.status', 'categories.father_id', 'categories.lft', 'categories.rgt'));
    
        foreach($categories as $id => $row) {
            $row->length = 1;
            $categories[$id] = $row;
        }
        
        return $categories;
    }
    
    /**
        * Retrun category tree with basic info
        * @param int $root
        * @return \Illuminate\Support\Collection
    */
    public function getCategoryTree($root = 1)
    {
        $array = array();
        $right = array();
        
        $root = Category::whereId($root)->first(array('lft', 'rgt'));

        $categories =  Category::relation()->join('category_translations as t', 'categories.id', '=', 't.category_id')
                        ->where('t.lang_id', '=', $this->langId)
                        ->sIncludeIds()
                        ->sStatus()
                        ->whereBetween('categories.lft', array(is_null($root) ? 0 : $root->lft, is_null($root) ? 1000 : $root->rgt))
                        ->orderBy('categories.lft', 'asc')
                        ->orderBy('categories.priority', 'asc')
                        ->get(array(
                                    'categories.id',
                                    't.name',
                                    't.slug',
                                    't.lang_id as lang_id',
                                    'categories.status',
                                    'categories.father_id',
                                    'categories.lft',
                                    'categories.rgt')
                                );
        foreach($categories as $id => $row)
        {
            if (count($right) > 0)
            {
                while (isset($right[count($right) - 1]) && ($right[count($right) - 1] < $row->rgt))
                {
                    array_pop($right);
                }
            }
            
            $row->length = count($right);
            $row->name_length = repeater($row->length) . $row->name;
            $array[] = $row;
            $right[] = $row->rgt;
        }
        
        return collect($array);
    }
    
    /**
        * Retrun child categories
        * @param int $root
        * @return \Illuminate\Database\Eloquent\Collection
    */
    public function getChilds($root = 1)
    {
        return self::join('category_translations', 'categories.id', '=', 'category_translations.category_id')
                        ->where('categories.father_id', '=', $root)
                        ->where('category_translations.lang_id', '=', $this->langId)
                        ->sStatus()
                        ->orderBy('categories.priority', 'asc')
                        ->get(array(
                                    'categories.id',
                                    'category_translations.slug',
                                    'category_translations.name',
                                    'category_translations.description',
                                    'category_translations.lang_id'
                                    )
                                );
    }
    
    /**
        * Retrun category ids tree
        * @param int $root
        * @return array
    */
    public function getCategoryTreeIds($root = 1)
    {
        $array = array();
        $right = array();
        
        $root = Category::whereId($root)->first(array('lft', 'rgt'));

        $categories =  Category::join('category_translations as t', 'categories.id', '=', 't.category_id')
                        ->where('t.lang_id', '=', $this->langId)
                        ->sStatus()
                        ->whereBetween('categories.lft', array($root->lft, $root->rgt))
                        ->orderBy('categories.lft', 'asc')
                        ->get(array('categories.id'));
        
        foreach($categories as $id => $row)
        {
            if (count($right) > 0)
            {
                while ($right[count($right) - 1] < $row->rgt)
                {
                    array_pop($right);
                }
            }
            
            $array[] = $row->id;
            $right[] = $row->rgt;
        }
        
        return $array;
    }
    
    /**
     * Get category config
     *
     * @param  string  $value
     * @return array
     */
    public function getConfigAttribute($value)
    {
        return json_decode($value);
    }

    public function getSlugUrlAttribute()
    {
        return url($this->lang_id . str_replace('adtexo', '', $this->slug));
    }
}
