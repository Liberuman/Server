<?php

//include ("../third_party/taobao-sdk/top/request/TopClient.php");
//include ("../third_party/taobao-sdk/top/request/AlibabaAliqinFcSmsNumSendRequest");

class ActivityController extends CI_Controller
{
	private $code = 0;
	private $msg = '操作失败';
	private $data = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ActivityModel', 'activity', TRUE);
		$this->load->model('ActivityTypeModel', 'activity_type', TRUE);
		$this->load->model('SuggestionModel', 'suggestion', TRUE);
		$this->load->library('pagination');
		$this->load->helper('format');
		$this->load->helper('util');
		$this->load->helper('url');
	}

	public function view($page = 'home')
	{
	    if (!file_exists(APPPATH.'/views/pages/'.$page.'.php'))
	    {
	        show_404();
	    }

	    $data['title'] = ucfirst($page); // Capitalize the first letter

	    $this->load->view('templates/header', $data);
	    $this->load->view('pages/'.$page, $data);
	    $this->load->view('templates/footer', $data);
	}

	/**
	 * 获取最新的活动信息
	 * @return [type]
	 */
	public function get_latest_activities()
	{
		if (isvalid_sign($_GET))
		{
			$this->data = $this->activity->get_latest_activities();
			if (!empty($this->data))
			{
				$this->code = 1;
				$this->msg = "数据获取成功";
			}
			else
			{
				$this->code = 0;
				$this->msg = "近期没有活动哦";
			}
		}
		else
		{
			$this->code = 2;
			$this->msg = "签名错误";
			$this->data = array();
		}

		echo_result($this->code, $this->msg, $this->data);
	}

	/**
	 * 获取附近的活动信息
	 * @return [type]
	 */
	public function get_nearby_activities()
	{
		if (isvalid_sign($_GET))
		{
			$this->data = $this->activity->get_nearby_activities();
			if (!is_null($this->data))
			{
				$this->code = 1;
				$this->msg = "数据获取成功";
			}
			else
			{
				$this->code = 0;
				$this->msg = "附近没有活动哦";
			}
		}
		else
		{
			$this->code = 2;
			$this->msg = "签名错误";
			$this->data = array();
		}

		echo_result($this->code, $this->msg, $this->data);
	}

	/**
	 * 获取指定类型的所有活动
	 * @return [type]
	 */
	public function get_specific_type_activities()
	{
		if (isvalid_sign($_GET))
		{
			$this->data = $this->activity->get_specific_type_activities();
			if (!empty($this->data))
			{
				$this->code = 1;
				$this->msg = "数据获取成功";
			}
			else
			{
				$this->code = 0;
				$this->msg = "近期没有活动哦";
			}
		}
		else
		{
			$this->code = 2;
			$this->msg = "签名错误";
			$this->data = array();
		}

		echo_result($this->code, $this->msg, $this->data);
	}

	/**
	 * 获取以已布的活动
	 * @return [type]
	 */
	public function get_launched_activities()
	{
		if (isvalid_sign($_GET))
		{
			if (has_logined())
			{
				$this->data = $this->activity->get_launched_activities();
				if (!is_null($this->data))
				{
					$this->code = 1;
					$this->msg = "数据获取成功";
				}
				else
				{
					$this->code = 0;
					$this->msg = "没有发布过活动哦";
				}
			}
			else
			{
				$this->code = -1;
				$this->msg = "没有登录";
			}
		}
		else
		{
			$this->code = 2;
			$this->msg = "签名错误";
		}

		echo_result($this->code, $this->msg, $this->data);
	}

	/**
	 * 获取指定活动的详细信息
	 * @return [type]
	 */
	public function get_specific_activity()
	{
		if (isvalid_sign($_GET))
		{
			if (has_logined())
			{
				$this->data = $this->activity->get_specific_activity();
				if (!is_null($this->data))
				{
					$this->code = 1;
					$this->msg = "数据获取成功";
				}
				else
				{
					$this->code = 0;
					$this->msg = "该活动不存在哦";
				}
			}
			else
			{
				$this->code = -1;
				$this->msg = "没有登录";
			}
		}
		else
		{
			$this->code = 2;
			$this->msg = "签名错误";
		}

		echo_result($this->code, $this->msg, $this->data);
	}

	/**
	 *  发布活动
	 */
	public function add_activity()
	{
		if (isvalid_sign($_REQUEST))
		{
			if (has_logined())
			{
				$this->data = $this->activity->add_activity();
				if ($this->data)
				{
					$this->code = 1;
					$this->msg = "活动添加成功";
				}
				else
				{
					$this->code = 0;
					$this->msg = "活动添加失败";
				}
			}
			else
			{
				$this->code = -1;
				$this->msg = "没有登录";
			}
		}
		else
		{
			$this->code = 2;
			$this->msg = "签名错误";
		}

		echo_result($this->code, $this->msg, $this->data);
	}

	/**
	 * 删除活动
	 * @return [type]
	 */
	public function del_activity()
	{
		if (isvalid_sign($_GET))
		{
			$this->data = $this->activity->del_activity();
			if ($this->data)
			{
				$this->code = 1;
				$this->msg = "活动已删除";
			}
			else
			{
				$this->code = 0;
				$this->msg = "活动删除失败";
			}
		}
		else
		{
			$this->code = 2;
			$this->msg = "签名错误";
		}
		
		echo_result($this->code, $this->msg, '');
	}

	/**
	 * 更新活动信息
	 * @return [type]
	 */
	public function update_activity()
	{
		//phpinfo();
		if (isvalid_sign($_REQUEST))
		{
			if (has_logined())
			{
				$this->data = $this->activity->update_activity();
				if ($this->data)
				{
					$this->code = 1;
					$this->msg = "更新成功";
				}
				else
				{
					$this->code = 0;
					$this->msg = "更新失败";
				}
			} 
			else 
			{
				$this->code = -1;
				$this->msg = "没有登录";
			}
		}
		else
		{
			$this->code = 2;
			$this->msg = "签名错误";
		}
		
		echo_result($this->code, $this->msg, $this->data);
	}

	/**
	 * 获取所有的活动类型
	 * @return [type]
	 */
	public function get_all_activity_type()
	{
		if (isvalid_sign($_GET))
		{
			$this->data = $this->activity_type->get_all_activity_type();
			if (!empty($this->data))
			{
				$this->code = 1;
				$this->msg = "数据获取成功";
			}
			else
			{
				$this->code = 0;
				$this->msg = "数据获取失败";
			}
		}
		else
		{
			$this->code = 2;
			$this->msg = "签名错误";
			$this->data = array();
		}

		echo_result($this->code, $this->msg, $this->data);
	}

	/**
	 * 提交建议
	 * @return [type]
	 */
	public function submit_suggestion()
	{
		if (isvalid_sign($_REQUEST))
		{
			$this->data = $this->suggestion->submit_suggestion();
			if ($this->data)
			{
				$this->code = 1;
				$this->msg = "提交成功";
			}
			else
			{
				$this->code = 0;
				$this->msg = "提交失败";
			}
		}
		else
		{
			$this->code = 2;
			$this->msg = "签名错误";
		}

		echo_result($this->code, $this->msg, $this->data);
	}
}

