<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;

$client = new Client();
$headers = [
    'Accept' => 'application/json',
    'Content-Type' => 'application/json',
    'Authorization' => 'Bearer 13|lOtcqibW2bIi8e7prNkzjawe8ujqbvwJAKr7r6FI'
];
$options = [
    'multipart' => [
        [
            'name' => 'judul',
            'contents' => 'Kunci Hilang 2'
        ],
        [
            'name' => 'body',
            'contents' => 'kunci hilang milik motor vario, bagi yang merasa kehilangan segera ambil di pos satpam dengan membawa stnk motor'
        ],
        [
            'name' => 'img_url',
            'contents' => Utils::tryFopen('shika.jpg', 'r'),
            'filename' => 'shika.jpg',
            'headers'  => [
                'Content-Type' => '<Content-type header>'
            ]
        ],
        [
            'name' => 'id_karyawan',
            'contents' => '2'
        ]
    ]
];
$request = new Request('POST', 'https://a177-2404-8000-102e-46f5-3cfa-28da-ab32-b65b.ap.ngrok.io/api/broadcasts', $headers);
$res = $client->sendAsync($request, $options)->wait();
echo $res->getBody();
