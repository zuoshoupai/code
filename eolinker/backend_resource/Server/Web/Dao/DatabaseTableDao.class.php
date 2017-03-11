<?php
/**
 * @name eolinker open source，eolinker开源版本
 * @link https://www.eolinker.com
 * @package eolinker
 * @author www.eolinker.com 深圳波纹聚联网络科技有限公司 ©2015-2016

 *  * eolinker，业内领先的Api接口管理及测试平台，为您提供最专业便捷的在线接口管理、测试、维护以及各类性能测试方案，帮助您高效开发、安全协作。
 * 如在使用的过程中有任何问题，欢迎加入用户讨论群进行反馈，我们将会以最快的速度，最好的服务态度为您解决问题。
 * 用户讨论QQ群：284421832
 *
 * 注意！eolinker开源版本仅供用户下载试用、学习和交流，禁止“一切公开使用于商业用途”或者“以eolinker开源版本为基础而开发的二次版本”在互联网上流通。
 * 注意！一经发现，我们将立刻启用法律程序进行维权。
 * 再次感谢您的使用，希望我们能够共同维护国内的互联网开源文明和正常商业秩序。
 *
 */
class DatabaseTableDao
{
	public function addTable(&$dbID, &$tableName, &$tableDesc)
	{
		$db = getDatabase();

		$db -> prepareExecute('INSERT INTO eo_database_table (eo_database_table.dbID,eo_database_table.tableName,eo_database_table.tableDescription) VALUES (?,?,?);', array(
			$dbID,
			$tableName,
			$tableDesc
		));

		if ($db -> getAffectRow() < 1)
		{
			return FALSE;
		}
		else
			return $db -> getLastInsertID();
	}

	public function checkTablePermission(&$tableID, &$userID)
	{
		$db = getDatabase();

		$result = $db -> prepareExecute('SELECT eo_database.dbID FROM eo_database_table INNER JOIN eo_database ON eo_database_table.dbID = eo_database.dbID INNER JOIN eo_conn_database ON eo_database.dbID = eo_conn_database.dbID WHERE eo_database_table.tableID =? AND eo_conn_database.userID =?;', array(
			$tableID,
			$userID
		));

		if (empty($result))
			return FALSE;
		else
			return $result['dbID'];
	}

	public function deleteTable(&$tableID)
	{
		$db = getDatabase();

		$db -> prepareExecute('DELETE FROM eo_database_table WHERE eo_database_table.tableID = ?;', array($tableID));

		if ($db -> getAffectRow() < 1)
			return FALSE;
		else
			return TRUE;
	}

	public function getTable(&$dbID)
	{
		$db = getDatabase();

		$result = $db -> prepareExecuteAll('SELECT eo_database_table.dbID,eo_database_table.tableID,eo_database_table.tableName,eo_database_table.tableDescription FROM eo_database_table WHERE eo_database_table.dbID =?;', array($dbID));

		if (empty($result))
			return FALSE;
		else
			return $result;
	}

	public function editTable(&$tableID, &$tableName, &$tableDesc)
	{
		$db = getDatabase();

		$db -> prepareExecute('UPDATE eo_database_table SET eo_database_table.tableName = ?,eo_database_table.tableDescription = ? WHERE eo_database_table.tableID = ?;', array(
			$tableName,
			$tableDesc,
			$tableID
		));

		if ($db -> getAffectRow() < 1)
			return FALSE;
		else
			return TRUE;
	}

}
?>