<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/person", name="person")
     */
    public function person(Request $request)
    {
        $name = $request->query->get('name', '');
        $mngr = $this->get('fos_elastica.index_manager');
        $search = $mngr->getIndex('app')->createSearch();
        $query = new \Elastica\Query(new \Elastica\Query\MatchNone());
        $suggest = new \Elastica\Suggest();
        $suggest->setParam('suggest', ['text' => $name, 'completion' => ['field' => 'name']]);
        $query->setSuggest($suggest);

        $result = $search->search($query);
        $data = array_map(function ($item) {
            return $item['text'];
        }, $result->getSuggests()['suggest'][0]['options'] ?? []);

        return new JsonResponse(['search' => $name, 'suggestions' => $data]);
    }
}
