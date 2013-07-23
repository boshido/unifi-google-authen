<?php

class UnifiController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public function getIndex()
    {
		$unifi = new Unifi();
		
    }
	
	public function getHistory()
	{
		$google_id = Input::get('google_id');
		$limit = Input::get('limit');
		$sort = Input::get('sort');
		$sort_type = Input::get('sort_type') != null ? (int)Input::get('sort_type') : 1;

		$db = Database::Connect();
		$log = $db->log;
		$result = array();
		
		$find = array('google_id' => $google_id);
		if($limit != null) $cursor = $log->find($find)->limit($limit);
		else $cursor = $log->find($find);
		
		if($sort != null){
			$cursor->sort(array($sort=>$sort_type));
		}
		foreach($cursor as $key => $value){
			$value['_id'] = (string)$value['_id'];
			$result[] =$value;
		}
		
		return Response::json($result);
	}
	
	public function getStat()
	{
		$mac = Input::get('mac');
		$limit = Input::get('limit');
		$sort = Input::get('sort');
		$sort_type = Input::get('sort_type') != null ? (int)Input::get('sort_type') : 1;

		$db = Database::Connect();
		$session = $db->session;
		$result = array();
		
		$find = array('mac' => $mac);
		if($limit != null) $cursor = $session->find($find)->limit($limit);
		else $cursor = $session->find($find);
		
		if($sort != null){
			$cursor->sort(array($sort=>$sort_type));
		}
		foreach($cursor as $key => $value){
			$value['_id'] = (string)$value['_id'];
			$value['user_id'] = (string)$value['user_id'];
			$result[] =$value;
		}
		
		return Response::json($result);
	}
	
	public function getStatDaily()
	{	
		$mac = Input::get('mac');
		$at = Input::get('at') != null ? strtotime("midnight", Input::get('at')) : strtotime("midnight", time());
		$to = Input::get('to') != null ? strtotime("tomorrow", Input::get('to'))- 1 : strtotime("tomorrow", time())- 1; 
	
		$db = Database::Connect();
		$session = $db->session;
		$result = array();
		
		$find = array('mac' => $mac,'$and'=>array(array('assoc_time'=>array('$gte'=>$at)),array('assoc_time'=>array('$lte'=>$to))));
		$cursor = $session->find($find);
		$cursor->sort(array('assoc_time'=>-1));
		
		$tmp = array();
		$stamp = strtotime("midnight", $at); 
		do{
			$tmp[$stamp]['date']=$stamp;
			$tmp[$stamp]['tx_bytes']=0;
			$tmp[$stamp]['rx_bytes']=0;
			$stamp = strtotime("tomorrow", $stamp);
		}while($stamp <= strtotime("midnight",$to));
		
		foreach($cursor as $key => $value){
			$time = strtotime("midnight", $value['assoc_time']);
			$tmp[$time]['tx_bytes'] = $tmp[$time]['tx_bytes']+$value['tx_bytes'];
			$tmp[$time]['rx_bytes'] = $tmp[$time]['rx_bytes']+$value['rx_bytes'];
		}
		
		foreach($tmp as $key => $value){
			$result[] = $value;
		}
		
		return Response::json($result);
	}
	
	public function getStatSummary()
	{	
		$type = Input::get('type');
		$data = Input::get('data');
		$db = Database::Connect();
		$stat = $db->stat;
		$result = array();
		
		$find = array($type => $data);
		$result = $stat->findOne($find);
		$result['_id']=(string)$result['_id'];
		
		return Response::json($result);
	}
	
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}