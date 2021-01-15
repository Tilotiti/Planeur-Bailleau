<?php


namespace App\Controller\Admin;


use App\Controller\ExtendedController;
use App\Entity\Aircraft;
use App\Form\AircraftType;
use App\Repository\AircraftRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AircraftController
 * @package App\Controller\Admin
 * @Route("/admin/aircraft")
 * @IsGranted("ROLE_ADMIN")
 */
class AircraftController extends ExtendedController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var AircraftRepository
     */
    private AircraftRepository $aircraftRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        AircraftRepository $aircraftRepository
    ) {
        $this->entityManager = $entityManager;
        $this->aircraftRepository = $aircraftRepository;
    }

    /**
     * @Route("", name="admin_aircraft")
     */
    public function index(): Response {
        $aircrafts = $this->aircraftRepository->findBy([], [
            'competitionNumber' => 'ASC'
        ]);


        return $this->render('admin/aircraft/index.html.twig', [
            'aircrafts' => $aircrafts
        ]);
    }

    /**
     * @Route("/add", name="admin_aircraft_add")
     * @Route("/edit/{aircraft}", name="admin_aircraft_edit")
     * @param Request $request
     * @param Aircraft|null $aircraft
     * @return Response
     */
    public function form(Request $request, ?Aircraft $aircraft = null): Response {
        if(!$aircraft) {
            $aircraft = new Aircraft();
        }

        $form = $this->createForm(AircraftType::class, $aircraft);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($aircraft);
            $this->entityManager->flush();

            $this->addFlash('success', "La machine a été enregistrée.");
            return $this->redirectToRoute('admin_aircraft');
        }

        return $this->render('admin/aircraft/form.html.twig', [
            'aircraft' => $aircraft,
            'form' => $form->createView()
        ]);
    }
}