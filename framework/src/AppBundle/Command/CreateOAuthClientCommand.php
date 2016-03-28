<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CreateOAuthClientCommand
 *
 * @package AppBundle\Command
 */
class CreateOAuthClientCommand extends ContainerAwareCommand
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('oauth:client:create')
            ->setDescription('Create OAuth Client')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Client Name?'
            )
            ->addArgument(
                'redirectUri',
                InputArgument::REQUIRED,
                'Redirect URI?'
            )
            ->addArgument(
                'grantTypes',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'Grant Type?'
            );
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $name = $input->getArgument('name');
        $redirectUri = $input->getArgument('redirectUri');
        $grantTypes = $input->getArgument('grantTypes');

        $clientManager = $container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setName($name);
        $client->setRedirectUris([$redirectUri]);
        $client->setAllowedGrantTypes($grantTypes);
        $clientManager->updateClient($client);

        // styling output
        $style = new OutputFormatterStyle('black', 'blue', array('bold'));
        $output->getFormatter()->setStyle('important', $style);
        $text = "<info>The client <important>%s</important> was created with <important>%s</important> as public id and <important>%s</important> as secret</info>";
        $output->writeln(sprintf($text,
            $client->getName(),
            $client->getPublicId(),
            $client->getSecret()));
    }
}