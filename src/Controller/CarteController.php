<?php

namespace App\Controller;
use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use App\Controller\AppointmentController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/carte", name="carte_")
 */
class CarteController extends AbstractController
{
    #[Route('/carte', name: 'index')]
    public function index(SessionInterface $session, AppointmentRepository $appointmentRepository): Response
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $appointment = $appointmentRepository->find($id);
            $dataPanier[] = [
                "produit" => $appointment,
                "quantite" => $quantite
            ];
            $total += $appointment->getPrice() * $quantite;
        }

        return $this->render('carte/index.html.twig', compact("dataPanier", "total"));
    }
    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(Appointment $Appointment, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $Appointment->getId();

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("carte_index");
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Appointment $Appointment, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $Appointment->getId();

        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("carte_index");
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Appointment $AppointmentController, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $AppointmentController->getId();

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("carte_index");
    }

    /**
     * @Route("/delete", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("carte_index");
    }
    #[Route('/{id}', name: 'app_appointment_delete', methods: ['POST'])]
    public function deletee(Request $request, Appointment $appointment, AppointmentRepository $appointmentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $appointmentRepository->remove($appointment);
        }

        return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
    }
}
