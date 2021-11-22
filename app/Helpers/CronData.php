<?php
namespace App\Helpers;

use GuzzleHttp\Client;

class CronData
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
                                'access_id' => $this->accessId, 'password' => $this->passw, 'page_size' => $this->pageSize,
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

    function DMStoDD($deg,$min,$sec)
    {
        return $deg+((($min*60)+($sec))/3600);
    }

    function DDtoDMS($dec)
    {
        $vars = explode(".",$dec);
        $deg = $vars[0];
        $tempma = "0.".$vars[1];

        $tempma = $tempma * 3600;
        $min = floor($tempma / 60);
        $sec = $tempma - ($min*60);

        return array("deg"=>$deg,"min"=>$min,"sec"=>$sec);
    }

    function NmetoDD($lng){
        $brk = strpos($lng,".") - 2;
        if($brk < 0){ $brk = 0; }

        $minutes = substr($lng, $brk);
        $degrees = substr($lng, 0,$brk);

        return $degrees + $minutes/60;
    }

    function printFloatWithLeadingZeros($num, $precision = 1, $leadingZeros = 3){
        $decimalSeperator = ".";
        $adjustedLeadingZeros = $leadingZeros + mb_strlen($decimalSeperator) + $precision;
        $pattern = "%0{$adjustedLeadingZeros}{$decimalSeperator}{$precision}f";
        return sprintf($pattern,$num);
    }

    function DDtoNme($lng)
    {
        $brk = strpos($lng,".");
        if($brk < 0){ $brk = 0; }
        $minus = $lng < 0 ? '-' : '';
        $minutes = substr($lng, $brk) *60;
        $minutes = $this->printFloatWithLeadingZeros($minutes, 4, 2);
        $degrees = str_pad( abs(substr($lng, 0,$brk)), 2, "0", STR_PAD_LEFT );
        return $minus.$degrees.$minutes;
    }
}

