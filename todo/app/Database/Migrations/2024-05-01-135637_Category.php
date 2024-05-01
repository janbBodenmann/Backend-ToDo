<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Category extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE category
        (
           id INT(11) UNSIGNED AUTO_INCREMENT,
           name VARCHAR(255),
           count int,
           created_at DATETIME,
           updated_at DATETIME,
           deleted_at DATETIME,
           PRIMARY KEY (id)
        );
        ");
        
    }

    public function down()
    {
        
    }
}
