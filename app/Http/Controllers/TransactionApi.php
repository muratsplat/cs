<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//use App\Http\Requests;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionReport;
use App\Libs\ClearSettle\Resource\ApiClientManager;


class TransactionApi extends Controller
{    
    
    /**
     * @var \App\Libs\ClearSettle\Resource\ApiClientManager
     */
    protected $clientManager;
    
    /**
     *
     * @var \Illuminate\Support\Collection
     */
    protected $results;
    
        /**
         * 
         * @param \App\Libs\ClearSettle\Resource\ApiClientManager $manager
         */   
        public function __construct(ApiClientManager $manager) 
        {
            $this->clientManager = $manager;
            
            $this->results  = new Collection();
        }        
        
        /**
         * Create Transaction Request
         * 
         * @return \App\Libs\ClearSettle\Resource\Request\Transaction
         */
        protected function createRequest()
        {
            return $this->clientManager->createNewRequest('transaction');
        }        
        
        /**
         * Show transaction report
         * 
         * @param App\Http\Requests\TransactionReport $request
         */
        public function postReport(TransactionReport $request)
        {                       
            $result = $this->sendReportRequest($request); 
            
            if ($result->isApproved()) {
                
                $items = $this->getResults();
                
                return view('transaction.report')
                        ->with(compact('items'));
            }
           
            if ($result->hasError()) {
                
                 return redirect('/console/welcome')
                         ->withErrors($result);           
            }
            
            $errors = $this->createMessageBag();
            
            $errors->add('unknown', 'Unknown Error !');
            
            return redirect('/console/welcome')->withErrors($errors);          
        }
        
        /**
         * To send request to remote server
         * 
         * @param App\Http\Requests\TransactionReport $request
         * @return \App\Libs\ClearSettle\Resource\Request\Transaction
         */
        protected function sendReportRequest(TransactionReport $request)
        {  
            $user = $this->getUser();
            
            list($fromDate, $toDate, $merchantId, $acquirer) = $this->prepareReportParams($request);
            
            $report = $this->createRequest();
            
            if ($report->report($user, $fromDate, $toDate, $merchantId, $acquirer)) {
                
                $response = (array) $report->getBodyAsObject()->response;
                
                $this->importToResults($response);                 
            }
            
            return $report;
        }
        
        /**
         * To get results
         * 
         * @return \Illuminate\Support\Collection
         */
        private function getResults()
        {
            return $this->results;
        }
        
        /**
         * To reponse report to import to collection
         * 
         * @param array $response
         */
        private function importToResults(array $response)
        {
            foreach ($response as $one) {
                
                $this->results->push($one);
            }
        }
        
        /**
         * To get user
         * 
         * @return \App\User
         */
        protected function getUser() 
        {            
            return \Auth::getUser();
        }
        
        /**
         * To create Message Bag for showing errors on view
         * 
         * @return \Illuminate\Support\MessageBag
         */
        protected function createMessageBag()
        {
            return new MessageBag();
        }
        
        /**
         * To get transaction report params
         * 
         * @param TransactionReport $request
         * @return array
         */
        protected function prepareReportParams(TransactionReport $request)
        {
            return [
                
                $request->get('fromDate'), 
                $request->get('toDate'),
                $request->get('merchant', null),
                $request->get('acquirer', null),
             ];
        }
        

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            return 'I am Index';
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            //
        }

        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            //
        }
}
