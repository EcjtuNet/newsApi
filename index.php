<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;
	require_once 'vendor/autoload.php';
	require_once 'config.php';
	$medoo=new medoo([
			'database_type' =>$db['database_type'],
		    'database_name' =>$db['database_name'],
		    'server' => $db['hostname'],
		    'username' =>$db['username'],
		    'password' =>$db['password'],
		    'charset' =>$db['charset']
	]);
$app = new \Slim\App;
$app->get('/list', function ($request,$response,$args=[]) use($medoo){
	if(isset($request->getQueryParams()['pageNum'])){
		$pageNum=$request->getQueryParams()['pageNum'];
	}else{
		$pageNum=3;
	}
	if(isset($request->getQueryParams()['page'])){
		$page=$request->getQueryParams()['page'];
	}else{
		$page=1;
	}
	$page<=0?1:$page;
	$pageNum<=0?3:$pageNum;
	$offset = ($page - 1) * $pageNum;
	$condition['LIMIT'] = array($offset, $pageNum);
	$condition['ORDER'] = 'mynews.senddate DESC';
    $datas = $medoo->select("addonarticle", [ "[<]mynews" => ["aid" => "aid"] ],["addonarticle.aid","addonarticle.typeid","addonarticle.redirecturl","mynews.title","mynews.writer","mynews.senddate","mynews.body"],$condition);
	$count=count($datas);
	echo json_encode(array('count'=>$count,'list'=>$datas));
	return  $response->withHeader( 'Content-Type','application/json');
});
$app->run();