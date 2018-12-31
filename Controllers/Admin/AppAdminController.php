<?php
namespace App\Controllers\Admin;

use App\Controllers\AppController;
use App\Src\Request;

/**
 * Class AppAdminController
 * @package App\Controllers\Admin
 */
class AppAdminController extends AppController
{

    /**
     * AppAdminController constructor.
     * @param Request $request
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        if (!$this->session->is_admin())
        {
            $this->redirect('articles/index');
        }
        $this->layout = 'admin';
    }

}
