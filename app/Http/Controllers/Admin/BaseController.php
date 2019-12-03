<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;

class BaseController extends Controller
{
    public $url, $accessId, $passw, $errorCodes, $mobiles, $returnMsgFromId, $sinceMobile, $pageSize;

    public function __construct ()
    {
        $this->url             = "https://isatdatapro.skywave.com/GLGW/GWServices_v1/RestMessages.svc/";
        $this->accessId        = 60002601;
        $this->passw           = "KRYSRSZ";
        $this->errorCodes      = [];
        $this->mobiles         = [];
        $this->returnMsgFromId = 0;
        $this->sinceMobile     = 0;
        $this->pageSize        = 100;
    }

    public function setSoap()
    {

    }

}
