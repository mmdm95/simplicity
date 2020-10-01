<?php

namespace Sim\Command;

use Sim\File\FileSystem;
use Sim\File\Filters\RegexFilter;
use Sim\File\Filters\TypeFilter;
use Sim\File\Interfaces\IFileSystem;
use Sim\Handler\WebpackHandler;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanWebpackFiles extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('webpack:clean-files')
            // the short description shown while running "php bin/console list"
            ->setDescription('Clean built files from [npm run build] according to generated manifest')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command clean files from public directory according to generated manifest');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // colors
        // black, red, green, yellow, blue, magenta, cyan, white, default

        // green text
//         $output->writeln('<info>foo</info>');
        // yellow text
//         $output->writeln('<comment>foo</comment>');
        // black text on a cyan background
//         $output->writeln('<question>foo</question>');
        // white text on a red background
//         $output->writeln('<error>foo</error>');


        $output->writeln('Start cleaning files...');

        try {
            $manifestJsonFile = manifest_content();
            if (count($manifestJsonFile)) {
                $correctFilename = get_path('public', '', true);
                foreach ($manifestJsonFile as $map => $filename) {
                    FileSystem::deleteDirAllFilteredFiles($correctFilename, [
                        new TypeFilter(IFileSystem::TYPE_FILE),
                        new RegexFilter('/' . preg_quote($filename) . '/i'),
                    ]);
                }
            }
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

        $output->writeln('<info>OK! All good</info>');

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
