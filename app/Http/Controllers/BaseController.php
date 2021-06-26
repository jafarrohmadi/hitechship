<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class BaseController extends Controller
{
    protected $url, $accessId, $passw, $errorCodes, $mobiles, $returnMsgFromId, $sinceMobile, $pageSize, $client;

    public function __construct ()
    {
        $this->url             = 'https://isatdatapro.skywave.com/GLGW/GWServices_v1/RestMessages.svc/';
        $this->accessId        = 70002601;
        $this->passw           = "KRYSRSZT";
        $this->errorCodes      = [];
        $this->mobiles         = [];
        $this->returnMsgFromId = 0;
        $this->sinceMobile     = 0;
        $this->pageSize        = 100;
        $this->client          = new Client(['base_uri' => $this->url]);
    }

    public function getInfoUtcTime ()
    {
        try {
            $response = $this->client->get('info_utc_time.json/');
            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getInfoVersion ()
    {
        try {
            $response = $this->client->get('info_version.json/');
            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getInfoErrors ()
    {
        try {
            $response = $this->client->get('info_errors.json/');
            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSubAccountInfos ()
    {
        try {
            $response = $this->client->request('GET', 'get_subaccount_infos.json/', [
                    'query' => [
                        'access_id' => $this->accessId, 'password' => $this->passw,
                    ],
                ]
            );
            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getBroadcastInfos ()
    {
        $subAccount = $this->getSubAccount();
        try {
            $data = [];
            if (count($subAccount) > 0) {
                for ($i = 0, $iMax = count($subAccount); $i < $iMax; $i++) {
                    $response = $this->client->request('GET', 'get_broadcast_infos.json/', [
                            'query' => [
                                'access_id' => $this->accessId, 'password' => $this->passw,
                                'subaccount_id' => $subAccount[$i],
                            ],
                        ]
                    );
                    $data[]   = $response->getBody()->getContents();
                }
            }
            return $data;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getMobilesPaged ()
    {
        $subAccount = $this->getSubAccount();
        try {
            $data = [];
            if (count($subAccount) > 0) {
                for ($i = 0, $iMax = count($subAccount); $i < $iMax; $i++) {
                    $response = $this->client->request('GET', 'get_mobiles_paged.json/', [
                            'query' => [
                                'access_id' => $this->accessId, 'password' => $this->passw,
                                'subaccount_id' => $subAccount[$i],
                            ],
                        ]
                    );
                    $data[]   = $response->getBody()->getContents();
                }
            }
            return $data;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getReturnMessages ()
    {
        $subAccount = $this->getSubAccount();
        try {
            $data = [];
            if (count($subAccount) > 0) {
                for ($i = 0, $iMax = count($subAccount); $i < $iMax; $i++) {
                    $response = $this->client->request('GET', 'get_return_messages.json/', [
                            'query' => [
                                'access_id' => $this->accessId, 'password' => $this->passw,
                                'start_utc' => date('Y-m-d H:i:s', strtotime(json_decode($this->getInfoUtcTime())) - (60 * 60 * 1)),
                                'sub_account_id' => $subAccount[$i],
                            ],
                        ]
                    );
                    $data[]   = $response->getBody()->getContents();
                }
            }
            return $data;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getForwardStatus ()
    {
        $subAccount = $this->getSubAccount();
        try {
            $data = [];
            if (count($subAccount) > 0) {
                for ($i = 0, $iMax = count($subAccount); $i < $iMax; $i++) {
                    $response = $this->client->request('GET', 'get_forward_statuses.json/', [
                            'query' => [
                                'access_id' => $this->accessId, 'password' => $this->passw,
                                'start_utc' => date('Y-m-d H:i:s', strtotime(json_decode($this->getInfoUtcTime())) - (60 * 60 * 1)),
                                'sub_account_id' => $subAccount[$i],
                            ],
                        ]
                    );
                    $data[]   = $response->getBody()->getContents();
                }
            }
            return $data;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getForwardMessages ()
    {
        $subAccount = $this->getSubAccount();
        try {
            $data = [];
            if (count($subAccount) > 0) {
                for ($i = 0, $iMax = count($subAccount); $i < $iMax; $i++) {
                    $response = $this->client->request('GET', 'get_forward_messages.json/', [
                            'query' => [
                                'access_id' => $this->accessId, 'password' => $this->passw,
                                'sub_account_id' => $subAccount[$i],
                            ],
                        ]
                    );
                    $data[]   = $response->getBody()->getContents();
                }
            }
            return $data;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function getSubAccount ()
    {
        $getSubAccountInfos = json_decode($this->getSubAccountInfos());
        $subAccount         = [];
        foreach ($getSubAccountInfos->Subaccounts as $account) {
            $subAccount[] = $account->AccountID;
        }
        return $subAccount;
    }
}

