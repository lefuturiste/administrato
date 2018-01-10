<?php
return [
	\STAILEUAccounts\STAILEUAccounts::class => function (\DI\Container $container) {
		return new \STAILEUAccounts\STAILEUAccounts($container->get('staileu')['private'], $container->get('staileu')['public'], false);
	}
];