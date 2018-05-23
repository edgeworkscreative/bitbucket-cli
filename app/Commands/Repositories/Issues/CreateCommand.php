<?php

namespace App\Commands\Repositories\Issues;

use App\Commands\Repositories\IssuesCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends IssuesCommand {
	
	protected function configure () {
		$this->setName('repositories:issues:create')
		     ->setDescription('Create a new issue')
		     ->addArgument('repo', InputArgument::REQUIRED, 'owner/repository_slug')
		     ->addArgument('title', InputArgument::REQUIRED, 'The title of the issue')
		     ->addArgument('content', InputArgument::REQUIRED, 'The content of the issue')
		     ->addArgument('kind', InputArgument::OPTIONAL, 'The kind of issue (bug, enhancement, proposal, task)')
		     ->addArgument('priority', InputArgument::OPTIONAL, 'The priority of the issue (trivial, minor, major, critical, blocker)');
	}
	
	protected function execute (InputInterface $input, OutputInterface $output) {
		$title    = $input->getArgument('title');
		$content  = $input->getArgument('content');
		$kind     = is_null($input->getArgument('kind')) ? 'task' : $input->getArgument('kind');
		$priority = is_null($input->getArgument('priority')) ? 'trivial' : $input->getArgument('priority');
		
		list($username, $repo_slug) = $this->splitRepo($input->getArgument('repo'));
		
		$payload = [
			'title'    => $title,
			'content'  => $content,
			'kind'     => $kind,
			'priority' => $priority
		];
		
		$issue        = $this->create();
		$create_issue = $issue->create($username, $repo_slug, $payload);
		
		if (!$create_issue->isOk()) {
			$this->throwApiResponseError($create_issue);
		}
		
		$output->writeln($create_issue->getContent());
	}
}
