<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\UnexpectedValueException;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    public static $salt;

    function __construct() {
        self::$salt = env('APP_PASS_SALT', '');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public static function cryptPassword($password) {
        return sha1(self::$salt . $password);
    }

    public static function checkAccessToken($token) {
        try {
            $decoded = JWT::decode($token, env('APP_JWT_SALT', ''), array('HS512'));
        } catch (\Exception $err) {
            throw new \Exception($err->getMessage());
        }

        if(empty($decoded->data->userId)) return;

        return User::where('id', $decoded->data->userId)->first();
    }

    public function generateAccessToken($req) {
        $data = [
            'iat' => time(),    // Issued at: time when the token was generated
            'jti' => base64_encode(mcrypt_create_iv(32)),   // Json Token Id
            'iss' => $req->server('SERVER_NAME'),           // Issuer
            'nbf' => time(),                                // Not before
            'exp' => strtotime("+1 week"),                  // Expire
            'data' => [                                     // Data related to the signer user
                'userId' => $this->id
            ]
        ];

        $this->access_token = JWT::encode($data, env('APP_JWT_SALT', ''), 'HS512');
        return $this;
    }
}
