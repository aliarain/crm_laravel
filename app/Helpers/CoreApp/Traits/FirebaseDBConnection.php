<?php

namespace App\Helpers\CoreApp\Traits;

trait FirebaseDBConnection
{
    //db connection with firebase firestore
    public function dbConnection()
    {
        return app('firebase.firestore')->database();
    }

}
