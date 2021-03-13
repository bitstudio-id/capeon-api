<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterConfirmRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthTokenRequest;
use App\Models\AppKey;
use App\Models\RegisterToken;
use App\Models\Token;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller {
	protected function jwt($data) {
        $payload = [
            'iss' => env('JWT_ISSUER'),
            'sub' => [
                "id" => $data->user_id,
            ],
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 30) // Expiration time
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }

    public function token(AuthTokenRequest $request)
	{
        $get = User::where("email", $request->username)
        	->orWhere("no_hp", $request->username)
        	->orWhere("nama_pengguna", $request->username)
			->first();

        if ($get == null) {
			throw new NotFoundException("user_not_found");
		}

    	if (password_verify($request->password, $get->password)) {
			$jwt = $this->jwt($get);

			$token = new Token();

			$app_key = AppKey::where("app_key_key", $request->header("X-App-Key"))
								->where("app_key_active", 1)
								->first();


			if($app_key != null) {
				$token->token_value = $jwt;
				$token->token_app_key_id = $app_key->app_key_id;
				$token->token_user_id = $get->id;

				try {
					$token->save();

					$data = [
			            "meta" => [
			            	"message"   => "auth_token_success",
			            ],
			            "data"      => [
			            	"auth_token" => $jwt
			            ]
			        ];

			        return response()->json($data, 200);
				} catch (Exception $e) {
					throw new BadRequestException($e->getMessage());
				}

			} else {
				throw new BadRequestException("invalid_app_key");
			}

		} else {
			throw new BadRequestException("invalid_username_or_password");
		}
	}

	public function register(AuthRegisterRequest $request)
	{
        $get = User::where("email", $request->username)
        	->orWhere("no_hp", $request->username)
        	->orWhere("nama_pengguna", $request->username)
			->first();

		if($get == null) {
			DB::beginTransaction();
			try {
				$user = new User();

				$user->nama = trim($request->nama);
				$user->no_hp = trim($request->username);
				$user->password = Hash::make($request->password);
				$user->save();

				$register_token = new RegisterToken();
				$register_token->register_token_value = Str::random(32);
				$register_token->register_token_used = 0;
				$register_token->register_token_expired_at = "2021-12-12 23:59:59";
				$register_token->register_token_code = "202020";
				$register_token->register_token_user_id = $user->id;
				$register_token->save();;



				$data = [
		            "meta" => [
		            	"message"   => "register_token_created",
		            ],
		            "data"      => [
		            	"register_token" => $register_token->register_token_value
		            ]
		        ];

				DB::commit();
		        return response()->json($data, 200);
			} catch (Exception $e) {
				DB::rollback();
				throw new BadRequestException($e->getMessage());
			}
		} else {
			throw new BadRequestException("already_registered");
		}
	}

	public function registerConfirm(AuthRegisterConfirmRequest $request)
	{
		$register_token = RegisterToken::where("register_token_value", $request->token)
							->where("register_token_used", 0)
							->first();

		if($register_token != null) {
			if($register_token->register_token_code == $request->kode) {
				DB::beginTransaction();

				try {
					$register_token->register_token_used = 1;
					$register_token->save();

					$get = User::find($register_token->register_token_user_id);
					$get->verified_at = date("Y-m-d H:i:s");

					$get->save();

					$jwt = $this->jwt($get);

					$token = new Token();

					$app_key = AppKey::where("app_key_key", $request->header("X-App-Key"))
										->where("app_key_active", 1)
										->first();


					if($app_key != null) {
						$token->token_value = $jwt;
						$token->token_app_key_id = $app_key->app_key_id;
						$token->token_user_id = $get->id;

						try {
							$token->save();

							$data = [
					            "meta" => [
					            	"message"   => "auth_token_success",
					            ],
					            "data"      => [
					            	"auth_token" => $jwt
					            ]
					        ];

					        return response()->json($data, 200);
						} catch (Exception $e) {
							throw new BadRequestException($e->getMessage());
						}

					} else {
						throw new BadRequestException("invalid_app_key");
					}



				} catch (\Exception $e) {
					DB::rollback();
					throw new BadRequestException($e->getMessage());
				}

			} else {
				$register_token->register_token_attempt += 1;
				$register_token->save();

				throw new BadRequestException("invalid_code");
			}
		} else {
			throw new BadRequestException("invalid_token");
		}
	}
}