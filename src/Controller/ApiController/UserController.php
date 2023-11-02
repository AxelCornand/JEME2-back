<?php

namespace App\Controller\ApiController;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


/**
 * @Route("/api/", name="app_api_")
 */
class UserController extends AbstractController {
    /**
     * @Route("userList", name="users", methods={"GET"})
     */
    public function userListJson(UserRepository $userRepository): Response
    {

        // Search for all users in the BDD
        $usersList = $userRepository->findAll();

        // Return Json of all users to the front
        return $this->json(
            // List of users convert in Json
            $usersList,
            // The status code 200
            200,
            // The header
            [],
            // Element group for users
            ['groups' => 'get_users_list']
        );
    }

    /**
     * @Route("user/info", name="user_token", methods={"GET"})
     */
    public function userJson(): Response
    {
        // Search for one user in the BDD by the token
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Return Json of one user to the front
        return $this->json(
            // One user convert in Json
            $user,
            // The status code 202
            202,
            // The header
            [],
            // Element group for user
            ['groups' => 'get_users_info']
        );
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ManagerRegistry $doctrine
     * @return void
     * 
     * @Route("user" , name="user", methods={"POST"})
     */
    public function createItemUser(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): JsonResponse
    {

        $content = $request->getContent();
        if (empty($content)) {
            return new JsonResponse(['error' => 'La requête doit contenir un corps JSON.'], JsonResponse::HTTP_BAD_REQUEST);
        }
        // Translation of Json information and creation of a class
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        // Check all parameters to look if they are not empty and if 'firstname' and '  lastname' are both string
        $missingFields = [];
        if (!$user->getFirstname() || !preg_match('/^[\p{L}]*$/u', $user->getFirstname())) {
            $missingFields[] = 'firstname';
            return new JsonResponse(['error' => sprintf('Prénom invalide')], JsonResponse::HTTP_BAD_REQUEST);
        }
        if (!$user->getLastname() || !preg_match('/^[\p{L}]*$/u', $user->getLastname())) {
            $missingFields[] = 'lastname';
            return new JsonResponse(['error' => sprintf('Nom invalide')], JsonResponse::HTTP_BAD_REQUEST);
        }
        if (!$user->getEmail()) {
            $missingFields[] = 'email';
        }
        if (!$user->getPassword()) {
            $missingFields[] = 'password';
        }

        // Add Rules for password 
        if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}$/', $user->getPassword())) {
            return new JsonResponse(['error' => 'Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, un chiffre et un caractère spécial.'], JsonResponse::HTTP_BAD_REQUEST);
        }
        // Password hash
        $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
        // Save the password hash
        $user->setPassword($hashedPassword);


        // Look if the user already exist in database
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
        if ($existingUser) {
            return new JsonResponse(['error' => 'Email ou mot de passe invalide.'], JsonResponse::HTTP_BAD_REQUEST);
        }


        // Save use in the BDD
        $userRepository->add($user, true);
        // Creation of the user in the database

        // Return Json of for the creation of the new user to the front
        return $this->json(
            // Information of the creation convert in Json
            $user,
            // The status code of create 201
            Response::HTTP_CREATED,
            // The header
            [],
            // Element group for user
            ['groups' => 'get_itemUser']
        );
    }

    /**
     * @Route("changeUser", name="change_user", methods={"POST"})
     */
    public function changeUserProfil(Request $request,UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): JsonResponse
    {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if(empty ($user)){
            return new JsonResponse(['message' => 'User non trouvé '],Response::HTTP_NOT_FOUND);
        }
        $newUser = json_decode($request->getContent(), true);
        
        if(!$newUser){
            return new JsonResponse(['message' => 'NewUser non trouvé'],Response::HTTP_NOT_FOUND);
        }
        $user->setFirstname($newUser["newFirstname"]);
        $user->setLastname($newUser["newLastname"]);
        $user->setPassword($newUser["newPassword"]);
        
        $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPassword()));
        $userRepository->add($user, true);

        return new JsonResponse(['message' => 'Profil modifié'], Response::HTTP_OK);
    }
}