<?php

namespace App\Commands\Repositories\Issues;

use App\Commands\Repositories\IssuesCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetCommand extends IssuesCommand {
	
	protected function configure () {
		$this->setName('repositories:issues:get')
		     ->setDescription('Get a specific issue')
		     ->addArgument('repo', InputArgument::REQUIRED, 'owner/repository_slug')
		     ->addArgument('issue_id', InputArgument::REQUIRED, 'The id of the issue.');
	}
	
	protected function execute (InputInterface $input, OutputInterface $output) {
		$pull = $this->create();
		
		list($username, $repo_slug) = $this->splitRepo($input->getArgument('repo'));
		
		$issue = $pull->get($username, $repo_slug, $input->getArgument('issue_id'));
		
		if (!$issue->isOk()) {
			$this->throwApiResponseError($issue);
		}
		
		$output->writeln($issue->getContent());
	}
}
