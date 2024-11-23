<?php

namespace App\Controller;

use App\Entity\BenefitsType;
use App\Form\BenefitsTypeType;
use App\Repository\BenefitsRepository;
use App\Repository\BenefitsTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class BenefitsController extends AbstractController
{
    public function __construct(
        private readonly BenefitsTypeRepository $benefitsTypeRepository,
        private readonly BenefitsRepository $benefitsRepository,
    ) {}

    #[Route('/benefits', name: 'app_benefits')]
    public function indexAction(Request $request): Response
    {
        $benefits = $this->benefitsTypeRepository->findAll();
        return $this->render('benefits/list.html.twig', [
            'benefits' => $benefits,
        ]);
    }
    #[Route('/benefits/manage/{benefitId}', name: 'app_benefits_manage')]
    public function manageAction(Request $request, int $benefitId = null): Response
    {
        $benefit = $this->benefitsTypeRepository->findOneBy(['id' => $benefitId]);
        if (!$benefit instanceof BenefitsType){
            $benefit = new BenefitsType();
        }

        $form = $this->createForm(BenefitsTypeType::class, $benefit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->benefitsTypeRepository->add($data);
            $this->addFlash('success', 'Erfolgreich gespeichert!');

            return $this->redirectToRoute('app_benefits');
        }

        return $this->render('benefits/manage.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/benefits/delete/{benefitId}', name: 'app_benefits_delete')]
    public function deleteEntity(int $benefitId): Response
    {
        $benefitsType = $this->benefitsTypeRepository->findOneBy(['id' => $benefitId]);
        $benefits = $this->benefitsRepository->findBy(["benefitsType" => $benefitsType]);

        foreach ($benefits as $benefit) {
            $this->benefitsRepository->remove($benefit);
        }

        if ($benefitsType instanceof \App\Entity\BenefitsType) {
            $this->benefitsTypeRepository->remove($benefitsType);
            $this->benefitsTypeRepository->flush();
        }

        return $this->redirectToRoute('app_benefits');
    }
}
