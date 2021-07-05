<?php

namespace App\Http\Controllers\Self;

use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Http\Transformers\Self\BankTransformer;
use App\Models\Self\Bank;
use Cacing69\BITBuilder\BITBuilder;
use Cacing69\BITBuilder\Filterable;
use Cacing69\BITBuilder\Sortable;
use Goutte\Client;
// use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;

class KredibelController extends Controller {
	public function index(Request $request)
	{
		$client = new Client();
		// $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_SSL_VERIFYHOST, FALSE);
    	// $client->getClient()->setDefaultOption('config/curl/'.CURLOPT_SSL_VERIFYPEER, FALSE);
		// $client->setClient(new GuzzleClient(['timeout' => 90, 'verify' => false, 'cookie'=>true ]));
		// $client->setHeader('User-Agent', "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:57.0) Gecko/20100101 Firefox/57.0");
		// $client->setServerParameter('HTTP_USER_AGENT', "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:57.0) Gecko/20100101 Firefox/57.0");
		$crawler = $client->request("GET", "https://www.kredibel.co.id/login", [
						'allow_redirects' => true
					]);



		// $crawler = $client->request('GET', 'https://www.kredibel.co.id/account/1690002241914');
		
		$form = $crawler->selectButton('Login')->form();
		$form->setValues([
			"email" => "room.thrift@gmail.com",
			"password" => "23Cacing09#@",
		]);

		dd($form);

		$crawler = $client->submit($form);
		dd($crawler->html());

		// dump($crawler->filterXPath('html/head/title')->text());

		// // dd($crawler);
		// // foreach ($crawler->filter("#information row") as $node) {
		// // 	dd($node);
		// // }
		//  // foreach ($crawler->filterXpath('//*[@id="information"]/div/div[1]/table/tbody/tr[1]/td[2]/div[2]') as $value) {
		//  // 	dd($value);
		//  // }

		//  // $output = $crawler->filterXpath('//*[@id="information"]/div/div[1]/table/tbody/tr[1]/td[2]/div[2]')->extract([]);
		// $x_path = '//*[@id="information"]/div/div[1]/table/tbody/tr[2]/td[2]/div[2]/a';

		// $results = $crawler->filterXPath($x_path)->each(function ($node, $i) {
		// 	dd($node, $i);
  //           return $node->nodeValue;	// This is a DOMElement Object
  //       });

		 // dd($results);
	}
}