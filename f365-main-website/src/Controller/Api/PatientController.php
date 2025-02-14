<?php

namespace App\Controller\Api;

use App\Dto\CreatePatientDto;
use App\Dto\UpdatePatientDto;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/patients')]
readonly class PatientController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    )
    {}

    /**
     * List patients with pagination and optional DOB search.
     */
    #[Route('', name: 'patient_index', methods: ['GET'])]
    #[OA\Get(
        summary: "Get paginated list of patients",
        parameters: [
            new OA\QueryParameter(name: "dob", description: "Filter by Date of Birth (YYYY-MM-DD)", required: false, schema: new OA\Schema(type: "string")),
            new OA\QueryParameter(name: "page", description: "Page number", required: false, schema: new OA\Schema(type: "integer", default: 1)),
            new OA\QueryParameter(name: "limit", description: "Results per page", required: false, schema: new OA\Schema(type: "integer", default: 10))
        ]
    )]
    #[OA\Response(
        response: 200,
        description: "List of patients (paginated)"
    )]
    public function index(Request $request): JsonResponse
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = max(1, (int) $request->query->get('limit', 10));
        $dob = $request->query->get('dob');

        $queryBuilder = $this->entityManager
            ->getRepository(Patient::class)
            ->createQueryBuilder('p')
            ->where('p.onHold = :onHold')
            ->setParameter('onHold', false);

        $dob = $dob ? \DateTime::createFromFormat('Y-m-d', $dob) : null;

        if ($dob) {
            $queryBuilder
                ->andWhere('p.dob = :dob')
                ->setParameter('dob', $dob->format('Y-m-d'));
        }

        $query = $queryBuilder
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $patients = iterator_to_array($paginator);

        return new JsonResponse(
            $this->serializer->normalize([
                'page' => $page,
                'limit' => $limit,
                'total' => count($paginator),
                'data' => $patients
            ], 'json', ['groups' => 'patient:read']),
            Response::HTTP_OK
        );
    }

    /**
     * Get a single patient by ID.
     */
    #[Route('/{id}', name: 'patient_show', methods: ['GET'])]
    #[OA\Get(summary: "Get patient by ID")]
    #[OA\Response(
        response: 200,
        description: "Patient updated successfully"
    )]
    #[OA\Response(response: 404, description: "Patient not found")]
    public function show(int $id): JsonResponse
    {
        $patient = $this->entityManager->getRepository(Patient::class)->findOneBy([
            'id' => $id,
            'onHold' => false,
        ]);

        if (!$patient) {
            return new JsonResponse(['message' => 'Patient not found'], 404);
        }


        return new JsonResponse(
            $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Create a new patient.
     */
    #[Route('', name: 'patient_create', methods: ['POST'])]
    #[OA\Post(
        summary: "Create a new patient",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Mr"),
                    new OA\Property(property: "firstName", type: "string", example: "John"),
                    new OA\Property(property: "lastName", type: "string", example: "Doe"),
                    new OA\Property(property: "dob", type: "string", format: "date", example: "1990-05-15"),
                ]
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Patient created successfully"
    )]
    #[OA\Response(response: 400, description: "Invalid input data")]
    public function create(Request $request): JsonResponse
    {
        try {
            /** @var CreatePatientDto $createPatientDto */
            $createPatientDto = $this->serializer->deserialize($request->getContent(), CreatePatientDto::class, 'json');
            $errors = $this->validator->validate($createPatientDto);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }
                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $patient = new Patient();
            $patient->setTitle($createPatientDto->title);
            $patient->setFirstName($createPatientDto->firstName);
            $patient->setLastName($createPatientDto->lastName);
            $patient->setDob(\DateTime::createFromFormat('Y-m-d', $createPatientDto->dob));
            $patient->setCreatedAt(new \DateTime());
            $patient->setUpdatedAt(new \DateTime());

            $this->entityManager->persist($patient);
            $this->entityManager->flush();

            return new JsonResponse(
                $this->serializer->serialize($patient, 'json', ['groups' => 'patient:read']),
                Response::HTTP_CREATED,
                [],
                true
            );
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Invalid input: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update a patient.
     */
    #[Route('/{id}', name: 'patient_update', methods: ['PATCH'])]
    #[OA\Patch(
        summary: "Update an existing patient",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Dr"),
                    new OA\Property(property: "firstName", type: "string", example: "Jane"),
                    new OA\Property(property: "lastName", type: "string", example: "Doe"),
                    new OA\Property(property: "dob", type: "string", format: "date", example: "1985-02-10"),
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Patient updated successfully"
    )]
    #[OA\Response(response: 404, description: "Patient not found")]
    public function update(int $id, Request $request): JsonResponse
    {
        $existingPatient = $this->entityManager->getRepository(Patient::class)->findOneBy(['id' => $id, 'onHold' => false]);
        if (!$existingPatient) {
            return new JsonResponse(['message' => 'Patient not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            /** @var UpdatePatientDto $updatePatientDto */
            $updatePatientDto = $this->serializer->deserialize($request->getContent(), UpdatePatientDto::class,'json');

            $errors = $this->validator->validate($updatePatientDto);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }
                return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            $updated = false;
            if ($updatePatientDto->title) {
                $updated = true;
                $existingPatient->setTitle($updatePatientDto->title);
            }
            if ($updatePatientDto->firstName) {
                $updated = true;
                $existingPatient->setFirstName($updatePatientDto->firstName);
            }
            if ($updatePatientDto->lastName) {
                $updated = true;
                $existingPatient->setLastName($updatePatientDto->lastName);
            }
            if ($updatePatientDto->dob) {
                $updated = true;
                $existingPatient->setDob(\DateTime::createFromFormat('Y-m-d', $updatePatientDto->dob));
            }

            if ($updated) {
                $existingPatient->setUpdatedAt(new \DateTime());
                $this->entityManager->persist($existingPatient);
                $this->entityManager->flush();
            }


            return new JsonResponse(
                $this->serializer->serialize($existingPatient, 'json', ['groups' => 'patient:read']),
                Response::HTTP_OK,
                [],
                true
            );
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Invalid input: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete a patient.
     */
    #[Route('/{id}', name: 'patient_delete', methods: ['DELETE'])]
    #[OA\Delete(summary: "Delete a patient")]
    #[OA\Response(response: 204, description: "Patient deleted successfully")]
    #[OA\Response(response: 404, description: "Patient not found")]
    public function delete(int $id): JsonResponse
    {
        $patient = $this->entityManager->getRepository(Patient::class)->findOneBy([
            'id' => $id,
            'onHold' => false,
        ]);
        if (!$patient) {
            return new JsonResponse(['message' => 'Patient not found'], Response::HTTP_NOT_FOUND);
        }

        $patient->setOnHold(true);
        $this->entityManager->persist($patient);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
