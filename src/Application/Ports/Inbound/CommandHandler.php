<?php

namespace CheapDelivery\Application\Ports\Inbound;

use CheapDelivery\Application\Commands\Command;

interface CommandHandler
{
    /**
     * Handles the given command.
     *
     * @param Command $command The command to handle.
     */
    public function handle(Command $command): void;
}
