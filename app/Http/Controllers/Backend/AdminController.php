<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Base\BaseController;

/**
 * 
 */
class AdminController extends BaseController
{
	public function index() 
	{
		return view('backend.admin.index');
	}
}