<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class AccountController extends BaseController
{

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @Route("/account/{id}", name="app_account")
     * @param User $user
     * @return Response
     */
    public function view(User $user)
    {
        return $this->render('account/view.html.twig', [
            'u' => $user ]);
    }

    /**
     * @Route("/account/edit/{id}", name="app_edit_account")
     * @param User $user
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function edit(User $user, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        // Check if users account or sysAdmin
        $this->denyAccessUnlessGranted('MANAGE', $user);

        // This page is reachable for two different purposes
        // Admin and User, so sho slightly different option
        $referer = $request->headers->get('referer');
        if ($referer == $this->generateUrl('app_admin_list_users', [], UrlGeneratorInterface::ABSOLUTE_URL)) {
            $parentTemplate = "admin.html.twig";
        } else {
            $parentTemplate = "base.html.twig";
        };


        /** @TODO Implement proper error */
        if ($this->getUser() !== $user) {
            // User is updating password so need to provide old password
            if ($request->get('newPassword')) {
                if ($passwordEncoder->isPasswordValid($user, $request->get('oldPassword'))){
                    return $this->render('account/edit.html.twig', [
                        'parent_template' => $parentTemplate,
                        'u' => $user]);
                }
            }
        }

        return $this->render('account/edit.html.twig', [
            'parent_template' => $parentTemplate,
            'u' => $user]);
    }
}
