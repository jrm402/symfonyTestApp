<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Form builder
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TempController extends AbstractController
{

	private $request;

    /**
     * @Route("/temp", name="temp")
     */
    public function index(Request $request): Response
    {
		/**
		 * DBAL
		 */
		// Select
		// $em = $this->getDoctrine()->getManager();
		// $stmt = $em->getConnection()->prepare('select * from users where uid > :uid');
		// $stmt->bindValue(':uid', 0);
		// $stmt->execute();
		//
		// $res = $stmt->fetchAll();
		// echo '<pre>'.print_r($res,true).'</pre>';

		// Insert
		//

		// Update
		//


		/**
		 * Build a form and handle submit.
		 */
		$this->request = $request;

		$defaults = [
			'date' => new \DateTime('03/11/2020'),
		];
		$form = $this->buildForm($defaults);
		if($this->validateForm($form)) {
			return $this->submitForm($form);
		}

		/**
		 * Return the content.
		 */
        return $this->render('temp/index.html.twig', [
			'form' => $form->createView(),
        ]);
    }

	/**
	 * Create a form.
	 */
	private function buildForm(array $defaults = []): FormInterface
	{
		$fb = $this->createFormBuilder($defaults);

		$fb->add('task', TextType::class, [
			'label' => 'Task Title',
			'required' => false,
			'attr' => [
				'placeholder' => 'Task title',
				'class' => 'hello-dolly',
			],
		]);
		$fb->add('date', DateType::class, [
			'label' => 'Date',
			'required' => false,
			'attr' => [],
		]);
		$fb->add('save', SubmitType::class, [
			'label' => 'Create Task',
		]);

		return $fb->getForm();
	}

	/**
	 * Validate form.
	 */
	private function validateForm(FormInterface $form): bool
	{
		// Handle the form request.
		$form->handleRequest($this->request);
		if($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();
			$success = true;

			if($data['task'] == '') {
				$this->addFlash('danger', 'Task cannot be blank.');
				$success = false;
			}

			echo '<pre>'.print_r($data['date'],true).'</pre>';

			return $success;

			// $this->addFlash('success', 'Success Message! @temp');
			// $this->addFlash('primary', 'Primary Message!');
			// $this->addFlash('danger', 'Danger Message!');
        }

		return false;
	}

	/**
	 * Submit form.
	 */
	private function submitForm(FormInterface $form): Response
	{
		$this->addFlash('success', 'Form submitted successfully');
		return $this->redirectToRoute('temp');
	}
}
