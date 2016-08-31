<?php

namespace App\Services;

use Illuminate\Support\MessageBag;

use App\Entities\Journal;

use App\Contracts\JournalDeleteInterface;

use App\Contracts\Policies\ValidatingJournalInterface;
use App\Contracts\Policies\ProceedJournalInterface;
use App\Contracts\Policies\EffectJournalInterface;

class JournalDelete implements JournalDeleteInterface 
{
	protected $journal;
	protected $errors;
	protected $saved_data;
	protected $pre;
	protected $post;
	protected $pro;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct(ValidatingJournalInterface $pre, ProceedJournalInterface $pro, EffectJournalInterface $post)
	{
		$this->errors 	= new MessageBag;
		$this->pre 		= $pre;
		$this->pro 		= $pro;
		$this->post 	= $post;
	}

	/**
	 * return errors
	 *
	 * @return MessageBag
	 **/
	function getError()
	{
		return $this->errors;
	}

	/**
	 * return saved_data
	 *
	 * @return saved_data
	 **/
	function getData()
	{
		return $this->saved_data;
	}

	/**
	 * Save
	 *
	 * Here's the workflow
	 * 
	 * @return Response
	 */
	public function delete(Journal $journal)
	{
		$this->journal 			= $journal->toArray();
		
		/** PREPROCESS */

		//1. Validate Journal
		$this->pre->validatejournaldelete($journal); 

		if($this->pre->errors->count())
		{
			$this->errors 		= $this->pre->errors;

			return false;
		}

		\DB::BeginTransaction();

		/** PROCESS */

		//2. Delete goods
		$this->pro->deletejournal($journal); 

		if($this->pro->errors->count())
		{
			\DB::rollback();
			
			$this->errors 		= $this->pro->errors;

			return false;
		}

		\DB::commit();

		//3. Return Journal Model Object
		$this->saved_data		= $this->journal;

		return true;
	}
}
