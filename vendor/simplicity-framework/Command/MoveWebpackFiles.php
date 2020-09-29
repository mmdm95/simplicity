<?php

namespace Sim\Command;

use Sim\Handler\WebpackHandler;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MoveWebpackFiles extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('webpack:move-files')
            // the short description shown while running "php bin/console list"
            ->setDescription('Move built files from [npm run build]')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command move files from webpack build directory');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        $output->writeln('Wait a moment to move webpack built directory files to wanted directories according to webpack config...');

        try {
            $webpackConfig = \config()->get('webpack');
            $webpackHandler = new WebpackHandler($webpackConfig['base_dir'], $webpackConfig['build_dir'], $webpackConfig['rules']);
            $webpackHandler->run();
        } catch (\Exception $e) {
            $err = $this->commandErr($e);
            $output->writeln('<error>' . $err . '</error>');

            // or return this if some error happened during the execution
            // (it's equivalent to returning int(1))
            return Command::FAILURE;
        } catch (IFileNotExistsException $e) {
            $err = $this->commandErr($e);
            $output->writeln('<error>' . $err . '</error>');
            return Command::FAILURE;
        } catch (IInvalidVariableNameException $e) {
            $err = $this->commandErr($e);
            $output->writeln('<error>' . $err . '</error>');
            return Command::FAILURE;
        }

        $output->writeln('<fg=green>OK! All good</>');

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;
    }

    /**
     * @param $e
     * @return string
     */
    protected function commandErr($e): string
    {
        $err = 'Message: ' . $e->getMessage() . PHP_EOL;
        $err .= 'File: ' . $e->getFile() . PHP_EOL;
        $err .= 'Line: ' . $e->getLine();
        return $err;
    }
}
