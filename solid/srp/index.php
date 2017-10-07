<?php 

class ProfileController {

    private $userService;
    private $userProfileTransformer;

    public function __construct(UserService $userService,
        UserProfileTransformer $userProfileTransformer) {
        $this->userService = $userService;
        $this->userProfileTransformer = $userProfileTransformer;
    }

    public function showProfilePage(Authentication $authentication) {
        return new ViewModel("profile",[
            "user" => $this->getTransformedUser($authentication->getEmail())
        ]);
    }

    public function showProfileEditPage(Authentication $authentication) {
        return new ViewModel("editProfile",[
            "user" => $this->getTransformedUser($authentication->getEmail())
        ]);
    }

    private function getTransformedUser($email) {
        return $this->userProfileTransformer->transform(
            $this->userService->find(
                $email
                )
            );
    }

}

class ProfilePageUpdaterController {

    private $userService;
    private $userValidator;

    public function __construct(UserService $userService, UserValidator $userValidator) {
        $this->userService = $userService;
        $this->userValidator = $userValidator;
    }

    public function updateProfile(Authentication $authentication, User $user) {
        if ($authentication != null && $this->userValidator->valid($user)) {
            $userService->update($user);
        }
        return redirect("/profile");
    }

}
