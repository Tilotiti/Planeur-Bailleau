<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ExtendedController extends AbstractController
{
    /**
     * Creates and returns a Form instance from the filter.
     * @param string $type
     * @param null $data
     * @param array $options
     * @return FormInterface
     */
    protected function createFilter(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->container->get('form.factory')->createNamed('', $type, $data, $options);
    }

    /**
     * @return RedirectResponse
     */
    protected function back(): RedirectResponse {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $router = $this->container->get('router');

        $back = $request->server->get('HTTP_REFERER', $router->generate('index'));

        return $this->redirect($back);
    }

    /**
     * @return RedirectResponse
     */
    protected function reload(): RedirectResponse {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $router = $this->container->get('router');

        $current = $request->server->get('REQUEST_URI', $router->generate('index'));

        return $this->redirect($current);
    }
}