<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;

class CategoryImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    { 
        return new Category([
            'id' => $row[0],
            'name' => $row[1],
            'slug' => $row[2],
            'status' => $row[4],            
        ]);
    }
}
