<?php
class ReportAction extends Action {
	public function agency()
	{
		$postData = file_get_contents("php://input");
		$data = json_decode($postData, true);
		echo $data['name'];
		mysql_query("set names utf8;");
		$agency = M('agency');
		$result = $agency->add($data);
		if (!$result)
		{
			$result = $agency->where(array('game_uid'=>$data['game_uid']))->save($data);
			echo $result;
		}
	}
}
