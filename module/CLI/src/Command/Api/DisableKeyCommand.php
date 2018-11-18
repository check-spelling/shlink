<?php
declare(strict_types=1);

namespace Shlinkio\Shlink\CLI\Command\Api;

use InvalidArgumentException;
use Shlinkio\Shlink\Rest\Service\ApiKeyServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function sprintf;

class DisableKeyCommand extends Command
{
    public const NAME = 'api-key:disable';

    /**
     * @var ApiKeyServiceInterface
     */
    private $apiKeyService;

    public function __construct(ApiKeyServiceInterface $apiKeyService)
    {
        parent::__construct();
        $this->apiKeyService = $apiKeyService;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME)
             ->setDescription('Disables an API key.')
             ->addArgument('apiKey', InputArgument::REQUIRED, 'The API key to disable');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $apiKey = $input->getArgument('apiKey');
        $io = new SymfonyStyle($input, $output);

        try {
            $this->apiKeyService->disable($apiKey);
            $io->success(sprintf('API key "%s" properly disabled', $apiKey));
        } catch (InvalidArgumentException $e) {
            $io->error(sprintf('API key "%s" does not exist.', $apiKey));
        }
    }
}
