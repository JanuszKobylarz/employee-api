<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    //Search for employees by name or surname
    public function search(string $search): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.position LIKE :search')
            ->setParameter('search', "%$search%")
            ->groupBy('e.position')
            ->getQuery()
            ->getResult();
    }

}
