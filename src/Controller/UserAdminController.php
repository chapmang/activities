<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\UserAdminRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserAdminController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class UserAdminController extends BaseController
{

    /**
     * @Route("/admin/listusers", name="app_admin_list_users")
     * @param Request $request
     * @param UserAdminRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function listUsers(Request$request, UserAdminRepository $repository, PaginatorInterface $paginator)
    {
        $users = $repository->findAll();
        $pagination = $paginator->paginate(
            $users, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        return $this->render('useradmin/listusers.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/createuser", name="app_create_user")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {

            $user = new User();
            $user->setUsername($request->get('username'));
            $user->setFirstName($request->get('firstname'));
            $user->setSurname($request->get('surname'));
            $user->setEmail($request->get('email'));
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $request->get('password')
            ));
            if($request->get('adminUser') == 'on') {
                $user->setRoles(["ROLE_ADMIN"]);
            }
            $em  = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_admin_list_users');
        }

        return $this->render('useradmin/listusers.html.twig');
    }
}