<?php
namespace App\TranslationJob;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\TranslationClient\TranslationClientFacade;
use App\Entity\Request;
use App\Repository\RequestRepository;


#[AsMessageHandler]
class TranslationJobHandler
{
    protected $requestRepository;
    protected $httpClient;
    protected $logger;

    public function __construct(RequestRepository $requestRepository, HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->requestRepository = $requestRepository;
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    public function __invoke(TranslationJob $message)
    {
        $data = json_decode($message->getContent());
        $translation = TranslationClientFacade::translate($data->lang, $data->text, $this->httpClient);

        if ($translation) {
            $date = \DateTime::createFromFormat(\DateTimeInterface::ATOM, $data->date);
            $request = new Request();
            $request->setLangFrom($translation['lang_from']);
            $request->setLangTo($data->lang);
            $request->setSearch($data->text);
            $request->setDatetime($date);
            $request->setResult($translation['text']);
            
            try {
                $this->requestRepository->add($request, true);
            } catch(\Exception $e) {
                $this->logger->error('Something went wrong while trying to insert '.print_r($request,1));
                $this->logger->error($e->getMessage());
                return false;
            }
        } else {
            $this->logger->error('No translation services availables at the moment');
            return false;
        }
        return true;
    }
}