<?php
 namespace App\Controller;
 use App\Entity\MeetsUser;
 use App\Entity\WheelUser;
 use App\Repository\MeetsUserRepository;
 use App\Repository\PairRepository;
 use App\Repository\WheelUserRepository;
 use http\Env\Response;
 use Symfony\Component\HttpFoundation\Cookie;
 use App\Entity\User;
 use App\Repository\UserRepository;
 use Doctrine\ORM\EntityManagerInterface;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
 use Symfony\Component\Routing\Annotation\Route;
 use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
 use Symfony\Component\Security\Core\User\UserInterface;




 /**
  * Class UserController
  * @package App\Controller
  * @Route("/api", name="user_api")
  */
 class UserController extends AbstractController
 {

     /**
      * @param Request $request
      * @param UserRepository $userRepository
      * @return JsonResponse
      * @throws \Exception
      * @Route("/login", name="user_login", methods={"POST"})
      */
     public function loginUser(Request $request,UserRepository $userRepository,UserPasswordHasherInterface $encoder)
     {
         try {
             $request = $this->transformJsonBody($request);
               // dd($user);
             $login = $request->get('login');
             $password = $request->get('password');

             $user = $userRepository->findOneBy(["login" => $login]);
            if (!$user){
                $data = [
                    'status' => 401,
                    'errors' => "User with login " .$login." not found ",
                ];
                return $this->response($data);
            }


            // if ($user->getPassword() == $password) {
             if ($encoder->isPasswordValid($user, $password)) {
                 //do autorization

                 $data = [
                     'status' => 200,
                     'success' => "User is authorized ",
                 ];
                 return $this->response($data);
             }else{
                 $data = [
                     'status' => 401,
                     'errors' => "Password - incorrect",
                 ];
                 return $this->response($data);
             }
         }catch (\Exception $e) {
             $data = [
                 'status' => 401,
                 'errors' => "Data no valid",
             ];
             return $this->response($data, 401);
         }

     }


     /**
      * @param UserRepository $userRepository
      * @return JsonResponse
      * @Route("/check-login/{slug}", name="checkLogin", methods={"GET"})
      */
     public function checkLogin(UserRepository $userRepository, $slug){
         try {
             $user = $userRepository->findOneBy(["login" => $slug]);
             if (!$user){
                 $data = [
                     'status' => 200,
                     'success' => "No user found with login - ".$slug,
                 ];
                 return $this->response($data, 200);
             }
             $data = [
                 'status' => 409,
                 'errors' => "User ".$slug." found in DB",
             ];
             return $this->response($data, 409);
         }catch (\Exception $e){
             $data = [
                 'status' => 409,
                 'errors' => "Data no valid",
             ];
             return $this->response($data, 409);
         }
     }
     /**
      * @param Request $request
      * @param EntityManagerInterface $entityManager
      * @param UserRepository $userRepository
      * @return JsonResponse
      * @throws \Exception
      * @Route("/register", name="users_add", methods={"POST"})
      */
     public function addUser(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $encoder){

         try{
             $request = $this->transformJsonBody($request);

             if (!$request || !$request->get('email') || !$request->request->get('login')){
                 throw new \Exception();
             }
             $user = new User();
             $userok = $userRepository->findOneBy(["email" => $request->get('email')]);
             if (isset($userok)){
                 $data = [
                     'status' => 422,
                     'errors' => "email - ". $request->get('email') . " exists",
                 ];
                 return $this->response($data, 422);
             }else{
                 $user->setEmail($request->get('email'));
             }
             $user->setLogin($request->get('login'));
             $user->setName($request->get('name'));
             $user->setPhone($request->get('phone'));
             $user->setCompanyName($request->get('companyName'));
             $user->setCompanyAddress($request->get('companyAddress'));
             $inputPassword = rand(8000000,160000000);
             $password = $encoder->hashPassword($user, $inputPassword);
             $user->setPassword($password);
             $entityManager->persist($user);
             $entityManager->flush();

             $data = [
                 'status' => 201,
                 'success' => "User added successfully",
                 'password' => $inputPassword,
             ];
             return $this->response($data);

         }catch (\Exception $e){
             $data = [
                 'status' => 422,
                 'errors' => "Data no valid",
             ];
             return $this->response($data, 422);
         }

     }

     /**
      * @param $slug
      * @param UserRepository $userRepository
      * @return JsonResponse
      * @Route("/user-data/{slug}", name="user_get", methods={"GET"})
      */
     public function getUserData($slug,UserRepository $userRepository)
     {
         try {
             if ($user = $userRepository->findOneBy(["login" => $slug])){
                 $data = [
                     'status' => 200,
                     'email' => $user->getEmail(),
                     'login' => $slug,
                     'name' => $user->getName(),
                     'companyName' => $user->getCompanyName(),
                     'companyAddress' => $user->getCompanyAddress(),
                 ];
                 return $this->response($data);
             }else{
                 $data = [
                     'status' => 401,
                     'errors' => "No user found",
                 ];
                 return $this->response($data, 401, ['Access-Control-Allow-Origin' => '*']);
             }
         }catch (\Exception $e){
             $data = [
                 'status' => 401,
                 'errors' => "No valid data",
             ];
             return $this->response($data, 401);
         }

     }


     /**
      * @param Request $request
      * @param EntityManagerInterface $entityManager
      * @param UserRepository $userRepository
      * @return JsonResponse
      * @Route("/user-data/{slug}", name="user_put", methods={"PUT"})
      */
     public function updateUser(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, $slug){

         try{
             $user = $userRepository->findOneBy(["login" => $slug]);

             if (!$user){
                 $data = [
                     'status' => 404,
                     'errors' => "User not found",
                 ];
                 return $this->response($data, 404);
             }

             $request = $this->transformJsonBody($request);

//             if (!$request || !$request->get('email') || !$request->request->get('login')){
//                 throw new \Exception();
//             }

             $user->setEmail($request->get('email'));
             $user->setName($request->get('name'));
            // $user->setPhone($request->get('phone'));
             $user->setCompanyName($request->get('companyName'));
             $user->setCompanyAddress($request->get('companyAddress'));
             $entityManager->persist($user);
             $entityManager->flush();

             $data = [
                 'status' => 202,
                 'success' => "User updated successfully",
             ];
             return $this->response($data);

         }catch (\Exception $e){
             $data = [
                 'status' => 401,
                 'errors' => "Data no valid",
             ];
             return $this->response($data, 401);
         }
     }

     /**
      * @param $slug
      * @return JsonResponse
      * @Route("/user-stats/{slug}", name="user_stats", methods={"GET"})
      */
     public function getUserStats($slug){

         $data = [
             'success' => 50.3,
             'involvement'=>75,
             'deals-stats' => [
                 'deal1' => 30,
                 'deal2' => 63.7,
                 'deal3' => 33,
             ],
         ];
         return $this->response($data);
     }

     /**
      * Returns a JSON response
      *
      * @param array $data
      * @param $status
      * @param array $headers
      * @return JsonResponse
      */
     public function response($data, $status = 200, $headers = ['Access-Control-Allow-Origin' => '*'])
     {
         return new JsonResponse($data, $status, $headers);
     }

     protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
     {
         $data = json_decode($request->getContent(), true);

         if ($data === null) {
             return $request;
         }

         $request->request->replace($data);

         return $request;
     }

     /**
      * @return JsonResponse
      * @Route("/wheel-user-add", name="wheel_user_add", methods={"POST"})
      */
     public function setWheelUserData(Request $request, EntityManagerInterface $entityManager, WheelUserRepository $wheelUserRepository){

         try{
             $request = $this->transformJsonBody($request);
             $user = $wheelUserRepository->findOneBy(["playerID" => $request->get('playerID')]);
             if (!$user){
                // dump($request->get('playerID'));
//             dump($request->get('amount'));
//             dump($request);
//             die;
                 $wheelUser = new WheelUser();
                 $wheelUser->setPlayerID($request->get('playerID'));
                 $wheelUser->setAmount($request->get('amount'));

                 $entityManager->persist($wheelUser);
                 $entityManager->flush();

                 $data = [
                     'status' => 201,
                     'success' => "User added successfully",
                 ];
                 return $this->response($data,201);
             }else{
                 $data = [
                     'status' => 456,
                     'errors' => "User exists",
                 ];
                 return $this->response($data, 456);
             }

//

         }catch (\Exception $e){
             $data = [
                 'status' => 422,
                 'errors' => "Data no valid",
             ];
             return $this->response($data, 422);
         }
     }

     /**
      * @param Request $request
      * @param EntityManagerInterface $entityManager
      * @param WheelUserRepository $wheelUserRepository
      * @param $slug
      * @return JsonResponse
      * @Route("/wheel-amount-update/{slug}/{amount}", name="wheel_amount-update", methods={"PUT"})
      */
     public function wheelAmountUpdate(Request $request, EntityManagerInterface $entityManager, WheelUserRepository $wheelUserRepository, $slug, $amount){

         try{
             $user = $wheelUserRepository->findOneBy(["playerID" => $slug]);

             if (!$user){
                 $data = [
                     'status' => 404,
                     'errors' => "User not found",
                 ];
                 return $this->response($data, 404);
             }

          //   $request = $this->transformJsonBody($request);

//             if (!$request || !$request->get('email') || !$request->request->get('login')){
//                 throw new \Exception();
//             }

             $user->setAmount($user->getAmount() + $amount);
             $entityManager->persist($user);
             $entityManager->flush();

             $data = [
                 'status' => 202,
                 'success' => "amount updated successfully",
             ];
             return $this->response($data,202);

         }catch (\Exception $e){
             $data = [
                 'status' => 401,
                 'errors' => "Data no valid",
             ];
             return $this->response($data, 401);
         }
     }

     /**
      * @param Request $request
      * @param EntityManagerInterface $entityManager
      * @param WheelUserRepository $wheelUserRepository
      * @param $slug
      * @return JsonResponse
      * @Route("/wheel-amount-get/{slug}", name="wheel_amount-get", methods={"GET"})
      */
     public function getWheelAmount(Request $request, EntityManagerInterface $entityManager, WheelUserRepository $wheelUserRepository, $slug){

         try{
             $user = $wheelUserRepository->findOneBy(["playerID" => $slug]);

             if (!$user){
                 $data = [
                     'amount' => 0,
                     'status' => 404,
                     'errors' => "User not found",
                 ];
                 return $this->response($data, 404);
             }

             //   $request = $this->transformJsonBody($request);

//             if (!$request || !$request->get('email') || !$request->request->get('login')){
//                 throw new \Exception();
//             }

            $amount = $user->getAmount();
             $data =[
                 'amount' => $amount
             ];
            return $this->response($data,222);


         }catch (\Exception $e){
             $data = [
                 'status' => 401,
                 'errors' => "Data no valid",
             ];
             return $this->response($data, 401);
         }
     }

     /**
      * @return JsonResponse
      * @Route("/meetsgetuser/{deviceID}", name="meetsgetuser", methods={"GET"})
      */
     public function getMeetsUserData(MeetsUserRepository $meetsUserRepository, $deviceID){
         $user = $meetsUserRepository->findOneBy(["deviceID" => $deviceID]);
//         $data = ['webview' => 'without button - on'];
//         return $this->response($data,200);
         try{
             $data = ['user'=>[
                 'deviceID' => $user->getDeviceID(),
                 'nickname' =>$user->getNickname(),
                 'name' =>$user->getName(),
                 'age' =>$user->getAge(),
                 'zodiac' =>$user->getZodiac(),
                 'meal_preferences' =>$user->getMealPreferences(),
                 'human_preferences' =>$user->getHumanPreferences(),
                 'photo' => $user->getPhoto(),
                 'gender' =>$user->getGender()
             ]
             ];
             return $this->response($data,201);
         }catch (\Exception $e){
             $data = [
                 'status' => 404,
                 'errors' => $e->getMessage(),
             ];
             return $this->response($data, 404);
         }
     }

     /**
      * @return JsonResponse
      * @Route("/meets-user-add", name="meets_user_add", methods={"POST"})
      */
     public function addMeetsUserData(Request $request, EntityManagerInterface $entityManager, MeetsUserRepository $meetsUserRepository){
         $request = $this->transformJsonBody($request);
         var_dump($request);
         $meetsUser = new MeetsUser();
         $meetsUser->setDeviceID($request->get('deviceID'));
         $meetsUser->setNickname($request->get('nickname'));
         $meetsUser->setName($request->get('name'));
         $meetsUser->setAge($request->get('age'));
         $meetsUser->setZodiac($request->get('zodiac'));
         $meetsUser->setMealPreferences($request->get('meal_preferences'));
         $meetsUser->setHumanPreferences($request->get('human_preferences'));
         $meetsUser->setPhoto($request->get('photo'));
         $meetsUser->setGender($request->get('gender'));
         try{
             $entityManager->persist($meetsUser);
             $entityManager->flush();
             $data = [
                 'status' => 201,
                 'success' => "User added successfully",
             ];
             return $this->response($data,201);
         }catch (\Exception $e){
             $data = [
                 'status' => 422,
                 'errors' => $e->getMessage(),
             ];
             return $this->response($data, 422);
         }

     }


     /**
      * @return JsonResponse
      * @Route("/pair/{deviceID}", name="get_user_meets", methods={"GET"})
      */
     public function getPair(MeetsUserRepository $meetsUserRepository,PairRepository $pairRepository,$deviceID){
         $user = $meetsUserRepository->findOneBy(["deviceID" => $deviceID]);
         $gender = $user->getGender();
         if ($gender == "male"){
             $pairs = $pairRepository->findBy(["gender" => "female"]);
         }elseif ($gender == "female"){
             $pairs = $pairRepository->findBy(["gender" => "male"]);
         }
         $ids = [];
         foreach ($pairs as $pair){
            $ids[] += $pair->getId();
         }
         try{
             $userPair = $pairRepository->find($ids[rand(0,count($ids)-1)]);
             $data = ['pair'=>[
                 'name' =>$userPair->getName(),
                 'age' =>$userPair->getAge(),
                 'photo' => $userPair->getPhoto()
             ]
             ];
             return $this->response($data,201);
         }catch (\Exception $e){
             $data = [
                 'status' => 404,
                 'errors' => $e->getMessage(),
             ];
             return $this->response($data, 404);
         }
     }
 }