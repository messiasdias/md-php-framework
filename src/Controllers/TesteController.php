<?php
namespace App\Controllers;
use App\App;
use App\Controllers\Controller;
use App\Tools\File2;

/**
 * TesteController Class
 */

class TesteController extends Controller
{	

	public function index($app, $args=null){
		$args['name'] = str_replace( '%20', ' ', $args['name']);
		return $app->view('teste', $args);
	}


	public function upload($app, $args=null){
		$app = $app->upload();
		return $app->view('teste/upload');
	}


	public function download($app, $args=null){
		return $app->download( $app->request->data['path'].'/'.$app->request->data['name'] );
	}


}