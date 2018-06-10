<?php

// database,log and properties related operations are available here
class base {
	
	//variable declaration
	public $logfile;
	public $props;
	public $ch;
	public $inifile;
	public $con;
	public $schd_exe_status = 0; // if error on query execution it will changed to 1
	
	public function __construct(){
		$this->inifile="props.ini";
		self::_Read_INI();
		self::_DB_Conn_Proc();				
	}
	
	/*********************************************************************
	*** Funtion Name  : _Read_INI
	*** Created By    : Sureshkumar S - 18-Oct-2016 08:54 AM
	*** Modified By   :
	*** Description   : read the ini file.ini file contains database connection properties
	************************************************************************/
	/************************** Start here *********************************/	
	public function _Read_INI()
	{
		$this->props = parse_ini_file($this->inifile,true);		
	}
	/************************** End here *********************************/	
	
	/*********************************************************************
	*** Funtion Name  : _DB_Conn_Proc
	*** Created By    : Sureshkumar S - 18-Oct-2016 08:54 AM
	*** Modified By   :
	*** Description   : connect the database
	************************************************************************/
	/************************** Start here *********************************/	
	private function _DB_Conn_Proc()
    {
		$this->con = mysqli_connect($this->props["db_server"],$this->props["db_userid"],$this->props["db_pass"],$this->props["db_name"],$this->props["db_port"]);
        if( !$this->con )// testing the connection
        {
            die ("Cannot connect to the database");
			
        }	
		
        return $this->con;
    }
	/************************** End here *********************************/
	
	/*********************************************************************
	*** Funtion Name  : _Exe_Query
	*** Created By    : Sureshkumar S - 18-Oct-2016 08:54 AM	
	*** Modified By   :	
	*** Description   : Execute the sql  and write log into table if enabled
	************************************************************************/
	/************************** Start here *********************************/
	public function _Exe_Query($sql)
	{		
		
		$msc=microtime(true);
		if ( mysqli_query($this->con,$sql ) === TRUE)
		{	
			$msc=microtime(true)-$msc;
			return 1;
		} 
			return 0;		
	}
	
	/*********************************************************************
	*** Funtion Name  : _Fetch_Data
	*** Created By    : Sureshkumar S - 18-Oct-2016 08:54 AM	
	*** Modified By   :	
	*** Description   : fetch data from database
	************************************************************************/
	/************************** Start here *********************************/
	public function _Fetch_Data($sql)
	{		
		$retData=array();		
		$result = mysqli_query($this->con,$sql);
		
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			
			$retData[]=$row;
		}
		
		return $retData;		
	}	
	/************************** End here ***********************************/ 
		
	/*********************************************************************
	*** Funtion Name : Query2CSV
	*** Created By   : Sureshkumar S - 22-Apr-2016 10:12 AM
	*** Modified By  : 
	*** Description  : Execute query and download result as CSV file
	************************************************************************/		
	/************************** Start here *********************************/
	public function Query2CSV($sql,$apiname)
	{
		$export = mysqli_query($this->con,$sql) or die ( "Query Error : " . $this->con->error );
		$fields = mysqli_num_fields ( $export );
		$header="";
		$i=0;
		//Column Header
		while ($fieldinfo=mysqli_fetch_field($export))
		{
			if($i!=0)
				$header.=",";
			
			$header .= $fieldinfo->name."\t";
			$i++;
		}
		
		// Column-Row values
		$data="";		
		while( $row = mysqli_fetch_row( $export ) )
		{
			$line = '';
			$i=0;
			foreach( $row as $value )
			{                                            
				if ( ( !isset( $value ) ) || ( $value == "" ) )
				{
					$value="";
				}
				else
				{
					$value = str_replace( '"' , '""' , $value );					
				}
				
				if($i!=0)
					$value=",".$value;
				
				$line .=trim($value);
				$i++;
			}
			$data .= trim( $line ) . "\n";
		}
		$data = str_replace( "\r" , "" , $data );

		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$apiname.csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		print "$header\n$data";
	}
	/************************** End here ***********************************/
	
	/*********************************************************************
	*** Funtion Name  : response
	*** Created By    : Sureshkumar S - 18-Oct-2016 08:54 AM	
	*** Modified By   :	
	*** Description   : Response will be returned in the json format
	************************************************************************/
	/************************** Start here *********************************/
	public function response($status,$message)
	{			
		$ret_data=array("status"=>"","response"=>"");
		
		$ret_data["status"]=$status;
		$ret_data["response"]=$message;
		
		echo json_encode($ret_data);
	}
	/************************** End here ***********************************/
	
	/*********************************************************************
	*** Funtion Name  : response
	*** Created By    : Sureshkumar S - 18-Oct-2016 08:54 AM	
	*** Modified By   :	
	*** Description   : Error message defined for each code. Get the message By passing the error code
	************************************************************************/
	/************************** Start here *********************************/	
	public function get_status_message($Err_code){
		$status = array(
					100 => 'Continue',  
					101 => 'Switching Protocols',  
					200 => 'OK',
					201 => 'Created',  
					202 => 'Accepted',  
					203 => 'Non-Authoritative Information',  
					204 => 'No Content',  
					205 => 'Reset Content',  
					206 => 'Partial Content',  
					300 => 'Multiple Choices',  
					301 => 'Moved Permanently',  
					302 => 'Found',  
					303 => 'See Other',  
					304 => 'Not Modified',  
					305 => 'Use Proxy',  
					306 => '(Unused)',  
					307 => 'Temporary Redirect',  
					400 => 'Bad Request',  
					401 => 'Unauthorized',  
					402 => 'Payment Required',  
					403 => 'Forbidden',  
					404 => 'Page Not Found',  
					405 => 'Method Not Allowed',  
					406 => 'Not Acceptable',  
					407 => 'Proxy Authentication Required',  
					408 => 'Request Timeout',  
					409 => 'Conflict',  
					410 => 'Gone',  
					411 => 'Length Required',  
					412 => 'Precondition Failed',  
					413 => 'Request Entity Too Large',  
					414 => 'Request-URI Too Long',  
					415 => 'Unsupported Media Type',  
					416 => 'Requested Range Not Satisfiable',  
					417 => 'Expectation Failed',  
					500 => 'Internal Server Error',  
					501 => 'Not Implemented',  
					502 => 'Bad Gateway',  
					503 => 'Service Unavailable',  
					504 => 'Gateway Timeout',  
					505 => 'HTTP Version Not Supported',
					506 => 'Invalid json data',
					507 => 'Database connection failed');
		return ($status[$Err_code])?$status[$Err_code]:$status[500];
	}
	/************************** End here ***********************************/

	/*********************************************************************
	*** Funtion Name  : update_Schd_Time
	*** Created By    : Prabhakaran M - 24-Feb-2017 12:01 AM	
	*** Modified By   :	
	*** Description   : Update Min & Max Time
	************************************************************************/
	/************************** Start here *********************************/	
	public function update_Schd_Time($table='',$time='',$where='',$column=''){
		
		if($time=="maxtime")
			$subquery="time2=(select max(".$column.") from ".$table.")";
		else
			$subquery="time1=time2";
				
		$query_String="update gpi_schedule_timestamp set ".$subquery." where tablename='".$where."' ";
		
		$ret=self::_Exe_Query($table,"updatescheduletime|| $time -> $table",$query_String);	
		
	}
	/************************** End here ***********************************/

	/*********************************************************************
	*** Funtion Name  : _Get_MixMax
	*** Created By    : Prabhakaran M - 24-Feb-2017 12:01 AM	
	*** Modified By   :	
	*** Description   : Update Min & Max Time
	************************************************************************/
	/************************** Start here *********************************/	
	public function _Get_MixMax($table=''){
		
		$retdata=array("mintime"=>"0000-00-00","maxtime"=>"0000-00-00");
		
		$query_String="select time1 as mintime,time2 as maxtime from gpi_schedule_timestamp where tablename='".$table."'";
		$time =self::_Fetch_Data($query_String);
		
		$retdata["mintime"]=$time[0]["mintime"];
		$retdata["maxtime"]=$time[0]["maxtime"];
		
		return $retdata;
		
	}
	/************************** End here ***********************************/
	
	public function write_Log($module,$msg){
			
		$path="";
		if($this->logfile==""){
			
			$path=$this->props['api_log'].date('Y-m-d');
			
			if(!file_exists($path) && !is_dir($path)){
				if (!mkdir($path, 0777, true)) 	die('Failed to create folders...');
			}
			
			$this->logfile=$path."/".$module."_".date('Y_m_d_h_i_s').".txt";
		}
		
		file_put_contents($this->logfile,"[".date('Y-m-d h:i:s')."]-->".$msg."\n",FILE_APPEND);
		
	}
	
}

?>
