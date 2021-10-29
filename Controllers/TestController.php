<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use  Modules\Admin\Repositories\Test\TestRepository;
use  Modules\Admin\Http\Requests\Test\CreateRequest;
use Illuminate\Http\Request;

class TestController extends Controller
{

    protected $testRepo;

    /**
     * TestController constructor.
     */
    public function __construct(TestRepository $testRepo)
    {
        $this->testRepo = $testRepo;
    }

    public function index(){
        $condition = [];
        $orderBy = [];
        $paginate = true;
        $take   = 15;
        $offset = 20;

        $allTest = $this->testRepo->getTest($condition,$orderBy,$paginate,$take,$offset);

        return view('admin::test.index',compact('allTest'));
    }

    public function create(Request $request){
        $test = $this->testRepo->getEmptyTest();
        $test = $this->testRepo->loadOldInput($test, $request);
        return view('admin::test.create',compact('test'));
    }

    public function store(CreateRequest $request){
        $newTest = $this->testRepo->createTest($request->all());
        return redirect()->route('admin.test.index');
    }

    public function edit($testId, Request $request){
        $test = $this->testRepo->findTestById($testId);
        $test = $this->testRepo->loadOldInput($test, $request);
        return view('admin::test.edit',compact('test'));
    }


    public function update($testId,Request $request){

        $test = $this->testRepo->updateTestById($testId,$request->all());
        return redirect()->route('admin.test.index');

    }

    public function destroy($testId){
        $result = $this->testRepo->deleteTestById($testId);
        return redirect()->route('admin.test.index');
    }

}
