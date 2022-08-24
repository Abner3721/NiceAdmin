<?php
/**
 * 登录和退出登录业务层
 */
declare (strict_types=1);
namespace app\admin\controller;
use app\common\facade\Token;
use ba\Captcha;
use think\facade\Config;
use think\facade\Validate;
use app\common\controller\Backend;
use app\admin\model\AdminLog;

class Index extends Backend
{
    protected $noNeedLogin      = ['logout', 'login', 'getBgImage'];
    protected $noNeedPermission = ['index'];
    public function index()
    {
        $adminInfo          = $this->auth->getInfo();
        $adminInfo['super'] = $this->auth->isSuperAdmin();
        unset($adminInfo['token']);
        $menus = $this->auth->getMenus();
        if (!$menus) {
            $this->error(__('No background menu, please contact super administrator!'));
        }
        $this->success('', [
            'adminInfo'  => $adminInfo,
            'menus'      => $menus,
            'siteConfig' => [
                'site_name' => get_sys_config('site_name'),
                'version'   => get_sys_config('version'),
            ],
            'terminal'   => [
                'install_service_port' => Config::get('niceadmin.install_service_port'),
                'npm_package_manager'  => Config::get('niceadmin.npm_package_manager'),
            ]
        ]);
    }

    /**
     * 登录
     */
    public function login()
    {
        // 检查登录态
        if ($this->auth->isLogin()) {
            $this->success(__('You have already logged in. There is no need to log in again~'), [
                'routePath' => '/admin'
            ], 302);
        }
        $captchaSwitch = Config::get('niceadmin.admin_login_captcha');
        // 检查提交
        if ($this->request->isPost()) {
            $username = $this->request->post('username');
            $password = $this->request->post('password');
            $keep     = $this->request->post('keep');
            $rule = [
                'username|' . __('Username') => 'require|length:3,30',
                'password|' . __('Password') => 'require|length:3,30',
            ];
            $data = [
                'username' => $username,
                'password' => $password,
            ];
            if ($captchaSwitch) {
                $rule['captcha|' . __('Captcha')]     = 'require|length:4,6';
                $rule['captchaId|' . __('CaptchaId')] = 'require';
                $data['captcha']   = $this->request->post('captcha');
                $data['captchaId'] = $this->request->post('captcha_id');
            }
            $validate = Validate::rule($rule);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            if ($captchaSwitch) {
                $captchaObj = new Captcha();
                if (!$captchaObj->check($data['captcha'], $data['captchaId'])) {
                    $this->error(__('Please enter the correct verification code'));
                }
            }
            AdminLog::setTitle(__('Login'));
            $res = $this->auth->login($username, $password, (bool)$keep);
            if ($res === true) {
                $this->success(__('Login succeeded!'), [
                    'userinfo'  => $this->auth->getInfo(),
                    'routePath' => '/admin'
                ]);
            } else {
                $msg = $this->auth->getError();
                $msg = $msg ? $msg : __('Incorrect user name or password!');
                $this->error($msg);
            }
        }
        $this->success('', [
            'captcha' => $captchaSwitch
        ]);
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        if ($this->request->isPost()) {
            $refreshToken = $this->request->post('refresh_token', '');
            if ($refreshToken) Token::delete((string)$refreshToken);
            $this->auth->logout();
            $this->success();
        }
    }

    /**
     * 获取登录的背景图
     */
    public function getBgImage()
    {
        $data = get_sys_config('', 'admincfg');
        $this->success('ok',$data);
    }
}