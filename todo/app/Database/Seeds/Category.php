<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Category extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Work',
                'count' => '0',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),

            ]


        ];

        $CategoryModel = new \App\Models\CategoryModel();

        foreach ($data as $entry_id => $entry_data) {
            if ($CategoryModel->insert($entry_data) === false) {
                echo "Errors on entry_id ${entry_id}:\n";
                print_r($CategoryModel->errors());
            }
        }
        
    }
}