<?php
namespace App\Controllers\Writer;

use App\Controllers\AppController;
use App\Src\Request;

class AppWriterController extends AppController
{

    /**
     * AppWriterController constructor.
     * @param Request $request
     */
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        if (!$this->session->is_writer())
        {
            $this->redirect('articles/index');
        }
        $this->layout = 'writer';
    }

}
