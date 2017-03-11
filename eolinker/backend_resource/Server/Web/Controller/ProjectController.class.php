<?php
/**
 * @name eolinker open source，eolinker开源版本
 * @link https://www.eolinker.com
 * @package eolinker
 * @author www.eolinker.com 深圳波纹聚联网络科技有限公司 ©2015-2016

 * eolinker，业内领先的Api接口管理及测试平台，为您提供最专业便捷的在线接口管理、测试、维护以及各类性能测试方案，帮助您高效开发、安全协作。
 * 如在使用的过程中有任何问题，欢迎加入用户讨论群进行反馈，我们将会以最快的速度，最好的服务态度为您解决问题。
 * 用户讨论QQ群：284421832
 *
 * 注意！eolinker开源版本仅供用户下载试用、学习和交流，禁止“一切公开使用于商业用途”或者“以eolinker开源版本为基础而开发的二次版本”在互联网上流通。
 * 注意！一经发现，我们将立刻启用法律程序进行维权。
 * 再次感谢您的使用，希望我们能够共同维护国内的互联网开源文明和正常商业秩序。
 *
 */
class ProjectController
{
	// 返回json类型
	private $returnJson = array('type' => 'project');
	
	/**
	 * 检查登录状态
	 */
	public function __construct()
	{
		// 身份验证
		$server = new GuestModule;
		if (!$server -> checkLogin())
		{
			$this -> returnJson['statusCode'] = '120005';
			exitOutput($this -> returnJson);
		}
	}

	/**
	 * 创建项目
	 */
	public function addProject()
	{
		$nameLen = mb_strlen(quickInput('projectName'), 'utf8');
		$projectName = securelyInput('projectName');
		$projectType = securelyInput('projectType');
		$projectVersion = quickInput('projectVersion');

		// 验证项目名和项目类型格式
		if (!($nameLen >= 1 && $nameLen <= 32 && preg_match('/^[0-3]{1}$/', $projectType)))
		{
			// 项目名或项目类型不合法
			$this -> returnJson['statusCode'] = '140002';
		}
		elseif (!(is_float(floatval($projectVersion)) && intval($projectVersion)))
		{
			// 项目版本不合法
			$this -> returnJson['statusCode'] = '140017';
		}
		else
		{
			// 项目名和项目类型合法
			$service = new ProjectModule();
			$result = $service -> addProject($projectName, $projectType, $projectVersion);
			// 判断新建项目是否成功
			if ($result)
			{
				// 新建项目成功
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['projectInfo'] = $result;
			}
			else
				// 新建项目失败
				$this -> returnJson['statusCode'] = '140001';
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 删除项目
	 */
	public function deleteProject()
	{
		$projectID = securelyInput('projectID');

		// 判断项目ID是否合法
		if (preg_match('/^[0-9]{1,11}$/', $projectID))
		{
			// 项目ID合法
			$service = new ProjectModule();
			$result = $service -> deleteProject($projectID);
			// 判断删除项目是否成功
			if ($result)
				// 删除项目成功
				$this -> returnJson['statusCode'] = '000000';
			else
				// 删除项目失败
				$this -> returnJson['statusCode'] = '140003';
		}
		else
		{
			// 项目ID不合法
			$this -> returnJson['statusCode'] = '140004';
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 获取项目列表
	 */
	public function getProjectList()
	{
		$nameLen = mb_strlen(quickInput('projectName'), 'utf8');
		$projectType = securelyInput('projectType');
		$projectName = securelyInput('projectName');
		if (!preg_match('/^[0-3]|[-1]{1}$/', $projectType) || ($nameLen != 0 && $nameLen < 1 || $nameLen > 30))
		{
			// 项目类型或项目名称不合法
			$this -> returnJson['statusCode'] = '140002';
			exitOutput($this -> returnJson);
		}
		else
		{
			$service = new ProjectModule();
			$result = $service -> getProjectList($projectType, $projectName);

			if ($result)
			{
				// 获取项目列表成功
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['projectList'] = $result;
			}
			else
			{
				// 项目列表为空
				$this -> returnJson['statusCode'] = '140005';
			}

		}

		exitOutput($this -> returnJson);
	}

	/**
	 * 更改项目
	 */
	public function editProject()
	{
		$nameLen = mb_strlen(quickInput('projectName'), 'utf8');
		$projectID = securelyInput('projectID');
		$projectType = securelyInput('projectType');
		$projectName = securelyInput('projectName');
		$projectVersion = quickInput('projectVersion');

		// 判断项目参数格式是否合法
		if (!(preg_match('/^[0-9]{1,11}$/', $projectID) && $nameLen >= 1 && $nameLen <= 32 && preg_match('/^[0-3]{1}$/', $projectType)))
		{
			// 项目参数格式不合法
			$this -> returnJson['statusCode'] = '140007';
		}
		elseif (!(is_float(floatval($projectVersion)) && intval($projectVersion)))
		{
			// 项目版本不合法
			$this -> returnJson['statusCode'] = '140017';
		}
		else
		{
			// 项目参数格式合法
			$service = new ProjectModule();
			$result = $service -> editProject($projectID, $projectName, $projectType, $projectVersion);

			// 判断修改项目是否成功
			if ($result)
				// 项目修改成功
				$this -> returnJson['statusCode'] = '000000';
			else
				// 项目修改失败
				$this -> returnJson['statusCode'] = '140006';
		}

		exitOutput($this -> returnJson);
	}

	/**
	 * 获取项目信息
	 */
	public function getProject()
	{
		$projectID = securelyInput('projectID');
		if (preg_match('/^[0-9]{1,11}$/', $projectID))
		{
			$service = new ProjectModule;
			$result = $service -> getProject($projectID);

			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson = array_merge($this -> returnJson, $result);
			}
			else
			{
				$this -> returnJson['statusCode'] = '140005';
			}
		}
		else
		{
			$this -> returnJson['statusCode'] = '140007';
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 获取项目环境列表
	 */
	public function getEnvList()
	{
		$projectID = securelyInput('projectID');

		if (!preg_match('/^[0-9]{1,11}$/', $projectID))
		{
			//项目ID不合法
			$this -> returnJson['statusCode'] = '140004';
		}
		else
		{
			$service = new ProjectModule;
			$result = $service -> getEnvList($projectID);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['envList'] = $result;
			}
			else
			{
				//环境列表为空
				$this -> returnJson['statusCode'] = '140018';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 添加项目环境
	 */
	public function addEnv()
	{
		$projectID = securelyInput('projectID');
		$envName = securelyInput('envName');
		$envURI = securelyInput('envURI');

		if (!preg_match('/^[0-9]{1,11}$/', $projectID))
		{
			//项目ID不合法
			$this -> returnJson['statusCode'] = '140004';
		}
		else
		{
			$service = new ProjectModule;
			$result = $service -> addEnv($projectID, $envName, $envURI);
			if ($result)
			{
				$this -> returnJson['statusCode'] = '000000';
				$this -> returnJson['envID'] = $result;
			}
			else
			{
				$this -> returnJson['statusCode'] = '140019';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 删除项目环境
	 */
	public function deleteEnv()
	{
		$projectID = securelyInput('projectID');
		$envID = securelyInput('envID');

		if (!preg_match('/^[0-9]{1,11}$/', $projectID))
		{
			//项目ID不合法
			$this -> returnJson['statusCode'] = '140004';
		}
		elseif (!preg_match('/^[0-9]{1,11}$/', $envID))
		{
			//环境ID不合法
			$this -> returnJson['statusCode'] = '140022';
		}
		else
		{
			$service = new ProjectModule;
			if ($service -> deleteEnv($projectID, $envID))
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				//删除环境失败，projectID与envID不匹配
				$this -> returnJson['statusCode'] = '140020';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 修改项目环境
	 */
	public function editEnv()
	{
		$projectID = securelyInput('projectID');
		$envID = securelyInput('envID');
		$envName = securelyInput('envName');
		$envURI = securelyInput('envURI');

		if (!preg_match('/^[0-9]{1,11}$/', $projectID))
		{
			//项目ID不合法
			$this -> returnJson['statusCode'] = '140004';
		}
		elseif (!preg_match('/^[0-9]{1,11}$/', $envID))
		{
			//环境ID不合法
			$this -> returnJson['statusCode'] = '140022';
		}
		else
		{
			$service = new ProjectModule;
			if ($service -> editEnv($projectID, $envID, $envName, $envURI))
			{
				$this -> returnJson['statusCode'] = '000000';
			}
			else
			{
				//修改失败
				$this -> returnJson['statusCode'] = '140021';
			}
		}
		exitOutput($this -> returnJson);
	}

	/**
	 * 导出项目
	 */
	public function dumpProject()
	{
		$projectID = securelyInput('projectID');
		if (!preg_match('/^[0-9]{1,11}$/', $projectID))
		{
			//项目ID不合法
			$this -> returnJson['statusCode'] = '140004';
		}
		$service = new ProjectModule;
		$fileName = $service -> dumpProject($projectID);
		if ($fileName)
		{
			$this -> returnJson['statusCode'] = '000000';
			$this -> returnJson['fileName'] = $fileName;
		}
		else
		{
			//修改失败
			$this -> returnJson['statusCode'] = '140021';
		}
		exitOutput($this -> returnJson);
	}

}
?>