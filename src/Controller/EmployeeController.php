<?php

namespace App\Controller;

use App\Entity\Employee;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class EmployeeController extends AbstractController
{
    #[Route('/employees', name: 'get_employee', methods:['get'])]
    public function index(ManagerRegistry $managerRegistry): JsonResponse
    {
        $employees = $managerRegistry->getRepository(Employee::class)->findAll();
        $response = [];

        foreach($employees as $employee){
            $response[] = $this->getEmployeeData($employee);
        }
        return $this->json($response);
    }

    #[Route('/employee', name: 'add_employee', methods:['post'])]
    public function add(ManagerRegistry $managerRegistry, Request $request): JsonResponse
    {
        try {
            $entityManager = $managerRegistry->getManager();
            $employee = new Employee();
            $employee->setName($request->request->get('name'));
            $employee->setSurname($request->request->get('surname'));
            $employee->setPosition($request->request->get('position'));

            //TODO:: Add Validation
            if (null !== $request->request->get('parent_id')) {
                $parent = $managerRegistry->getRepository(Employee::class)->find($request->request->get('parent_id'));
                if(null === $parent) {
                    throw new EntityNotFoundException('Parent not found');
                }
                $employee->setParent($parent);
            }

            $entityManager->persist($employee);
            $entityManager->flush();

            $response = $this->getEmployeeData($employee);

            return $this->json($response);
        } catch (\Throwable $exception){
            return $this->json([$exception->getMessage()]);
        }
    }

    private function getEmployeeData(Employee $employee): array {
        return [
            'id' => $employee->getId(),
            'name' => $employee->getName(),
            'surname' => $employee->getSurname(),
            'position' => $employee->getPosition(),
            'parent_id' => $employee->getParent()?->getId()
        ];
    }
}
