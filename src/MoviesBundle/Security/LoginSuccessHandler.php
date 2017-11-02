<?php
// AcmeBundle\Security\LoginSuccessHandler.php

namespace MoviesBundle\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface {

    protected $router;
    protected $authorizationChecker;

    public function __construct(Router $router, AuthorizationChecker $authorizationChecker) {
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {

        $response = null;

        if(isset($_SESSION['_sf2_attributes']['url']) && !empty($_SESSION['_sf2_attributes']['url'])){
            $response = new RedirectResponse($_SESSION['_sf2_attributes']['url']);
        } else if($request->request->has('film') && $request->request->has('p')
            && $request->request->get('p') == 1){
            $response = new RedirectResponse($this->router->generate('comment_add'));
        } else if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $response = new RedirectResponse($this->router->generate('films'));
        } else if ($this->authorizationChecker->isGranted('ROLE_USER')) {
            $response = new RedirectResponse($this->router->generate('films'));
        }
        return $response;
    }

}