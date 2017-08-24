<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function(Blueprint $table) {
            $sql = <<<QUERY
                CREATE TABLE IF NOT EXISTS `user` (
                  `userid` int(11) NOT NULL AUTO_INCREMENT,
                  `deleted` datetime DEFAULT NULL,
                  `email` varchar(100) DEFAULT NULL,
                  `username` varchar(45) DEFAULT NULL,
                  `name` varchar(100) DEFAULT NULL,
                  `surname` varchar(45) NOT NULL,
                  `phone` varchar(15) DEFAULT NULL,
                  `logins` int(11) NOT NULL DEFAULT '0',
                  `last_login` datetime DEFAULT NULL,
                  `active` int(11) NOT NULL DEFAULT '1',
                  `password` varchar(100) DEFAULT NULL,
                  `created` datetime DEFAULT NULL,
                  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  `public` int(11) NOT NULL DEFAULT '1' ,
                  `settings` text,
                  `isroot` int(11) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`userid`),
                  UNIQUE KEY `email` (`email`,`isroot`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 ;
QUERY;

            DB::connection()->getPdo()->exec($sql);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user');
    }
}
