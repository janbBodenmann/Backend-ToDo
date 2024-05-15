<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Todos extends Seeder
{
    public function run()
    {
        $example_data = [
            [
                'name' => 'Got to do my homework',
                'open' => '1',
                'category_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Finish Project',
                'open' => '0',
                'category_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $TodoModel = new \App\Models\Todos();

        foreach ($example_data as $entry_id => $data) {
            if ($TodoModel->insert($data) === false) {
                echo "Errors on entry_id ${entry_id}:\n";
                print_r($TodoModel->errors());
            }
        }
    }
}