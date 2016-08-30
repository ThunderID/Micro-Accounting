<?php

namespace App\Http\Controllers;

use App\Libraries\JSend;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use App\Services\AccountStore;
use App\Entities\Account;

/**
 * Account resource representation.
 *
 * @Resource("Accounts", uri="/Accounts")
 */
class AccountController extends Controller
{
	public function __construct(Request $request, AccountStore $store)
	{
		$this->request 				= $request;
		$this->store				= $store;
	}

	/**
	 * Show all Accounts
	 *
	 * Get a JSON representation of all the stored Accounts.
	 *
	 * @Get("/accounts")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"type":"","search":[{"name":string, "companyid":"integer","code":"string","type":"asset|liability|equity|income|expense"}],"sort":[{"newest":"asc","company":"desc","type":"desc", "code":"asc"}], "take":"integer", "skip":"integer"}),
	 *      @Response(200, body={"status": "success", "data": {"data":[{"id":null,"company_id":"integer","name":"string","code":"string","type":"string"}],"count":"integer"} })
	 * })
	 */
	public function index()
	{
		$result						= new Account;

		if(Input::has('search'))
		{
			$search					= Input::get('search');

			foreach ($search as $key => $value) 
			{
				switch (strtolower($key)) 
				{
					case 'name':
						$result		= $result->name($value);
						break;
					case 'companyid':
						$result		= $result->companyid($value);
						break;
					case 'code':
						$result		= $result->code($value);
						break;
					case 'type':
						$result		= $result->type($value);
						break;
					default:
						# code...
						break;
				}
			}
		}

		if(Input::has('sort'))
		{
			$sort					= Input::get('sort');

			foreach ($sort as $key => $value) 
			{
				if(!in_array($value, ['asc', 'desc']))
				{
					return response()->json( JSend::error([$key.' harus bernilai asc atau desc.'])->asArray());
				}
				switch (strtolower($key)) 
				{
					case 'newest':
						$result		= $result->orderby('transact_at', $value);
						break;
					case 'company':
						$result		= $result->orderby('company_id', $value);
						break;
					case 'type':
						$result		= $result->orderby('type', $value);
						break;
					case 'code':
						$result		= $result->orderby('code', $value);
						break;
					default:
						# code...
						break;
				}
			}
		}

		$count                      = count($result->get());

		if(Input::has('skip'))
		{
			$skip                   = Input::get('skip');
			$result                 = $result->skip($skip);
		}

		if(Input::has('take'))
		{
			$take                   = Input::get('take');
			$result                 = $result->take($take);
		}

		$result 					= $result->get();
		
		return response()->json( JSend::success(['data' => $result->toArray(), 'count' => $count])->asArray())
				->setCallback($this->request->input('callback'));
	}

	/**
	 * Store Account
	 *
	 * Store a new Account with a goods costs and service costs.
	 *
	 * @Post("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null,"company_id":"integer","name":"string","code":"string","type":"string"}),
	 *      @Response(200, body={"status": "success", "data": {"id":null,"company_id":"integer","name":"string","code":"string","type":"string"}}),
	 *      @Response(422, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function post()
	{
		$result						= $this->store;

		$result->fill(Input::all());

		if($result->save())
		{
			return response()->json( JSend::success($result->getData()->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		
		return response()->json( JSend::error($result->getError()->toArray())->asArray());
	}

	/**
	 * Delete Account
	 *
	 * Delete a new Account with a goods costs and service costs.
	 *
	 * @Post("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null}),
	 *      @Response(200, body={"status": "success", "data": {"id":null,"ref_number":"string","issued_by":"integer","company_id":"integer","customer_id":"integer","issued_at":"datetime","due_at":"datetime","goods":[{"id":null,"Account_id":"integer","product_id":"integer","quantity":"integer","price":"double","discount":"double"}],"services":[{"id":null,"Account_id":"integer","service_id":"integer","price":"double","discount":"double"}]}}),
	 *      @Response(422, body={"status": {"error": {"cannot delete."}}})
	 * })
	 * Event created : tlab.Account.deleted
	 */
	public function delete()
	{
		$result				= Account::id(Input::get('id'))->first();

		if($this->deleted->delete($result))
		{
			$connection 	= new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
			$channel 		= $connection->channel();
			
			$routing_key 	= 'tlab.Account.deleted';

			$channel->exchange_declare('topic_logs', 'topic', false, false, false);

			$data 		= '{"date": "'.$this->deleted->getData()['issued_at'].'","amount": "'.abs($this->deleted->getData()['amount']).'"}';
			$msg 		= new AMQPMessage($data);

			$channel->basic_publish($msg, 'topic_logs', $routing_key);

			echo " [x] Sent ", $data, "\n";

			$channel->close();
			$connection->close();

			return response()->json( JSend::success($this->deleted->getData())->asArray())
					->setCallback($this->request->input('callback'));
		}

		return response()->json( JSend::error($this->deleted->getError())->asArray());
	}
}