<?php


    namespace Modules\Admin\Repositories\Test;


    use Modules\Admin\Entities\Test;
    use Modules\Admin\Repositories\Base\AdminRepositoryEloquent;

    class TestRepositoryEloquent extends AdminRepositoryEloquent implements TestRepository
    {

        public function model()
        {
            return Test::class;
        }

        public function getTest(
            array $condition = [],
            array $orderBy = [],
            $paginate = false,
            int $take = 15,
            int $offset = 0
        ) {
            $query = $this->model()::query();
        //Xử lý condition ở đây
        if ($condition) {
            $query = $this->conditionBuilder($query, $condition);
        }

        $query = $this->orderBuilder($query, $orderBy);

        return $this->paginateBuilder($query, $paginate, $take, $offset);

        }

        public function createTest(array $all)
        {
            return $this->model()::create($all);
        }

        public function findTestById($testId)
        {
            return $this->model()::find($testId);
        }

        public function updateTestById($testId, $all)
        {

            $test = $this->model()::find($testId);

            if ($test) {
                return $test->update($all);
            } else {
                return false;
            }
        }

        public function deleteTestById($testId)
        {
            $test = $this->model()::find($testId);

            if ($test) {
                return $test->delete();
            } else {
                return false;
            }
        }

        public function getEmptyTest()
        {
            return new $this->model();
        }

        public function loadOldInput($test, \Illuminate\Http\Request $request)
                {
                    $fillable = $test->getFillable();
                    foreach($fillable as $field){
                        if($request->old($field)) {
                            $test->$field = $request->old($field);
                        }
                    }
                    return $test;
                }


    }
