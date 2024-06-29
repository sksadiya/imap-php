<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class menuItem extends Model
{
    use HasFactory;
    protected $fillable = ['menu_id','page_id','parent_id','title','url','order'];
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id');
    }
}
