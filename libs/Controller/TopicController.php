<?php
/** @package    Autoresponse::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Topic.php");
require_once("Model/Log.php");
require_once("Model/Setting.php");
require_once(dirname(dirname(__DIR__)) . '/vendor/autoload.php');

/**
 * TopicController is the controller class for the Topic object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Autoresponse::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class TopicController extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 *
	 * @inheritdocs
	 */
	protected function Init()
	{
		parent::Init();

		// TODO: add controller-wide bootstrap code
		
		// TODO: if authentiation is required for this entire controller, for example:
		// $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm');
	}

	/**
	 * Displays a list view of Topic objects
	 */
	public function ListView()
	{
		$this->Render();
	}

	/**
	 * API Method queries for Topic records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new TopicCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,Keyword,Reply,TotalHits'
				, '%'.$filter.'%')
			);

			// TODO: this is generic query filtering based only on criteria properties
			foreach (array_keys($_REQUEST) as $prop)
			{
				$prop_normal = ucfirst($prop);
				$prop_equals = $prop_normal.'_Equals';

				if (property_exists($criteria, $prop_normal))
				{
					$criteria->$prop_normal = RequestUtil::Get($prop);
				}
				elseif (property_exists($criteria, $prop_equals))
				{
					// this is a convenience so that the _Equals suffix is not needed
					$criteria->$prop_equals = RequestUtil::Get($prop);
				}
			}

			$output = new stdClass();

			// if a sort order was specified then specify in the criteria
 			$output->orderBy = RequestUtil::Get('orderBy');
 			$output->orderDesc = RequestUtil::Get('orderDesc') != '';
 			if ($output->orderBy) $criteria->SetOrder($output->orderBy, $output->orderDesc);

			$page = RequestUtil::Get('page');

			if ($page != '')
			{
				// if page is specified, use this instead (at the expense of one extra count query)
				$pagesize = $this->GetDefaultPageSize();

				$topics = $this->Phreezer->Query('Topic',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $topics->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $topics->TotalResults;
				$output->totalPages = $topics->TotalPages;
				$output->pageSize = $topics->PageSize;
				$output->currentPage = $topics->CurrentPage;
			}
			else
			{
				// return all results
				$topics = $this->Phreezer->Query('Topic',$criteria);
				$output->rows = $topics->ToObjectArray(true, $this->SimpleObjectParams());
				$output->totalResults = count($output->rows);
				$output->totalPages = 1;
				$output->pageSize = $output->totalResults;
				$output->currentPage = 1;
			}


			$this->RenderJSON($output, $this->JSONPCallback());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method retrieves a single Topic record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('id');
			$topic = $this->Phreezer->Get('Topic',$pk);
			$this->RenderJSON($topic, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Topic record and render response as JSON
	 */
	public function Create()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$topic = new Topic($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $topic->Id = $this->SafeGetVal($json, 'id');

			$topic->Keyword = $this->SafeGetVal($json, 'keyword');
			$topic->Reply = $this->SafeGetVal($json, 'reply');
			$topic->TotalHits = $this->SafeGetVal($json, 'totalHits');

			$topic->Validate();
			$errors = $topic->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$topic->Save();
				$this->RenderJSON($topic, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Topic record and render response as JSON
	 */
	public function Update()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$pk = $this->GetRouter()->GetUrlParam('id');
			$topic = $this->Phreezer->Get('Topic',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $topic->Id = $this->SafeGetVal($json, 'id', $topic->Id);

			$topic->Keyword = $this->SafeGetVal($json, 'keyword', $topic->Keyword);
			$topic->Reply = $this->SafeGetVal($json, 'reply', $topic->Reply);
			$topic->TotalHits = $this->SafeGetVal($json, 'totalHits', $topic->TotalHits);

			$topic->Validate();
			$errors = $topic->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$topic->Save();
				$this->RenderJSON($topic, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Topic record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$topic = $this->Phreezer->Get('Topic',$pk);

			$topic->Delete();

			$output = new stdClass();

			$this->RenderJSON($output, $this->JSONPCallback());

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	public function TwilioCallback()
	{
		if (empty($_REQUEST['From']) || empty($_REQUEST['Body']))
			die(1);

		$from = trim($_REQUEST['From']);
		$sms  = trim($_REQUEST['Body']);

		$criteria = new TopicCriteria();
		$criteria->Keyword_Equals = $sms;
		$topics = $this->Phreezer->Query('Topic',$criteria);
		$result = $topics->ToObjectArray(true, $this->SimpleObjectParams());
		if (count($result) > 0){
			//reply with twilio
			$criteria = new SettingCriteria();
			$criteria->Option_Equals = 'from_number';
			$from_number = $this->Phreezer->GetByCriteria('Setting',$criteria);

			$response = new Services_Twilio_Twiml();
			$response->message($result[0]->reply, array(
			  'to' => $from,
			  'from' => $from_number->Value
			));
			print $response;

			//update hit counter
			$topic = $this->Phreezer->Get('Topic',$result[0]->id);
			$topic->TotalHits = $topic->TotalHits + 1;
			$topic->Save();

			//log incoming sms
			$log = new Log($this->Phreezer);
			$log->From = $from;
			$log->TextMessage = $sms;
			$log->Date = date('Y-m-d H:i:s',time());
			$log->Save();
			
		}
	}
}

?>
