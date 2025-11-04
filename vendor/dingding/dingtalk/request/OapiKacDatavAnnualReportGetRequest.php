<?php
/**
 * dingtalk API: dingtalk.oapi.kac.datav.annual_report.get request
 * 
 * @author auto create
 * @since 1.0, 2021.01.08
 */
class OapiKacDatavAnnualReportGetRequest
{
	/** 
	 * 部门id，当type=2时该参数需存在
	 **/
	private $deptId;
	
	/** 
	 * 获取数据年报类型，1-企业纬度，2-部门纬度，3-员工纬度（员工纬度的数据需要额外授权，请联系对接BD)
	 **/
	private $type;
	
	/** 
	 * 用户id，当type=3时该参数需存在
	 **/
	private $userId;
	
	/** 
	 * 年份标识
	 **/
	private $year;
	
	private $apiParas = array();
	
	public function setDeptId($deptId)
	{
		$this->deptId = $deptId;
		$this->apiParas["dept_id"] = $deptId;
	}

	public function getDeptId()
	{
		return $this->deptId;
	}

	public function setType($type)
	{
		$this->type = $type;
		$this->apiParas["type"] = $type;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setUserId($userId)
	{
		$this->userId = $userId;
		$this->apiParas["user_id"] = $userId;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function setYear($year)
	{
		$this->year = $year;
		$this->apiParas["year"] = $year;
	}

	public function getYear()
	{
		return $this->year;
	}

	public function getApiMethodName()
	{
		return "dingtalk.oapi.kac.datav.annual_report.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->type,"type");
		RequestCheckUtil::checkMaxLength($this->userId,128,"userId");
		RequestCheckUtil::checkNotNull($this->year,"year");
		RequestCheckUtil::checkMaxLength($this->year,32,"year");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
