<?php
/**
 * @desc    用户还款
 * @date    2017年04月20日
 *
 **/
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use Illuminate\Http\Request;

class UserRefundController extends BaseController
{

    protected $homeName = '还款管理';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('admin.userrefund.index');
    }
}
