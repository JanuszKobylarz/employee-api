<?php

namespace App\Controller;

use App\Entity\Employee;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class EmployeeController extends AbstractController
{
    #[Route('/employees', name: 'app_employee', methods:['get'])]
    public function index(ManagerRegistry $managerRegistry): JsonResponse
    {
        $employees = $managerRegistry->getRepository(Employee::class)->findAll();
        $response = [];

        foreach($employees as $employee){
            $response[] = [
                'id' => $employee->getId(),
                'name' => $employee->getName(),
                'surname' => $employee->getSurname(),
                'position' => $employee->getPosition()
            ];
        }
        return $this->json($response);
    }
}
