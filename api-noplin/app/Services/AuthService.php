<?php


namespace App\Services;


use App\Constants\Constant;
use App\Exceptions\BusinessException;
use App\Manager\Mail\SendRegisterCode;
use App\Manager\Mail\SendResetCode;
use App\Models\Card;
use App\Models\User;
use App\Resources\AuthResource;
use App\Services\BaseService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use JetBrains\PhpStorm\ArrayShape;


class AuthService extends BaseService
{
    /**
     * AuthService constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model, AuthResource::class);
    }

    /**
     * 登录
     * @param $params
     * @return array
     * @throws BusinessException
     */
    #[ArrayShape(['username' => "mixed", 'station_id' => "mixed", 'access_token' => "", 'token_type' => "string", 'expires_in' => "float|int", 'station' => "", 'unit' => "\Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed"])]
    public function login($params): array
    {
        //转换为jwt组件的格式
        $credentials = [
            'username' => $params['username'],
            'password' => $params['password']
        ];
        $user = User::query()->where('username', $params['username'])->first();
        if (empty($user)) {
            throw new BusinessException('账号不存在');
        }
        if (!$token = $this->guard()->attempt($credentials)) {
            throw new BusinessException('用户名或密码错误！');
        }
        if (auth('admin')->user()['status'] == Constant::USER_STATUS_2) {
            auth('admin')->logout();
            throw new BusinessException('暂时无法登录，请联系管理员！');
        }
        return $this->respondWithToken($token);
    }

    /**
     * 看守器
     * Get the guard to be used during authentication.
     */
    protected function guard(): Guard|StatefulGuard
    {
        return Auth::guard('admin');
    }

    /**
     * 登录成功返回
     * @param $token
     * @param $station
     * @return array
     */
    #[ArrayShape(['username' => "mixed", 'station_id' => "mixed", 'access_token' => "", 'type' => "mixed", 'token_type' => "string", 'expires_in' => "float|int", 'station' => "", 'unit' => "\Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed"])]
    protected function respondWithToken($token): array
    {
        return [
            'username' => auth('admin')->user()['username'],
            'access_token' => $token,
            'type' => auth('admin')->user()['type'],
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60,
        ];
    }

    /**
     * 个人信息
     * @return object|array
     * @throws BusinessException
     * @throws BindingResolutionException
     */
    public function me(): object|array
    {
        return parent::getInfo(['id' => auth()->user()['id']], ['*'], true);
    }

    /**
     * 登出
     * @return void
     */
    public function logout(): void
    {
        auth('admin')->logout();
    }

    /**
     * 刷新令牌
     * @return array
     */
//    public function refresh(): array
//    {
//        return $this->respondWithToken(auth('admin')->refresh());
//    }

    /**
     * 更新自己的密码
     * @param $params
     * @throws BusinessException
     */
    public function updatePassword($params)
    {
        /** @var User $admin */
        $admin = auth('admin')->user();
        if (!password_verify($params['origin_password'], $admin['password'])) {
            throw new BusinessException('原密码不正确');
        }
        $res = $admin->update(['password' => bcrypt($params['new_password'])]);
        if ($res) {
            auth('admin')->logout();
        }
    }

    /**
     * 删除验证码
     * @param string $mail
     * @param string $use
     * @return bool
     */
    public function deleteVerifyCode(string $mail, string $use = Constant::AUTH_TYPE_1): bool
    {
        return Cache::forget('VERIFY_CODE:' . $use . ':' . $mail);
    }

    /**
     * 注册
     * @param $params
     * @return array
     * @throws BusinessException|BindingResolutionException
     */
    #[ArrayShape(['username' => "mixed", 'station_id' => "mixed", 'access_token' => "", 'token_type' => "string", 'expires_in' => "float|int", 'station' => "", 'unit' => "\Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed"])]
    public function register($params): array
    {
        $this->verifyCode($params);
        if (parent::getList()->isEmpty()) {
            $this->init($params);
        } else {
            throw new BusinessException('无注册权限');
        }
        //删除验证码
        $this->deleteVerifyCode($params['username']);
        return $this->respondWithToken($this->getToken($params));
    }

    /**
     * @param $params
     * @return void
     */
    public function init($params): void
    {
           parent::create([
               'username'=>$params['username'],
               'password' => bcrypt($params['password']),
               'type'=>Constant::USER_TYPE_1
           ]);
    }

    /**
     * @param $params
     * @return string
     * @throws BusinessException
     */
    public function getToken($params): string
    {
        $credentials = [
            'username' => $params['username'],
            'password' => $params['password']
        ];
        if (!$token = $this->guard()->attempt($credentials)) {
            throw new BusinessException('用户名或密码错误！');
        }
        return $token;
    }

    /**
     * @param $data
     * @param null $id
     * @throws BusinessException
     */
    public function check(&$data, $id = null)
    {
        if (User::query()->where('username', $data['username'])->exists()) {
            throw new BusinessException('账号已注册');
        }
    }


    /**
     * 生成验证码
     * @param string $mail
     * @param string $use
     * @return string
     */
    protected static function makeVerifyCode(string $mail, string $use = Constant::REGISTER): string
    {
        $verifyCode = mt_rand(100000, 999999);
        if ($use == Constant::RESET) {
            Cache::put('RESET_CODE:' . $mail, $verifyCode, 300);
        } elseif ($use == Constant::REGISTER) {
            Cache::put('REGISTER_CODE:' . $mail, $verifyCode, 300);

        }
        return $verifyCode;
    }

    /**
     * 发码
     * @param string $email
     * @param string $use
     * @return array
     * @throws BusinessException
     */
    public function sendCode(string $email, string $use = Constant::REGISTER): array
    {
        try {
            if ($use == Constant::REGISTER) {
                Mail::to($email)->send(new SendRegisterCode($this->makeVerifyCode($email, $use)));
            } elseif ($use == Constant::RESET) {
                Mail::to($email)->send(new SendResetCode($this->makeVerifyCode($email, $use)));
            }
        } catch (\Exception $exception) {
            info('用户认证邮件发送失败：', ['message' => $exception->getMessage()]);
            throw new BusinessException('验证码发送失败');
        }
        return [];
    }

    /**
     * 重置发码
     * @param $data
     * @return array
     * @throws BusinessException
     */
    public function getResetCode($data): array
    {
        if (empty($this->query->where('username', $data['username'])->first())) {
            throw new BusinessException('该邮箱未注册，请联系管理员');
        }
        return $this->sendCode($data['username'], Constant::RESET);
    }

    /**
     * 注册发码
     * @param $data
     * @return array
     * @throws BusinessException
     */
    public function getRegisterCode($data):array
    {
        return $this->sendCode($data['username']);
    }

    /**
     * 重置密码
     * @param $data
     * @throws BusinessException
     */
    public function reset($data)
    {
        $this->verifyCode($data, Constant::RESET);
        $this->modify(['username' => $data['username']], ['password' => bcrypt($data['new_password'])]);
        $this->deleteVerifyCode($data['username'], Constant::RESET);
    }

    /**
     * 检查验证码
     * @param $data
     * @param int $use
     * @throws BusinessException
     */
    public function verifyCode($data, int $use = Constant::REGISTER)
    {
        if ($use == Constant::REGISTER) {
            $cacheCode = Cache::get('REGISTER_CODE:' . $data['username']);
        } else {
            $cacheCode = Cache::get('RESET_CODE:' . $data['username']);
        }
        if (empty($cacheCode) || $cacheCode != $data['code']) {
            throw new BusinessException('验证码错误');
        }
    }
}
