<?php

namespace SilverStripe\ApiDocs\Console;

use Gitonomy\Git\Admin;
use SilverStripe\ApiDocs\Data\Config;
use SilverStripe\ApiDocs\Data\RepoFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckoutCommand extends Command
{
    public function run(InputInterface $input, OutputInterface $output)
    {
        $config = Config::getConfig();

        // Ensure all dirs exist
        foreach ($config['paths'] as $path) {
            $fullPath = Config::configPath($path);
            if (!file_exists($fullPath)) {
                $output->writeln("Creating path <info>$fullPath</info>");
                mkdir($fullPath, 0755, true);
            }
        }

        // Ensure all repos are checked out
        foreach ($config['packages'] as $name => $data) {
            $root = Config::configPath($config['paths']['packages'] . '/' . $name);

            // Either update or checkout
            if (file_exists($root . '/.git')) {
                $output->writeln("Updating <info>$name</info>");
                $repo = RepoFactory::repoFor($root, $output);
                $repo->run('remote', ['update']);
                $repo->run('pull', ['--all']);
            } else {
                $output->writeln("Checking out <info>$name</info>");
                if (!file_exists($root)) {
                    mkdir($root, 0755, true);
                }
                Admin::cloneTo($root, $data['repository'], false, RepoFactory::options());
            }
        }
    }
}
