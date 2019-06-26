<?php


Class HomeModel extends CI_Model{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}	

	 
function cityAll()
 {
	$this->db->order_by('city');
	 $query=$this->db->get('tb_city');

	   
	 	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
 
 public function liveSeach($search){
  
  $query = $this->db->query("SELECT DISTINCT(title),title,id FROM `tb_job` WHERE `title` LIKE '%".$search."%' LIMIT 10");
  return $query->result();
 }

function checkActive()
 {
$this -> db -> where('flag', '1');
	$this -> db -> where('regId',$_SESSION['id']);
	
	 $query=$this->db->get('tb_seeker');
	
	   
	 	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
 function getCategory()
 {
	
	 $query=$this->db->get('tb_category');
	   
	 	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
		
	function get_checkinviteemail($email)
	{
		$this -> db -> select('id');
		$this -> db -> from('tb_reg');
		$this -> db -> where('emailId', $email);
		$query = $this -> db -> get();
		if($query -> num_rows() > 0)
		{
			return 1;
		}
		else
		{
			$this -> db -> select('id');
			$this -> db -> from('tbinvite');
			$this -> db -> where('email', $email);
			$query2 = $this -> db -> get();
			if($query2 -> num_rows() > 0)
			{
				return 2;
			}else
			{
				return 0;
			}
		}
	}
	
	
	
	
	function crateNewUser($ContactPresonName,$email,$contactno)
	{
		
		$this -> db -> select('id');
		$this -> db -> from('tb_reg');
		$this -> db -> where('emailId', $email);
		$this->db->or_where('mobile',$contactno); 
		$this -> db -> limit(1);
		$query = $this -> db -> get();
		if($query -> num_rows() == 1)
		{
			foreach($query->result() as $val)
			{
				$result = $val->id;
			}
		}
		else
		{
			$data = array(
				'username' => $ContactPresonName,
				'emailId' => $email,
				'userGroup' => 'employer',
				'mobile' =>$contactno
				);
			   $this->db->insert('tb_reg',$data);
			 $result = $this->db->insert_id();
		}
		return $result ;
		
	}  
	function AddCompanyDetals($CompanyName,$jobtitle,$ContactPresonName,$email,$locationID,$jobtype,$contactno,$userid)
		{
		$this -> db -> select('company_id');
		$this -> db -> from('tb_company');
		$this -> db -> where('regId', $userid);
	    $this -> db -> limit(1);
		$query = $this -> db -> get();
		if($query -> num_rows() == 1)
		{
			foreach($query->result() as $val)
			{
				$result = $val->company_id;
			}
		}
		else
		{
		       $data1 = array(
					'name' => $CompanyName,
					'person' => $ContactPresonName,
					'email' => $email,
					'city_id' => $locationID,
					'contact' => $contactno,
					'regId' => $userid
				 );
				 $this->db->insert('tb_company',$data1);
				$result = $this->db->insert_id();
						
		  }
		  return $result ;
		}
	
	
	
	function SaveJobPost($CompanyName,$jobtitle,$ContactPresonName,$email,$locationID,$jobtype,$contactno,$salary,$vacancies,$education,$experience,$skils,$description,$userid,$comanyId)
	{
		 $data = array(
                'title' => $jobtitle,
                'location' => $locationID,
                'experience' => $experience,
                'education' => $education,
                'skill' => $skils,
                'type' => $jobtype,
                'salary' => $salary,
                'vacancies' => $vacancies,
                'desc' => $description,
                'regId' => $userid,
                'flag' => 0
              );
			  
			  $this->db->insert('tb_job',$data);
			  $jobID = $this->db->insert_id();
			  return  $jobID;
	}
	
	function checkcat()
	{
		
		$this -> db -> select('mobile,emailId');
		$this -> db -> from('tb_reg');
		$this -> db -> where('mobile', $this->input->post('Entermob'));
		$this->db->or_where('emailId',$this->input->post('email')); 
		$this -> db -> limit(1);
		$query = $this -> db -> get();
		$result=$query->result();
	//	echo count($result);
		//$query=$this->db->query("select *from tb_reg where (mobile='$uname' OR emailId='$uname') and  password='$pass'")->result(); 
		if($query -> num_rows() == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
		function checkcat1($jobId,$regId)
	    {
		$this -> db -> select('*');
		$this -> db -> from('tb_appliedjob');
		$this -> db -> where('jobId', $jobId);
		$this->db->where('regId',$regId); 
		$this -> db -> limit(1);
		$query = $this -> db -> get();
		if($query -> num_rows() == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function checkcat10($regId,$companyId)
	{
		
		$this -> db -> select('*');
		$this -> db -> from('tb_hire');
		$this -> db -> where('companyId',$companyId);
		$this->db->where('regId',$regId); 
		$this -> db -> limit(1);
		$query = $this -> db -> get();
		if($query -> num_rows() == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function insertnew()
	{
		
		$refferedBy1=$this->input->post('refferedBy');
		$date=date('Y/m/d');
		$refferedBy=str_replace('GOINDIAID-', '', $refferedBy1);
		$pin = mt_rand(1000, 9999);
	
           $data = array(
				'username' => $this->input->post('cperson'),
				'emailId' => $this->input->post('email'),
				'userGroup' => 'seeker',
				'password' => $this->input->post('contactno'),
				'mobile' => $this->input->post('contactno'),
				'date'=>$date
                
          	   );
			$name= $this->input->post('cperson');
			
			$query = $this->db->insert('tb_reg',$data);
			$insert_iduser = $this->db->insert_id();
			$data1 = array(
				'name'=>$name,
				'emailId' => $this->input->post('email'),
				'refferedBy' => $refferedBy,
				'city' => $this->input->post('city'),
				'mobile' => $this->input->post('contactno'),
				'regId' => $insert_iduser,
				
			 );				
			
			$insert_id = $this->db->insert('tb_seeker',$data1);

			//$verify_code = random_string('numeric',5);
			$verify_code = $insert_id + 9999;

			$mobile1 = $this->input->post('contactno');
			$mobile = '+91'.$mobile1;
            $_SESSION['otp_id']=$insert_iduser;
			$_SESSION['resent_mobile']=$mobile;
			$xml_data ='<?xml version="1.0"?>
				  <parent>
						<child>
							<user>Advision</user>
							<key>f8514eb017XX</key>
							<mobile>'.$mobile.'</mobile>
							<message>Welcome to goindiaearn! Your verification code is '.$verify_code.'. Login to www.goindiaearn.com to activate your account.</message>
							<accusage>1</accusage>
							 <senderid>GOINDI</senderid>
						</child>
						
					</parent> 
				';
				$URL = "http://103.233.79.246//submitsms.jsp?";		 
				
				$ch = curl_init($URL);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
				curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				curl_close($ch);		
				
				$OTP = array( 
						'uid'  		=> $insert_iduser,
						'otp'  => $verify_code,
						'generated_date'  => date("Y-m-d H:i:s")
						);	

	 		 //print_r ($OTP);exit;					
			$this->db->select('id');
			$this->db->where('uid',$insert_iduser);
			$this->db->from('tb_login_otp');			 
			$fetch1  = $this->db->get();	
			if($fetch1->num_rows()>0)
			{  
				$this->db->where('uid',$insert_iduser);
				$query = $this->db->update('tb_login_otp',$OTP);
			}
			else
			{ 
				
				$query = $this->db->insert('tb_login_otp',$OTP);
				$insertid=$this->db->insert_id();
				 
			 } 


			return $insert_iduser;
			
	}

	
	
	function insertnewJobPost()
	{
		
		$refferedBy1=$this->input->post('refferedBy');
		$date=date('Y/m/d');
		$refferedBy=str_replace('GOINDIAID-', '', $refferedBy1);
		$pin = mt_rand(1000, 9999);
	
           $data = array(
				'username' => $this->input->post('cperson'),
				'emailId' => $this->input->post('email'),
				'userGroup' => 'employer',
				'password' => $this->input->post('contactno'),
				'mobile' => $this->input->post('contactno'),
				'id'=>$pin,
				'date'=>$date
                
            );
			$name= $this->input->post('cperson');
			
			$query = $this->db->insert('tb_reg',$data);
			
			echo $insert_iduser = $this->db->insert_id();
			
			die;
			
			$data1 = array(
				'name'=>$name,
				'emailId' => $this->input->post('email'),
				'refferedBy' => $refferedBy,
				'city' => $this->input->post('city'),
				'mobile' => $this->input->post('contactno'),
				'regId' => $insert_iduser,
				
			 );				
			
			$insert_id = $this->db->insert('tb_seeker',$data1);

			//$verify_code = random_string('numeric',5);
			$verify_code = $insert_id + 9999;

			$mobile1 = $this->input->post('contactno');
			$mobile = '+91'.$mobile1;

			$xml_data ='<?xml version="1.0"?>
				  <parent>
						<child>
							<user>Advision</user>
							<key>f8514eb017XX</key>
							<mobile>'.$mobile.'</mobile>
							<message>Welcome to goindiaearn! Your verification code is '.$verify_code.'. Login to www.goindiaearn.com to activate your account.</message>
							<accusage>1</accusage>
							 <senderid>GOINDI</senderid>
						</child>
						
					</parent> 
				';
				$URL = "http://103.233.79.246//submitsms.jsp?";		 
				
				$ch = curl_init($URL);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
				curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				curl_close($ch);		
				
				$OTP = array( 
						'uid'  		=> $insert_id,
						'otp'  => $verify_code,
						'generated_date'  => date("Y-m-d H:i:s")
						);	

	 		 //print_r ($OTP);exit;					
			$this->db->select('id');
			$this->db->where('uid',$insert_id);
			$this->db->from('tb_login_otp');			 
			$fetch1  = $this->db->get();	
			if($fetch1->num_rows()>0)
			{  
				$this->db->where('uid',$insert_iduser);
				$query = $this->db->update('tb_login_otp',$OTP);
			}
			else
			{ 
				
				$query = $this->db->insert('tb_login_otp',$OTP);
				$insertid=$this->db->insert_id();
				 
			 } 


			return $insert_iduser;
			
	}
	
	
	
	
	
	
	
	
	
	
	function insert_invitefriend($data)
	{
		$query = $this->db->insert('tbinvite',$data);
		$insertid=$this->db->insert_id();
		return $insertid;
	}

	function resendotpagain($id)
	{
		//$verify_code = random_string('numeric',5);
			$verify_code = $id + 9989;
			
			$this->db->select('mobile');
			$this->db->where('id',$id);
			$this->db->from('tb_reg'); 
			$fetch12  = $this->db->get();
			$insert_id = $id;

			foreach($fetch12->result() as $row2)
			{
				$mobile1 = $row2->mobile;
				$mobile = '+91'.$mobile1;
			}

			

			$xml_data ='<?xml version="1.0"?>
				  <parent>
					<child>
						<user>Advision</user>
						<key>f8514eb017XX</key>
						<mobile>'.$mobile.'</mobile>
						<message>Welcome to goindiaearn! Your verification code is '.$verify_code.'. Login to www.goindiaearn.com to activate your account.</message>
						<accusage>1</accusage>
						 <senderid>GOINDI</senderid>
					</child>
                 </parent>';
				$URL = "http://103.233.79.246//submitsms.jsp?";		 
				
				$ch = curl_init($URL);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
				curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				curl_close($ch);		
				
				$OTP = array( 
						'uid'  		=> $insert_id,
						'otp'  => $verify_code,
						'generated_date'  => date("Y-m-d H:i:s")
						);	

	 		 //print_r ($OTP);exit;					
			$this->db->select('id');
			$this->db->where('uid',$insert_id);
			$this->db->from('tb_login_otp');			 
			$fetch1  = $this->db->get();	
			if($fetch1->num_rows()>0)
			{  
				$this->db->where('uid',$uid);
				$query = $this->db->update('tb_login_otp',$OTP);
			}
			else
			{ 
				
				$query = $this->db->insert('tb_login_otp',$OTP);
				$insertid=$this->db->insert_id();
				 
			 } 

		return 1;	
	}

		
	function insert()
	{
		
		$refferedBy1=$this->input->post('refferedBy');
		$date=date('Y/m/d');
		$refferedBy=str_replace('GOINDIAID-', '', $refferedBy1);
		$pin = mt_rand(1000, 9999);
	
               $data = array(
				'username' => $this->input->post('username'),
				'emailId' => $this->input->post('email'),
				'userGroup' => 'seeker',
				'password' => $this->input->post('password'),
				'mobile' => $this->input->post('Entermob'),
				'status'=>'no',
				'date'=>$date);
			$name= $this->input->post('username')." ".$this->input->post('lastname');
			
			$query = $this->db->insert('tb_reg',$data);
			$insert_id = $this->db->insert_id();
			$data1 = array(
				'name'=>$name,
				'emailId' => $this->input->post('email'),
				'refferedBy' => $refferedBy,
				'city' => $this->input->post('city'),
				'mobile' => $this->input->post('Entermob'),
				'regId' => $insert_id,
				
			 );				
			
			$this->db->insert('tb_seeker',$data1);

			//$verify_code = random_string('numeric',5);
			$verify_code = $insert_id + 9999;

			$mobile1 = $this->input->post('Entermob');
			$mobile = '+91'.$mobile1;
            $_SESSION['otp_id']=$insert_id;
		    $_SESSION['resent_mobile']=$mobile;
			/*$xml_data ='<?xml version="1.0"?>
				  <parent>
						<child>
							<user>Advision</user>
							<key>f8514eb017XX</key>
							<mobile>'.$mobile.'</mobile>
							<message>Welcome to goindiaearn! Your verification code is '.$verify_code.'. Login to www.goindiaearn.com to activate your account.</message>
							<accusage>1</accusage>
							 <senderid>GOINDI</senderid>
						</child>
                     </parent> 
				';
				$URL = "http://103.233.79.246//submitsms.jsp?";		 
				
				$ch = curl_init($URL);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
				curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				curl_close($ch);		
				
				$OTP = array( 
						'uid'  		=> $insert_id,
						'otp'  => $verify_code,
						'generated_date'  => date("Y-m-d H:i:s")
						);	

	 		 //print_r ($OTP);exit;					
			$this->db->select('id');
			$this->db->where('uid',$insert_id);
			$this->db->from('tb_login_otp');			 
			$fetch1  = $this->db->get();	
			if($fetch1->num_rows()>0)
			{  
				$this->db->where('uid',$uid);
				$query = $this->db->update('tb_login_otp',$OTP);
			}
			else
			{ 
				
				$query = $this->db->insert('tb_login_otp',$OTP);
				$insertid=$this->db->insert_id();
				 
			 } */


			return $insert_id;
			
	}
	
	function getinvitedfriends($id)
	{
		$this->db->select('*');
		$this->db->where('userid',$id);
		$this->db->from('tbinvite');			 
		$fetch1  = $this->db->get();
		
		$i=0;
		if($fetch1->num_rows() > 0)
		{	
			foreach($fetch1->result() as $row)
			{
				$data[$i]['name'] = $row->name;
				$data[$i]['contactno'] = $row->mobile;
				$data[$i]['email'] = $row->email;
				
				//Check Status 
				$this->db->select('*');
				//$this->db->where('mobile',$row->mobile);
			
				$this->db->or_where('mobile',$row->mobile);
				$this->db->or_where('emailId',$row->email);
				$this->db->from('tb_reg');			 
				$fetch2  = $this->db->get();
				if($fetch2->num_rows() > 0)
				{
					$data[$i]['status'] = 'Registered';
				}else
				{
					$data[$i]['status'] = 'Pending';	
				}			
				
				
				$i++;
			}
		}else
		{
			$data = array();
		}
		
		
		return $data;
	}
		
	function otpcheck($otptxt)
	{
			$this->db->select('id');
			$this->db->where('otp',$otptxt);
			$this->db->from('tb_login_otp');
			$this->db->order_by('id','desc');			 
			$fetch1  = $this->db->get();	
			if($fetch1->num_rows()>0)
			{  
				$OTP = array( 						
						'generated_date'  => date("Y-m-d H:i:s"),
						'flag'  		=> 1
						);
			  $this->db->query("delete from tb_login_otp where uid='".$_SESSION['otp_id']."'");
			  unset($_SESSION['otp_id']);
			  unset($_SESSION['resent_mobile']);
				$this->db->where('otp',$otptxt);
				$query = $this->db->update('tb_login_otp',$OTP);
				return 1;
			}
			else
			{ 
				return 0;							 
			}
	}

	function addfeedback()
	{
		
		
			
			$id=$_SESSION['id'];
			$date=date('Y-m-d');
          
			   $data = array(
                		'title' => $this->input->post('InputTitle'),
				'feedback' => $this->input->post('Inputfeedback'),
				'regId' => $id,
				'date' => $date,
				
                
            );
			
			$query = $this->db->insert('tb_feedback',$data);
			
		
			
	}
	
		function addaccount()
	{
		
		
			
			  $regId=$_SESSION['id'];
			   $data = array(
                'name' => $this->input->post('name'),
				'bank' => $this->input->post('bank'),
				   'acc' => $this->input->post('acc'),
				      'ifsc' => $this->input->post('ifsc'),
				      'regId' => $regId,
				
                
            );
				
                
            	
			$query = $this->db->insert('tb_account',$data);
			
		
			
	}
	function insertJob($jobId,$regId)
	{
		
		
			
			$date=date('Y/m/d');
          
			   $data = array(
                'jobId' => $jobId,
				'regId' => $regId,
				'date' => $date,
				
				
                
            );
			
			$query = $this->db->insert('tb_appliedjob',$data);
			
		
			
	}
	function insertJob1($regId,$companyId)
	{
		
		
			
			
          
			   $data = array(
                'companyId' => $companyId,
				'regId' => $regId,
				
				
                
            );
			
			$query = $this->db->insert('tb_hire',$data);
			
		
			
	}
	function addEnq($data)
	{
		
		
			
		$query = $this->db->insert('tb_enquiry',$data);
			
		
			
	}
	function insert1()
	{
		
		$date=date('Y/m/d');
		$pin1 = mt_rand(1000, 9999);	
	
           $data = array(
				'username' => $this->input->post('companyName'),
				'emailId' => $this->input->post('email'),
				'userGroup' => 'employer',
				'password' => $this->input->post('password'),
				'date'=>$date,
				'id'=>$pin1,	
			 );
			
			
			$query = $this->db->insert('tb_reg',$data);
			$insert_id = $this->db->insert_id();
			$data1 = array(
				'name' => $this->input->post('companyName'),
				'person' => $this->input->post('contactPerson'),
				'email' => $this->input->post('email'),
				'city_id' => $this->input->post('city'),
				'contact' => $this->input->post('Entermob'),
				'regId' => $pin1,
			 );
				
			
				$query1 = $this->db->insert('tb_company',$data1);
			
	}
	function getProfile()
		{
			$id=$_SESSION['id'];
			$this->db->select('name,tb_seeker.emailId,gender,tb_seeker.jobp as jobp1,tb_seeker.mobile,birthDate,tb_seeker.exp as exp1,tb_exp.experience,tb_seeker.salary as salary1,tb_salary.salary,tb_seeker.edu as edu1,tb_edu.education,tb_seeker.country as country2,tb_seeker.state as state2,tb_state.state as state1,pincode,jobp,pref,language,workArea,skill,photo,tb_seeker.city as city2,tb_city.city as city1,tb_country.country as country1');
			$this->db->where('tb_reg.id',$id);
			$this->db->from('tb_reg');
			$this->db->join('tb_seeker','tb_seeker.regId = tb_reg.id');
			$this->db->join('tb_city','tb_seeker.city = tb_city.city_id','left');
			$this->db->join('tb_country','tb_seeker.country = tb_country.country_id','left');
			$this->db->join('tb_state','tb_seeker.state = tb_state.state_id','left');
			$this->db->join('tb_exp','tb_seeker.exp = tb_exp.id','left');
			$this->db->join('tb_edu','tb_seeker.edu = tb_edu.id','left');
			$this->db->join('tb_salary','tb_seeker.salary = tb_salary.id','left');
			$this->db->join('tb_category','tb_seeker.jobp = tb_category.category_id','left');
			//print_r($this->db->last_query());exit;
		$fetch  = $this->db->get();
			
			return $fetch;
			
		}
		
		/*function getProfileview($regid)
		{
			$id=$regid;
			$this->db->select('name,tb_seeker.emailId,gender,tb_seeker.jobp as jobp1,tb_seeker.mobile,birthDate,tb_seeker.exp as exp1,tb_exp.experience,tb_seeker.salary as salary1,tb_salary.salary,tb_seeker.edu as edu1,tb_edu.education,tb_seeker.country as country2,tb_seeker.state as state2,tb_state.state as state1,pincode,jobp,pref,language,workArea,skill,photo,tb_seeker.city as city2,tb_city.city as city1,tb_country.country as country1');
			$this->db->where('tb_reg.id',$id);
			$this->db->from('tb_reg');
			$this->db->join('tb_seeker','tb_seeker.regId = tb_reg.id');
			$this->db->join('tb_city','tb_seeker.city = tb_city.city_id','left');
			$this->db->join('tb_country','tb_seeker.country = tb_country.country_id','left');
			$this->db->join('tb_state','tb_seeker.state = tb_state.state_id','left');
			$this->db->join('tb_exp','tb_seeker.exp = tb_exp.id','left');
			$this->db->join('tb_edu','tb_seeker.edu = tb_edu.id','left');
			$this->db->join('tb_salary','tb_seeker.salary = tb_salary.id','left');
			$this->db->join('tb_category','tb_seeker.jobp = tb_category.category_id','left');
			//print_r($this->db->last_query());exit;
		$fetch  = $this->db->get();
			
			return $fetch;
			
		}*/
		function getProfileview($regid)
		{
			$id=$regid;
			$this->db->select('tb_seeker.*,tb_seeker.language as lang123,tb_seeker.job_profile_details,tb_seeker.job_responsiblities,tb_seeker.edu,tb_seeker.exp,name,tb_seeker.emailId,gender,tb_seeker.jobp as jobp1,tb_seeker.mobile,birthDate,tb_seeker.exp as exp1,tb_exp.experience,tb_seeker.salary as salary1,tb_salary.salary,tb_seeker.edu as edu1,tb_edu.education,tb_seeker.country as country2,tb_seeker.state as state2,tb_state.state as state1,pincode,jobp,pref,language,workArea,skill,photo,tb_seeker.city as city2,tb_city.city as city1,tb_country.country as country1');
			$this->db->where('tb_reg.id',$id);
			$this->db->from('tb_reg');
			$this->db->join('tb_seeker','tb_seeker.regId = tb_reg.id');
			$this->db->join('tb_city','tb_seeker.city = tb_city.city_id','left');
			$this->db->join('tb_country','tb_seeker.country = tb_country.country_id','left');
			$this->db->join('tb_state','tb_seeker.state = tb_state.state_id','left');
			$this->db->join('tb_exp','tb_seeker.exp = tb_exp.id','left');
			$this->db->join('tb_edu','tb_seeker.edu = tb_edu.id','left');
			$this->db->join('tb_salary','tb_seeker.salary = tb_salary.id','left');
			$this->db->join('tb_category','tb_seeker.jobp = tb_category.category_id','left');
			//print_r($this->db->last_query());exit;
		    $fetch  = $this->db->get();
			
			return $fetch;
			
		}
		
		function getProfile1()
		{
			$id=$_SESSION['id'];
			$this->db->select('name,person,address,website,landline,desc,tb_company.email,contact,tb_company.state_id as state2,tb_state.state as state1,pincode,pic,tb_company.city_id as city2,tb_city.city as city1');
			$this->db->where('tb_reg.id',$id);
			$this->db->from('tb_reg');
			$this->db->join('tb_company','tb_company.regId = tb_reg.id');
			$this->db->join('tb_city','tb_company.city_id = tb_city.city_id','left');
			$this->db->join('tb_state','tb_company.state_id = tb_state.state_id','left');
			//print_r($this->db->last_query());exit;
		$fetch  = $this->db->get();
			
			return $fetch;
			
		}
		function update()
	{

	$data = array(
				 'name' => $this->input->post('InputName'),
				'emailId' => $this->input->post('InputEmail'),
				'gender' => $this->input->post('gendermale'),
				'mobile' => $this->input->post('InputPhone'),
				'birthDate' => $this->input->post('InputBdate'),
				'country' => $this->input->post('InputCountry'),
				'state' => $this->input->post('InputState'),
				'pincode' => $this->input->post('InputPincode'),
				'city' => $this->input->post('InputCity'),
				);
           
				$id =$_SESSION['id'];
			if($id!=0)
			{
				$this->db->where('regId',$id);
				$query = $this->db->update('tb_seeker',$data);
				 
			}
			else
			{
				$query = $this->db->insert('tb_seeker',$data);
			}
	}
function resetpassword($id,$pass)
	{

	$data = array(
				 'password' => $pass,
				
				);
           
				
			if($id!=0)
			{
				$this->db->where('id',$id);
				$query = $this->db->update('tb_reg',$data);
				 
			}
			
	}
function mailstatus($id)
	{

	
				$id =$id;
				$data = array(
				 'status' =>'yes',
				
				);
				$this->db->where('id',$id);
				$query = $this->db->update('tb_reg',$data);
				 
			
			
	}

	function otpstatus()
	{
		$id=$_SESSION['id'];
		$this->db->select('*');
		$this->db->from('tb_login_otp');
		$this->db->where('uid',$id);
		$this->db->where('flag',1);
		$query  = $this->db->get();
		if($query->num_rows()>0)
		{
			return 1;
		}else
		{
			return 0;	
		}
		

	}

	function updateC($data)
	{
 
		$id =$_SESSION['id'];	
		$this->db->select('regId');
		$this->db->where('regId',$id);
		$this->db->from('tb_company'); 
		$fetch  = $this->db->get();
       // print_r($data); echo "in model save project"; exit;
		if($fetch->num_rows()>0)
		{
			$this->db->where('regId',$id);
			$query = $this->db->update('tb_company',$data);
			
		}
		else
		{
			$query = $this->db->insert('tb_company',$data);
			$id=$this->db->insert_id();
		}
 
			
		
	} 
	function updateC1($data,$data1)
	{
 
		$id =$_SESSION['id'];	
		$this->db->select('regId');
		$this->db->where('regId',$id);
		$this->db->from('tb_other'); 
		$fetch  = $this->db->get();
      if($fetch->num_rows()>0)
		{
			$this->db->where('regId',$id);
			$query = $this->db->update('tb_other',$data);
			
			$this->db->where('id',$id);
			$query = $this->db->update('tb_reg',$data1);
			
		}
		else
		{
			$query = $this->db->insert('tb_other',$data);
			$id=$this->db->insert_id();
		}
 
			
		
	} 
		function update1()
	{

	$data = array(
				 'pref' => $this->input->post('worktype'),
				'edu' => $this->input->post('edu'),
				'exp' => $this->input->post('exp'),
				'salary' => $this->input->post('salary'),
				'language' => $this->input->post('lang'),
				'jobp' => $this->input->post('job'),
				
				);
           
				$id =$_SESSION['id'];
			if($id!=0)
			{
				$this->db->where('regId',$id);
				$query = $this->db->update('tb_seeker',$data);
				 
			}
			else
			{
				$query = $this->db->insert('tb_seeker',$data);
			}
	}
	function update12()
	{
	$category=$this->input->post('otherprofile');
	if($category!="")
	{
	$data1 = array(
				 'category' => $this->input->post('otherprofile'),
				
				
				);
	$query1 = $this->db->insert('tb_category',$data1);
	}
	else
	{
	$category=$this->input->post('job');
	}
	
	$exp=$this->input->post('otherexp');
	if($exp!="")
	{
	$data10 = array(
				 'experience' => $this->input->post('otherexp'),
				
				
				);
	$query10 = $this->db->insert('tb_exp',$data10);
	$exp=$this->db->insert_id();
	
	}
	else
	{
	$exp=$this->input->post('exp');
	}


	$edu=$this->input->post('otheredu');
	if($edu!="")
	{
	$data101 = array(
				 'education' => $this->input->post('otheredu'),
				
				
				);
	$query101 = $this->db->insert('tb_edu',$data101);
	$edu=$this->db->insert_id();
	
	}
	else
	{
	$edu=$this->input->post('edu');
	}


		$salary=$this->input->post('othersalary');
	if($salary!="")
	{
	$data10111 = array(
				 'salary' => $this->input->post('othersalary'),
				
				
				);
	$query10111 = $this->db->insert('tb_salary',$data10111);
	$salary=$this->db->insert_id();
	
	}
	else
	{
	$salary=$this->input->post('salary');
	}



	
	$data = array(
				 'pref' => $this->input->post('worktype'),
				'edu' => $edu,
				'exp' => $exp,
				'salary' => $salary,
				'language' => $this->input->post('lang'),
				'jobp' => $category,
				
				);
           
				$id =$_SESSION['id'];
			if($id!=0)
			{
				$this->db->where('regId',$id);
				$query = $this->db->update('tb_seeker',$data);
				 
			}
			else
			{
				$query = $this->db->insert('tb_seeker',$data);
			}

	}
	function update2($data)
	{
 
           
				$id =$_SESSION['id'];
			if($id!=0)
			{
				$this->db->where('regId',$id);
				$query = $this->db->update('tb_seeker',$data);
				 
			}
			else
			{
				$query = $this->db->insert('tb_seeker',$data);
			}
	}
	function categoryAll()
 {
	
	 $query=$this->db->get('tb_category');
	   
	 	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
 function skillAll()
 {
	
	 $query=$this->db->get('tb_skill');
	   
	 	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
 function jobtypeAll()
 {
	
	 $query=$this->db->get('tb_jobtype');
	   
	 	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
 function countrygetAll()
 {
	$this->db->order_by('country');
	
	 $query=$this->db->get('tb_country');

	   
	 	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
 	function expAll()
 {
	$this->db->order_by('id');
	
	 $query=$this->db->get('tb_exp');

	   
	 	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
 function eduAll()
 {
	$this->db->order_by('id','asc');
	
	 $query=$this->db->get('tb_edu');
	 	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
 function salaryAll()
 {
	$this->db->order_by('id','asc');
	
	 $query=$this->db->get('tb_salary');
	  	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }

	function getuserdetailsformail($id)
	{
		$this->db->select('*');
		$this->db->where('id',$id);
		$this->db->from('tb_reg');
			
		$fetch1  = $this->db->get();
		return $fetch1;
	}

 function getcount()
 {
	// echo "Hii";
			$this->db->select('count(*) as count1');
			$this->db->where('tb_reg.userGroup','seeker');
			$this->db->from('tb_reg');
			
			$fetch1  = $this->db->get();
			return $fetch1;
	
	
 }
 function getcounte()
 {
	// echo "Hii";
			$this->db->select('count(*) as count1');
			$this->db->where('tb_reg.userGroup','employer');
			$this->db->from('tb_reg');
			
			$fetch1  = $this->db->get();
			return $fetch1;
	
	
 }
  function get_cms($id)
 {
	
			$this->db->select('*');
			$this->db->where('id',$id);
			//$this->db->or_where("pg_title LIKE '%".$id."'");
			$this->db->from('cmspages');
			$fetch1  = $this->db->get();
			return $fetch1;
	
	
 }
 function countp()
 {
	// echo "Hii";
			$this->db->select('count(*) as count30');
			$this->db->from('tb_job');
			$this->db->join('tb_appliedjob','tb_appliedjob.jobId = tb_job.id');
			$this->db->where('tb_job.type','4');
			
			
			$fetch1  = $this->db->get();
			return $fetch1;
	
	
 }
 function getbanner()
 {
	// echo "Hii";
			$this->db->select('id,bnr_caption,file_name');
			$this->db->where('flag','0');
			$this->db->from('tb_banners');
			
			$fetch1  = $this->db->get();
			return $fetch1;
	
	
 }
 function gettest()
 {
	// echo "Hii";
			$this->db->select('*');
			$this->db->where('flag','0');
			$this->db->from('tb_testimonial');
			
			$fetch1  = $this->db->get();
			return $fetch1;
	
	
 }
 function getadv()
 {
	// echo "Hii";
			$this->db->select('*');
			$this->db->where('flag','0');
			$this->db->from('tb_adv');
			
			$fetch1  = $this->db->get();
			return $fetch1;
	
	
 }
 function getadv1()
 {
	
			$this->db->select('*');
			$this->db->from('tb_adv1');
			
			$fetch1  = $this->db->get();
			return $fetch1;
	
	
 }
 function getcountj()
 {
	// echo "Hii";
			$this->db->select('count(*) as count1');
			$this->db->from('tb_job');
			
			$fetch1  = $this->db->get();
			return $fetch1;
	
	
 }
 function citygetAll()
 {
	$this->db->order_by('city');
	
	 $query=$this->db->get('tb_city');
	 	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
	
	function stategetAll()
 {
	$this->db->order_by('state');
	
	 $query=$this->db->get('tb_state');
	   	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }

	
	function postjob_insert0new($regId)
	{
		$id=$this->input->post('id');
		$regId=$regId;
		$date=date('Y-m-d');
		 $skill1= $this->input->post('skill');
 $othersalary= $this->input->post('othersalary');
 $otherarea= $this->input->post('otherarea');
$otheredu= $this->input->post('otheredu');
 $otherexp= $this->input->post('otherexp');
	if($othersalary!="")
{
		$salary= $this->input->post('othersalary');
		$data10 = array(
					
				'salary' => $this->input->post('othersalary'),
				
				);
		$query10 = $this->db->insert('tb_salary',$data10);
		$salary=$this->db->insert_id();
}
else
{
$salary= $this->input->post('salary');
}
if($otheredu!="")
{
		$education= $this->input->post('otheredu');
		$data106 = array(
					
				'education' => $this->input->post('otheredu'),
				
				);
		$query106 = $this->db->insert('tb_edu',$data106);
		$education=$this->db->insert_id();
}
else
{
$education= $this->input->post('education');
}
if($otherexp!="")
{
		$experience= $this->input->post('otherexp');
		$data1060 = array(
					
				'experience' => $this->input->post('otherexp'),
				
				);
		$query1060 = $this->db->insert('tb_exp',$data1060);
		$experience=$this->db->insert_id();
}
else
{
$experience= $this->input->post('experience');
}		
	if($otherarea!="")
{
		$city= $this->input->post('otherarea');
		$data101 = array(
					
				'city' => $this->input->post('otherarea'),
				
				);
		$query101 = $this->db->insert('tb_city',$data101);
		$city=$this->db->insert_id();
}
else
{
$city= $this->input->post('location');
}
	
	

           
				$data = array(
					'title' => $this->input->post('title'),
				'location' => $city,
				 'experience' =>$experience,
				  'education' => $education,
				'skill'=>implode(",", $skill1),
				'type' => $this->input->post('type'),
				'salary' => $salary,
				'desc' => $this->input->post('desc'),
				'vacancies' => $this->input->post('vacancies'),
				'regId' => $regId,
				'date' => $date,
				'flag' => "0"
				);
			if($id!=0)
			{
				$this->db->where('id',$id);
				$query = $this->db->update('tb_job',$data);
				 
			}
			else
			{
				$query = $this->db->insert('tb_job',$data);
				$insert_id = $this->db->insert_id();
			}

	return $insert_id;
}


   	function postjob_insert0()
	{
		$id=$this->input->post('id');
		$regId=$_SESSION['id'];
		$date=date('Y-m-d');
		 $skill1= $this->input->post('skill');
 $othersalary= $this->input->post('othersalary');
 $otherarea= $this->input->post('otherarea');
$otheredu= $this->input->post('otheredu');
 $otherexp= $this->input->post('otherexp');
	if($othersalary!="")
{
		$salary= $this->input->post('othersalary');
		$data10 = array(
					
				'salary' => $this->input->post('othersalary'),
				
				);
		$query10 = $this->db->insert('tb_salary',$data10);
		$salary=$this->db->insert_id();
}
else
{
$salary= $this->input->post('salary');
}
if($otheredu!="")
{
		$education= $this->input->post('otheredu');
		$data106 = array(
					
				'education' => $this->input->post('otheredu'),
				
				);
		$query106 = $this->db->insert('tb_edu',$data106);
		$education=$this->db->insert_id();
}
else
{
$education= $this->input->post('education');
}
if($otherexp!="")
{
		$experience= $this->input->post('otherexp');
		$data1060 = array(
					
				'experience' => $this->input->post('otherexp'),
				
				);
		$query1060 = $this->db->insert('tb_exp',$data1060);
		$experience=$this->db->insert_id();
}
else
{
$experience= $this->input->post('experience');
}		
	if($otherarea!="")
{
		$city= $this->input->post('otherarea');
		$data101 = array(
					
				'city' => $this->input->post('otherarea'),
				
				);
		$query101 = $this->db->insert('tb_city',$data101);
		$city=$this->db->insert_id();
}
else
{
$city= $this->input->post('location');
}
	
	

           
				$data = array(
					'title' => $this->input->post('title'),
				'location' => $city,
				 'experience' =>$experience,
				  'education' => $education,
				'skill'=>implode(",", $skill1),
				'type' => $this->input->post('type'),
				'salary' => $salary,
				'desc' => $this->input->post('desc'),
				'vacancies' => $this->input->post('vacancies'),
				'regId' => $regId,
				'date' => $date,
				);
			if($id!=0)
			{
				$this->db->where('id',$id);
				$query = $this->db->update('tb_job',$data);
				 
			}
			else
			{
				$query = $this->db->insert('tb_job',$data);
				$insert_id = $this->db->insert_id();
			}

	return $insert_id;
}


function postjob_insert()
	{
		$id=$this->input->post('id');
		$regId=$_SESSION['id'];
		$date=date('Y-m-d');
		 $skill1= $this->input->post('skill');
	
	$data = array(
					'title' => $this->input->post('title'),
				'location' => $this->input->post('location'),
				 'experience' => $this->input->post('experience'),
				  'education' => $this->input->post('education'),
				'skill'=>implode(",", $skill1),
				'type' => $this->input->post('type'),
				'salary' => $this->input->post('salary'),
				'desc' => $this->input->post('desc'),
				'vacancies' => $this->input->post('vacancies'),
				'regId' => $regId,
				'date' => $date,
				);
           
				
			if($id!=0)
			{
				$this->db->where('id',$id);
				$query = $this->db->update('tb_job',$data);
				 
			}
			else
			{
				$query = $this->db->insert('tb_job',$data);
			$insert_id = $this->db->insert_id();
			}
return $insert_id;
	}


	function postjob_insertnew($regId)
	{
		$id=$this->input->post('id');
		$regId=$regId;
		$date=date('Y-m-d');
		 $skill1= $this->input->post('skill');
	
			$data = array(
						'title' => $this->input->post('title'),
						'location' => $this->input->post('location'),
						'experience' => $this->input->post('experience'),
						'education' => $this->input->post('education'),
						'skill'=>implode(",", $skill1),
						'type' => $this->input->post('type'),
						'salary' => $this->input->post('salary'),
						'desc' => $this->input->post('desc'),
						'vacancies' => $this->input->post('vacancies'),
						'regId' => $regId,
						'date' => $date,
						'flag' => '0',
						);
					$query = $this->db->insert('tb_job',$data);
					$insert_id = $this->db->insert_id();
					
		return $insert_id;
	}

	function ownPostJob()
	{
		$regId=$_SESSION['id'];
	
		$this->db->select('tb_job.id,date,tb_job.title as category,skill,tb_company.name,vacancies');
		$this->db->from('tb_company'); 
		$this->db->join('tb_job', 'tb_company.regId = tb_job.regId');
	   $this->db->where('tb_job.regId',$regId);
		$this->db->where('tb_job.flag','1');
			  
	    $query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
					
	}	
	function getId()
	{
		$regId=$_SESSION['id'];
	
		$this->db->select('regId');
		$this->db->from('tb_seeker'); 
		$this->db->where('regId',$regId);
		$this->db->where('flag','0');
			  
	    $query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
					
	}
function getDetails()
	{
		$regId=$_SESSION['id'];
	
		$this->db->select('name,emailId,mobile');
		$this->db->from('tb_seeker'); 
		$this->db->where('refferedBy',$regId);
		$this->db->where('flag','0');
			  
	    $query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
					
	}	
	function getDetails10()
	{
		$regId=$_SESSION['id'];
	
		$this->db->select('name,emailId,mobile');
		$this->db->from('tb_seeker'); 
		$this->db->where('refferedBy',$regId);
		$this->db->where('flag','0');
			  
	    $query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
					
	}
function getDetails1()
	{
		$regId=$_SESSION['id'];
	
		$this->db->select('count(tb_seeker.id) as count');
		$this->db->from('tb_seeker'); 
		$this->db->where('refferedBy',$regId);
		$this->db->where('flag','0');
				//
	    $query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
					
	}
function getEmail()
	{
		$regId=$_SESSION['id'];
	
		$this->db->select('email');
			$this->db->from('tb_company');
			$this->db->where('regId',$regId);
			
			$query  = $this->db->get();
	
		
			return $query->row('email');
				
					
	}	
	function getDetails2()
	{
		$regId=$_SESSION['id'];
	
		$this->db->select('sum(tb_wallet.commission) as sum');
		$this->db->from('tb_wallet');
		$this->db->where('cTo',$regId);
		
	    $query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
					
	}
	
	function getDetails3()
	{
		$regId=$_SESSION['id'];
	
		$this->db->select('*');
		$this->db->from('tb_other');
		$this->db->where('regId',$regId);
		
	    $query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
					
	}	
	function deletejob($id)
	{
		$data = array(
								'flag' => 0
							  );
			
			
			$this->db->where('id',$id);
			$this->db->update('tb_job',$data);
	}
	function edit_job($id)
	{
		$this->db->select('tb_job.id,tb_job.title as category1,tb_job.type as type1,tb_job.location as city1,tb_job.salary as salary1,desc,tb_job.skill as skill1,tb_job.experience as experience1,tb_job.education as education1,vacancies');
		$this->db->from('tb_job'); 
			
		$this->db->where('id',$id);
	    $query  = $this->db->get();
		return $query->result();;
		
	}
	function get_searchdata($keyword,$location,$salary)
	{
		
		$this->db->select('tb_job.id,tb_job.date,tb_job.title,tb_jobtype.type as type1, tb_company.name,tb_job.type,tb_city.city,tb_salary.salary ');
			$this->db->from('tb_job');
			$this->db->join('tb_company', 'tb_company.regId = tb_job.regId','left');
			$this->db->join('tb_city', 'tb_city.city_id = tb_job.location','left');
			$this->db->join('tb_jobtype', 'tb_jobtype.jobtype_id = tb_job.type','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_job.salary','left');
			if($salary != "")
			{
			$this->db->where('tb_job.salary',$salary);
			
			}
			 if($location != "")
			{
			$this->db->where('tb_job.location',$location);
			}
			 if($keyword != "")
			{
				
			 $this->db->where("(tb_job.title LIKE '%".$keyword."%' OR tb_job.skill LIKE '%".$keyword."%')");
			}
			
			
			$fetch  = $this->db->get();
			return $fetch;
	}
	function get_searchdatan()
	{
		
		$this->db->select('tb_job.id,tb_job.date,tb_job.title,tb_jobtype.type as type1, tb_company.name,tb_job.type,tb_city.city,tb_salary.salary ');
		$this->db->select('tb_job.id,tb_job.date,tb_job.title,tb_jobtype.type as type1, tb_company.name,tb_job.type,tb_city.city,tb_salary.salary ');
			$this->db->from('tb_job');
			$this->db->join('tb_company', 'tb_company.regId = tb_job.regId','left');
			$this->db->join('tb_city', 'tb_city.city_id = tb_job.location','left');
			$this->db->join('tb_jobtype', 'tb_jobtype.jobtype_id = tb_job.type','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_job.salary','left');
			
			$fetch  = $this->db->get();
			return $fetch;
	}

	function getuserdetails($id)
	{
		$this->db->select('name,emailId,mobile');
		$this->db->from('tb_seeker');
		$this->db->where('regId',$id);
		$fetch  = $this->db->get();
		return $fetch;
	}

	function get_searchdata0($keyword,$location,$salary)
	{
		
		$this->db->select('tb_seeker.id,tb_seeker.regId,tb_seeker.jobp as title,tb_seeker.name,tb_seeker.mobile,tb_seeker.emailId,tb_city.city,tb_salary.salary ');
			$this->db->from('tb_seeker');
			$this->db->join('tb_city', 'tb_city.city_id = tb_seeker.city','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_seeker.salary','left');
			if($salary != "")
			{
			$this->db->where('tb_seeker.salary',$salary);
			
			}
			 if($location != "")
			{
			$this->db->where('tb_seeker.city',$location);
			}
			 if($keyword != "")
			{
				
			 $this->db->where("(tb_seeker.jobp LIKE '%".$keyword."%' OR tb_seeker.skill LIKE '%".$keyword."%' OR tb_seeker.workArea LIKE '%".$keyword."%')");
			}
			
			
			$fetch  = $this->db->get();
			return $fetch;
	}
	function get_searchdata00()
	{
		
		$this->db->select('tb_seeker.id,tb_seeker.regId,tb_seeker.jobp as title,tb_seeker.name,tb_seeker.mobile,tb_seeker.emailId,tb_city.city,tb_salary.salary ');
			$this->db->from('tb_seeker');
			$this->db->join('tb_city', 'tb_city.city_id = tb_seeker.city','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_seeker.salary','left');
		
			
			$fetch  = $this->db->get();
			return $fetch;
	}
	function get_searchdata10($keyword,$location,$salary)
	{
		
		$this->db->select('count(*) as count');
			$this->db->from('tb_seeker');
			$this->db->join('tb_city', 'tb_city.city_id = tb_seeker.city','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_seeker.salary','left');
			if($salary != "")
			{
			$this->db->where('tb_seeker.salary',$salary);
			
			}
			 if($location != "")
			{
			$this->db->where('tb_seeker.city',$location);
			}
			 if($keyword != "")
			{
				
			 $this->db->where("(tb_seeker.jobp LIKE '%".$keyword."%' OR tb_seeker.skill LIKE '%".$keyword."%' OR tb_seeker.workArea LIKE '%".$keyword."%')");
			}
			
			
			$fetch  = $this->db->get();
			return $fetch;
	}
	function get_searchdata100()
	{
		
		$this->db->select('count(*) as count');
			$this->db->from('tb_seeker');
			$this->db->join('tb_city', 'tb_city.city_id = tb_seeker.city','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_seeker.salary','left');
			
			$fetch  = $this->db->get();
			return $fetch;
	}
	function get_searchdata1($keyword,$location,$salary)
	{
		
		$this->db->select('count(*) as count');
			$this->db->from('tb_job');
			$this->db->join('tb_company', 'tb_company.regId = tb_job.regId','left');
			$this->db->join('tb_city', 'tb_city.city_id = tb_job.location','left');
			$this->db->join('tb_jobtype', 'tb_jobtype.jobtype_id = tb_job.type','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_job.salary','left');
			if($salary != "")
			{
			$this->db->where('tb_job.salary',$salary);
			
			}
			 if($location != "")
			{
			$this->db->where('tb_job.location',$location);
			}
			 if($keyword != "")
			{
				
			 $this->db->where("(tb_job.title LIKE '%".$keyword."%' OR tb_job.skill LIKE '%".$keyword."%')");
			}
			
			
			$fetch1  = $this->db->get();
			return $fetch1;
	}
	function get_searchdataf($keyword,$location)
	{
		
		$this->db->select('tb_job.id,tb_job.date,tb_job.title,tb_jobtype.type as type1, tb_company.name,tb_job.type,tb_city.city,tb_salary.salary ');
			$this->db->from('tb_job');
			$this->db->join('tb_company', 'tb_company.regId = tb_job.regId','left');
			$this->db->join('tb_city', 'tb_city.city_id = tb_job.location','left');
			$this->db->join('tb_jobtype', 'tb_jobtype.jobtype_id = tb_job.type','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_job.salary','left');
			
			 if($location != "")
			{
			$this->db->where('tb_job.location',$location);
			}
			 if($keyword != "")
			{
				
			 $this->db->where("(tb_job.title LIKE '%".$keyword."%' OR tb_job.skill LIKE '%".$keyword."%')");
			}
			
			
			$fetch  = $this->db->get();
			return $fetch;
	}
	function get_searchdataf1($keyword,$location)
	{
		
		$this->db->select('count(*) as count');
		$this->db->from('tb_job');
			$this->db->join('tb_company', 'tb_company.regId = tb_job.regId','left');
			$this->db->join('tb_city', 'tb_city.city_id = tb_job.location','left');
			$this->db->join('tb_jobtype', 'tb_jobtype.jobtype_id = tb_job.type','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_job.salary','left');
			
			 if($location != "")
			{
			$this->db->where('tb_job.location',$location);
			}
			 if($keyword != "")
			{
				
			 $this->db->where("(tb_job.title LIKE '%".$keyword."%' OR tb_job.skill LIKE '%".$keyword."%')");
			}
			
			
			$fetch  = $this->db->get();
			return $fetch;
	}
	function get_searchdatan1()
	{
		
		$this->db->select('count(*) as count');
			$this->db->from('tb_job');
			$this->db->join('tb_company', 'tb_company.regId = tb_job.regId','left');
			$this->db->join('tb_city', 'tb_city.city_id = tb_job.location','left');
			$this->db->join('tb_jobtype', 'tb_jobtype.jobtype_id = tb_job.type','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_job.salary','left');
		
			
			$fetch1  = $this->db->get();
			return $fetch1;
	}
	
	function get_companydata($id)
	{
			$this->db->select('tb_job.id,tb_job.title,tb_company.*,tb_city.city');
			$this->db->from('tb_job');
			$this->db->join('tb_company', 'tb_company.regId = tb_job.regId','left');
			$this->db->join('tb_city', 'tb_city.city_id = tb_job.location','left');
			$this->db->join('tb_jobtype', 'tb_jobtype.jobtype_id = tb_job.type','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_job.salary','left');
			$this->db->where('tb_job.id',$id);
			
			
			$fetch  = $this->db->get();
			return $fetch;
		
	}
	
	function get_companydatanew()
	{
			$this->db->select('tb_company.*,tb_city.city');
			$this->db->from('tb_company');
			$this->db->join('tb_city', 'tb_city.city_id = tb_company.city_id','left');
			$this->db->where('tb_company.name',$_SESSION['uname']);
			
			
			$fetch  = $this->db->get();
			return $fetch;
		
	}
	
	function get_jobdata($id)
	{
		
		$this->db->select('tb_job.id,tb_job.title,tb_job.desc as desc1,tb_jobtype.type as type1,tb_job.skill,tb_job.salary_type, tb_company.name,tb_company.address,tb_company.contact,tb_company.email,tb_company.website,tb_company.landline,tb_company.pic,tb_company.desc,tb_job.type,tb_city.city,tb_salary.salary,vacancies ');
			$this->db->from('tb_job');
			$this->db->join('tb_company', 'tb_company.regId = tb_job.regId','left');
			$this->db->join('tb_city', 'tb_city.city_id = tb_job.location','left');
			$this->db->join('tb_jobtype', 'tb_jobtype.jobtype_id = tb_job.type','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_job.salary','left');
			$this->db->where('tb_job.id',$id);
			
			
			$fetch  = $this->db->get();
			return $fetch;
	}
	function get_seekerdata($id)
	{
		
		$this->db->select('tb_seeker.id,regId,tb_seeker.jobp as title,language,birthDate,photo,tb_exp.experience,tb_seeker.name,tb_seeker.mobile,tb_seeker.emailId,tb_city.city,skill,workArea,tb_edu.education,tb_salary.salary ');
			$this->db->from('tb_seeker');
			$this->db->join('tb_city', 'tb_city.city_id = tb_seeker.city','left');
			$this->db->join('tb_salary', 'tb_salary.id = tb_seeker.salary','left');
			$this->db->join('tb_edu', 'tb_edu.id = tb_seeker.edu','left');
			$this->db->join('tb_exp', 'tb_exp.id = tb_seeker.exp','left');
			$this->db->where('tb_seeker.id',$id);
			
			
			$fetch1  = $this->db->get();
			return $fetch1;
	}
	function applyJob()
	{
		$id=$_SESSION['id'];
		$this->db->select('tb_job.id,tb_job.title,tb_job.skill,tb_appliedjob.date, tb_company.name');
			$this->db->from('tb_job');
			$this->db->join('tb_company', 'tb_company.regId = tb_job.regId');
			$this->db->join('tb_appliedjob', 'tb_appliedjob.jobId = tb_job.id');
			
			$this->db->where('tb_appliedjob.regId',$id);
			$query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
				
	}
function status()
	{
		$id=$_SESSION['id'];
		$this->db->select('status');
			$this->db->from('tb_reg');
			$this->db->where('id',$id);
			$query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
				
	}
	function get_seekerinfo($jobid,$regId)
	{
		
		$this->db->select('tb_job.id,tb_company.email,tb_job.title as title1,tb_seeker.id,tb_seeker.regId,tb_seeker.jobp as title,language,birthDate,tb_exp.experience,tb_seeker.name,tb_seeker.mobile,tb_seeker.emailId,tb_city.city,tb_seeker.skill,workArea,tb_edu.education,tb_salary.salary');
			$this->db->from('tb_seeker');
			$this->db->from('tb_job');
			$this->db->join('tb_company', 'tb_company.regId = tb_job.regId','left');
			$this->db->join('tb_exp', 'tb_seeker.exp= tb_exp.id','left');
			$this->db->join('tb_edu', 'tb_seeker.edu= tb_edu.id','left');
			$this->db->join('tb_salary', 'tb_seeker.salary= tb_salary.id','left');
			$this->db->join('tb_city', 'tb_seeker.city= tb_city.city_id','left');
			
			
			$this->db->where('tb_job.id',$jobid);
			$this->db->where('tb_seeker.regId',$regId);
			//print_r( $this->db->last_query());exit;
			$query  = $this->db->get();
	
		
			return $query->result();
				
	}
	function get_companyinfo($companyId,$regId)
	{
		
		$this->db->select('tb_company.name,tb_company.contact,tb_company.email as companyemail,tb_company.address,website,person,tb_seeker.emailId as email123');
			$this->db->from('tb_company');
			$this->db->join('tb_hire', 'tb_company.regId= tb_hire.companyId');
			$this->db->join('tb_seeker','tb_seeker.regId=tb_hire.regId');
			
			$this->db->where('tb_company.regId',$companyId);
			
			$query  = $this->db->get();
	
		
			return $query->result();
				
	}
	function get_pass($email)
	{
		
			$this->db->select('id,password,userGroup');
			$this->db->from('tb_reg');
			$this->db->where('tb_reg.emailId',$email);
			$this->db->or_where('tb_reg.mobile',$email);
			
			$query  = $this->db->get();
	
		
			return $query->result();
				
	}
	function get_pass1($id)
	{
		
			$this->db->select('id,emailId as email');
			$this->db->from('tb_reg');
			$this->db->where('tb_reg.id',$id);
			
			$query  = $this->db->get();
	
		
			return $query->result();
				
	}
	
	function relatedJob()
	{
		$id=$_SESSION['id'];
		$this->db->select('tb_company.name,person,email,contact');
			$this->db->from('tb_company');
			$this->db->join('tb_hire', 'tb_company.regId = tb_hire.companyId');
			
			$this->db->where('tb_hire.regId',$id);
			$query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
				
	}
	function aJob()
	{
			$id=$_SESSION['id'];
			$this->db->select('tb_seeker.id,tb_appliedjob.regId,tb_job.title,tb_seeker.name,tb_seeker.mobile,tb_seeker.emailId,tb_seeker.skill');
			$this->db->from('tb_seeker');
			$this->db->join('tb_appliedjob', 'tb_appliedjob.regId = tb_seeker.regId','left');
			$this->db->join('tb_job', 'tb_appliedjob.jobId = tb_job.id','left');
			$this->db->where('tb_job.regId',$id);
			$query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
				
				
	}
	
	
	function getDetails30()
	{
		$regId=$_SESSION['id'];
	
		$this->db->select('sum(tb_paid.amount) as amount');
		$this->db->from('tb_paid');
		$this->db->where('regId',$regId);
		
	    $query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
					
	}
	function getDetails40()
	{
		$regId=$_SESSION['id'];
	
		$this->db->select('*');
		$this->db->from('tb_paid');
		$this->db->join('tb_account', 'tb_paid.accNo= tb_account.id');
		$this->db->where('tb_paid.regId',$regId);
		
	    $query  = $this->db->get();
	
		 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
	 }
	
	/*  ----------- check Mail is exist ajax call written by Nadaf------------------------*/

	
	 function is_email_available($email)  
      {  
           $this->db->where('emailId', $email);  
           $query = $this->db->get("tb_reg");  
           if($query->num_rows() > 0)  
           {  
                return true;  
           }  
           else  
           {  
                return false;  
           }  
      }  
/*  ----------- check Mobile is exist ajax call written by Nadaf------------------------*/
	 function is_Mobile_available($mobile)  
      {  
           $this->db->where('mobile', $mobile);  
           $query = $this->db->get("tb_reg");  
           if($query->num_rows() > 0)  
           {  
                return true;  
           }  
           else  
           {  
                return false;  
           }  
      }  
	  
 /******************This Function checkProfileDataCheck *******************************************************/
	  function checkProfileDataCheck($id,$usergroup)
      {  
	  
	  
	     if($usergroup =="seeker")
		 {
				$this->db->select('*');
				$this->db->from('tb_reg');
				$this->db->join('tb_seeker','tb_reg.id=tb_seeker.regId');
				$this->db->where('tb_reg.id',$id);
				$query=$this->db->get();
				$result= $query->result_array();
			 return $result;	
		 }
		 else if($usergroup == "employer")
		 { 
	        
	 
			    $this->db->select('*');
				$this->db->from('tb_reg');
				$this->db->join('tb_company','tb_reg.id=tb_company.regId');
				$this->db->where('tb_reg.id',$id);
				$query=$this->db->get();
				$result= $query->result_array();
			 return $result;	
		 }
		 else
		 {
			  return 0;	
			// return false;
		 }
           
      }  
	  /***************** Seeker update data ***********************/
	  function UpdateProfileDataSeeker($id,$SEEKERPOSTDATA)
	  {
		  $this->db->where('regId', $id);
			$this->db->update('tb_seeker', $SEEKERPOSTDATA);
		  return 2;
	  } 
	   /***************** Company update data ***********************/
	  function UpdateProfileDataCompany($id,$EMPLOYERPOSTDATA)
	  {
		  $this->db->where('regId', $id);
		  $this->db->update('tb_company', $EMPLOYERPOSTDATA);
		  
		  return 2;
	  }
	  
	   function UpdateProfileRegistration($id,$RegisteredData)
	  {
		  $this->db->where('id', $id);
		  $this->db->update('tb_reg', $RegisteredData); 
		  return 1;
	  }
	  
	  
	  
 function getAllcountry()
 {
	$this->db->order_by('country');
	
	 $query=$this->db->get('tb_country');

	   
	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
	
	
function getAllState($countryID)
 {
	$this->db->where('country_id',$countryID);
	$this->db->order_by('state');
    $query=$this->db->get('tb_state');
	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
	
	
function getAllCity($StateID)
 {
	$this->db->where('state_id',$StateID);
	$this->db->order_by('city');
    $query=$this->db->get('tb_city');
	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
	
 public function Jobtype()
 {
  $query = $this->db->query("SELECT * from tb_jobtype");
  return $query->result();
 }
 public function location()
 {
  $query = $this->db->query("SELECT * FROM `tb_city`");
  return $query->result();
 }

 function getSalary()
 {
	$this->db->order_by('id');
	 $query=$this->db->get('tb_salary');

	   
	 	 if($query->num_rows()>0)
	 {
		 foreach($query->result() as $row){
			 $disp[]=$row;
		 }
		 return $disp;
	 }
	 return false;
 }
 
 public function getDesignation()
 {
  $query = $this->db->query("SELECT id,title,COUNT(id) AS total FROM tb_job GROUP BY title");
  return $query->result();
 }
 
 
 
 
	
public function filterSearch($data)
 {
	 if(isset($data['jobname']) && $data['jobname']!= "")
	 {
		 $jobname = " AND j.title LIKE '%".$data['jobname']."%' OR `skill` LIKE '%".$data['jobname']."%'"; 
	 }
	 else
	 {
		 $jobname ='';
	 }
	 if(isset($data['Location'])&& $data['Location'] != 0)
	 {
		  $Location = " AND j.location = ".$data['Location'].""; 
	 }
	 else
	 {
		  $Location = "";
	 }
	 if(isset($data['jobtype'])&& $data['jobtype']!= 0)
	 {
		  $jobtype = " AND j.type= ".$data['jobtype'].""; 
	 }
	 else
	 {
		 $jobtype = '';
	 }
     $start = $data['currentstart'];
 
     $RowQuery = "SELECT j.id as jobid, j.title as Jobtitle ,c.city as location,ex.experience as jobexperience ,j.skill as JobSkill,e.education as reqeducation ,com.name as companyname,j.vacancies,t.type as Jobtype,com.pic as logos FROM  tb_company com ,tb_job j, tb_city c,tb_jobtype t,tb_edu e,tb_exp ex WHERE j.location = c.city_id AND j.type = t.jobtype_id AND j.education = e.id AND j.experience = ex.id AND com.regId = j.regId AND j.id IS NOT NULL ".$jobname.$Location.$jobtype.' ORDER BY j.id DESC LIMIT '.$start.' , 10';
     $query = $this->db->query($RowQuery);
     return $query->result();
	 
	
 }
 
 
 
 
 
 
	
}
 ?>