<?php

    namespace App\Models\coreApp\Social;


    use App\Models\User;
    use Illuminate\Database\Eloquent\Model;

    class SocialIdentity extends Model
    {
        protected $fillable = ['user_id', 'provider_name', 'provider_id'];

        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }
