<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Form builder
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

class TempController extends AbstractController
{

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
		 * Build a form.
		 */
		$fb = $this->createFormBuilder();
		$fb->add('task', TextType::class, [
			'label' => 'Task Title',
			'required' => false,
			'attr' => [
				'placeholder' => 'Task title',
				'class' => 'hello-dolly',
			]
			// 'constraints' => [
			// 	new NotBlank(),
			// ],
		]);
		$fb->add('save', SubmitType::class, [
			'label' => 'Create Task',
		]);
		$form = $fb->getForm();

		// Handle the form request.
		$form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

			$this->addFlash(
	            'success',
	            'Success Message!'
	        );
			$this->addFlash(
	            'primary',
	            'Primary Message!'
	        );
			$this->addFlash(
	            'primary',
	            'Primary Message!'
	        );
			$this->addFlash(
	            'danger',
	            'Danger Message!'
	        );

			echo '<pre>'.print_r($data, true).'</pre>';
            // return $this->redirectToRoute('task_success');
        }


		/**
		 * Return the content.
		 */
        return $this->render('temp/index.html.twig', [
            'controller_name' => 'TempController',
			'form' => $form->createView(),
        ]);
    }
}
