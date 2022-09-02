<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use App\TranslationJob\TranslationJob;
use App\Entity\Request;
use App\Repository\RequestRepository;

class TranslateController extends AbstractController
{
    #[Route('/translate/{lang}/{text}', name: 'app_translate')]
    public function index(string $lang, string $text, LoggerInterface $logger, MessageBusInterface $bus, RequestRepository $requestRepository): JsonResponse
    {
        //We look for a equivalent request done in the past
        $request = $requestRepository->findOneBy([
            'lang_to' => $lang,
            'search' => urldecode($text),
        ]);

        if ($request){
            //If we find a request, we return that
            $message = json_encode($request);
        } else {
            //Otherwise we log the new request and then add it to the queue to be processed
            $logger->info('Request to translate "' . urldecode($text) . '" into '. $lang);
            $bus->dispatch(new TranslationJob(json_encode(['date' => date(\DateTimeInterface::ATOM), 'lang' => $lang, 'text' => urldecode($text)])));
            $message = 'Your request have been queued for translation';  
        }
        
        return $this->json([
            'message' => $message,
        ]); 
    }

    #[Route('/list', name: 'app_list')]
    public function list(RequestRepository $requestRepository): JsonResponse
    {
        $requests = $requestRepository->findAll();
        $jsonRequests = [];
        if ($requests) {
            foreach($requests as $req) {
                $jsonRequests[] = json_encode($req);
            }
        } 
        return $this->json(['requests' => $jsonRequests]);
    }

    #[Route('/get/{lang}/{text}', name: 'app_get')]
    public function get(string $lang, string $text, RequestRepository $requestRepository): JsonResponse
    {
        $request = $requestRepository->findOneBy([
            'lang_to' => $lang,
            'search' => urldecode($text),
        ]);
        return $this->json([
            'result' => is_null($request) ? "We couldn´t find any result" : $request->getResult(),
        ]);
    }
}
