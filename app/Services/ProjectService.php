<?php namespace App\Services;

use Utils;
use Auth;
use App\Models\Client;
use App\Ninja\Repositories\ProjectRepository;
use App\Ninja\Datatables\ProjectDatatable;

/**
 * Class ProjectService
 */
class ProjectService extends BaseService
{
    /**
     * @var ProjectRepository
     */
    protected $projectRepo;

    /**
     * @var DatatableService
     */
    protected $datatableService;

    /**
     * CreditService constructor.
     *
     * @param ProjectRepository $creditRepo
     * @param DatatableService $datatableService
     */
    public function __construct(ProjectRepository $projectRepo, DatatableService $datatableService)
    {
        $this->projectRepo = $projectRepo;
        $this->datatableService = $datatableService;
    }

    /**
     * @return CreditRepository
     */
    protected function getRepo()
    {
        return $this->projectRepo;
    }

    /**
     * @param $data
     * @return mixed|null
     */
    public function save($data)
    {
        if (isset($data['client_id']) && $data['client_id']) {
            $data['client_id'] = Client::getPrivateId($data['client_id']);
        }

        return $this->projectRepo->save($data);
    }

    /**
     * @param $clientPublicId
     * @param $search
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatatable($search, $userId)
    {
        // we don't support bulk edit and hide the client on the individual client page
        $datatable = new ProjectDatatable();

        $query = $this->projectRepo->find($search, $userId);

        return $this->datatableService->createDatatable($datatable, $query);
    }
}
