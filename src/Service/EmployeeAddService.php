<?php

namespace App\Service;

use App\Entity\Employee;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmployeeAddService
{
    public function __construct(private ManagerRegistry $managerRegistry, private ValidatorInterface $validator)
    {
    }


    public function addEmployee($content): void
    {
        if (empty($content->get('name')) || empty($content->get('surname')) || empty($content->get('position'))) {
            throw new \InvalidArgumentException('Name, Surname and Position are required');
        }

        $employee = new Employee();

        $employee->setName($content->get('name'));
        $employee->setSurname($content->get('surname'));
        $employee->setPosition($content->get('position'));

        $errors = $this->validator->validate($employee);

        if (count($errors) > 0) {
            throw new \InvalidArgumentException($errors);
        }

        if (null !== $content->get('parent_id')) {
            $parent = $this->managerRegistry->getRepository(Employee::class)->find($content->get('parent_id'));
            if (null === $parent) {
                throw new EntityNotFoundException('Parent not found');
            }
            $employee->setParent($parent);
        }

        $entityManager = $this->managerRegistry->getManager();

        $entityManager->persist($employee);
        $entityManager->flush();
    }
}