<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Service\EmployeeAddService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class EmployeeController extends AbstractController
{
    #[Route('/employees', name: 'get_employee', methods:['get'])]
    public function index(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $parent = $request->get('parent');
        $employees = $managerRegistry->
            getRepository(Employee::class)->
            findBy(['parent' => $parent], ['surname' => 'ASC']);
        $response = [];

        foreach($employees as $employee){
            $response[] = $this->getEmployeeData($employee);
        }
        return $this->json($response);
    }

    #[Route('/employee', name: 'add_employee', methods:['post'])]
    public function add(Request $request, EmployeeAddService $addService): JsonResponse
    {
        try {
            $content = $request->getPayload();

            $employee = $addService->addEmployee($content);

            return $this->json($this->getEmployeeData($employee), 201);
        } catch (\Throwable $exception){
            return $this->json(['error' => $exception->getMessage()], 500);
        }
    }

    //Search Employee by name
    #[Route('/employee/search', name: 'search_employee', methods:['get'])]
    public function search(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        $name = $request->get('name');
        $employees = $managerRegistry->
            getRepository(Employee::class)->search($name);
        $response = [];

        foreach($employees as $employee){
            $response[] = $this->getEmployeeData($employee);
        }
        return $this->json($response);
    }


    private function getEmployeeData(Employee $employee): array {
        return [
            'id' => $employee->getId(),
            'name' => $employee->getName(),
            'surname' => $employee->getSurname(),
            'position' => $employee->getPosition(),
            'parent_id' => $employee->getParent()?->getId(),
            'has_children' => $employee->getChildren()->count() > 0
        ];
    }
}
