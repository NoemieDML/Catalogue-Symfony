<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Repository\MenusRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:stats',
    description: 'Add a short description for your command',
)]
class StatsCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private MenusRepository $menusRepository,
        private CategoriesRepository $categoriesRepository
    ) {
        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userCount = $this->userRepository->count([]);
        $menuCount = $this->menusRepository->count([]);
        $categFindall = $this->menusRepository->findAll([]);

        // writeln : Affiche un message suivi d'un saut de ligne. Cela permet de donner une sortie formatée dans la console.
        $output->writeln("<info>Nombre d'utilisateurs :</info> <comment>$userCount</comment>");
        $output->writeln("<info>Nombre total de menus :</info> <comment>$menuCount</comment>");
        $output->writeln("<info>Nom des catégories :</info> <comment>$categFindall</comment>");

        return Command::SUCCESS;
    }
}
