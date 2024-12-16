<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'export:destinations',
    description: 'Fetches all destinations via an API and exports them into a CSV file.',
)]
class ExportDestinationsCommand extends Command
{

    public function __construct(private HttpClientInterface $httpClient)
    {
        parent::__construct();
        $this->httpClient = $httpClient;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $verbose = $input->getOption('verbose');

        if ($verbose) {
            $io->title('Fetching Destinations');
        }

        try {
            $response = $this->httpClient->request('GET', "http://localhost:8000/api/destinations");
            $statusCode = $response->getStatusCode();
            if ($statusCode !== 200) {
                $io->error('Failed to fetch destinations. HTTP Status: ' . $statusCode);
                return Command::FAILURE;
            }
            $destinations = $response->toArray();
            if ($verbose) {
                $io->note(sprintf('Fetched %d destinations.', count($destinations)));
            }
            $file = 'destinations_export_' . date('Y-m-d_H-i-s') . '.csv';
            $fileHandle = fopen($file, 'w');
            if ($fileHandle === false) {
                $io->error('Failed to open the file for writing.');
                return Command::FAILURE;
            }
            fputcsv($fileHandle, ['Name', 'Description', 'Price', 'Duration']);
            foreach ($destinations as $destination) {
                fputcsv($fileHandle, [
                    $destination['name'],
                    $destination['description'],
                    $destination['price'],
                    $destination['duration'],
                ]);
            }
            fclose($fileHandle);
            $io->success('Destinations successfully exported to ' . $file);

        } catch (\Exception $e) {
            $io->error('An error occurred: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
