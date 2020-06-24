<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class Plex
{
    const BASE_DOMAIN = "https://plex.tv/api/v2";

    public function authenticate()
    {
        $baseDomain = self::BASE_DOMAIN;
        $plex_version = config('app.plex_version');
        $identifier = self::getDeviceIdentifier();
        $baseUrl = $baseDomain . '/users/signin?X-Plex-Product=Plex%20Web&X-Plex-Version='.$plex_version.'&X-Plex-Client-Identifier='.$identifier.'X-Plex-Platform=Chrome';

        $client = new Client();
        $response = $client->post(
            $baseUrl,[
            'form_params' => [
                'login' => config('app.plex_username'),
                'password' => config('app.plex_password'),
                'skipAuthentication' => true,
                'noGuest' => false,
                'rememberMe' => true
            ]
        ]);

        return $response;
    }

    public function getDevicePlatform()
    {
        //patform for the device
    }

    public function getDeviceResolution()
    {
        //the screeen resolution
    }

    public function getDeviceOs()
    {
        //the operating system
    }

    public function getDeviceMac()
    {
        //get the mac address
        $macAddr = false;
        $arp = `arp -n`;
        $lines = explode("\n", $arp);

        foreach ($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));

            if ($cols[0] == $_SERVER['REMOTE_ADDR']) {
                $macAddr = $cols[2];
            }
        }
        return $macAddr;
    }

    public function getDeviceIdentifier()
    {
        //base64 encode timestamp and mac address
        $timestamp = Carbon::now()->timestamp;
        $string = base64_encode(self::getDeviceMac() . $timestamp);

        return $string;

    }
}