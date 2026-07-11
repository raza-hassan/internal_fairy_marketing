<?php

namespace App\Imports;
use App\Leads;
use Maatwebsite\Excel\Concerns\ToModel;
  
class LeadsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        echo '<pre>';
        print_r($row);
        exit;
        return new Leads([
            'name'     => $row[0],
            'email'    => $row[1], 
            'password' => \Hash::make('123456'),
        ]);
    }
}