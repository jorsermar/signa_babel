<?php 
namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use App\TranslationJob\TranslationJob;
use App\Repository\RequestRepository;

#[AsCommand(name: 'app:get')]
class GetCommand extends Command
{
    private $requestRepository;

    public function __construct(RequestRepository $requestRepository)
    {
        $this->requestRepository = $requestRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('lang', InputArgument::REQUIRED, 'The language to translate.');
        $this->addArgument('text', InputArgument::REQUIRED, 'The string to translate.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $text = $input->getArgument('text');
        $lang = $input->getArgument('lang');

        $request = $this->requestRepository->findOneBy([
            'lang_to' => $lang,
            'search' => urldecode($text),
        ]);

        if(is_null($request)) {
            $output->writeln("We couldn´t find any result"); 
        } else {
            $output->writeln(print_r($request, 1)); 
        }
        
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}