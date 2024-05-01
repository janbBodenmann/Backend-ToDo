<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Todos extends Migration
{
    public function up()
    {
        $this->db->query("CREATE TABLE todo
        (
            id INT(11) UNSIGNED AUTO_INCREMENT,
            name VARCHAR(255),
            open BOOLEAN,
            category_id INT(11),
            created_at DATETIME,
            updated_at DATETIME,
            deleted_at DATETIME,
            PRIMARY KEY (id)
        );
        ");
    }

    public function down()
    {
        //
    }
}