<?php

//Maker Routers
use App\Maker\Maker;


$this->get('/maker',  function($app,$args) {  
	//Maker index|help
	$maker = new Maker($app);
	return $app->view('help' , [ 'commands' => $maker->commands() ] , __DIR__.'/views/'  ) ;
 }, 'debug');


$this->group(['/maker/{command}string/{subcommand}string', '/maker/{command}string' ], function($app,$args) {  
	//Maker File | Migrate	
	$maker = new Maker($app);
	$continue = false;

	foreach ($maker->commands() as $i => $command ) {
		if($command->name == $args->command){
			$continue = true;
		}
	}
	
	$method = $args->command;

	if($continue && ( isset($args->subcommand) && ( strtolower($args->subcommand) != 'help'))  ){
		return $app->view('error', (array) $maker->$method($args->subcommand) , __DIR__.'/views/' );
	}else{
		return $app->redirect('/maker');
	}

 }, 'debug' );


 $this->group(['/map', '/map/{mode}string'], function($app,$args) {  
	//Router Map	
	if( isset($args->mode ) ){
		$app->mode = $args->mode;
	}else{
		$args->mode = 'app';
	}

	switch( $args->mode ){
		default:
		case 'view' : 
		case 'app' :
		case 'html' :  
			return $app->view('map' , [ 'routers' => $app->routes] ,  __DIR__. '/views/' ) ;
		break;
		
		case 'api' : 
		case 'json' : 
			return $app->json(  [ 'routers' => $app->routes] );
		break;
	}

 }, 'debug');
