<?php

namespace Modules\Admin\Repositories\Test;

use Prettus\Repository\Contracts\RepositoryInterface;

interface TestRepository extends RepositoryInterface
{

        public function getTest(
            array $condition = [],
            array $orderBy = [],
            $paginate = false,
            int $take = 15,
            int $offset = 0
        );

    public function createTest(array $all);

    public function findTestById($testId);

    public function updateTestById($testId, $all);

    public function deleteTestById($testId);

    public function getEmptyTest();

    public function loadOldInput($test, \Illuminate\Http\Request $request);


}
