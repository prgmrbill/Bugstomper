<?php
/*
 * IssueReport - builds datasets that work
 * with Flot charts based on Issue data
 * 
 */
namespace model;
class IssueReport extends Model
{
    public function GetIssueCommentDistribution()
	{
		$q = "SELECT CONCAT('#', i.id) AS label,
					 COUNT(*) AS data
			  FROM issue i
			  JOIN issue_comment ic ON i.id = ic.issue_id
			  GROUP BY i.id";
		$report = $this->FetchAll($q);
		return json_encode($report, JSON_NUMERIC_CHECK);
	}
    
	public function GetIssueStatusDistribution()
	{
		$q = 'SELECT st.name AS label,
					 COUNT(*) AS data
			  FROM issue i
			  JOIN issue_status st ON st.id = i.status
			  GROUP BY st.id';
		$report = $this->FetchAll($q);
		return json_encode($report, JSON_NUMERIC_CHECK);
	}
	
	public function GetIssueAssigneeDistribution()
	{
		$q = 'SELECT u.login AS label,
				     COUNT(*) AS data
			  FROM issue i
			  JOIN user u ON u.id = i.assigned_to
			  GROUP BY u.id';
		$report = $this->FetchAll($q);
		return json_encode($report, JSON_NUMERIC_CHECK);
	}
	
	public function GetIssueOpenerDistribution()
	{
		$q = 'SELECT u.login AS label,
				     COUNT(*) AS data
			  FROM issue i
			  LEFT JOIN user u ON u.id = i.opened_by
			  GROUP BY u.id';
		$report = $this->FetchAll($q);
		return json_encode($report, JSON_NUMERIC_CHECK);
	}
}