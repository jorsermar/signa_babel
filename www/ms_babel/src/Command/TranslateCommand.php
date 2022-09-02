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

#[AsCommand(name: 'app:translate')]
class TranslateCommand extends Command
{
    private $logger;
    private $bus;

    public function __construct(LoggerInterface $logger, MessageBusInterface $bus)
    {
        $this->logger = $logger;
        $this->bus = $bus;

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

        //TODO: Log the commands request, maybe change commands to work as calls to the API?
        //$this->logger->info('Request to translate "' . $text . '" into '. $lang);
        $this->bus->dispatch(new TranslationJob(json_encode(['date' => date(\DateTimeInterface::ATOM), 'lang' => $lang, 'text' => $text])));
        
        // retrieve the argument value using getArgument()
        $output->writeln('Lang: '.$lang);
        $output->writeln('Text: '.$text);
        
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}